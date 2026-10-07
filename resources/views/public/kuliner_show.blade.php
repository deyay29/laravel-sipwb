@extends('layouts.app')
@section('title', $kuliner->nama_kuliner)

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    /* Hero Section */
    .kuliner-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('{{ $kuliner->foto ? asset("storage/".$kuliner->foto) : asset("images/beranda.png") }}') center/cover no-repeat;
        padding: 2rem 0 3rem;
        min-height: 450px;
        display: flex;
        flex-direction: column;
    }

    .hero-top-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: auto;
    }

    .hero-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        text-decoration: none;
        font-family: 'Outfit', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .hero-brand img {
        height: 50px;
        filter: brightness(0) invert(1);
    }

    .hero-nav-links {
        display: flex;
        gap: 0.75rem;
        list-style: none;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .hero-nav-pill {
        background-color: white;
        color: #1e3a8a;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .hero-nav-pill.active {
        background-color: #2563eb;
        color: white;
    }

    .hero-nav-pill:hover:not(.active) {
        background-color: #f1f5f9;
        color: #1d4ed8;
    }

    .hero-search {
        background-color: white;
        border-radius: 9999px;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        width: 220px;
    }

    .hero-search input {
        border: none;
        outline: none;
        width: 100%;
        margin-left: 0.5rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
    }

    .hero-bottom-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        color: white;
        margin-top: 4rem;
    }

    .hero-breadcrumb {
        font-size: 1rem;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .hero-breadcrumb span {
        color: white;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        font-family: 'Outfit', sans-serif;
        line-height: 1.2;
    }

    .rating-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        margin-right: 1.5rem;
    }

    .suhu-badge {
        display: inline-block;
        background-color: rgba(220, 38, 38, 0.85); /* Red/Fire */
        border: 1px solid white;
        color: white;
        padding: 0.4rem 1.5rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.9rem;
        backdrop-filter: blur(4px);
    }
    
    .suhu-badge.menyegarkan {
        background-color: rgba(37, 99, 235, 0.85); /* Blue/Ice */
    }

    .weather-widget-blue {
        background-color: #1e40af;
        border: 2px solid white;
        border-radius: 1rem;
        padding: 1.2rem 2.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 1.2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        line-height: 1.4;
    }

    /* Main Content Layout */
    .content-layout {
        display: flex;
        gap: 3rem;
        padding: 3rem 0;
        background-color: white;
    }
    
    .left-col {
        width: 38%;
    }
    
    .right-col {
        width: 62%;
    }

    .btn-kembali-blue {
        background-color: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 9999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        transition: background 0.3s;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
    }
    
    .btn-kembali-blue:hover {
        background-color: #1d4ed8;
        color: white;
    }

    .main-image {
        width: 100%;
        border-radius: 1.5rem;
        border: 6px solid #3b82f6; /* Thick blue border as in screenshot */
        object-fit: cover;
        aspect-ratio: 4/3;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
    }

    .map-preview {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        border: 2px solid #cbd5e1;
        aspect-ratio: 16/9;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .map-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-buka-maps {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        background-color: #2563eb;
        color: white;
        padding: 0.6rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .stats-bar {
        display: flex;
        justify-content: center;
        gap: 4rem;
        align-items: center;
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .info-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .info-card {
        border: 2px solid #1e3a8a;
        border-radius: 1rem;
        padding: 1.5rem 1rem;
        background: white;
        text-align: center;
    }

    .info-title {
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1.05rem;
    }

    .info-text {
        font-size: 0.85rem;
        color: #334155;
        font-weight: 500;
        line-height: 1.4;
    }

    .detail-title {
        color: #1e40af;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 1rem;
        font-family: 'Outfit', sans-serif;
        line-height: 1.4;
    }

    .detail-desc {
        color: #334155;
        font-size: 1.05rem;
        line-height: 1.8;
        text-align: justify;
    }

    /* Related Section */
    .related-section {
        padding: 4rem 0;
        background-color: white;
        border-top: 1px solid #e2e8f0;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .related-card {
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .related-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .related-info {
        background-color: #2563eb;
        color: white;
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .btn-outline-light {
        border: 1px solid white;
        background: transparent;
        color: white;
        padding: 0.6rem;
        border-radius: 0.5rem;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        margin-top: auto;
        display: block;
        transition: all 0.3s;
    }
    
    .btn-outline-light:hover {
        background: white;
        color: #2563eb;
    }
    
    @media (max-width: 992px) {
        .content-layout { flex-direction: column; }
        .left-col, .right-col { width: 100%; }
        .related-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .info-cards-grid { grid-template-columns: 1fr; }
        .related-grid { grid-template-columns: 1fr; }
        .hero-bottom-container { flex-direction: column; align-items: flex-start; gap: 2rem; }
        .stats-bar { justify-content: space-between; gap: 1rem; }
    }
</style>

<div class="kuliner-hero">
    <div class="container" style="display: flex; flex-direction: column; height: 100%;">
        <!-- Custom Top Navigation -->
        <div class="hero-top-nav">
            <a href="{{ route('home') }}" class="hero-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Bandung Info Center">
                <span>Bandung<br>Info Center</span>
            </a>
            
            <ul class="hero-nav-links">
                <li><a href="{{ route('home') }}" class="hero-nav-pill">Beranda</a></li>
                <li><a href="{{ route('public.berita') }}" class="hero-nav-pill">Berita</a></li>
                <li><a href="{{ route('public.wisata') }}" class="hero-nav-pill">Wisata</a></li>
                <li><a href="{{ route('public.kuliner') }}" class="hero-nav-pill active">Kuliner</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill">Sejarah</a></li>
                <li><a href="#" class="hero-nav-pill">Profil</a></li>
                <li><a href="{{ route('public.galeri') }}" class="hero-nav-pill">Galeri</a></li>
                <li>
                    <div class="hero-search">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Search Here">
                    </div>
                </li>
            </ul>
        </div>

        <!-- Hero Info -->
        <div class="hero-bottom-container">
            <div class="hero-info-left">
                <div class="hero-breadcrumb">
                    Beranda &nbsp;&#10095;&nbsp; Kuliner &nbsp;&#10095;&nbsp; <span>{{ Str::limit($kuliner->nama_kuliner, 30) }}</span>
                </div>
                <!-- Menyesuaikan dengan mockup yang menggunakan "Kuliner Khas Bandung" atau nama aslinya -->
                <h1 class="hero-title">{{ Str::limit($kuliner->nama_kuliner, 40) }}</h1>
                
                <div style="display: flex; align-items: center; margin-top: 1.5rem;">
                    <div class="rating-badge">
                        <span style="color: #fbbf24;">&#9733;</span> 4.5
                    </div>
                    @if($kuliner->kategori_suhu)
                    <div class="suhu-badge {{ strtolower($kuliner->kategori_suhu) == 'menyegarkan' ? 'menyegarkan' : '' }}">
                        {{ strtoupper($kuliner->kategori_suhu) }} 
                        @if(strtolower($kuliner->kategori_suhu) == 'menghangatkan') &#128293; @else &#10052; @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="hero-info-right">
                <div class="weather-widget-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;"><path d="M17.5 19a4.5 4.5 0 0 0 .5-8.97V10a6 6 0 0 0-11.83-1.42 4.00 4.00 0 0 0-1.03 7.75"></path><path d="M8 15v2"></path><path d="M8 21v2"></path><path d="M12 15v2"></path><path d="M12 21v2"></path><path d="M16 15v2"></path><path d="M16 21v2"></path></svg>
                    <br>
                    22&deg;C<br>Berawan
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="content-layout">
        <!-- Left Column -->
        <div class="left-col">
            <a href="{{ route('public.kuliner') }}" class="btn-kembali-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali Ke Daftar Kuliner
            </a>
            
            <img src="{{ $kuliner->foto ? asset('storage/'.$kuliner->foto) : asset('images/beranda.png') }}" alt="{{ $kuliner->nama_kuliner }}" class="main-image">
            
            <div class="map-preview">
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Map Bandung">
                <a href="https://maps.google.com/?q={{ urlencode($kuliner->alamat_lengkap) }}" target="_blank" class="btn-buka-maps">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M11.584 2.366a2.25 2.25 0 012.832 0l8 5.774a2.25 2.25 0 01.33 3.37l-7.25 8.459a2.25 2.25 0 01-3.492 0L4.755 11.51a2.25 2.25 0 01.33-3.37l8-5.774zM12 10.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    Buka Maps
                </a>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="right-col">
            <div class="stats-bar">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #0f172a;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color: #2563eb;">
                      <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                      <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                    </svg>
                    110 Kali Dibaca
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #0f172a;">
                    Bagikan: 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: #2563eb; cursor: pointer;">
                      <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 11.825 2.066l-8.421 4.679a3.002 3.002 0 010 1.51l8.421 4.679a3 3 0 11-.729 1.31l-8.421-4.678a3 3 0 110-4.132l8.421-4.679a3 3 0 01-.096-.755z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            
            <div class="info-cards-grid">
                <div class="info-card">
                    <div class="info-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: #ef4444;">
                          <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Location
                    </div>
                    <div class="info-text">{{ $kuliner->alamat_lengkap ?? '-' }}</div>
                </div>
                
                <div class="info-card">
                    <div class="info-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: #3b82f6;">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        Jam Operasional
                    </div>
                    <div class="info-text">{{ $kuliner->jam_operasional ?? '-' }}</div>
                </div>
                
                <div class="info-card">
                    <div class="info-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: #3b82f6;">
                          <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                        </svg>
                        Kontak Penghubung
                    </div>
                    <div class="info-text">{{ $kuliner->kontak ?? '-' }}</div>
                </div>
            </div>
            
            <h2 class="detail-title">{{ $kuliner->nama_kuliner }}</h2>
            <div class="detail-desc">
                {!! nl2br(e($kuliner->deskripsi)) !!}
            </div>
        </div>
    </div>
</div>

<!-- Kuliner Terkait Section -->
<div class="related-section">
    <div class="container">
        <h2 style="font-size: 2.2rem; font-weight: 800; font-family: 'Outfit', sans-serif; margin-bottom: 2rem;">Kuliner Terkait Lainnya</h2>
        
        <div class="related-grid">
            @php
                // Fetch 3 related kuliner except current one
                $related = \App\Models\Kuliner::where('id_kuliner', '!=', $kuliner->id_kuliner)
                            ->where('status', 'Aktif')
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();
            @endphp
            
            @foreach($related as $rel)
            <div class="related-card">
                <img src="{{ $rel->foto ? asset('storage/'.$rel->foto) : asset('images/beranda.png') }}" alt="{{ $rel->nama_kuliner }}">
                <div class="related-info">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0; font-size: 1.25rem; font-weight: 700; font-family: 'Outfit', sans-serif;">{{ $rel->nama_kuliner }}</h4>
                        <div style="display: flex; align-items: center; gap: 0.25rem; font-weight: 700; font-size: 0.95rem;">
                            <span style="color: #fbbf24;">&#9733;</span> 4.7
                        </div>
                    </div>
                    <div style="font-size: 0.85rem; margin-bottom: 1.5rem; color: #f1f5f9; line-height: 1.6;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #ef4444;">&#128205;</span> Bandung &bull; 1.5 km
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Buka {{ Str::limit($rel->jam_operasional, 15) }}
                        </div>
                    </div>
                    <a href="{{ route('public.kuliner.show', $rel->slug) }}" class="btn-outline-light">Lihat Detail</a>
                </div>
            </div>
            @endforeach
            
            @if(count($related) == 0)
                <div style="grid-column: 1 / -1; text-align: center; color: #64748b; margin-top: 1rem;">
                    Belum ada kuliner terkait lainnya.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
