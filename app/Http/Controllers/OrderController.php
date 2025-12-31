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
                'payment_method' => $request->payment_method ?? 'cash',
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
                'payment_method' => $request->payment_method ?? 'cash',
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

        if (Auth::user()->role === 'customer') {
            $firstOrder = $createdOrders[0] ?? null;

            if ($request->payment_method === 'qris' && $request->pickup === 'dijemput' && $firstOrder) {
                return redirect()->route('payment.create', $firstOrder)
                    ->with('success', 'Order berhasil! Silakan bayar DP penjemputan via QRIS.');
            }

            if ($request->payment_method === 'qris' && $request->pickup === 'antar_sendiri' && $firstOrder) {
                return redirect()->route('customer.dashboard')
                    ->with('success', 'Order berhasil dibuat! Anda bisa bayar via QRIS di halaman riwayat order.');
            }

            return redirect()->route('customer.dashboard')
                ->with('success', 'Order berhasil dibuat! Silakan bayar cash saat pengambilan/pengantaran.');
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
