<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Paket;
use App\Models\Gaji;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        $orders = Order::with(['user', 'paket'])
            ->where('antrian', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('paket', function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($orders as $order) {
            $results[] = [
                'type' => 'order',
                'icon' => '📦',
                'title' => "Order #{$order->antrian} - {$order->user->name}",
                'subtitle' => "{$order->paket->nama} - Rp " . number_format($order->total_harga, 0, ',', '.'),
                'url' => route('order.index'),
                'badge' => $order->status,
            ];
        }

        $customers = User::where('role', 'customer')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($customers as $customer) {
            $results[] = [
                'type' => 'customer',
                'icon' => '👤',
                'title' => $customer->name,
                'subtitle' => $customer->email . ($customer->phone ? " - {$customer->phone}" : ''),
                'url' => route('customer.index'),
                'badge' => $customer->is_active ? 'Aktif' : 'Nonaktif',
            ];
        }

        $karyawans = User::where('role', 'karyawan')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($karyawans as $karyawan) {
            $results[] = [
                'type' => 'karyawan',
                'icon' => '👔',
                'title' => $karyawan->name,
                'subtitle' => $karyawan->email . ($karyawan->phone ? " - {$karyawan->phone}" : ''),
                'url' => route('karyawan.index'),
                'badge' => $karyawan->is_active ? 'Aktif' : 'Nonaktif',
            ];
        }

        $pakets = Paket::where('nama', 'like', "%{$query}%")
            ->orWhere('kode', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($pakets as $paket) {
            $results[] = [
                'type' => 'paket',
                'icon' => '📋',
                'title' => $paket->nama,
                'subtitle' => "Kode: {$paket->kode} - Rp " . number_format($paket->harga, 0, ',', '.') . "/{$paket->satuan}",
                'url' => route('paket.index'),
                'badge' => $paket->is_express ? 'Express' : 'Regular',
            ];
        }

        $gajis = Gaji::with('karyawan')
            ->whereHas('karyawan', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('nominal', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($gajis as $gaji) {
            $results[] = [
                'type' => 'gaji',
                'icon' => '💰',
                'title' => "Gaji {$gaji->karyawan->name}",
                'subtitle' => "Rp " . number_format($gaji->nominal, 0, ',', '.') . " - " . $gaji->tanggal_gaji->format('d M Y'),
                'url' => route('gaji.index'),
                'badge' => $gaji->status,
            ];
        }

        return response()->json($results);
    }
}
