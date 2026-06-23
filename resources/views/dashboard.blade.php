@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ADMIN DASHBOARD --}}
@if(auth()->user()->isAdmin())
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-bag"></i></div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $data['total_pesanan'] ?? 0 }}</div>
            <div class="stat-sub">Semua pesanan</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-label">Pesanan Baru</div>
            <div class="stat-value">{{ $data['pesanan_baru'] ?? 0 }}</div>
            <div class="stat-sub">Menunggu tindakan</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-label">Total Customer</div>
            <div class="stat-value">{{ $data['total_customer'] ?? 0 }}</div>
            <div class="stat-sub">Customer terdaftar</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-shield-exclamation"></i></div>
            <div class="stat-label">Menunggu Verif</div>
            <div class="stat-value">{{ $data['menunggu_verif'] ?? 0 }}</div>
            <div class="stat-sub">Pembayaran pending</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pesanan Terbaru</span>
                <a href="{{ route('pesanans.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Buat Pesanan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive-wrap">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['pesanan_terbaru'] ?? [] as $p)
                        <tr style="cursor:pointer" onclick="window.location='{{ route('pesanans.show', $p) }}'">
                            <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                            <td>{{ $p->customer->nama_customer }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('pesanans.create') }}" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i>Buat Pesanan Baru
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-people me-1"></i>Data Customer
                </a>
                <a href="{{ route('pesanans.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-list-ul me-1"></i>Lihat Semua Pesanan
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- FINANCE DASHBOARD --}}
@if(auth()->user()->isFinance())

@php $totalMenunggu = ($data['menunggu_verif_dp'] ?? 0) + ($data['menunggu_verif_lunas'] ?? 0); @endphp
@if($totalMenunggu > 0)
<div class="card mb-4" style="background:#fffbeb; border:1px solid #fde68a;">
    <div class="card-body d-flex flex-wrap align-items-center gap-3 py-3">
        <div style="width:42px;height:42px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-exclamation-circle-fill" style="color:#f59e0b;font-size:1.3rem"></i>
        </div>
        <div class="flex-grow-1" style="min-width:160px">
            <div class="fw-semibold" style="color:#92400e;font-size:.92rem">Menunggu Verifikasi</div>
            <div style="color:#a16207;font-size:.83rem">
                Ada {{ $totalMenunggu }} pembayaran yang perlu diverifikasi
            </div>
        </div>
        <a href="{{ route('pembayarans.index') }}" class="fw-semibold text-decoration-none" style="color:#b45309;font-size:.85rem;white-space:nowrap">
            Lihat Pesanan <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-label">Menunggu Verif DP</div>
            <div class="stat-value">{{ $data['menunggu_verif_dp'] ?? 0 }}</div>
            <div class="stat-sub">Pembayaran DP pending</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-label">Menunggu Verif Lunas</div>
            <div class="stat-value">{{ $data['menunggu_verif_lunas'] ?? 0 }}</div>
            <div class="stat-sub">Pelunasan pending</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-label">Terverifikasi Hari Ini</div>
            <div class="stat-value">{{ $data['total_verif_hari_ini'] ?? 0 }}</div>
            <div class="stat-sub">Pembayaran valid</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Pembayaran Menunggu Verifikasi</span>
        <a href="{{ route('pembayarans.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive-wrap">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Customer</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['pembayaran_terbaru'] ?? [] as $pb)
                <tr>
                    <td class="fw-semibold">{{ $pb->pesanan->nomor_pesanan }}</td>
                    <td>{{ $pb->pesanan->customer->nama_customer }}</td>
                    <td><span class="badge bg-info text-dark">{{ strtoupper($pb->jenis_pembayaran) }}</span></td>
                    <td>Rp {{ number_format($pb->nominal, 0, ',', '.') }}</td>
                    <td><a href="{{ route('pembayarans.show', $pb) }}" class="btn btn-sm btn-primary">Verifikasi</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pembayaran pending.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endif

{{-- PRODUKSI DASHBOARD --}}
@if(auth()->user()->isProduksi())
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $data['total_produk'] ?? 0 }}</div>
            <div class="stat-sub">Jenis produk</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-label">Stok Kritis</div>
            <div class="stat-value">{{ $data['stok_kritis'] ?? 0 }}</div>
            <div class="stat-sub">Stok ≤ 5 pcs</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-play-circle"></i></div>
            <div class="stat-label">Siap Produksi</div>
            <div class="stat-value">{{ $data['pesanan_siap_produksi'] ?? 0 }}</div>
            <div class="stat-sub">DP terverifikasi</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="bi bi-tools"></i></div>
            <div class="stat-label">Dalam Produksi</div>
            <div class="stat-value">{{ $data['sedang_produksi'] ?? 0 }}</div>
            <div class="stat-sub">Sedang diproses</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('produksi.index') }}" class="btn btn-primary w-100">
                    <i class="bi bi-tools me-1"></i>Status Pesanan
                </a>
                <a href="{{ route('produks.index') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-box-seam me-1"></i>Manajemen Stok
                </a>
                <a href="{{ route('produks.create') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Produk Baru
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Stok Rendah / Kritis</div>
            <div class="card-body p-0">
                @forelse($data['produk_stok_rendah'] ?? [] as $p)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem">{{ $p->nama_produk }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $p->kategori }}</div>
                    </div>
                    <span class="badge bg-{{ $p->stok <= 5 ? 'danger' : 'warning text-dark' }}">
                        {{ $p->stok }} pcs
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-4">Semua stok aman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif

@endsection