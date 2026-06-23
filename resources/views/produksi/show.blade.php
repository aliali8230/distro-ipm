@extends('layouts.app')
@section('title', 'Kelola Produksi')
@section('page-title', 'Kelola Produksi')

@section('content')

<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('produksi.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div>
        <span class="fw-bold fs-5">{{ $pesanan->nomor_pesanan }}</span>
        <span class="badge bg-{{ $pesanan->badge_status }} ms-2">{{ $pesanan->label_status }}</span>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        {{-- Info Pesanan --}}
        <div class="card mb-3">
            <div class="card-header">Informasi Pesanan</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Customer</div>
                        <div class="fw-semibold">{{ $pesanan->customer->nama_customer }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Kurir</div>
                        <div>{{ $pesanan->jasa_kurir }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Total</div>
                        <div class="fw-bold text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="card">
            <div class="card-header">Detail Produk</div>
            <div class="card-body p-0">
                <div class="table-responsive-wrap">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanans as $d)
                        <tr>
                            <td>{{ $d->produk->nama_produk }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        {{-- Mulai Produksi --}}
        @if($pesanan->status_pesanan === 'dp_terverifikasi')
        <div class="card mb-3" style="border:2px solid #10b981">
            <div class="card-header" style="background:#ecfdf5; color:#065f46;">
                <i class="bi bi-play-circle-fill me-2"></i>Mulai Produksi
            </div>
            <div class="card-body">
                <form action="{{ route('produksi.mulai', $pesanan) }}" method="POST">
                    @csrf
                    <label class="form-label">Estimasi Tgl Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="estimasi_selesai"
                        class="form-control mb-3 @error('estimasi_selesai') is-invalid @enderror" required>
                    @error('estimasi_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <button type="submit" class="btn w-100" style="background:#10b981;color:#fff">
                        <i class="bi bi-play-fill me-1"></i>Konfirmasi Mulai Produksi
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        @if($pesanan->status_pesanan === 'dalam_produksi')
        <div class="card mb-3" style="border:2px solid #4f6ef7">
            <div class="card-header" style="background:#eef1fe; color:#3d5ce0;">
                <i class="bi bi-arrow-repeat me-2"></i>Update Status Produksi
            </div>
            <div class="card-body">
                <form action="{{ route('produksi.updateStatus', $pesanan) }}" method="POST" class="mb-3">
                    @csrf
                    <label class="form-label">Status Baru</label>
                    <select name="status_produksi" class="form-select mb-2" required>
                        <option value="dalam_produksi">Dalam Produksi</option>
                        <option value="selesai">Selesai Produksi</option>
                    </select>
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control mb-2" rows="2" placeholder="Catatan progres..."></textarea>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i>Update Status
                    </button>
                </form>

                <hr>

                <form action="{{ route('produksi.estimasi', $pesanan) }}" method="POST">
                    @csrf
                    <label class="form-label">Perbarui Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" class="form-control mb-2"
                        value="{{ $pesanan->produksi?->estimasi_selesai?->format('Y-m-d') }}" required>
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-calendar me-1"></i>Update Estimasi
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Input Pengiriman --}}
        @if($pesanan->status_pesanan === 'lunas' && !$pesanan->pengiriman)
        <div class="card mb-3" style="border:2px solid #181f36">
            <div class="card-header" style="background:#f0f2f7; color:#181f36;">
                <i class="bi bi-truck me-2"></i>Input Pengiriman
            </div>
            <div class="card-body">
                <form action="{{ route('produksi.kirim', $pesanan) }}" method="POST">
                    @csrf
                    <label class="form-label">Jasa Kurir</label>
                    <input type="text" class="form-control mb-2 bg-light" value="{{ $pesanan->jasa_kurir }}" readonly>
                    <label class="form-label">Nomor Resi <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_resi"
                        class="form-control mb-2 @error('nomor_resi') is-invalid @enderror"
                        placeholder="Masukkan nomor resi" required>
                    @error('nomor_resi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <label class="form-label">Tanggal Kirim</label>
                    <input type="date" name="tanggal_kirim" class="form-control mb-3" value="{{ date('Y-m-d') }}" required>
                    <button type="submit" class="btn w-100" style="background:#181f36;color:#fff">
                        <i class="bi bi-send me-1"></i>Konfirmasi Pengiriman
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Info Pengiriman (jika sudah dikirim) --}}
        @if($pesanan->pengiriman)
        <div class="card">
            <div class="card-header">
                <i class="bi bi-truck me-2 text-success"></i>Info Pengiriman
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Kurir</div>
                    <div class="fw-semibold">{{ $pesanan->pengiriman->jasa_kurir }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">No. Resi</div>
                    <div class="fw-bold text-primary">{{ $pesanan->pengiriman->nomor_resi }}</div>
                </div>
                <div>
                    <div class="text-muted mb-1" style="font-size:.8rem">Tgl Kirim</div>
                    <div>{{ $pesanan->pengiriman->tanggal_kirim->format('d M Y') }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Info Produksi (jika ada) --}}
        @if($pesanan->produksi && $pesanan->status_pesanan !== 'dp_terverifikasi')
        <div class="card mt-3">
            <div class="card-header">Informasi Produksi</div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Tgl Mulai</div>
                    <div>{{ $pesanan->produksi->tanggal_mulai?->format('d M Y') ?? '—' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Est. Selesai</div>
                    <div>{{ $pesanan->produksi->estimasi_selesai?->format('d M Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-muted mb-1" style="font-size:.8rem">Operator</div>
                    <div>{{ $pesanan->produksi->operator?->nama ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection