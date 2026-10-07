<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP Bandung - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <!-- Logo Brand -->
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Bandung Info Center" style="height: 50px;">
            </a>

            <!-- Menu Navigasi -->
            <ul class="nav-links" style="display: flex; align-items: center; gap: 10px; list-style: none; margin: 0; padding: 0;">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('public.berita') }}" class="{{ request()->routeIs('public.berita*') ? 'active' : '' }}">Berita</a></li>
                <li><a href="{{ route('public.wisata') }}" class="{{ request()->routeIs('public.wisata*') ? 'active' : '' }}">Wisata</a></li>
                <li><a href="{{ route('public.kuliner') }}" class="{{ request()->routeIs('public.kuliner*') ? 'active' : '' }}">Kuliner</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="{{ request()->routeIs('public.sejarah*') ? 'active' : '' }}">Sejarah</a></li>
                <li><a href="{{ route('public.galeri') }}" class="{{ request()->routeIs('public.galeri*') ? 'active' : '' }}">Galeri</a></li>
                
                <!-- Kotak Pencarian (Search Bar) -->
                <li class="nav-search" style="margin-left: 5px;">
                    <form action="#" method="GET" style="display: flex; align-items: center;">
                        <input type="text" name="q" placeholder="Search Here..." style="background: white; border: none; border-radius: 50px; padding: 0.4rem 1rem; outline: none; font-size: 0.9rem; width: 130px;">
                    </form>
                </li>

                <!-- Autentikasi User / Admin -->
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin*') ? 'active' : '' }}">Dashboard Admin</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 1rem; border-radius: 50px;">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; color: white; border-radius: 50px;">Login</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <main>
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-error">{{ session('error') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistem Layanan Informasi Publik Kota Bandung.</p>
        </div>
    </footer>
</body>
</html>