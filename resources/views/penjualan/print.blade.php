<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 40px;
            background-color: #f9fafb;
        }

        h2 {
            margin-bottom: 8px;
            font-size: 24px;
            color: #1f2937;
        }

        p {
            margin: 2px 0;
        }

        .info {
            margin-bottom: 20px;
            padding: 16px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            color: #374151;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 20px;
            width: 100%;
            border: none;
        }

        .summary td {
            padding: 6px 8px;
            border: none;
        }

        .summary tr:not(:last-child) td {
            border-bottom: 1px solid #e5e7eb;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }

        .footer strong {
            color: #111827;
        }
    </style>
</head>

<body onload="window.print()">
    <h2>CerMarts</h2>

    <div class="info">
        <p><strong>Member Status:</strong> {{ $invoice->status_member === 'member' ? 'Member' : 'NON-MEMBER' }}</p>
        <p><strong>No. HP:</strong> {{ $invoice->member->telp ?? '000' }}</p>
        <p><strong>Bergabung Sejak:</strong>
            {{ $invoice->status_member === 'member' ? \Carbon\Carbon::parse($invoice->created_at)->translatedFormat('d F Y') : '-' }}
        </p>
        <p><strong>Poin Member:</strong> {{ $invoice->poin_didapat ?? '0' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->detailPenjualans as $item)
                <tr>
                    <td>{{ $item->produk->produk }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-right">Rp. {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
    $poinDiskon = $invoice->poin_dipakai ?? 0;
    $totalBeforeDiscount = $invoice->total_harga + $poinDiskon;
    $totalAfterDiscount = $invoice->total_harga;
    $kembalian = ($invoice->total_bayar ?? 0) - $totalAfterDiscount;
    @endphp

    <table class="summary">
        <tr>
            <td><strong>Total Sebelum Diskon</strong></td>
            <td class="text-right"><strong>Rp. {{ number_format($totalBeforeDiscount, 0, ',', '.') }}</strong></td>
        </tr>

        @if ($poinDiskon)
            <tr>
                <td>Diskon dari Poin</td>
                <td class="text-right">Rp. {{ number_format($poinDiskon, 0, ',', '.') }}</td>
            </tr>
        @endif

        <tr>
            <td>Total Setelah Diskon</td>
            <td class="text-right">Rp. {{ number_format($totalAfterDiscount, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td>Total Dibayar</td>
            <td class="text-right">Rp. {{ number_format($invoice->total_bayar ?? 0, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td><strong>Total Kembalian</strong></td>
            <td class="text-right"><strong>Rp. {{ number_format($kembalian, 0, ',', '.') }}</strong></td>
        </tr>

        @if ($invoice->poin_dipakai)
            <tr>
                <td>Poin Dipakai</td>
                <td class="text-right">{{ $invoice->poin_dipakai }}</td>
            </tr>
        @endif

        @if ($invoice->poin_didapat)
            <tr>
                <td>Poin Didapat</td>
                <td class="text-right">{{ $invoice->poin_didapat }}</td>
            </tr>
        @endif
    </table>


    <div class="footer">
        <p>{{ $invoice->created_at->format('Y-m-d H:i:s') }} | {{ $invoice->user->name }}</p>
        <strong>Terima kasih atas pembelian Anda!</strong>
    </div>
</body>

</html>
