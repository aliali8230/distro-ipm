<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'jenis_pembayaran',
        'tanggal_pembayaran',
        'nominal',
        'bukti_transfer',
        'status_verifikasi',
        'catatan_finance',
        'diverifikasi_oleh',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'verified_at' => 'datetime',
        'nominal' => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}