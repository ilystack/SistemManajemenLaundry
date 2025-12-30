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

    public function createPayment(Order $order)
    {
        if ($order->pickup !== 'dijemput') {
            return redirect()->back()->with('error', 'Order ini tidak memerlukan pembayaran DP');
        }

        if ($order->payment && $order->payment->status === 'success') {
            return redirect()->back()->with('error', 'Order ini sudah dibayar');
        }

        $orderCode = 'DP-' . $order->id . '-' . time();

        $customerDetails = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->whatsapp ?? '08123456789',
        ];

        try {
            $snapToken = $this->midtransService->createTransaction(
                $orderCode,
                $order->biaya_pickup, // DP = biaya pickup
                $customerDetails
            );

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_code' => $orderCode,
                    'payment_type' => 'qris',
                    'amount' => $order->biaya_pickup,
                    'status' => 'pending',
                    'snap_token' => $snapToken->token,
                    'payment_url' => $snapToken->redirect_url,
                ]
            );

            return view('pages.payment.show', compact('payment', 'order'));

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
