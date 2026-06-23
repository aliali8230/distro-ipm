<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek role AKTIF (hasil pilihan di halaman pilih-role), bukan langsung
     * kolom role di database. Ini memastikan user benar-benar sudah
     * melewati layar pilih role sebelum mengakses halaman ber-role.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $activeRole = $request->session()->get('active_role');

        if (!$activeRole) {
            return redirect()->route('pilih-role');
        }

        if (!in_array($activeRole, $roles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}