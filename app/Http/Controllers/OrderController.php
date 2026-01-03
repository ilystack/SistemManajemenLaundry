<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'paket'])
            ->latest()
            ->paginate(20);

        $pakets = Paket::all();

        $admin = \App\Models\User::where('role', 'admin')->first();
        $laundryLocation = [
            'latitude' => $admin->latitude ?? null,
            'longitude' => $admin->longitude ?? null,
        ];

        return view('pages.order.index', compact('orders', 'pakets', 'laundryLocation'));
    }

    public function create()
    {
        $pakets = Paket::all();
        return view('pages.order.create', compact('pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_order' => 'required|in:kg,pcs',
            'pickup' => 'required|in:antar_sendiri,dijemput',
            'payment_method' => 'required|in:cash,qris',
            'jarak_km' => 'required_if:pickup,dijemput|nullable|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $biayaPickup = 0;
        if ($request->pickup === 'dijemput' && $request->jarak_km) {
            $biayaPickup = $request->jarak_km * 1000; // Rp 1000 per km
        }

        $antrianTerakhir = Order::max('antrian') ?? 0;
        $createdOrders = [];

        if ($request->tipe_order === 'kg') {
            $request->validate([
                'paket_id_kg' => 'required|exists:pakets,id',
                'jumlah_kg' => 'required|numeric|min:0.1',
            ]);

            $paket = Paket::findOrFail($request->paket_id_kg);
            $subtotal = $paket->harga * $request->jumlah_kg;

            $order = Order::create([
                'user_id' => Auth::id(),
                'paket_id' => $paket->id,
                'jumlah' => $request->jumlah_kg,
                'pickup' => $request->pickup,
                'jarak_km' => $request->jarak_km,
                'biaya_pickup' => $biayaPickup,
                'total_harga' => $subtotal + $biayaPickup,
                'antrian' => $antrianTerakhir + 1,
                'status' => 'menunggu',
                'payment_method' => $request->payment_method,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            $createdOrders[] = $order;

            \App\Models\ActivityLog::log(
                'order',
                "Order kiloan #{$order->antrian} - {$paket->nama} ({$request->jumlah_kg} kg)",
                Auth::id(),
                Auth::user()->name,
                '📦',
                'blue'
            );

        } else {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.paket_id' => 'required|exists:pakets,id',
                'items.*.jumlah' => 'required|integer|min:1',
            ]);

            $totalHargaItems = 0;
            $itemDetails = [];

            foreach ($request->items as $item) {
                $paket = Paket::findOrFail($item['paket_id']);
                $subtotal = $paket->harga * $item['jumlah'];
                $totalHargaItems += $subtotal;

                $itemDetails[] = [
                    'paket_id' => $paket->id,
                    'nama_paket' => $paket->nama,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $paket->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $firstItem = $request->items[array_key_first($request->items)];
            $totalItems = array_sum(array_column($request->items, 'jumlah'));

            $order = Order::create([
                'user_id' => Auth::id(),
                'paket_id' => $firstItem['paket_id'],
                'jumlah' => $totalItems,
                'pickup' => $request->pickup,
                'jarak_km' => $request->jarak_km,
                'biaya_pickup' => $biayaPickup,
                'total_harga' => $totalHargaItems + $biayaPickup,
                'antrian' => $antrianTerakhir + 1,
                'status' => 'menunggu',
                'payment_method' => $request->payment_method,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            $createdOrders[] = $order;

            $itemNames = array_column($itemDetails, 'nama_paket');
            $itemSummary = implode(', ', array_map(function ($name, $detail) {
                return "{$detail['jumlah']}x {$name}";
            }, $itemNames, $itemDetails));

            \App\Models\ActivityLog::log(
                'order',
                "Order satuan #{$order->antrian} - {$itemSummary}",
                Auth::id(),
                Auth::user()->name,
                '👕',
                'purple'
            );
        }

        $firstOrder = $createdOrders[0] ?? null;

        // Determine if payment is needed and calculate amount
        $needsPayment = !($request->pickup === 'antar_sendiri' && $request->payment_method === 'cash');
        $paymentAmount = 0;
        $paymentType = '';
        $snapToken = null;

        if ($needsPayment && $firstOrder) {
            $laundryTotal = $firstOrder->total_harga - $firstOrder->biaya_pickup;

            if ($request->pickup === 'antar_sendiri' && $request->payment_method === 'qris') {
                // Antar sendiri + QRIS = Full laundry only
                $paymentAmount = $laundryTotal;
                $paymentType = 'full';
            } elseif ($request->pickup === 'dijemput' && $request->payment_method === 'cash') {
                // Dijemput + cash = DP (jarak only)
                $paymentAmount = $firstOrder->biaya_pickup;
                $paymentType = 'dp';
            } elseif ($request->pickup === 'dijemput' && $request->payment_method === 'qris') {
                // Dijemput + QRIS = Full (laundry + jarak)
                $paymentAmount = $firstOrder->total_harga;
                $paymentType = 'full';
            }

            // Create Midtrans payment
            try {
                $orderCode = strtoupper($paymentType) . '-' . $firstOrder->id . '-' . time();

                $customerDetails = [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '08123456789',
                ];

                $itemDetails = [
                    [
                        'id' => $firstOrder->id,
                        'price' => $paymentAmount,
                        'quantity' => 1,
                        'name' => $paymentType === 'dp'
                            ? "DP Biaya Pickup - Order #{$firstOrder->antrian}"
                            : "Pembayaran Laundry - Order #{$firstOrder->antrian}"
                    ]
                ];

                $midtransService = app(\App\Services\MidtransService::class);
                $snapResponse = $midtransService->createTransaction(
                    $orderCode,
                    $paymentAmount,
                    $customerDetails,
                    $itemDetails
                );

                // Save payment record
                \App\Models\Payment::updateOrCreate(
                    ['order_id' => $firstOrder->id],
                    [
                        'order_code' => $orderCode,
                        'payment_type' => 'qris',
                        'amount' => $paymentAmount,
                        'status' => 'pending',
                        'snap_token' => $snapResponse->token,
                        'payment_url' => $snapResponse->redirect_url,
                    ]
                );

                $snapToken = $snapResponse->token;

            } catch (\Exception $e) {
                \Log::error('Midtrans payment creation failed: ' . $e->getMessage());

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
                    ], 500);
                }

                return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
            }
        }

        // Return JSON for AJAX handling
        if ($request->expectsJson() || $request->ajax()) {
            if ($needsPayment && $snapToken) {
                return response()->json([
                    'success' => true,
                    'needs_payment' => true,
                    'snap_token' => $snapToken,
                    'payment_amount' => $paymentAmount,
                    'payment_type' => $paymentType,
                    'order_id' => $firstOrder->id,
                    'message' => 'Order berhasil dibuat. Silakan lakukan pembayaran.'
                ]);
            }

            return response()->json([
                'success' => true,
                'needs_payment' => false,
                'redirect_url' => Auth::user()->role === 'customer'
                    ? route('customer.dashboard')
                    : route('order.index'),
                'message' => 'Order berhasil dibuat!'
            ]);
        }

        // Fallback for non-AJAX requests
        if ($needsPayment && $firstOrder) {
            return redirect()->route('payment.create', [
                'order' => $firstOrder->id,
                'amount' => $paymentAmount,
                'type' => $paymentType
            ]);
        }

        if (Auth::user()->role === 'customer') {
            return redirect()->route('customer.dashboard')
                ->with('success', 'Order berhasil dibuat!');
        }

        return redirect()->route('order.index')->with('success', 'Order berhasil dibuat');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'paket', 'payment']);
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,diambil'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        \App\Models\ActivityLog::log(
            'order',
            "Status order #{$order->antrian} diubah dari {$oldStatus} → {$request->status}",
            Auth::id(),
            Auth::user()->name,
            '🔄',
            'indigo'
        );

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diupdate!',
            'order' => $order->load(['user', 'paket'])
        ]);
    }

    public function receipt(Order $order)
    {
        $order->load(['user', 'paket', 'payment']);

        $admin = \App\Models\User::where('role', 'admin')->first();
        $laundryInfo = [
            'name' => 'Almas Laundry',
            'address' => $admin->address ?? 'Jl. Bekasi Timur No. 123',
            'phone' => $admin->phone ?? '08123456789',
        ];

        return view('pages.order.receipt', compact('order', 'laundryInfo'));
    }
}
