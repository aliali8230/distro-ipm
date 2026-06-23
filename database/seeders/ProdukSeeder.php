<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produks = [
            ['nama_produk' => 'Kaos Polos Cotton Combed 30s', 'kategori' => 'Kaos', 'harga' => 75000, 'stok' => 50],
            ['nama_produk' => 'Kaos Sablon Custom', 'kategori' => 'Kaos', 'harga' => 95000, 'stok' => 30],
            ['nama_produk' => 'Jaket Hoodie Fleece', 'kategori' => 'Jaket', 'harga' => 185000, 'stok' => 20],
            ['nama_produk' => 'Topi Snapback Custom', 'kategori' => 'Aksesoris', 'harga' => 65000, 'stok' => 8],
            ['nama_produk' => 'Kemeja Flanel Kotak', 'kategori' => 'Kemeja', 'harga' => 125000, 'stok' => 3],
            ['nama_produk' => 'Celana Jogger', 'kategori' => 'Celana', 'harga' => 110000, 'stok' => 15],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}