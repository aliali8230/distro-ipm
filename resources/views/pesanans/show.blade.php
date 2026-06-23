@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
    <div>
        <h5 class="mb-0 fw-bold">{{ $pesanan->nomor_pesanan }}</h5>
        <div class="text-muted mt-1" style="font-size:.85rem">
            <i class="bi bi-calendar3 me-1"></i>{{ $pesanan->tanggal_pesan->format('d M Y') }}
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <span class="badge bg-{{ $pesanan->badge_status }} px-3 py-2" style="font-size:.85rem">{{ $pesanan->label_status }}</span>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('pesanans.invoice', $pesanan) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bi bi-file-text me-1"></i>Invoice
        </a>
        @if($pesanan->status_pesanan === 'pesanan_masuk')
        <a href="{{ route('pesanans.edit', $pesanan) }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
        <a href="{{ route('pesanans.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @else
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        {{-- Info Pesanan --}}
        <div class="card mb-3">
            <div class="card-header">Informasi Pesanan</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Customer</div>
                        <div class="fw-semibold">{{ $pesanan->customer->nama_customer }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">No. WhatsApp</div>
                        <div>{{ $pesanan->customer->no_whatsapp }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Tanggal Pesan</div>
                        <div>{{ $pesanan->tanggal_pesan->format('d M Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.8rem">Jasa Kurir</div>
                        <div>{{ $pesanan->jasa_kurir }}</div>
                    </div>
                    @if($pesanan->catatan)
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.8rem">Catatan</div>
                        <div>{{ $pesanan->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="card mb-3">
            <div class="card-header">Detail Produk</div>
            <div class="card-body p-0">
                <div class="table-responsive-wrap">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanans as $d)
                        <tr>
                            <td>{{ $d->produk->nama_produk }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-medium">Ongkir</td>
                            <td>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-warning fw-medium">DP (50%)</td>
                            <td class="text-warning fw-semibold">Rp {{ number_format($pesanan->nominal_dp, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>

        {{-- Upload Bukti Pembayaran (khusus Admin) --}}
        @if(auth()->user()->isAdmin() && in_array($pesanan->status_pesanan, ['pesanan_masuk', 'selesai_produksi']))
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-upload me-2 text-primary"></i>
                Upload Bukti {{ $pesanan->status_pesanan === 'pesanan_masuk' ? 'DP' : 'Pelunasan' }}
            </div>
            <div class="card-body">
                <form action="{{ route('pesanans.uploadBukti', $pesanan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_pembayaran" value="{{ $pesanan->status_pesanan === 'pesanan_masuk' ? 'dp' : 'pelunasan' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pembayaran</label>
                            <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control"
                                value="{{ $pesanan->status_pesanan === 'pesanan_masuk' ? $pesanan->nominal_dp : ($pesanan->total_harga - $pesanan->nominal_dp) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Bukti
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Riwayat Pembayaran --}}
        <div class="card mb-3">
            <div class="card-header">Riwayat Pembayaran</div>
            <div class="card-body p-0">
                @forelse($pesanan->pembayarans as $pb)
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:.85rem">{{ strtoupper($pb->jenis_pembayaran) }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $pb->tanggal_pembayaran->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge bg-{{ $pb->status_verifikasi === 'valid' ? 'success' : ($pb->status_verifikasi === 'ditolak' ? 'danger' : 'warning text-dark') }}">
                        {{ ucfirst($pb->status_verifikasi) }}
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-3" style="font-size:.85rem">Belum ada pembayaran.</div>
                @endforelse
            </div>
        </div>

        {{-- Info Produksi --}}
        @if($pesanan->produksi)
        <div class="card mb-3">
            <div class="card-header">Info Produksi</div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Status</div>
                    <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $pesanan->produksi->status_produksi)) }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Tgl Mulai</div>
                    <div>{{ $pesanan->produksi->tanggal_mulai?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Est. Selesai</div>
                    <div>{{ $pesanan->produksi->estimasi_selesai?->format('d M Y') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-muted mb-1" style="font-size:.8rem">Operator</div>
                    <div>{{ $pesanan->produksi->operator?->nama ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Info Pengiriman --}}
        @if($pesanan->pengiriman)
        <div class="card">
            <div class="card-header">Info Pengiriman</div>
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
    </div>
</div>

@endsection