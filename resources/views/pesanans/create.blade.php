@extends('layouts.app')
@section('title', 'Buat Pesanan')
@section('page-title', 'Buat Pesanan Baru')

@section('content')

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Form Input Pesanan</div>
            <div class="card-body">
                <form action="{{ route('pesanans.store') }}" method="POST" id="formPesanan">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">— Pilih Customer —</option>
                                @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_customer }} ({{ $c->no_whatsapp }})
                                </option>
                                @endforeach
                            </select>
                            @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Pesan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pesan" class="form-control @error('tanggal_pesan') is-invalid @enderror"
                                value="{{ old('tanggal_pesan', date('Y-m-d')) }}" required>
                            @error('tanggal_pesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jasa Kurir <span class="text-danger">*</span></label>
                            <select name="jasa_kurir" class="form-select @error('jasa_kurir') is-invalid @enderror" required>
                                <option value="">— Pilih Kurir —</option>
                                @foreach(['JNE','J&T','SiCepat','Anteraja','Gosend','Grab Express','Ambil Sendiri'] as $k)
                                <option value="{{ $k }}" {{ old('jasa_kurir') === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('jasa_kurir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ongkir (Rp)</label>
                            <input type="number" name="ongkir" id="ongkir" class="form-control"
                                value="{{ old('ongkir', 0) }}" min="0">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Catatan</label>
                            <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Catatan tambahan (opsional)">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fw-semibold" style="font-size:.95rem">Detail Produk</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="tambahProduk">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Produk
                        </button>
                    </div>

                    <div id="produkContainer">
                        <div class="row g-2 mb-2 produk-row align-items-center">
                            <div class="col-12 col-md-6">
                                <select name="produk_id[]" class="form-select produk-select" required>
                                    <option value="">— Pilih Produk —</option>
                                    @foreach($produks as $p)
                                    <option value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}">
                                        {{ $p->nama_produk }} — Rp {{ number_format($p->harga, 0, ',', '.') }} (Stok: {{ $p->stok }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-7 col-md-2">
                                <input type="number" name="jumlah[]" class="form-control jumlah-input"
                                    placeholder="Qty" min="1" value="1" required>
                            </div>
                            <div class="col-12 col-md-3 order-md-0">
                                <input type="text" class="form-control subtotal-display bg-light" placeholder="Subtotal" readonly>
                            </div>
                            <div class="col-5 col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100 hapus-produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Pesanan
                        </button>
                        <a href="{{ route('pesanans.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card summary-sticky-card">
            <div class="card-header">Ringkasan Pesanan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal Produk</span>
                    <strong id="totalProduk">Rp 0</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ongkir</span>
                    <strong id="totalOngkir">Rp 0</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Total Harga</span>
                    <strong class="text-primary fs-5" id="totalHarga">Rp 0</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span style="color:#f59e0b;font-weight:500">DP (50%)</span>
                    <strong style="color:#f59e0b" id="nominalDp">Rp 0</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const produkData = @json($produks->keyBy('id'));

function formatRp(n) {
    return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
}

function hitungTotal() {
    let totalProduk = 0;
    document.querySelectorAll('.produk-row').forEach(row => {
        const sel = row.querySelector('.produk-select');
        const jml = parseInt(row.querySelector('.jumlah-input').value) || 0;
        if (sel.value) {
            const harga = parseInt(produkData[sel.value]?.harga || 0);
            const sub = harga * jml;
            row.querySelector('.subtotal-display').value = formatRp(sub);
            totalProduk += sub;
        }
    });
    const ongkir = parseInt(document.getElementById('ongkir').value) || 0;
    const total = totalProduk + ongkir;
    document.getElementById('totalProduk').textContent = formatRp(totalProduk);
    document.getElementById('totalOngkir').textContent = formatRp(ongkir);
    document.getElementById('totalHarga').textContent = formatRp(total);
    document.getElementById('nominalDp').textContent = formatRp(total * 0.5);
}

document.getElementById('ongkir').addEventListener('input', hitungTotal);
document.getElementById('produkContainer').addEventListener('change', e => {
    if (e.target.classList.contains('produk-select') || e.target.classList.contains('jumlah-input')) hitungTotal();
});
document.getElementById('produkContainer').addEventListener('input', e => {
    if (e.target.classList.contains('jumlah-input')) hitungTotal();
});
document.getElementById('tambahProduk').addEventListener('click', () => {
    const clone = document.querySelector('.produk-row').cloneNode(true);
    clone.querySelector('.produk-select').value = '';
    clone.querySelector('.jumlah-input').value = 1;
    clone.querySelector('.subtotal-display').value = '';
    document.getElementById('produkContainer').appendChild(clone);
});
document.getElementById('produkContainer').addEventListener('click', e => {
    if (e.target.closest('.hapus-produk')) {
        if (document.querySelectorAll('.produk-row').length > 1) {
            e.target.closest('.produk-row').remove();
            hitungTotal();
        }
    }
});
</script>
@endpush
@endsection