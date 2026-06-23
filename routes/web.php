<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'role.selected'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::get('/notifikasi/count', [NotifikasiController::class, 'count'])->name('notifikasi.count');

    // ADMIN — tracking dan resource (termasuk /pesanans/create) didaftarkan
    // duluan supaya tidak tertangkap sebagai {pesanan} oleh route show di bawah.
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/pesanans/tracking', [PesananController::class, 'tracking'])->name('pesanans.tracking');
        Route::resource('customers', CustomerController::class);
        Route::resource('pesanans', PesananController::class)->except(['show']);
        Route::post('/pesanans/{pesanan}/upload-bukti', [PesananController::class, 'uploadBukti'])->name('pesanans.uploadBukti');
        Route::get('/pesanans/{pesanan}/invoice', [PesananController::class, 'invoice'])->name('pesanans.invoice');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Bisa diakses semua role yang sudah login (Admin, Finance, Produksi) --
    // dipakai bersama saat klik notifikasi atau perlu lihat detail pesanan
    // dari alur kerja masing-masing role. Didaftarkan SETELAH semua route
    // statis (tracking, create, dst) supaya {pesanan} tidak menelan kata
    // kunci tersebut.
    Route::get('/pesanans/{pesanan}', [PesananController::class, 'show'])->name('pesanans.show');

    // FINANCE
    Route::middleware(['role:finance'])->group(function () {
        Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('pembayarans.index');
        Route::get('/pembayarans/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayarans.show');
        Route::post('/pembayarans/{pembayaran}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayarans.verifikasi');
    });

    // PRODUKSI
    Route::middleware(['role:produksi'])->group(function () {
        Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
        Route::get('/produksi/{pesanan}', [ProduksiController::class, 'show'])->name('produksi.show');
        Route::post('/produksi/{pesanan}/mulai', [ProduksiController::class, 'mulaiProduksi'])->name('produksi.mulai');
        Route::post('/produksi/{pesanan}/update-status', [ProduksiController::class, 'updateStatus'])->name('produksi.updateStatus');
        Route::post('/produksi/{pesanan}/estimasi', [ProduksiController::class, 'updateEstimasi'])->name('produksi.estimasi');
        Route::post('/produksi/{pesanan}/kirim', [ProduksiController::class, 'inputPengiriman'])->name('produksi.kirim');
        Route::resource('produks', ProdukController::class)->except(['show']);
    });
});