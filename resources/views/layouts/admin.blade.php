<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Bandung Info Center</title>
    <meta name="description" content="Panel Admin Bandung Info Center - Kelola konten portal informasi Kota Bandung.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --sidebar-w: 220px;
            --sidebar-bg: #1a2a6c;
            --sidebar-active: #f5a623;
            --sidebar-text: rgba(255,255,255,0.80);
            --sidebar-text-hover: #fff;
            --topbar-h: 60px;
            --body-bg: #f0f2f8;
            --card-bg: #ffffff;
            --primary: #1a2a6c;
            --accent: #f5a623;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius: 14px;
            --shadow: 0 2px 16px rgba(26,42,108,0.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 18px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand-text h3 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }
        .sidebar-brand-text span {
            font-size: 10px;
            font-weight: 500;
            color: var(--accent);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.40);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 18px 18px 6px;
        }

        .admin-menu { list-style: none; padding: 4px 10px; }
        .admin-menu li { margin-bottom: 2px; }
        .admin-menu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }
        .admin-menu li a:hover {
            background: rgba(255,255,255,0.08);
            color: var(--sidebar-text-hover);
        }
        .admin-menu li a.active {
            background: var(--accent);
            color: #fff;
            font-weight: 600;
        }
        .admin-menu li a .menu-icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }
        .menu-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 600;
            background: rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7);
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        .admin-menu li a.active .menu-badge {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 10px 18px;
        }

        .sidebar-user {
            margin-top: auto;
            padding: 14px 18px 18px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .sidebar-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #e8920e);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 12.5px;
            font-weight: 600;
            color: #fff;
            line-height: 1.3;
        }
        .sidebar-user-email {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 8px 12px;
            background: rgba(255,255,255,0.07);
            border: none;
            border-radius: 8px;
            color: rgba(255,255,255,0.70);
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
        }
        .btn-logout:hover {
            background: rgba(255,100,100,0.18);
            color: #ff8888;
        }

        /* ===== MAIN WRAPPER ===== */
        .admin-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .admin-topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e8eaf0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 0.2px;
        }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .btn-topbar-public {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 16px;
            background: #f0f2f8;
            border-radius: 8px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e0e3ef;
        }
        .btn-topbar-public:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        /* ===== MAIN CONTENT ===== */
        .admin-main {
            flex: 1;
            padding: 28px;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-city"></i>
        </div>
        <div class="sidebar-brand-text">
            <h3>Bandung Info Center</h3>
            <span>Panel Admin</span>
        </div>
    </div>

    <p class="sidebar-section-label">Menu Utama</p>
    <ul class="admin-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" id="nav-dashboard">
                <span class="menu-icon"><i class="fas fa-th-large"></i></span>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.kuliner.index') }}" class="{{ request()->routeIs('admin.kuliner.*') ? 'active' : '' }}" id="nav-kuliner">
                <span class="menu-icon"><i class="fas fa-utensils"></i></span>
                Manajemen Kuliner
            </a>
        </li>
        <li>
            <a href="{{ route('admin.wisata.index') }}" class="{{ request()->routeIs('admin.wisata.*') ? 'active' : '' }}" id="nav-wisata">
                <span class="menu-icon"><i class="fas fa-mountain-sun"></i></span>
                Manajemen Wisata
            </a>
        </li>
        <li>
            <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" id="nav-berita">
                <span class="menu-icon"><i class="fas fa-newspaper"></i></span>
                Manajemen Berita
            </a>
        </li>
        <li>
            <a href="{{ route('admin.sejarah.index') }}" class="{{ request()->routeIs('admin.sejarah.*') ? 'active' : '' }}" id="nav-sejarah">
                <span class="menu-icon"><i class="fas fa-landmark"></i></span>
                Sejarah &amp; Museum
            </a>
        </li>
    </ul>

    <div class="sidebar-divider"></div>
    <p class="sidebar-section-label">Konten Kota</p>
    <ul class="admin-menu">
        <li>
            <a href="{{ route('public.wisata') }}" target="_blank" id="nav-pub-wisata">
                <span class="menu-icon"><i class="fas fa-globe"></i></span>
                Halaman Wisata
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
        <li>
            <a href="{{ route('public.berita') }}" target="_blank" id="nav-pub-berita">
                <span class="menu-icon"><i class="fas fa-rss"></i></span>
                Pembaruan Berita
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
        <li>
            <a href="{{ route('public.sejarah') }}" target="_blank" id="nav-pub-sejarah">
                <span class="menu-icon"><i class="fas fa-scroll"></i></span>
                Arsip Sejarah
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
        <li>
            <a href="{{ route('public.galeri') }}" target="_blank" id="nav-pub-galeri">
                <span class="menu-icon"><i class="fas fa-images"></i></span>
                Galeri
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
        @if(Route::has('admin.contact.index'))
        <li>
            <a href="{{ route('admin.contact.index') }}" class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}" id="nav-profil">
                <span class="menu-icon"><i class="fas fa-address-card"></i></span>
                Profil
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('home') }}" target="_blank" id="nav-pub-home">
                <span class="menu-icon"><i class="fas fa-phone"></i></span>
                Kontak
                <span class="menu-badge">PUBLIK</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-user">
        <div class="sidebar-user-info">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="sidebar-user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout" id="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN WRAPPER --}}
<div class="admin-wrapper">
    {{-- TOPBAR --}}
    <header class="admin-topbar">
        <span class="topbar-title">@yield('topbar-title', 'Dashboard Admin')</span>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" target="_blank" class="btn-topbar-public" id="btn-lihat-publik">
                Lihat Situs Publik <i class="fas fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
