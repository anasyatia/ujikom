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

            foreach ($item->detailPenjualans as $dp) {
                $product = $dp->produk;

                $rows->push([
                    $isFirstRow ? ($item->member->nama ?? 'Bukan Member') : '',
                    $isFirstRow ? ($item->member->telp ?? '-') : '',
                    $isFirstRow ? ($item->member->poin ?? '-') : '',
                    $product->produk ?? '-',
                    $dp->qty,
                    'Rp. ' . number_format($product->harga ?? 0, 0, ',', '.'),
                    'Rp. ' . number_format($dp->sub_total, 0, ',', '.'),
                    $isFirstRow ? 'Rp. ' . number_format($item->total_harga, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->total_bayar, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->total_diskon_poin ?? 0, 0, ',', '.') : '',
                    $isFirstRow ? 'Rp. ' . number_format($item->kembalian, 0, ',', '.') : '',
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
            'Poin Pelanggan',
            'Nama Produk',
            'Jumlah Produk',
            'Harga Satuan',
            'Subtotal Produk',
            'Total Harga Transaksi',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }
}
