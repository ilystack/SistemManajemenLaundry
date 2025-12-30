<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function checkAbsensi()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $todayName = $hariMap[$today->format('l')];

        $jamKerjaAktif = JamKerja::where('is_active', true)
            ->where('hari', $todayName)
            ->first();

        return response()->json([
            'sudah_absen' => $absensi ? true : false,
            'jam_kerja' => $jamKerjaAktif,
            'dalam_jam_kerja' => $this->isDalamJamKerja($jamKerjaAktif),
        ]);
    }

    private function isDalamJamKerja($jamKerja)
    {
        if (!$jamKerja)
            return false;

        $now = Carbon::now()->format('H:i:s');
        return $now >= $jamKerja->jam_masuk && $now <= $jamKerja->jam_keluar;
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_selfie' => 'required|string', // base64
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        $existingAbsensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAbsensi) {
            return response()->json(['error' => 'Anda sudah absen hari ini'], 400);
        }

        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $todayName = $hariMap[$today->format('l')];

        $jamKerja = JamKerja::where('is_active', true)
            ->where('hari', $todayName)
            ->first();
        if (!$jamKerja) {
            return response()->json(['error' => 'Jam kerja belum diatur'], 400);
        }

        $fotoBase64 = $request->foto_selfie;
        $fotoBase64 = str_replace('data:image/png;base64,', '', $fotoBase64);
        $fotoBase64 = str_replace(' ', '+', $fotoBase64);
        $fotoData = base64_decode($fotoBase64);

        $fileName = 'absensi/' . $user->id . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, $fotoData);

        $jamAbsen = Carbon::now();
        $jamMasukWithToleransi = Carbon::parse($jamKerja->jam_masuk)
            ->addMinutes($jamKerja->toleransi_menit);

        $status = $jamAbsen->lte($jamMasukWithToleransi) ? 'tepat_waktu' : 'terlambat';

        $absensi = Absensi::create([
            'user_id' => $user->id,
            'jam_kerja_id' => $jamKerja->id,
            'tanggal' => $today,
            'jam_absen' => $jamAbsen->format('H:i:s'),
            'foto_selfie' => $fileName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $status,
        ]);

        \App\Models\ActivityLog::log(
            'absensi',
            "Absensi {$status} - {$user->name}",
            $user->id,
            $user->name,
            '📸',
            $status === 'tepat_waktu' ? 'green' : 'orange'
        );

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dikirim ke admin',
            'status' => $status,
        ]);
    }

    // Admin: View riwayat absensi
    public function index(Request $request)
    {
        $query = Absensi::with(['user', 'jamKerja'])->latest('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensis = $query->paginate(20);

        $karyawans = \App\Models\User::where('role', 'karyawan')->get();

        return view('pages.absensi.index', compact('absensis', 'karyawans'));
    }
}