@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div class="text-muted" style="font-size:.9rem">Semua pemberitahuan sistem</div>
    <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-check2-all me-1"></i>Tandai Semua Dibaca
        </button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($notifikasis as $n)
        <div class="d-flex flex-wrap gap-3 px-3 px-md-4 py-3 border-bottom {{ !$n->is_read ? 'bg-light' : '' }}">
            <div class="flex-shrink-0 mt-1">
                @php
                $iconClass = match($n->tipe) {
                    'payment' => 'bi-credit-card',
                    'produksi' => 'bi-tools',
                    'pengiriman' => 'bi-truck',
                    default => 'bi-bell',
                };
                $iconColor = match($n->tipe) {
                    'payment' => '#f59e0b',
                    'produksi' => '#4f6ef7',
                    'pengiriman' => '#10b981',
                    default => '#7a869a',
                };
                @endphp
                <div style="width:36px;height:36px;border-radius:9px;background:#f4f6fb;display:flex;align-items:center;justify-content:center;">
                    <i class="bi {{ $iconClass }}" style="color:{{ $iconColor }};font-size:1.1rem"></i>
                </div>
            </div>
            <div class="flex-grow-1" style="min-width:180px">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div class="fw-semibold" style="font-size:.9rem">{{ $n->judul }}</div>
                    <div class="text-muted" style="font-size:.76rem;white-space:nowrap">
                        {{ $n->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="text-muted mt-1" style="font-size:.83rem">{{ $n->pesan }}</div>
                @if($n->pesanan)
                <a href="{{ route('pesanans.show', $n->pesanan) }}" class="mt-1 d-inline-block"
                    style="font-size:.82rem;color:#4f6ef7;text-decoration:none">
                    Lihat Pesanan →
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
            Tidak ada notifikasi.
        </div>
        @endforelse
    </div>
</div>
<div class="mt-3">{{ $notifikasis->links() }}</div>

@endsection