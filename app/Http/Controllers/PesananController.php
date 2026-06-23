<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Http\Requests\StorePesananRequest;
use App\Http\Requests\UploadBuktiRequest;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('customer')->latest();

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pesanan', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('nama_customer', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pesan', [$request->dari, $request->sampai]);
        }

        $pesanans = $query->paginate(10)->withQueryString();
        return view('pesanans.index', compact('pesanans'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $produks   = Produk::orderBy('nama_produk')->get();
        return view('pesanans.create', compact('customers', 'produks'));
    }

    public function store(StorePesananRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $subtotalItems = 0;

            foreach ($data['produk_id'] as $i => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $subtotalItems += $produk->harga * $data['jumlah'][$i];
            }

            $totalHarga = $subtotalItems + $data['ongkir'];
            $nominalDp  = $totalHarga * 0.5;

            $pesanan = Pesanan::create([
                'nomor_pesanan' => Pesanan::generateNomorPesanan(),
                'customer_id'   => $data['customer_id'],
                'admin_id'      => auth()->id(),
                'tanggal_pesan' => $data['tanggal_pesan'],
                'jasa_kurir'    => $data['jasa_kurir'],
                'ongkir'        => $data['ongkir'],
                'total_harga'   => $totalHarga,
                'nominal_dp'    => $nominalDp,
                'catatan'       => $data['catatan'] ?? null,
                'status_pesanan'=> 'pesanan_masuk',
            ]);

            foreach ($data['produk_id'] as $i => $produkId) {
                $produk = Produk::findOrFail($produkId);
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'produk_id'    => $produkId,
                    'jumlah'       => $data['jumlah'][$i],
                    'harga_satuan' => $produk->harga,
                    'subtotal'     => $produk->harga * $data['jumlah'][$i],
                ]);
            }

            DB::commit();
            return redirect()->route('pesanans.show', $pesanan)
                             ->with('success', "Pesanan {$pesanan->nomor_pesanan} berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['customer', 'detailPesanans.produk', 'pembayarans.verifikator', 'produksi.operator', 'pengiriman', 'admin']);
        return view('pesanans.show', compact('pesanan'));
    }

    public function edit(Pesanan $pesanan)
    {
        if (!in_array($pesanan->status_pesanan, ['pesanan_masuk'])) {
            return back()->with('error', 'Pesanan tidak dapat diedit pada status ini.');
        }
        $customers = Customer::orderBy('nama_customer')->get();
        $produks   = Produk::orderBy('nama_produk')->get();
        return view('pesanans.edit', compact('pesanan', 'customers', 'produks'));
    }

    public function update(StorePesananRequest $request, Pesanan $pesanan)
    {
        if (!in_array($pesanan->status_pesanan, ['pesanan_masuk'])) {
            return back()->with('error', 'Pesanan tidak dapat diedit.');
        }

        DB::beginTransaction();
        try {
            $data = $request->validated();
            $subtotalItems = 0;

            foreach ($data['produk_id'] as $i => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $subtotalItems += $produk->harga * $data['jumlah'][$i];
            }

            $totalHarga = $subtotalItems + $data['ongkir'];

            $pesanan->update([
                'customer_id'  => $data['customer_id'],
                'tanggal_pesan'=> $data['tanggal_pesan'],
                'jasa_kurir'   => $data['jasa_kurir'],
                'ongkir'       => $data['ongkir'],
                'total_harga'  => $totalHarga,
                'nominal_dp'   => $totalHarga * 0.5,
                'catatan'      => $data['catatan'] ?? null,
            ]);

            $pesanan->detailPesanans()->delete();
            foreach ($data['produk_id'] as $i => $produkId) {
                $produk = Produk::findOrFail($produkId);
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'produk_id'    => $produkId,
                    'jumlah'       => $data['jumlah'][$i],
                    'harga_satuan' => $produk->harga,
                    'subtotal'     => $produk->harga * $data['jumlah'][$i],
                ]);
            }

            DB::commit();
            return redirect()->route('pesanans.show', $pesanan)->with('success', 'Pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'pesanan_masuk') {
            return back()->with('error', 'Pesanan yang sudah diproses tidak dapat dihapus.');
        }
        $pesanan->delete();
        return redirect()->route('pesanans.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    // FR-04: Upload Bukti Pembayaran
    public function uploadBukti(UploadBuktiRequest $request, Pesanan $pesanan)
    {
        $data = $request->validated();

        $path = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

        $pesanan->pembayarans()->create([
            'jenis_pembayaran'   => $data['jenis_pembayaran'],
            'tanggal_pembayaran' => $data['tanggal_pembayaran'],
            'nominal'            => $data['nominal'],
            'bukti_transfer'     => $path,
            'status_verifikasi'  => 'menunggu',
        ]);

        $statusBaru = $data['jenis_pembayaran'] === 'dp'
            ? 'menunggu_verifikasi_dp'
            : 'menunggu_verifikasi_lunas';

        $pesanan->update(['status_pesanan' => $statusBaru]);

        NotifikasiService::kirimKeFinance(
            'Bukti Pembayaran Masuk',
            "Pesanan {$pesanan->nomor_pesanan} membutuhkan verifikasi " . strtoupper($data['jenis_pembayaran']),
            'payment',
            $pesanan->id
        );

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi Finance.');
    }

    // FR-10: Tracking Pesanan
    public function tracking(Request $request)
    {
        $query = Pesanan::with(['customer', 'produksi', 'pengiriman'])->latest();

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('nomor_pesanan', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('nama_customer', 'like', "%{$request->search}%"));
        }

        $pesanans = $query->paginate(10)->withQueryString();
        return view('pesanans.tracking', compact('pesanans'));
    }

    // FR-03: Invoice
    public function invoice(Pesanan $pesanan)
    {
        $pesanan->load(['customer', 'detailPesanans.produk', 'admin']);
        return view('pesanans.invoice', compact('pesanan'));
    }
}