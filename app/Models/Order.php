<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'paket_id',
        'jumlah',
        'pickup',
        'jarak_km',
        'biaya_pickup',
        'total_harga',
        'antrian',
        'status',
        'payment_method',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'jarak_km' => 'decimal:2',
        'biaya_pickup' => 'integer',
        'total_harga' => 'integer',
        'antrian' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
