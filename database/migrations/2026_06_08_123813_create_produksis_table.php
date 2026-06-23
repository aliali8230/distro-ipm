<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->date('tanggal_mulai')->nullable();
            $table->date('estimasi_selesai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_produksi', [
                'menunggu',
                'dalam_produksi',
                'selesai'
            ])->default('menunggu');
            $table->foreignId('dikerjakan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};