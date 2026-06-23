@extends('layouts.app')
@section('title', 'Manajemen Produksi')
@section('page-title', 'Manajemen Produksi')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <select name="status" class="form-select form-select-sm filter-input" style="--filter-w:200px; border-radius:9px">
            <option value="">Semua Status</option>
            <option value="dp_terverifikasi" {{ request('status') === 'dp_terverifikasi' ? 'selected' : '' }}>Siap Produksi</option>
            <option value="dalam_produksi" {{ request('status') === 'dalam_produksi' ? 'selected' : '' }}>Dalam Produksi</option>
            <option value="selesai_produksi" {{ request('status') === 'selesai_produksi' ? 'selected' : '' }}>Selesai Produksi</option>
            <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas (Siap Kirim)</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-filter me-1"></i>Filter
        </button>
        <a href="{{ route('produksi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive-wrap">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Mulai Produksi</th>
                    <th>Est. Selesai</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                <tr style="cursor:pointer" onclick="window.location='{{ route('produksi.show', $p) }}'">
                    <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                    <td>{{ $p->customer->nama_customer }}</td>
                    <td>
                        <span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span>
                    </td>
                    <td class="text-muted">{{ $p->produksi?->tanggal_mulai?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-muted">{{ $p->produksi?->estimasi_selesai?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-end pe-3">
                        <i class="bi bi-chevron-right text-muted"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-tools fs-2 d-block mb-2"></i>
                        Tidak ada pesanan dalam produksi.
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