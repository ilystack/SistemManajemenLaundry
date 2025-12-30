<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'harga',
        'satuan',
        'jenis_layanan',
        'estimasi_hari',
        'is_express',
        'keterangan',
    ];

    protected $casts = [
        'harga' => 'integer',
        'estimasi_hari' => 'integer',
        'is_express' => 'boolean',
    ];

    public function getJenisLayananLabelAttribute()
    {
        $labels = [
            'cuci_saja' => 'Cuci Saja',
            'cuci_setrika' => 'Cuci + Setrika',
            'kilat' => 'Kilat',
        ];
        return $labels[$this->jenis_layanan] ?? 'Cuci + Setrika';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

