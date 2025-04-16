<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $penjualans = Penjualan::with(['member', 'detailPenjualans.produk'])->get();

        $rows = collect();

        foreach ($penjualans as $item) {
            $isFirstRow = true;

            // Diskon dan total awal
            $poinDiskon = $item->poin_dipakai ?? 0;
            $totalBeforeDiscount = $item->total_harga + $poinDiskon;
            $poinDidapat = $item->poin_didapat ?? 0;

            foreach ($item->detailPenjualans as $dp) {
                $product = $dp->produk;

                $rows->push([
                    $isFirstRow ? ($item->member->nama ?? 'Bukan Member') : '',
                    $isFirstRow ? ($item->member->telp ?? '-') : '',
                    $isFirstRow ? $poinDidapat : '',
                    $product->produk ?? '-',
                    $dp->qty,
                    'Rp. ' . number_format($product->harga ?? 0, 0, ',', '.'),
                    'Rp. ' . number_format($dp->sub_total, 0, ',', '.'),
                    $isFirstRow ? 'Rp. ' . number_format($totalBeforeDiscount, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->total_harga, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->total_bayar ?? 0, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($poinDiskon, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->kembalian ?? 0, 0, ',', '.') : '',
                    $isFirstRow ? $item->created_at->format('d-m-Y') : '',
                ]);

                $isFirstRow = false;
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Didapat',
            'Nama Produk',
            'Jumlah Produk',
            'Harga Satuan',
            'Subtotal Produk',
            'Total Sebelum Diskon',
            'Total Setelah Diskon',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }
}
