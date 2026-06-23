<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Setelah login berhasil, user diarahkan ke halaman pilih role
     * (bukan langsung ke dashboard) sesuai alur SKPL.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Pastikan tidak ada sisa active_role dari sesi sebelumnya
        $request->session()->forget('active_role');

        return redirect()->route('pilih-role');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('active_role');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}