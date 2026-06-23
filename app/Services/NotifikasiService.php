<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    public static function kirim(int $userId, string $judul, string $pesan, string $tipe, ?int $pesananId = null): void
    {
        Notifikasi::create([
            'user_id'    => $userId,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'tipe'       => $tipe,
            'pesanan_id' => $pesananId,
            'is_read'    => false,
        ]);
    }

    public static function kirimKeRole(string $role, string $judul, string $pesan, string $tipe, ?int $pesananId = null): void
    {
        $users = User::where('role', $role)->get();
        foreach ($users as $user) {
            self::kirim($user->id, $judul, $pesan, $tipe, $pesananId);
        }
    }

    public static function kirimKeAdmin(string $judul, string $pesan, string $tipe, ?int $pesananId = null): void
    {
        self::kirimKeRole('admin', $judul, $pesan, $tipe, $pesananId);
    }

    public static function kirimKeProduksi(string $judul, string $pesan, string $tipe, ?int $pesananId = null): void
    {
        self::kirimKeRole('produksi', $judul, $pesan, $tipe, $pesananId);
    }

    public static function kirimKeFinance(string $judul, string $pesan, string $tipe, ?int $pesananId = null): void
    {
        self::kirimKeRole('finance', $judul, $pesan, $tipe, $pesananId);
    }
}