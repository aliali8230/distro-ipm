<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pesanan',
        'customer_id',
        'admin_id',
        'tanggal_pesan',
        'status_pesanan',
        'jasa_kurir',
        'ongkir',
        'total_harga',
        'nominal_dp',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
        'total_harga' => 'decimal:2',
        'nominal_dp' => 'decimal:2',
        'ongkir' => 'decimal:2',
    ];

    public static function generateNomorPesanan(): string
    {
        $prefix = 'IPM-' . date('Ymd');
        $last = self::where('nomor_pesanan', 'like', $prefix . '%')->latest()->first();
        $seq = $last ? (int) substr($last->nomor_pesanan, -4) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pembayaranDp()
    {
        return $this->hasOne(Pembayaran::class)->where('jenis_pembayaran', 'dp');
    }

    public function pembayaranLunas()
    {
        return $this->hasOne(Pembayaran::class)->where('jenis_pembayaran', 'pelunasan');
    }

    public function produksi()
    {
        return $this->hasOne(Produksi::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }

    public function getLabelStatusAttribute(): string
    {
        return match($this->status_pesanan) {
            'pesanan_masuk' => 'Pesanan Masuk',
            'menunggu_verifikasi_dp' => 'Menunggu Verifikasi DP',
            'dp_terverifikasi' => 'DP Terverifikasi',
            'dalam_produksi' => 'Dalam Produksi',
            'selesai_produksi' => 'Selesai Produksi',
            'menunggu_verifikasi_lunas' => 'Menunggu Verifikasi Lunas',
            'lunas' => 'Lunas',
            'dikirim' => 'Dikirim',
            default => '-',
        };
    }

    public function getBadgeStatusAttribute(): string
    {
        return match($this->status_pesanan) {
            'pesanan_masuk' => 'secondary',
            'menunggu_verifikasi_dp' => 'warning',
            'dp_terverifikasi' => 'info',
            'dalam_produksi' => 'primary',
            'selesai_produksi' => 'success',
            'menunggu_verifikasi_lunas' => 'warning',
            'lunas' => 'success',
            'dikirim' => 'dark',
            default => 'secondary',
        };
    }
}