<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::all();
        return view('pages.paket.index', compact('pakets'));
    }

    public function create()
    {
        return view('pages.paket.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:pakets,kode',
            'harga' => 'required|integer|min:0',
            'satuan' => 'required|in:kg,pcs',
            'estimasi_hari' => 'nullable|integer|min:1', // Nullable untuk PCS
            'is_express' => 'boolean',
            'keterangan' => 'nullable|string',
        ]);

        if ($validated['satuan'] === 'kg') {
            $validated['jenis_layanan'] = 'cuci_setrika'; // default untuk KG
            Paket::create($validated);
            return redirect()->route('paket.index')->with('toast', [
                'variant' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Paket berhasil ditambahkan'
            ]);
        }

        $hargaDasar = $validated['harga'];
        $jenisLayanan = [
            [
                'jenis' => 'cuci_saja',
                'harga' => $hargaDasar - 500,  // -500
                'estimasi_hari' => 3,  // 2-3 hari (kita set 3)
                'kode_suffix' => '-CS'
            ],
            [
                'jenis' => 'cuci_setrika',
                'harga' => $hargaDasar,  // harga dasar
                'estimasi_hari' => 3,  // 2-3 hari (kita set 3)
                'kode_suffix' => '-CST'
            ],
            [
                'jenis' => 'kilat',
                'harga' => $hargaDasar + 2000,  // +2000
                'estimasi_hari' => 0,  // 3 jam (kita set 0 hari karena < 1 hari)
                'kode_suffix' => '-KL'
            ],
        ];

        foreach ($jenisLayanan as $jenis) {
            Paket::create([
                'nama' => $validated['nama'],
                'kode' => $validated['kode'] . $jenis['kode_suffix'],
                'harga' => $jenis['harga'],
                'satuan' => $validated['satuan'],
                'jenis_layanan' => $jenis['jenis'],
                'estimasi_hari' => $jenis['estimasi_hari'],
                'is_express' => false, // PCS tidak pakai express
                'keterangan' => $validated['keterangan'],
            ]);
        }

        return redirect()->route('paket.index')->with('toast', [
            'variant' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Paket satuan berhasil ditambahkan dengan 3 jenis layanan'
        ]);
    }

    public function update(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:pakets,kode,' . $paket->id,
            'harga' => 'required|integer|min:0',
            'satuan' => 'required|in:kg,pcs',
            'estimasi_hari' => 'required|integer|min:1',
            'is_express' => 'boolean',
            'keterangan' => 'nullable|string',
        ]);

        $validated['is_express'] = $request->has('is_express') ? 1 : 0;

        $paket->update($validated);

        return redirect()->route('paket.index')->with('toast', [
            'variant' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Paket berhasil diupdate'
        ]);
    }

    public function destroy(Paket $paket)
    {
        $namaPaket = $paket->nama;
        $paket->delete();

        return redirect()->route('paket.index')->with('toast', [
            'variant' => 'success',
            'title' => 'Berhasil!',
            'message' => "Paket {$namaPaket} berhasil dihapus"
        ]);
    }
}
