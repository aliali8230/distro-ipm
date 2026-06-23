<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'tanggal_mulai',
        'estimasi_selesai',
        'tanggal_selesai',
        'status_produksi',
        'dikerjakan_oleh',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'estimasi_selesai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'dikerjakan_oleh');
    }
}