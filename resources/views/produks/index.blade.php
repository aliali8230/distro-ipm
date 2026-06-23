@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('produks.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Produk Baru
    </a>
</div>

{{-- Ringkasan Stok (sesuai mockup: Total Produk / Total Stok / Stok Kritis) --}}
<div class="card mb-4" style="background:linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color:#fff; border-radius:16px;">
    <div class="card-body py-3">
        <div class="mb-2" style="font-size:.85rem; opacity:.85; font-weight:500;">Ringkasan Stok</div>
        <div class="row text-center g-2">
            <div class="col-4">
                <div class="ringkasan-stok-value">{{ $ringkasan['total_produk'] }}</div>
                <div style="font-size:.7rem; opacity:.8;">Total Produk</div>
            </div>
            <div class="col-4" style="border-left:1px solid rgba(255,255,255,.25); border-right:1px solid rgba(255,255,255,.25);">
                <div class="ringkasan-stok-value">{{ $ringkasan['total_stok'] }}</div>
                <div style="font-size:.7rem; opacity:.8;">Total Stok</div>
            </div>
            <div class="col-4">
                <div class="ringkasan-stok-value">{{ $ringkasan['stok_kritis'] }}</div>
                <div style="font-size:.7rem; opacity:.8;">Stok Kritis</div>
            </div>
        </div>
    </div>
</div>

<style>
    .ringkasan-stok-value { font-size: 1.6rem; font-weight: 700; }
    @media (max-width: 400px) {
        .ringkasan-stok-value { font-size: 1.25rem; }
    }
</style>

<div class="d-flex gap-2 flex-wrap mb-3">
    <span class="badge py-2 px-3" style="background:#fef2f2;color:#ef4444;font-size:.8rem">
        <i class="bi bi-circle-fill me-1"></i>Kritis (≤5)
    </span>
    <span class="badge py-2 px-3" style="background:#fffbeb;color:#f59e0b;font-size:.8rem">
        <i class="bi bi-circle-fill me-1"></i>Rendah (≤15)
    </span>
    <span class="badge py-2 px-3" style="background:#ecfdf5;color:#10b981;font-size:.8rem">
        <i class="bi bi-circle-fill me-1"></i>Aman (>15)
    </span>
</div>

<div class="row g-3">
    @forelse($produks as $p)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100" style="border-left: 4px solid {{ $p->status_stok === 'kritis' ? '#ef4444' : ($p->status_stok === 'rendah' ? '#f59e0b' : '#10b981') }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-semibold" style="font-size:.97rem">{{ $p->nama_produk }}</div>
                        <div class="text-muted mt-1" style="font-size:.8rem">{{ $p->kategori }}</div>
                    </div>
                    <span class="badge fs-6 px-3 py-1"
                        style="background:{{ $p->status_stok === 'kritis' ? '#fef2f2' : ($p->status_stok === 'rendah' ? '#fffbeb' : '#ecfdf5') }};color:{{ $p->status_stok === 'kritis' ? '#ef4444' : ($p->status_stok === 'rendah' ? '#f59e0b' : '#10b981') }}">
                        {{ $p->stok }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('produks.edit', $p) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('produks.destroy', $p) }}" method="POST"
                            onsubmit="return confirm('Hapus produk {{ $p->nama_produk }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-5">
        <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
        Belum ada produk terdaftar.
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $produks->links() }}</div>

@endsection