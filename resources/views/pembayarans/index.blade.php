@extends('layouts.app')
@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <select name="status" class="form-select form-select-sm filter-input" style="--filter-w:170px; border-radius:9px">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-filter me-1"></i>Filter
        </button>
        <a href="{{ route('pembayarans.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
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
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayarans as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->pesanan->nomor_pesanan }}</td>
                    <td>{{ $p->pesanan->customer->nama_customer }}</td>
                    <td>
                        <span class="badge" style="background:#eef1fe;color:#4f6ef7">
                            {{ strtoupper($p->jenis_pembayaran) }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td class="text-muted">{{ $p->tanggal_pembayaran->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status_verifikasi === 'valid' ? 'success' : ($p->status_verifikasi === 'ditolak' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($p->status_verifikasi) }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <a href="{{ route('pembayarans.show', $p) }}"
                            class="btn btn-sm {{ $p->status_verifikasi === 'menunggu' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $p->status_verifikasi === 'menunggu' ? 'Verifikasi' : 'Detail' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-credit-card fs-2 d-block mb-2"></i>
                        Tidak ada data pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $pembayarans->links() }}</div>

@endsection