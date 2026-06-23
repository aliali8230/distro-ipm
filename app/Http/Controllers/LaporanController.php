<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $status = $request->get('status');

        $query = Pesanan::with('customer')
            ->whereBetween('tanggal_pesan', [$dari, $sampai]);

        if ($status) {
            $query->where('status_pesanan', $status);
        }

        $pesanans = $query->latest()->get();

        $totalPendapatan = $pesanans->whereIn('status_pesanan', ['lunas', 'dikirim'])->sum('total_harga');

        $rekapStatus = $pesanans->groupBy('status_pesanan')
            ->map->count();

        return view('laporan.index', compact('pesanans', 'totalPendapatan', 'rekapStatus', 'dari', 'sampai', 'status'));
    }
}