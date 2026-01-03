<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createTransaction($orderCode, $amount, $customerDetails, $itemDetails = null)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderCode,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails,
            'enabled_payments' => ['qris', 'gopay', 'shopeepay'], // QRIS & E-wallet
        ];

        if ($itemDetails) {
            $params['item_details'] = $itemDetails;
        }

        return Snap::createTransaction($params);
    }
}