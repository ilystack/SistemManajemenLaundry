<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jam_kerja_id',
        'tanggal',
        'jam_absen',
        'foto_selfie',
        'latitude',
        'longitude',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke jam kerja
    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class);
    }
}