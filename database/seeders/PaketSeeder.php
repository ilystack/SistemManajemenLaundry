<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        Paket::create([
            'nama' => 'CUCI SAJA',
            'kode' => 'KG-CUCI',
            'harga' => 6000,
            'satuan' => 'kg',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Paket cuci reguler tanpa setrika',
        ]);

        Paket::create([
            'nama' => 'CUCI + SETRIKA',
            'kode' => 'KG-CUCI-SETRIKA',
            'harga' => 7000,
            'satuan' => 'kg',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Paket cuci lengkap dengan setrika rapi',
        ]);

        Paket::create([
            'nama' => 'CUCI KILAT',
            'kode' => 'KG-KILAT',
            'harga' => 12000,
            'satuan' => 'kg',
            'estimasi_hari' => 1,
            'is_express' => true,
            'keterangan' => 'Paket express selesai 1 hari',
        ]);

        Paket::create([
            'nama' => 'Selimut',
            'kode' => 'PCS-SELIMUT-CUCI',
            'harga' => 9500,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_saja',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci selimut tanpa setrika',
        ]);

        Paket::create([
            'nama' => 'Selimut',
            'kode' => 'PCS-SELIMUT-SETRIKA',
            'harga' => 10000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_setrika',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci selimut dengan setrika',
        ]);

        Paket::create([
            'nama' => 'Selimut',
            'kode' => 'PCS-SELIMUT-KILAT',
            'harga' => 12000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'kilat',
            'estimasi_hari' => 0,
            'is_express' => true,
            'keterangan' => 'Cuci selimut express selesai hari yang sama',
        ]);

        Paket::create([
            'nama' => 'Baju / Kaos / Celana',
            'kode' => 'PCS-BAJU-CUCI',
            'harga' => 2000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_saja',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci baju/kaos/celana tanpa setrika',
        ]);

        Paket::create([
            'nama' => 'Baju / Kaos / Celana',
            'kode' => 'PCS-BAJU-SETRIKA',
            'harga' => 2500,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_setrika',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci baju/kaos/celana dengan setrika',
        ]);

        Paket::create([
            'nama' => 'Baju / Kaos / Celana',
            'kode' => 'PCS-BAJU-KILAT',
            'harga' => 4500,
            'satuan' => 'pcs',
            'jenis_layanan' => 'kilat',
            'estimasi_hari' => 0,
            'is_express' => true,
            'keterangan' => 'Cuci baju/kaos/celana express',
        ]);

        Paket::create([
            'nama' => 'Pakaian Dalam',
            'kode' => 'PCS-DALAM-CUCI',
            'harga' => 3500,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_saja',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci pakaian dalam tanpa setrika',
        ]);

        Paket::create([
            'nama' => 'Pakaian Dalam',
            'kode' => 'PCS-DALAM-SETRIKA',
            'harga' => 4000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_setrika',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci pakaian dalam dengan setrika',
        ]);

        Paket::create([
            'nama' => 'Pakaian Dalam',
            'kode' => 'PCS-DALAM-KILAT',
            'harga' => 6000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'kilat',
            'estimasi_hari' => 0,
            'is_express' => true,
            'keterangan' => 'Cuci pakaian dalam express',
        ]);

        Paket::create([
            'nama' => 'JAKET',
            'kode' => 'PCS-JAKET-CUCI',
            'harga' => 6500,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_saja',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci jaket tanpa setrika',
        ]);

        Paket::create([
            'nama' => 'JAKET',
            'kode' => 'PCS-JAKET-SETRIKA',
            'harga' => 7000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'cuci_setrika',
            'estimasi_hari' => 3,
            'is_express' => false,
            'keterangan' => 'Cuci jaket dengan setrika',
        ]);

        Paket::create([
            'nama' => 'JAKET',
            'kode' => 'PCS-JAKET-KILAT',
            'harga' => 9000,
            'satuan' => 'pcs',
            'jenis_layanan' => 'kilat',
            'estimasi_hari' => 0,
            'is_express' => true,
            'keterangan' => 'Cuci jaket express',
        ]);
    }
}
