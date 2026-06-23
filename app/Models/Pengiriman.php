<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimens';

    protected $fillable = [
        'pesanan_id',
        'jasa_kurir',
        'nomor_resi',
        'tanggal_kirim',
        'status_pengiriman',
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}