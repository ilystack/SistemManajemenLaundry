<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Order::with(['user', 'paket'])
            ->whereIn('status', ['selesai', 'diambil'])
            ->latest()
            ->paginate(20);

        $totalPendapatan = Order::whereIn('status', ['selesai', 'diambil'])
            ->sum('total_harga');

        $totalTransaksi = Order::whereIn('status', ['selesai', 'diambil'])
            ->count();

        return view('pages.transaksi.index', compact('transaksis', 'totalPendapatan', 'totalTransaksi'));
    }

    public function show(Order $transaksi)
    {
        $transaksi->load(['user', 'paket']);
        return view('pages.transaksi.show', compact('transaksi'));
    }
}
