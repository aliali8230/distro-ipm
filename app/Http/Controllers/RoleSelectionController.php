<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleSelectionController extends Controller
{
    /**
     * Tampilkan halaman pilih role.
     * Hanya role milik akun yang login yang aktif/clickable.
     */
    public function create(): View
    {
        return view('auth.pilih-role');
    }

    /**
     * Simpan role aktif ke session lalu lempar ke dashboard.
     * Role yang dikirim WAJIB sama dengan role asli akun (server-side check),
     * supaya disable di UI tidak bisa dilewati lewat request manual.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:admin,finance,produksi'],
        ]);

        if ($request->role !== $request->user()->role) {
            return back()->withErrors([
                'role' => 'Anda tidak memiliki akses ke role tersebut.',
            ]);
        }

        $request->session()->put('active_role', $request->role);

        return redirect()->route('dashboard');
    }
}