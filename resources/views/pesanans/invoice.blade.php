<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $pesanan->nomor_pesanan }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @media print { .no-print { display: none !important; } body { padding: 0 !important; } }
        body { background: #f4f6fb; }
        .invoice-wrap { max-width: 760px; margin: 2rem auto; background: #fff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 4px 24px rgba(30,50,100,0.10); }
        .brand-logo { width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #4f6ef7, #7c3aed); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
        .invoice-tag { background: #eef1fe; color: #4f6ef7; border-radius: 8px; padding: 4px 14px; font-weight: 700; font-size: .85rem; letter-spacing: 1px; }
        .divider { border-top: 1.5px solid #f0f2f7; margin: 1.5rem 0; }
        .section-label { font-size: .73rem; color: #7a869a; text-transform: uppercase; letter-spacing: .8px; font-weight: 600; margin-bottom: 4px; }
        table thead th { background: #181f36; color: #fff; font-size: .8rem; font-weight: 600; padding: 10px 14px; }
        table td { padding: 10px 14px; font-size: .88rem; vertical-align: middle; }
        table tfoot td { font-size: .88rem; padding: 8px 14px; }
        .total-row td { font-weight: 700; font-size: 1rem; color: #4f6ef7; }
        .dp-row td { color: #f59e0b; font-weight: 600; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: .78rem; font-weight: 600; background: #eef1fe; color: #4f6ef7; }
        .invoice-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        @media (max-width: 575.98px) {
            .invoice-wrap { margin: 0.75rem; padding: 1.4rem 1.1rem; border-radius: 14px; }
            table thead th, table td { padding: 8px 10px; font-size: .78rem; }
            .row.mb-4 { flex-direction: column; }
            .row.mb-4 .col-6 { width: 100%; max-width: 100%; }
            .row.mb-4 .col-6.text-end { text-align: left !important; margin-top: 0.75rem; }
        }
    </style>
</head>
<body class="p-3 p-md-4">
<div class="invoice-wrap">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="brand-logo">🧵</div>
            <div>
                <div style="font-size:1.1rem;font-weight:700;color:#1a2340">Distro IPM</div>
                <div style="font-size:.78rem;color:#7a869a">Sistem Manajemen Pesanan</div>
            </div>
        </div>
        <div class="text-end">
            <div class="invoice-tag mb-2">INVOICE</div>
            <div style="font-size:1rem;font-weight:700;color:#1a2340">{{ $pesanan->nomor_pesanan }}</div>
            <div style="font-size:.82rem;color:#7a869a">{{ $pesanan->tanggal_pesan->format('d M Y') }}</div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Customer & Admin Info --}}
    <div class="row mb-4">
        <div class="col-6">
            <div class="section-label">Kepada</div>
            <div style="font-weight:700;font-size:.97rem">{{ $pesanan->customer->nama_customer }}</div>
            <div style="font-size:.85rem;color:#5a6278">{{ $pesanan->customer->no_whatsapp }}</div>
            <div style="font-size:.82rem;color:#7a869a;margin-top:2px">{{ $pesanan->customer->alamat }}</div>
        </div>
        <div class="col-6 text-end">
            <div class="section-label">Diproses Oleh</div>
            <div style="font-weight:600;font-size:.9rem">{{ $pesanan->admin->nama ?? 'Admin' }}</div>
            <div style="font-size:.83rem;color:#5a6278">Kurir: {{ $pesanan->jasa_kurir }}</div>
            <div class="mt-2">
                <span class="status-badge">{{ $pesanan->label_status }}</span>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="invoice-table-wrap">
    <table class="table table-bordered mb-0" style="border-color:#e8ecf4">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-center" style="width:80px">Qty</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->detailPesanans as $d)
            <tr>
                <td>{{ $d->produk->nama_produk }}</td>
                <td class="text-center">{{ $d->jumlah }}</td>
                <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f7f9ff">
                <td colspan="3" class="text-end text-muted">Ongkir</td>
                <td class="text-end">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row" style="background:#eef1fe">
                <td colspan="3" class="text-end">Total Harga</td>
                <td class="text-end">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr class="dp-row" style="background:#fffbeb">
                <td colspan="3" class="text-end">DP (50%)</td>
                <td class="text-end">Rp {{ number_format($pesanan->nominal_dp, 0, ',', '.') }}</td>
            </tr>
            <tr style="background:#f7f9ff">
                <td colspan="3" class="text-end text-muted">Sisa Pelunasan</td>
                <td class="text-end fw-semibold">Rp {{ number_format($pesanan->total_harga - $pesanan->nominal_dp, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    </div>

    <div class="divider"></div>

    <div style="font-size:.8rem;color:#7a869a;line-height:1.6">
        <i>Pembayaran DP dilakukan sebelum produksi dimulai. Pelunasan dibayarkan setelah produksi selesai.
        Terima kasih telah mempercayakan pesanan Anda kepada Distro IPM.</i>
    </div>

    <div class="mt-4 d-flex gap-2 no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i>Print Invoice
        </button>
        <a href="{{ route('pesanans.show', $pesanan) }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>
</body>
</html>