@extends('layouts.app')

@section('title', 'Struk Penjualan')

@section('content')
<div class="container mx-auto pt-6">
    <div class="mb-6">
        <a href="{{ route('penjualan.index') }}"
            class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded-md shadow-md transition">
            ← Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Struk Penjualan</h1>

        <div class="text-gray-700 space-y-1 mb-6">
            <p><span class="font-semibold">ID Penjualan:</span> {{ $penjualan->id }}</p>
            <p><span class="font-semibold">Tanggal:</span> {{ $penjualan->created_at->format('d-m-Y H:i') }}</p>
            <p><span class="font-semibold">Kasir:</span> {{ auth()->user()->name }}</p>

            @if ($penjualan->member)
                <p><span class="font-semibold">Member:</span> {{ $penjualan->member->nama }} ({{ $penjualan->member->telp }})</p>
            @endif
        </div>

        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm text-left border rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border-b">Produk</th>
                        <th class="px-4 py-2 border-b">Jumlah</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach ($penjualan->detailPenjualans as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 border-b">{{ $item->produk->produk }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->qty }}</td>
                            <td class="px-4 py-2 border-b">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border-b">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $poinDiskon = $penjualan->poin_dipakai ?? 0;
            $totalBeforeDiscount = $penjualan->total_harga + $poinDiskon;
            $totalAfterDiscount = $penjualan->total_harga;
            $kembalian = $penjualan->total_bayar - $totalAfterDiscount;
        @endphp

        <div class="text-right text-sm text-gray-700 space-y-1 mb-3">
            <p><span class="font-semibold">Total Sebelum Diskon:</span> Rp {{ number_format($totalBeforeDiscount, 0, ',', '.') }}</p>

            @if ($poinDiskon)
                <p><span class="font-semibold">Diskon dari Poin:</span> Rp {{ number_format($poinDiskon, 0, ',', '.') }}</p>
            @endif

            <p><span class="font-semibold">Total Setelah Diskon:</span> Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}</p>
            <p><span class="font-semibold">Dibayar:</span> Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
            <p><span class="font-semibold">Kembalian:</span> Rp {{ number_format($kembalian, 0, ',', '.') }}</p>

            @if ($penjualan->poin_dipakai)
                <p><span class="font-semibold">Point Dipakai:</span> {{ $penjualan->poin_dipakai }}</p>
            @endif
            @if ($penjualan->poin_didapat)
                <p><span class="font-semibold">Point Didapat:</span> {{ $penjualan->poin_didapat }}</p>
            @endif
        </div>
        <a href="{{ route('penjualan.pdf', $penjualan->id) }}"
            class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 text-md transition">
            Unduh PDF
        </a>
    </div>
</div>
@endsection
