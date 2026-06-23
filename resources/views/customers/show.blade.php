@extends('layouts.app')
@section('title', 'Detail Customer')
@section('page-title', 'Detail Customer')

@section('content')

<div class="mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#4f6ef7,#7c3aed);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.8rem;color:#fff;font-weight:700;">
                    {{ strtoupper(substr($customer->nama_customer, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $customer->nama_customer }}</h5>
                <div class="text-muted mb-1" style="font-size:.88rem">
                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $customer->no_whatsapp }}
                </div>
                <div class="text-muted" style="font-size:.83rem">{{ $customer->alamat }}</div>
                <div class="mt-3 d-flex gap-2 justify-content-center">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Riwayat Pesanan</div>
            <div class="card-body p-0">
                <div class="table-responsive-wrap">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->pesanans as $p)
                        <tr style="cursor:pointer" onclick="window.location='{{ route('pesanans.show', $p) }}'">
                            <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                            <td class="text-muted">{{ $p->tanggal_pesan->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada pesanan dari customer ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection