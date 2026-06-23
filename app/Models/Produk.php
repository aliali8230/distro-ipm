<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga',
        'stok',
    ];

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 5) return 'kritis';
        if ($this->stok <= 15) return 'rendah';
        return 'aman';
    }
}