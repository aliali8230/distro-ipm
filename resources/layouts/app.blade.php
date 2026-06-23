<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Distro IPM')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #212529; width: 260px; position: fixed; top: 0; left: 0; z-index: 100; transition: all 0.3s; }
        .sidebar .brand { padding: 1.2rem 1.5rem; border-bottom: 1px solid #495057; }
        .sidebar .nav-link { color: #adb5bd; padding: 0.6rem 1.5rem; border-radius: 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #495057; }
        .sidebar .nav-link i { margin-right: 8px; width: 18px; }
        .sidebar .nav-section { color: #6c757d; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 0.8rem 1.5rem 0.3rem; }
        .main-content { margin-left: 260px; padding: 1.5rem; min-height: 100vh; }
        .topbar { background: #fff; padding: 0.8rem 1.5rem; border-bottom: 1px solid #dee2e6; margin: -1.5rem -1.5rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .badge-notif { position: absolute; top: -5px; right: -5px; font-size: 0.65rem; }
        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .table th { font-size: 0.82rem; color: #6c757d; }
        @media (max-width: 768px) { .sidebar { width: 100%; min-height: auto; position: relative; } .main-content { margin-left: 0; } }
    </style>
    @stack('styles')
</head>
<body>

@auth
<div class="sidebar">
    <div class="brand text-white fw-bold fs-5">
        <i class="bi bi-bag-heart-fill me-2 text-warning"></i>Distro IPM
    </div>
    <nav class="mt-2">
        <div class="nav-section">Umum</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link position-relative {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
            @if(auth()->user()->unreadNotifikasis()->count() > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ auth()->user()->unreadNotifikasis()->count() }}</span>
            @endif
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section">Admin</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customer
        </a>
        <a href="{{ route('pesanans.index') }}" class="nav-link {{ request()->routeIs('pesanans.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Pesanan
        </a>
        <a href="{{ route('pesanans.tracking') }}" class="nav-link {{ request()->routeIs('pesanans.tracking') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Tracking
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
        @endif

        @if(auth()->user()->isFinance())
        <div class="nav-section">Finance</div>
        <a href="{{ route('pembayarans.index') }}" class="nav-link {{ request()->routeIs('pembayarans.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Verifikasi Bayar
        </a>
        @endif

        @if(auth()->user()->isProduksi())
        <div class="nav-section">Produksi</div>
        <a href="{{ route('produksi.index') }}" class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Manajemen Produksi
        </a>
        <a href="{{ route('produks.index') }}" class="nav-link {{ request()->routeIs('produks.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Stok Produk
        </a>
        @endif
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="fw-semibold text-muted">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
            <span class="text-dark fw-medium">{{ auth()->user()->nama }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>
@endauth

@guest
    @yield('content')
@endguest

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>