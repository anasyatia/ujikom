<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);
        $search = $request->input('search');

        $query = Penjualan::with(['detailPenjualans', 'user', 'member'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('total_harga', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('member', function ($q3) use ($search) {
                        $q3->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('telp', 'like', '%' . $search . '%');
                    });
            });
        }        

        $penjualans = $query->paginate($entries)->withQueryString();

        return view('penjualan.index', compact('penjualans'));
    }

    public function dashboardAdmin()
    {
        $salesPerDay = DB::table('penjualans')
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->groupByRaw("DATE(created_at)")
            ->orderBy('date')
            ->get();

        $dates = $salesPerDay->pluck('date')->map(fn($date) => Carbon::parse($date)->translatedFormat('d F Y'));
        $totals = $salesPerDay->pluck('total');

        $productSales = DB::table('detail_penjualans')
            ->join('produks', 'detail_penjualans.produk_id', '=', 'produks.id')
            ->select('produks.produk as name', DB::raw('SUM(detail_penjualans.qty) as y'))
            ->groupBy('produks.produk')
            ->get()
            ->map(function ($item) {
                $item->y = (int) $item->y;
                return $item;
            });

        return view('dashboard.admin', [
            'dates' => $dates,
            'totals' => $totals,
            'productSales' => $productSales,
        ]);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $salesToday = Penjualan::whereDate('created_at', $today)->count();
        return view('dashboard.petugas', compact('salesToday'));
    }

    public function create()
    {
        $produks = Produk::all();
        return view('penjualan.create', compact('produks'));
    }

    public function show($id)
    {
        $penjualan = Penjualan::with('details.produk')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    public function downloadInvoice($id)
    {
        $invoice = Penjualan::with('detailPenjualans.produk', 'user')->find($id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan');
        }

        $pdf = PDF::loadView('penjualan.print', compact('invoice'));

        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }

    public function sales()
    {
        $data = Produk::all();
        return view('sales.create')->with('data', $data);
    }

    public function processProduct(Request $request)
    {
        $quantities = $request->input('jumlah', []);
        $orders = [];
        $totalPrice = 0;
    
        foreach ($quantities as $productId => $qty) {
            if ($qty > 0) {
                $product = Produk::find($productId);
                if ($product) {
                    $subtotal = $product->harga * $qty;
    
                    $orders[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'subtotal' => $subtotal
                    ];
    
                    $totalPrice += $subtotal;
                }
            }
        }
    
        // Tambahkan ini untuk mengirim semua member ke view
        $members = Member::orderBy('nama')->get();
    
        return view('sales.checkout', compact('orders', 'totalPrice', 'members'));
    }

    public function processMember(Request $request)
    {
        $totalPrice = $request->input('total_price');
        $orders = $request->input('orders');

        $totalPaid = $request->input('total_paid');
        $isMember = $request->input('is_member');
        $numberTelephone = $request->input('number_telephone');

        if ($totalPaid < $totalPrice) {
            return back()->with('error', 'Total bayar tidak boleh kurang dari total harga!');
        }

        $changeAmount = $totalPaid - $totalPrice;

        if ($isMember == 1) {
            $member = Member::where('telp', $numberTelephone)->first();

            foreach ($orders as $index => $orderItem) {
                $product = Produk::find($orderItem['product_id']);
                $orders[$index]['product'] = $product;
            }

            if ($member) {
                $points = intval($totalPrice / 100);
                $memberPoint = $member->poin + $points;

                return view('sales.member')->with([
                    'orders' => $orders,
                    'totalPrice' => $totalPrice,
                    'totalPaid' => $totalPaid,
                    'member' => $member,
                    'number_telephone' => $numberTelephone,
                    'point' => $memberPoint
                ]);
            } else {
                $points = intval($totalPrice / 100);

                return view('sales.member')->with([
                    'orders' => $orders,
                    'totalPrice' => $totalPrice,
                    'totalPaid' => $totalPaid,
                    'number_telephone' => $numberTelephone,
                    'point' => $points
                ]);
            }
        }

        return $this->store($orders, $totalPaid, $totalPrice, $changeAmount);
    }

    public function member(Request $request)
    {
        $totalPrice = floatval($request->input('total_harga', 0));
        $totalPaid = floatval($request->input('total_bayar', 0));
        $orders = $request->input('orders', []);
        $pointConversionRate = 100;

        $pointConversionRate = 100;
        $pointReward = intval($totalPrice / $pointConversionRate);
        $pointUsed = 0;
        $adjustedTotalPrice = $totalPrice;

        if ($request->filled('member_id')) {
            $memberId = $request->input('member_id');
            $member = Member::find($memberId);

            if (!$member) {
                return response()->json(['error' => 'Member not found.'], 404);
            }

            $memberNeedsSaving = false;

            if ($request->has('poin_dipakai') && $member->poin > 0) {
                $pointUsed = min($member->poin, $totalPrice);
                $adjustedTotalPrice = max(0, $totalPrice - $pointUsed);

                $member->poin -= $pointUsed;
                $memberNeedsSaving = true;
            }

            $member->poin += $pointReward;
            $memberNeedsSaving = true;

            if ($memberNeedsSaving) {
                $member->save();
            }

            $changeAmount = $totalPaid - $adjustedTotalPrice;

            if ($changeAmount < 0) {
                return back()->with('error', 'Total bayar tidak mencukupi setelah penggunaan poin!');
            }

            return $this->store(
                $orders,
                $totalPaid,
                $adjustedTotalPrice,
                $changeAmount,
                $memberId,
                $pointUsed,
                $pointReward
            );
        }

        $member = Member::create([
            'nama' => $request->input('nama'),
            'telp' => $request->input('telp'),
            'poin' => $pointReward
        ]);

        $memberId = $member->id;
        $changeAmount = $totalPaid - $totalPrice;
        if ($changeAmount < 0) {
            return back()->with('error', 'Total bayar tidak mencukupi!');
        }

        return $this->store(
            $orders,
            $totalPaid,
            $totalPrice,
            $changeAmount,
            $memberId,
            0,
            $pointReward
        );
    }

    public function store($orders, $totalPaid, $totalPrice, $changeAmount, $memberId = null, $pointUsed = 0, $pointReward = 0)
    {
        $penjualan = Penjualan::create([
            'dibuat_oleh'    => Auth::id(),
            'member_id'      => $memberId,
            'poin_dipakai'   => $pointUsed,
            'poin_didapat'   => $pointReward,
            'total_harga'    => $totalPrice,
            'total_bayar'    => $totalPaid,
            'kembalian'      => $changeAmount,
            'status_member'  => $memberId ? 'member' : 'non_member',
        ]);

        foreach ($orders as $orderItem) {
            $produk = Produk::find($orderItem['product_id']);

            if ($produk) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id'    => $produk->id,
                    'qty'          => $orderItem['quantity'],
                    'harga_satuan' => $produk->harga,
                    'sub_total'    => $orderItem['subtotal'] ?? ($produk->harga * $orderItem['quantity'])
                ]);

                $produk->stok -= $orderItem['quantity'];
                $produk->save();
            }
        }

        $penjualan->load(['member', 'detailPenjualans.produk']);
        return view('sales.receipt', compact('penjualan'));
    }
}
