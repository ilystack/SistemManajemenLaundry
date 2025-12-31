<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <title>Resi Order #{{ $order->antrian }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2563eb;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .receipt-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .receipt-title h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .receipt-title .order-number {
            color: #2563eb;
            font-size: 20px;
            font-weight: bold;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h3 {
            color: #2563eb;
            font-size: 16px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .info-label {
            color: #666;
            font-weight: 600;
        }

        .info-value {
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 3px solid #2563eb;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 16px;
        }

        .total-row.grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .print-button:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">🖨️ Cetak Resi</button>

    <div class="receipt-container">
        <div class="header">
            <h1>{{ $laundryInfo['name'] }}</h1>
            <p>{{ $laundryInfo['address'] }}</p>
            <p>Telp: {{ $laundryInfo['phone'] }}</p>
        </div>

        <div class="receipt-title">
            <h2>RESI LAUNDRY</h2>
            <div class="order-number">#{{ $order->antrian }}</div>
        </div>

        <div class="info-section">
            <h3>Informasi Customer</h3>
            <div class="info-grid">
                <div class="info-label">Nama</div>
                <div class="info-value">{{ $order->user->name }}</div>

                <div class="info-label">Email</div>
                <div class="info-value">{{ $order->user->email }}</div>

                @if($order->user->phone)
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $order->user->phone }}</div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <h3>Informasi Pesanan</h3>
            <div class="info-grid">
                <div class="info-label">Tanggal Order</div>
                <div class="info-value">{{ $order->created_at->format('d F Y, H:i') }}</div>

                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge status-selesai">✓ Selesai</span>
                </div>

                <div class="info-label">Metode Pengantaran</div>
                <div class="info-value">{{ ucwords(str_replace('_', ' ', $order->pickup)) }}</div>

                @if($order->pickup === 'dijemput')
                    <div class="info-label">Jarak Penjemputan</div>
                    <div class="info-value">{{ $order->jarak_km }} km</div>
                @endif

                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">{{ $order->payment_method === 'cash' ? '💵 Cash' : '📱 QRIS' }}</div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Paket Laundry</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->paket->nama }}</td>
                    <td style="text-align: center;">{{ $order->jumlah }} {{ $order->paket->satuan }}</td>
                    <td style="text-align: right;">Rp {{ number_format($order->paket->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp
                        {{ number_format($order->paket->harga * $order->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            @if($order->pickup === 'dijemput' && $order->biaya_pickup > 0)
                <div class="total-row">
                    <span>Biaya Penjemputan ({{ $order->jarak_km }} km)</span>
                    <span>Rp {{ number_format($order->biaya_pickup, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="total-row grand-total">
                <span>TOTAL PEMBAYARAN</span>
                <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
            <p>Resi ini dicetak pada {{ now()->format('d F Y, H:i') }}</p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;">
                Simpan resi ini sebagai bukti pengambilan laundry
            </p>
        </div>
    </div>

    <script>
    </script>
</body>

</html>