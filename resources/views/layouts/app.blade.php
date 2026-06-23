<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Distro IPM')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }

        :root {
            --sidebar-bg: #181f36;
            --sidebar-hover: rgba(255,255,255,0.07);
            --sidebar-active: rgba(79,110,247,0.18);
            --sidebar-width: 240px;
            --accent: #4f6ef7;
            --accent2: #7c3aed;
            --bottom-nav-h: 64px;
        }

        body { background: #f4f6fb; color: #1a2340; }

        /* ============ SIDEBAR (desktop) ============ */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0; z-index: 200;
            display: flex; flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.4rem 1.4rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand .brand-name {
            font-size: 1.15rem; font-weight: 700;
            color: #fff; letter-spacing: -0.3px;
        }
        .sidebar-brand .brand-sub { font-size: 0.75rem; color: #8896b3; margin-top: 2px; }
        .sidebar-logo {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem; flex-shrink: 0;
        }
        .nav-section {
            font-size: 0.68rem; text-transform: uppercase;
            letter-spacing: 1.2px; color: #4a5880;
            padding: 1rem 1.4rem 0.3rem; font-weight: 600;
        }
        .nav-item-link {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.55rem 1.4rem; color: #8896b3;
            text-decoration: none; font-size: 0.9rem; font-weight: 500;
            border-radius: 0; transition: background .15s, color .15s;
            position: relative;
        }
        .nav-item-link i { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-item-link:hover { background: var(--sidebar-hover); color: #fff; }
        .nav-item-link.active { background: var(--sidebar-active); color: #fff; }
        .nav-item-link.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: var(--accent); border-radius: 0 2px 2px 0;
        }
        .notif-badge {
            margin-left: auto; background: #ef4444;
            color: #fff; border-radius: 20px; font-size: 0.68rem;
            padding: 1px 7px; font-weight: 600;
        }
        .sidebar-footer {
            margin-top: auto; border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1rem 1.4rem;
        }
        .sidebar-footer .user-name { color: #fff; font-weight: 600; font-size: 0.9rem; }
        .sidebar-footer .user-role { font-size: 0.74rem; color: #8896b3; text-transform: capitalize; }
        .avatar-chip {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.9rem; font-weight: 700; flex-shrink: 0;
        }

        /* ============ MAIN (desktop default) ============ */
        .main-wrapper { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        /* ============ TOPBAR (desktop) ============ */
        .topbar {
            background: #fff; padding: 0.85rem 1.8rem;
            border-bottom: 1px solid #e8ecf4;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .page-title { font-size: 1.3rem; font-weight: 700; color: #1a2340; letter-spacing: -0.3px; }
        .topbar-right { display: flex; align-items: center; gap: 0.8rem; }
        .role-badge {
            background: #eef1fe; color: var(--accent);
            border-radius: 20px; padding: 3px 12px;
            font-size: 0.78rem; font-weight: 600; text-transform: capitalize;
            display: inline-block;
        }
        .btn-logout {
            background: transparent; border: 1.5px solid #e2e8f0;
            border-radius: 8px; padding: 0.35rem 0.9rem;
            font-size: 0.83rem; color: #5a6278; cursor: pointer;
            font-weight: 500; transition: all .2s;
        }
        .btn-logout:hover { background: #fff0f0; border-color: #fca5a5; color: #ef4444; }

        /* ============ MOBILE TOPBAR (hidden on desktop) ============ */
        .mobile-topbar {
            display: none;
            background: #fff; padding: 0.85rem 1.1rem;
            border-bottom: 1px solid #e8ecf4;
            align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 150;
        }
        .mobile-topbar .login-as { font-size: 0.7rem; color: #7a869a; line-height: 1.1; }
        .mobile-topbar .login-role { font-size: 0.92rem; font-weight: 700; color: #1a2340; text-transform: capitalize; }
        .mobile-topbar .btn-logout-icon {
            background: transparent; border: none; color: #5a6278; font-size: 1.2rem;
            padding: 4px;
        }

        /* ============ BOTTOM NAV (mobile only) ============ */
        .bottom-nav {
            display: none;
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 200;
            background: #fff; border-top: 1px solid #e8ecf4;
            padding: 6px 4px calc(6px + env(safe-area-inset-bottom));
            justify-content: space-around; align-items: center;
        }
        .bottom-nav-item {
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            color: #9aa3b8; text-decoration: none; font-size: 0.68rem; font-weight: 500;
            padding: 4px 8px; flex: 1; position: relative;
        }
        .bottom-nav-item i { font-size: 1.25rem; }
        .bottom-nav-item.active { color: var(--accent); }
        .bottom-nav-item .bn-badge {
            position: absolute; top: 0px; right: calc(50% - 16px);
            background: #ef4444; color: #fff; border-radius: 20px;
            font-size: 0.6rem; padding: 0px 5px; font-weight: 700; line-height: 1.4;
        }

        /* ============ CONTENT ============ */
        .content-area { padding: 1.8rem; flex: 1; }

        /* ============ CARDS ============ */
        .card { border: none; border-radius: 14px; box-shadow: 0 1px 6px rgba(30,50,100,0.07); }
        .card-header {
            background: #fff; border-bottom: 1px solid #f0f2f7;
            border-radius: 14px 14px 0 0 !important;
            padding: 1rem 1.2rem; font-weight: 600; color: #1a2340;
        }

        /* ============ TABLES (scrollable on small screens) ============ */
        .table th { font-size: 0.8rem; color: #7a869a; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
        .table td { vertical-align: middle; font-size: 0.9rem; color: #2d3a5a; }
        .table-hover tbody tr:hover { background: #f7f9ff; }
        .table-responsive-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* ============ FORMS ============ */
        .form-control, .form-select {
            border-radius: 9px; border: 1.5px solid #e2e8f0;
            font-size: 0.92rem; color: #1a2340;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,110,247,0.10);
        }
        .form-label { font-weight: 500; font-size: 0.88rem; color: #344054; margin-bottom: 5px; }

        /* ============ FILTER INPUTS (responsive width) ============ */
        .filter-input { width: 100%; }
        @media (min-width: 576px) {
            .filter-input { width: var(--filter-w, 200px); }
        }

        /* ============ BUTTONS ============ */
        .btn { border-radius: 9px; font-weight: 500; font-size: 0.88rem; }
        .btn-primary { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background: #3d5ce0; border-color: #3d5ce0; }
        .btn-sm { border-radius: 7px; }

        /* ============ STATUS BADGES ============ */
        .badge { border-radius: 6px; font-weight: 500; font-size: 0.78rem; padding: 4px 10px; }

        /* ============ STAT CARDS ============ */
        .stat-card { border-radius: 16px; padding: 1.4rem 1.5rem; color: #fff; position: relative; overflow: hidden; }
        .stat-card .stat-label { font-size: 0.84rem; opacity: 0.85; font-weight: 500; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; line-height: 1.2; margin: 0.3rem 0; }
        .stat-card .stat-sub { font-size: 0.78rem; opacity: 0.7; }
        .stat-card .stat-icon {
            position: absolute; right: 1.2rem; top: 50%;
            transform: translateY(-50%); font-size: 3.5rem; opacity: 0.15;
        }
        .stat-blue { background: linear-gradient(135deg, #4f6ef7 0%, #6385ff 100%); }
        .stat-purple { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); }
        .stat-green { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
        .stat-orange { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }

        /* ============ ALERTS ============ */
        .alert { border-radius: 10px; border: none; font-size: 0.9rem; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-danger { background: #fef2f2; color: #991b1b; }

        /* ============ STICKY SUMMARY (desktop only) ============ */
        @media (min-width: 992px) {
            .summary-sticky-card { position: sticky; top: 90px; }
        }

        /* ============ PAGINATION ============ */
        .pagination { flex-wrap: wrap; }
        .pagination .page-link { border-radius: 7px !important; margin: 0 2px; border-color: #e2e8f0; color: var(--accent); font-size: 0.85rem; }
        .pagination .page-item.active .page-link { background: var(--accent); border-color: var(--accent); }

        /* ===================================================== */
        /* RESPONSIVE: below 992px -> mobile/tablet app shell      */
        /* ===================================================== */
        @media (max-width: 991.98px) {
            .sidebar { display: none; }
            .topbar { display: none; }
            .mobile-topbar { display: flex; }
            .bottom-nav { display: flex; }
            .main-wrapper { margin-left: 0; padding-bottom: var(--bottom-nav-h); }
            .content-area { padding: 1rem; }
            .stat-card { padding: 1.1rem 1.2rem; border-radius: 14px; }
            .stat-card .stat-value { font-size: 1.6rem; }
            .stat-card .stat-icon { font-size: 2.6rem; }
            .card-header { padding: 0.85rem 1rem; font-size: 0.92rem; }
        }

        @media (max-width: 575.98px) {
            .content-area { padding: 0.75rem; }
            .auth-card { padding: 1.6rem 1.2rem !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
{{-- ===================== DESKTOP SIDEBAR ===================== --}}
<div class="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div class="sidebar-logo"><i class="bi bi-bag-heart-fill"></i></div>
        <div>
            <div class="brand-name">Distro IPM</div>
            <div class="brand-sub">Sistem Manajemen</div>
        </div>
    </div>

    <nav class="mt-2 flex-grow-1">
        <div class="nav-section">Umum</div>
        <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('notifikasi.index') }}" class="nav-item-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
            @if(auth()->user()->unreadNotifikasis()->count() > 0)
                <span class="notif-badge">{{ auth()->user()->unreadNotifikasis()->count() }}</span>
            @endif
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section">Admin</div>
        <a href="{{ route('customers.index') }}" class="nav-item-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customer
        </a>
        <a href="{{ route('pesanans.index') }}" class="nav-item-link {{ request()->routeIs('pesanans.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Pesanan
        </a>
        <a href="{{ route('pesanans.tracking') }}" class="nav-item-link {{ request()->routeIs('pesanans.tracking') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Tracking
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-item-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
        @endif

        @if(auth()->user()->isFinance())
        <div class="nav-section">Finance</div>
        <a href="{{ route('pembayarans.index') }}" class="nav-item-link {{ request()->routeIs('pembayarans.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Verifikasi Bayar
        </a>
        @endif

        @if(auth()->user()->isProduksi())
        <div class="nav-section">Produksi</div>
        <a href="{{ route('produksi.index') }}" class="nav-item-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Status Pesanan
        </a>
        <a href="{{ route('produks.index') }}" class="nav-item-link {{ request()->routeIs('produks.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Manajemen Stok
        </a>
        @endif
    </nav>

    <div class="sidebar-footer d-flex align-items-center gap-2">
        <div class="avatar-chip">{{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div class="flex-grow-1 overflow-hidden">
            <div class="user-name text-truncate">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
            <div class="user-role">{{ session('active_role', auth()->user()->role) }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

<div class="main-wrapper">
    {{-- ===================== DESKTOP TOPBAR ===================== --}}
    <div class="topbar">
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            <a href="{{ route('pilih-role') }}" class="role-badge text-decoration-none" title="Ganti role">
                <i class="bi bi-person-fill me-1"></i>{{ ucfirst(session('active_role', auth()->user()->role)) }}
            </a>
        </div>
    </div>

    {{-- ===================== MOBILE TOPBAR (sesuai mockup SKPL) ===================== --}}
    <div class="mobile-topbar">
        <a href="{{ route('pilih-role') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="avatar-chip" style="background:linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div class="login-as">Login sebagai</div>
                <div class="login-role">{{ ucfirst(session('active_role', auth()->user()->role)) }}</div>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-icon" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>

    <div class="content-area">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- ===================== BOTTOM NAV (mobile, sesuai mockup SKPL) ===================== --}}
<div class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
        Beranda
    </a>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('pesanans.index') }}" class="bottom-nav-item {{ request()->routeIs('pesanans.*') ? 'active' : '' }}">
        <i class="bi bi-bag{{ request()->routeIs('pesanans.*') ? '-fill' : '' }}"></i>
        Pesanan
    </a>
    @endif

    @if(auth()->user()->isFinance())
    <a href="{{ route('pembayarans.index') }}" class="bottom-nav-item {{ request()->routeIs('pembayarans.*') ? 'active' : '' }}">
        <i class="bi bi-credit-card{{ request()->routeIs('pembayarans.*') ? '-fill' : '' }}"></i>
        Pesanan
    </a>
    @endif

    @if(auth()->user()->isProduksi())
    <a href="{{ route('produksi.index') }}" class="bottom-nav-item {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
        <i class="bi bi-bag{{ request()->routeIs('produksi.*') ? '-fill' : '' }}"></i>
        Pesanan
    </a>
    <a href="{{ route('produks.index') }}" class="bottom-nav-item {{ request()->routeIs('produks.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam{{ request()->routeIs('produks.*') ? '-fill' : '' }}"></i>
        Stok
    </a>
    @endif

    <a href="{{ route('notifikasi.index') }}" class="bottom-nav-item {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
        <i class="bi bi-bell{{ request()->routeIs('notifikasi.*') ? '-fill' : '' }}"></i>
        Notifikasi
        @if(auth()->user()->unreadNotifikasis()->count() > 0)
            <span class="bn-badge">{{ auth()->user()->unreadNotifikasis()->count() }}</span>
        @endif
    </a>
</div>
@endauth

@guest
    @yield('content')
@endguest

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>