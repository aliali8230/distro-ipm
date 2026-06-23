<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_customer',
        'no_whatsapp',
        'alamat',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}