@extends('layouts.app')
@section('title', 'Tracking Pesanan')
@section('page-title', 'Tracking Pesanan')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="search" class="form-control form-control-sm filter-input"
            placeholder="No. Pesanan / Customer" value="{{ request('search') }}"
            style="--filter-w:220px; border-radius:9px">
        <select name="status" class="form-select form-select-sm filter-input" style="--filter-w:200px; border-radius:9px">
            <option value="">Semua Status</option>
            @foreach(['pesanan_masuk','menunggu_verifikasi_dp','dp_terverifikasi','dalam_produksi','selesai_produksi','menunggu_verifikasi_lunas','lunas','dikirim'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucwords(str_replace('_', ' ', $s)) }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-search me-1"></i>Filter
        </button>
        <a href="{{ route('pesanans.tracking') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
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
                    <th>Est. Selesai</th>
                    <th>Kurir / Resi</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                    <td>{{ $p->customer->nama_customer }}</td>
                    <td>
                        <span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span>
                    </td>
                    <td class="text-muted">
                        {{ $p->produksi?->estimasi_selesai?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td>
                        @if($p->pengiriman)
                            <div class="fw-medium" style="font-size:.88rem">{{ $p->pengiriman->jasa_kurir }}</div>
                            <div class="text-muted" style="font-size:.78rem">{{ $p->pengiriman->nomor_resi }}</div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:.82rem">
                        {{ $p->updated_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-geo-alt fs-2 d-block mb-2"></i>
                        Tidak ada data pesanan.
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