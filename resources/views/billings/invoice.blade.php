<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $billing->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 14px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #000;
        }

        .header .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-details {
            margin-bottom: 40px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details td {
            padding: 5px 0;
        }

        .invoice-details .right {
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .summary-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .summary-table .total {
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .paid-stamp {
            position: absolute;
            top: 150px;
            left: 50%;
            transform: translateX(-50%) rotate(-15deg);
            font-size: 80px;
            font-weight: bold;
            color: #28a745;
            opacity: 0.1;
            border: 10px solid #28a745;
            padding: 20px;
            border-radius: 10px;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="paid-stamp">LUNAS</div>
    <div class="container">
        <div class="header">
            <div class="logo">KOSTIFY</div>
            <h1>INVOICE</h1>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td>
                        <strong>Ditagihkan Kepada:</strong><br>
                        {{ $billing->penghuni->name }}<br>
                        Kamar {{ $billing->penghuni->room->room_number }}
                    </td>
                    <td class="right">
                        <strong>No. Invoice:</strong> {{ $billing->invoice_number }}<br>
                        <strong>Tanggal Lunas:</strong>
                        {{ \Carbon\Carbon::parse($billing->paid_at)->translatedFormat('d F Y') }}<br>
                        <strong>Jatuh Tempo Awal:</strong>
                        {{ \Carbon\Carbon::parse($billing->due_date)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            </table>
        </div>

        <h3>Rincian Tagihan</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Sewa Kamar {{ $billing->penghuni->room->room_number }} - Tipe
                        {{ $billing->penghuni->room->type }}<br>
                        <small>Untuk periode tagihan bulan
                            {{ \Carbon\Carbon::parse($billing->due_date)->translatedFormat('F Y') }}</small>
                    </td>
                    <td>Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <h3>Rincian Pembayaran</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Tanggal Bayar</th>
                    <th>Jumlah Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billing->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d F Y') }}</td>
                        <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td><strong>Total Tagihan</strong></td>
                <td><strong>Rp {{ number_format($billing->amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Total Dibayar</strong></td>
                <td><strong>Rp {{ number_format($billing->payments->sum('amount_paid'), 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="total">
                <td><strong>Sisa Tagihan</strong></td>
                <td><strong>Rp {{ number_format($billing->balance, 0, ',', '.') }}</strong></td>
            </tr>
        </table>


        <div class="footer">
            Invoice ini dibuat secara otomatis oleh sistem Kostify pada tanggal {{ $tanggal_cetak }}.<br>
            Status: <strong>LUNAS</strong>
        </div>
    </div>
</body>

</html>
