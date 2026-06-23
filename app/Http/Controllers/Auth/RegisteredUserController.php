<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Daftarkan user baru ke tabel users.
     *
     * Catatan alur: form register HANYA untuk membuat akun (nama, email,
     * password). Role (admin/finance/produksi) TIDAK dipilih bebas oleh
     * user saat daftar -- role di-set oleh pengelola sistem lewat
     * database/seeder. Default role akun baru adalah 'admin' dan bisa
     * diubah manual sesuai kebutuhan tim.
     *
     * Setelah daftar, user diarahkan ke halaman LOGIN (bukan auto-login),
     * supaya alurnya konsisten dengan login -> pilih role.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        event(new Registered($user));

        return redirect()->route('login')
            ->with('status', 'Akun berhasil dibuat. Silakan login.');
    }
}