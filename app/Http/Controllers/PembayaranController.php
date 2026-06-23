<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('pesanan.customer')->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $pembayarans = $query->paginate(10)->withQueryString();
        return view('pembayarans.index', compact('pembayarans'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pesanan.customer', 'pesanan.detailPesanans.produk', 'verifikator']);
        return view('pembayarans.show', compact('pembayaran'));
    }

    // FR-05: Verifikasi Pembayaran
    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'keputusan'        => 'required|in:valid,ditolak',
            'catatan_finance'  => 'nullable|string|max:500',
        ]);

        $pembayaran->update([
            'status_verifikasi'  => $request->keputusan,
            'catatan_finance'    => $request->catatan_finance,
            'diverifikasi_oleh'  => auth()->id(),
            'verified_at'        => now(),
        ]);

        $pesanan = $pembayaran->pesanan;

        if ($request->keputusan === 'valid') {
            if ($pembayaran->jenis_pembayaran === 'dp') {
                $pesanan->update(['status_pesanan' => 'dp_terverifikasi']);

                NotifikasiService::kirimKeAdmin(
                    'DP Terverifikasi',
                    "Pembayaran DP pesanan {$pesanan->nomor_pesanan} telah diverifikasi.",
                    'payment',
                    $pesanan->id
                );
                NotifikasiService::kirimKeProduksi(
                    'Pesanan Siap Diproduksi',
                    "Pesanan {$pesanan->nomor_pesanan} telah lunas DP dan siap diproses produksi.",
                    'produksi',
                    $pesanan->id
                );
            } elseif ($pembayaran->jenis_pembayaran === 'pelunasan') {
                $pesanan->update(['status_pesanan' => 'lunas']);

                NotifikasiService::kirimKeAdmin(
                    'Pelunasan Terverifikasi',
                    "Pelunasan pesanan {$pesanan->nomor_pesanan} telah diverifikasi. Pesanan siap dikirim.",
                    'payment',
                    $pesanan->id
                );
                NotifikasiService::kirimKeProduksi(
                    'Pesanan Lunas - Siap Kirim',
                    "Pelunasan pesanan {$pesanan->nomor_pesanan} telah diverifikasi. Silakan proses pengiriman.",
                    'pengiriman',
                    $pesanan->id
                );
            }
        } else {
            // ditolak — kembalikan ke status sebelumnya
            $statusSebelumnya = $pembayaran->jenis_pembayaran === 'dp'
                ? 'pesanan_masuk'
                : 'selesai_produksi';

            $pesanan->update(['status_pesanan' => $statusSebelumnya]);

            NotifikasiService::kirimKeAdmin(
                'Pembayaran Ditolak',
                "Pembayaran " . strtoupper($pembayaran->jenis_pembayaran) . " pesanan {$pesanan->nomor_pesanan} ditolak oleh Finance.",
                'payment',
                $pesanan->id
            );
        }

        return redirect()->route('pembayarans.index')
                         ->with('success', 'Verifikasi pembayaran berhasil disimpan.');
    }
}