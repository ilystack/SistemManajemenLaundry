<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createPayment(Request $request, Order $order)
    {
        // Check if already paid
        if ($order->payment && $order->payment->status === 'success') {
            return redirect()->back()->with('error', 'Order ini sudah dibayar');
        }

        // Get payment amount and type from request
        $paymentAmount = $request->query('amount', $order->biaya_pickup);
        $paymentType = $request->query('type', 'dp'); // dp or full

        // Validate payment amount
        if ($paymentAmount <= 0) {
            return redirect()->back()->with('error', 'Jumlah pembayaran tidak valid');
        }

        $orderCode = strtoupper($paymentType) . '-' . $order->id . '-' . time();

        $customerDetails = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->phone ?? '08123456789',
        ];

        // Item details for Midtrans
        $itemDetails = [
            [
                'id' => $order->id,
                'price' => $paymentAmount,
                'quantity' => 1,
                'name' => $paymentType === 'dp'
                    ? "DP Biaya Pickup - Order #{$order->antrian}"
                    : "Pembayaran Laundry - Order #{$order->antrian}"
            ]
        ];

        try {
            $snapToken = $this->midtransService->createTransaction(
                $orderCode,
                $paymentAmount,
                $customerDetails,
                $itemDetails
            );

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_code' => $orderCode,
                    'payment_type' => 'qris',
                    'amount' => $paymentAmount,
                    'status' => 'pending',
                    'snap_token' => $snapToken->token,
                    'payment_url' => $snapToken->redirect_url,
                ]
            );

            return view('pages.payment.show', compact('payment', 'order', 'paymentType'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('order_code', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? 'accept';

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->update([
                    'status' => 'success',
                    'transaction_id' => $request->transaction_id,
                    'paid_at' => now(),
                ]);
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->update([
                'status' => 'success',
                'transaction_id' => $request->transaction_id,
                'paid_at' => now(),
            ]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update([
                'status' => 'failed',
                'transaction_id' => $request->transaction_id,
            ]);
        } elseif ($transactionStatus == 'pending') {
            $payment->update([
                'status' => 'pending',
                'transaction_id' => $request->transaction_id,
            ]);
        }

        if ($payment->status === 'success') {
            \App\Models\ActivityLog::log(
                'payment',
                "Pembayaran DP berhasil - Order #{$payment->order->antrian}",
                $payment->order->user_id,
                $payment->order->user->name,
                '💰',
                'green'
            );
        }

        return response()->json(['message' => 'OK']);
    }

    public function checkStatus(Payment $payment)
    {
        return response()->json([
            'status' => $payment->status,
            'paid_at' => $payment->paid_at,
        ]);
    }
}
