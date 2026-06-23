<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->isAdmin()) {
            $data = [
                'total_pesanan'    => Pesanan::count(),
                'pesanan_baru'     => Pesanan::where('status_pesanan', 'pesanan_masuk')->count(),
                'total_customer'   => Customer::count(),
                'menunggu_verif'   => Pesanan::whereIn('status_pesanan', ['menunggu_verifikasi_dp', 'menunggu_verifikasi_lunas'])->count(),
                'pesanan_terbaru'  => Pesanan::with('customer')->latest()->take(5)->get(),
            ];
        } elseif ($user->isFinance()) {
            $data = [
                'menunggu_verif_dp'    => Pembayaran::where('status_verifikasi', 'menunggu')->where('jenis_pembayaran', 'dp')->count(),
                'menunggu_verif_lunas' => Pembayaran::where('status_verifikasi', 'menunggu')->where('jenis_pembayaran', 'pelunasan')->count(),
                'total_verif_hari_ini' => Pembayaran::where('status_verifikasi', 'valid')->whereDate('verified_at', today())->count(),
                'pembayaran_terbaru'   => Pembayaran::with('pesanan.customer')->where('status_verifikasi', 'menunggu')->latest()->take(5)->get(),
            ];
        } elseif ($user->isProduksi()) {
            $data = [
                'total_produk'          => Produk::count(),
                'stok_kritis'           => Produk::where('stok', '<=', 5)->count(),
                'pesanan_siap_produksi' => Pesanan::where('status_pesanan', 'dp_terverifikasi')->count(),
                'sedang_produksi'       => Pesanan::where('status_pesanan', 'dalam_produksi')->count(),
                'produk_stok_rendah'    => Produk::where('stok', '<=', 15)->orderBy('stok')->take(6)->get(),
            ];
        }

        return view('dashboard', compact('data'));
    }
}