@extends('layouts.app')
@section('title', 'Detail Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')

<div class="mb-4">
    <a href="{{ route('pembayarans.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Bukti Transfer</div>
            <div class="card-body text-center p-3">
                @if(Str::endsWith($pembayaran->bukti_transfer, ['.jpg', '.jpeg', '.png']))
                <img src="{{ Storage::url($pembayaran->bukti_transfer) }}"
                    class="img-fluid rounded" style="max-height:420px; border:1px solid #e2e8f0">
                @else
                <div class="py-4">
                    <i class="bi bi-file-pdf fs-1 text-danger d-block mb-2"></i>
                    <a href="{{ Storage::url($pembayaran->bukti_transfer) }}" target="_blank"
                        class="btn btn-outline-danger">
                        <i class="bi bi-file-pdf me-1"></i>Lihat PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Info Pembayaran</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">No. Pesanan</div>
                        <div class="fw-bold">{{ $pembayaran->pesanan->nomor_pesanan }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Customer</div>
                        <div>{{ $pembayaran->pesanan->customer->nama_customer }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Jenis Pembayaran</div>
                        <span class="badge" style="background:#eef1fe;color:#4f6ef7;font-size:.82rem">
                            {{ strtoupper($pembayaran->jenis_pembayaran) }}
                        </span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Status</div>
                        <span class="badge bg-{{ $pembayaran->status_verifikasi === 'valid' ? 'success' : ($pembayaran->status_verifikasi === 'ditolak' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($pembayaran->status_verifikasi) }}
                        </span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Nominal Dibayar</div>
                        <div class="fw-bold text-primary">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Seharusnya</div>
                        <div class="fw-semibold">
                            Rp {{ number_format($pembayaran->jenis_pembayaran === 'dp'
                                ? $pembayaran->pesanan->nominal_dp
                                : ($pembayaran->pesanan->total_harga - $pembayaran->pesanan->nominal_dp),
                                0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Tanggal Bayar</div>
                        <div>{{ $pembayaran->tanggal_pembayaran->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($pembayaran->status_verifikasi === 'menunggu')
        <div class="card">
            <div class="card-header">Form Verifikasi</div>
            <div class="card-body">
                <form action="{{ route('pembayarans.verifikasi', $pembayaran) }}" method="POST">
                    @csrf
                    <label class="form-label">Keputusan <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keputusan"
                                value="valid" id="valid" required>
                            <label class="form-check-label fw-medium text-success" for="valid">
                                <i class="bi bi-check-circle me-1"></i>Valid
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keputusan"
                                value="ditolak" id="tolak">
                            <label class="form-check-label fw-medium text-danger" for="tolak">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                        <textarea name="catatan_finance" class="form-control" rows="3"
                            placeholder="Catatan ketidaksesuaian jika ditolak..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"
                        onclick="return confirm('Simpan keputusan verifikasi?')">
                        <i class="bi bi-check-lg me-1"></i>Simpan Verifikasi
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($pembayaran->catatan_finance)
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:.8rem">Catatan Finance</div>
                <p class="mb-0">{{ $pembayaran->catatan_finance }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection