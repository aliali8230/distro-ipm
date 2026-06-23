<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_pesan');
            $table->enum('status_pesanan', [
                'pesanan_masuk',
                'menunggu_verifikasi_dp',
                'dp_terverifikasi',
                'dalam_produksi',
                'selesai_produksi',
                'menunggu_verifikasi_lunas',
                'lunas',
                'dikirim'
            ])->default('pesanan_masuk');
            $table->string('jasa_kurir');
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('nominal_dp', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};