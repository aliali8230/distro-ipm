@extends('layouts.app')
@section('title', 'Daftar Pesanan')
@section('page-title', 'Pesanan')

@section('content')

{{-- Filter & Action Row --}}
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="search" class="form-control form-control-sm filter-input"
            placeholder="Cari no. pesanan / customer..." value="{{ request('search') }}"
            style="--filter-w:230px; border-radius:9px">
        <select name="status" class="form-select form-select-sm filter-input" style="--filter-w:190px; border-radius:9px">
            <option value="">Semua Status</option>
            @foreach(['pesanan_masuk','menunggu_verifikasi_dp','dp_terverifikasi','dalam_produksi','selesai_produksi','menunggu_verifikasi_lunas','lunas','dikirim'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucwords(str_replace('_', ' ', $s)) }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-search me-1"></i>Cari
        </button>
        <a href="{{ route('pesanans.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </form>
    <a href="{{ route('pesanans.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Buat Pesanan
    </a>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive-wrap">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Kurir</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                <tr style="cursor:pointer" onclick="window.location='{{ route('pesanans.show', $p) }}'">
                    <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                    <td>{{ $p->customer->nama_customer }}</td>
                    <td class="text-muted">{{ $p->tanggal_pesan->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $p->jasa_kurir }}</td>
                    <td><span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span></td>
                    <td class="text-end pe-3">
                        <i class="bi bi-chevron-right text-muted"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada pesanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $pesanans->links() }}</div>

@endsection