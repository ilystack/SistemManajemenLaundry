<?php

namespace App\Http\Controllers;

use App\Models\JamKerja;
use Illuminate\Http\Request;

class JamKerjaController extends Controller
{
    public function index()
    {
        $jamKerjas = JamKerja::latest()->get();
        return view('pages.jam-kerja.index', compact('jamKerjas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk',
            'toleransi_menit' => 'required|integer|min:0',
        ]);

        JamKerja::create($request->all());

        return redirect()->route('jam-kerja.index')
            ->with('toast', ['variant' => 'success', 'title' => 'Berhasil', 'message' => 'Jam kerja berhasil ditambahkan']);
    }

    public function update(Request $request, JamKerja $jamKerja)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk',
            'toleransi_menit' => 'required|integer|min:0',
        ]);

        $jamKerja->update($request->all());

        return redirect()->route('jam-kerja.index')
            ->with('toast', ['variant' => 'success', 'title' => 'Berhasil', 'message' => 'Jam kerja berhasil diupdate']);
    }

    public function toggleActive(JamKerja $jamKerja)
    {
        // Toggle active status
        $jamKerja->update(['is_active' => !$jamKerja->is_active]);

        return redirect()->route('jam-kerja.index')
            ->with('toast', ['variant' => 'success', 'title' => 'Berhasil', 'message' => 'Status jam kerja berhasil diubah']);
    }

    public function destroy(JamKerja $jamKerja)
    {
        $jamKerja->delete();

        return redirect()->route('jam-kerja.index')
            ->with('toast', ['variant' => 'success', 'title' => 'Berhasil', 'message' => 'Jam kerja berhasil dihapus']);
    }
}