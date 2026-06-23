<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleSelected
{
    /**
     * Pastikan user sudah memilih role (active_role di session) sebelum
     * mengakses halaman manapun di dalam sistem. Kalau belum, lempar ke
     * halaman pilih role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('active_role')) {
            return redirect()->route('pilih-role');
        }

        return $next($request);
    }
}