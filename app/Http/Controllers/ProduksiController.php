<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\Pengiriman;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    // FR-06: Daftar pesanan siap produksi
    public function index(Request $request)
    {
        $query = Pesanan::with(['customer', 'produksi'])
            ->whereIn('status_pesanan', ['dp_terverifikasi', 'dalam_produksi', 'selesai_produksi', 'lunas'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $pesanans = $query->paginate(10)->withQueryString();
        return view('produksi.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['customer', 'detailPesanans.produk', 'produksi.operator', 'pengiriman']);
        return view('produksi.show', compact('pesanan'));
    }

    // FR-06: Konfirmasi mulai produksi
    public function mulaiProduksi(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'estimasi_selesai' => 'required|date|after_or_equal:' . $pesanan->tanggal_pesan->format('Y-m-d'),
        ], [
            'estimasi_selesai.after_or_equal' => 'Estimasi selesai tidak boleh sebelum tanggal pesanan.',
        ]);

        Produksi::create([
            'pesanan_id'      => $pesanan->id,
            'tanggal_mulai'   => today(),
            'estimasi_selesai'=> $request->estimasi_selesai,
            'status_produksi' => 'dalam_produksi',
            'dikerjakan_oleh' => auth()->id(),
        ]);

        $pesanan->update(['status_pesanan' => 'dalam_produksi']);

        // Kurangi stok produk
        foreach ($pesanan->detailPesanans as $detail) {
            $detail->produk->decrement('stok', $detail->jumlah);
        }

        NotifikasiService::kirimKeAdmin(
            'Produksi Dimulai',
            "Pesanan {$pesanan->nomor_pesanan} telah mulai diproduksi.",
            'produksi',
            $pesanan->id
        );

        return back()->with('success', 'Produksi berhasil dimulai.');
    }

    // FR-07: Update status produksi
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status_produksi' => 'required|in:dalam_produksi,selesai',
            'catatan'         => 'nullable|string',
        ]);

        $produksi = $pesanan->produksi;
        $produksi->update([
            'status_produksi' => $request->status_produksi,
            'catatan'         => $request->catatan,
            'tanggal_selesai' => $request->status_produksi === 'selesai' ? today() : null,
        ]);

        if ($request->status_produksi === 'selesai') {
            $pesanan->update(['status_pesanan' => 'selesai_produksi']);

            NotifikasiService::kirimKeAdmin(
                'Produksi Selesai',
                "Pesanan {$pesanan->nomor_pesanan} selesai diproduksi. Minta pelunasan dari customer.",
                'produksi',
                $pesanan->id
            );
        }

        return back()->with('success', 'Status produksi berhasil diperbarui.');
    }

    // FR-08: Update estimasi
    public function updateEstimasi(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'estimasi_selesai' => 'required|date|after_or_equal:' . $pesanan->tanggal_pesan->format('Y-m-d'),
        ]);

        $pesanan->produksi->update(['estimasi_selesai' => $request->estimasi_selesai]);

        NotifikasiService::kirimKeAdmin(
            'Estimasi Diperbarui',
            "Estimasi selesai pesanan {$pesanan->nomor_pesanan} diperbarui ke " . $request->estimasi_selesai,
            'produksi',
            $pesanan->id
        );

        return back()->with('success', 'Estimasi berhasil diperbarui.');
    }

    // FR-09: Input pengiriman
    public function inputPengiriman(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'nomor_resi'   => 'required|string|max:100',
            'tanggal_kirim'=> 'required|date',
        ], [
            'nomor_resi.required' => 'Nomor resi wajib diisi.',
        ]);

        if ($pesanan->status_pesanan !== 'lunas') {
            return back()->with('error', 'Pesanan belum lunas. Tidak dapat diproses pengiriman.');
        }

        Pengiriman::create([
            'pesanan_id'        => $pesanan->id,
            'jasa_kurir'        => $pesanan->jasa_kurir,
            'nomor_resi'        => $request->nomor_resi,
            'tanggal_kirim'     => $request->tanggal_kirim,
            'status_pengiriman' => 'dikirim',
        ]);

        $pesanan->update(['status_pesanan' => 'dikirim']);

        NotifikasiService::kirimKeAdmin(
            'Pesanan Dikirim',
            "Pesanan {$pesanan->nomor_pesanan} telah dikirim via {$pesanan->jasa_kurir}, resi: {$request->nomor_resi}",
            'pengiriman',
            $pesanan->id
        );

        return back()->with('success', 'Data pengiriman berhasil disimpan.');
    }
}