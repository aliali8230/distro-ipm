<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin IPM',
            'email' => 'admin@distroipm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Finance IPM',
            'email' => 'finance@distroipm.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
        ]);

        User::create([
            'nama' => 'Produksi IPM',
            'email' => 'produksi@distroipm.com',
            'password' => Hash::make('password'),
            'role' => 'produksi',
        ]);
    }
}