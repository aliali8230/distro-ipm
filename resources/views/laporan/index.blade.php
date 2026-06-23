@extends('layouts.app')
@section('title', 'Laporan Pesanan')
@section('page-title', 'Laporan Pesanan')

@section('content')

<div class="card mb-4">
    <div class="card-header">Filter Laporan</div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="{{ $dari }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['pesanan_masuk','dp_terverifikasi','dalam_produksi','selesai_produksi','lunas','dikirim'] as $s)
                    <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $s)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-bar-chart me-1"></i>Generate Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-bag"></i></div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $pesanans->count() }}</div>
            <div class="stat-sub">Periode yang dipilih</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" style="font-size:1.4rem">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <div class="stat-sub">Lunas + Dikirim</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Rekap Status</div>
            <div class="card-body py-2">
                @foreach($rekapStatus as $s => $jml)
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted" style="font-size:.83rem">
                        {{ ucwords(str_replace('_', ' ', $s)) }}
                    </span>
                    <strong style="font-size:.83rem">{{ $jml }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Daftar Pesanan:
        {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} —
        {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                    <td>{{ $p->customer->nama_customer }}</td>
                    <td class="text-muted">{{ $p->tanggal_pesan->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Tidak ada data pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection