<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isProduksi(): bool
    {
        return $this->role === 'produksi';
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'admin_id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifikasis()
    {
        return $this->notifikasis()->where('is_read', false);
    }
}