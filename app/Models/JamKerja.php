<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;

class JamKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'hari',
        'jam_masuk',
        'jam_keluar',
        'toleransi_menit',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}