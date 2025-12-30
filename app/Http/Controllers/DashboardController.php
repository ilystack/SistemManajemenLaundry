<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function karyawan()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;

        // Data real dari database
        $stats = [
            'orderan_hari_ini' => Order::whereDate('created_at', $today)->count(),
            'orderan_selesai' => Order::where('status', 'selesai')->count(),
            'absensi_bulan_ini' => Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $thisMonth)
                ->count(),
        ];

        // Orderan terbaru (limit 5)
        $orders = Order::with(['user', 'paket'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.dashboard.karyawan', compact('stats', 'orders'));
    }
}
