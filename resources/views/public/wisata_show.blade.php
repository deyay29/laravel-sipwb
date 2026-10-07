@extends('layouts.app')
@section('title', $wisata->nama_wisata ?? 'Detail Wisata')

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    .wisata-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.6)), url('{{ $wisata->gambar ? asset('storage/'.$wisata->gambar) : asset('images/beranda.png') }}') center/cover no-repeat;
        padding: 2rem 0 6rem;
        min-height: 450px;
    }

    .hero-top-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4rem;
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

    .hero-search svg {
        width: 16px;
        height: 16px;
        color: #6b7280;
    }

    .hero-content {
        color: white;
    }
    
    .hero-breadcrumb {
        font-size: 1rem;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
    }

    .hero-breadcrumb span {
        color: white;
        font-weight: 600;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Main Container */
    .wisata-container {
        background-color: white;
        padding: 2rem 0 4rem;
    }

    .top-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .btn-back-blue {
        background-color: #3b82f6;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px rgba(59,130,246,0.3);
        transition: background 0.3s;
    }
    
    .btn-back-blue:hover {
        background-color: #2563eb;
    }

    .meta-actions {
        display: flex;
        gap: 3rem;
        align-items: center;
        font-weight: 700;
        color: #0f172a;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .meta-item svg {
        color: #3b82f6;
    }

    /* Grid Layout */
    .content-grid {
        display: flex;
        gap: 3rem;
        margin-bottom: 4rem;
    }
    
    .left-col {
        width: 35%;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    
    .right-col {
        width: 65%;
    }

    .main-img {
        width: 100%;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        aspect-ratio: 16/9;
        object-fit: cover;
    }

    .info-boxes-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .info-box {
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        padding: 1rem;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .info-box-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .info-box-content {
        font-size: 0.8rem;
        color: #475569;
        line-height: 1.4;
    }

    .ticket-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    
    .map-embed {
        width: 100%;
        height: 250px;
        border-radius: 0.75rem;
        border: 1px solid #cbd5e1;
        background: url('https://upload.wikimedia.org/wikipedia/commons/b/b3/Map_of_Bandung.png') center/cover;
    }

    /* Deskripsi & Right Content */
    .section-heading {
        font-size: 1.8rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        color: #0f172a;
        margin-bottom: 1rem;
    }
    
    .desc-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
        text-align: justify;
        margin-bottom: 2rem;
    }

    /* Galeri */
    .gallery-grid {
        display: flex;
        gap: 1rem;
        margin-bottom: 3rem;
        align-items: center;
    }
    
    .gallery-img {
        flex: 1;
        height: 130px;
        border-radius: 0.75rem;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .gallery-nav {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #1d4ed8;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(29,78,216,0.3);
    }

    /* Features / Tips / Weather */
    .info-3col {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }
    
    .feature-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
    }
    
    .feature-item svg {
        color: #3b82f6;
    }
    
    .tips-card {
        background-color: #3b82f6;
        color: white;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    
    .tips-title {
        font-weight: 700;
        text-align: center;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    
    .tips-list {
        font-size: 0.75rem;
        padding-left: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .weather-card {
        background-color: #f8fafc;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    
    .weather-title {
        color: #2563eb;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    
    .temp {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        line-height: 1.1;
    }
    
    .temp-desc {
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        color: #0f172a;
    }
    
    .weather-details {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #475569;
        border-top: 1px solid #cbd5e1;
        border-bottom: 1px solid #cbd5e1;
        padding: 0.75rem 0;
        margin-bottom: 1rem;
    }
    
    .w-detail {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .crowd-box {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .crowd-box svg {
        color: #3b82f6;
    }
    
    .crowd-info h4 {
        margin: 0;
        font-size: 0.9rem;
        color: #0f172a;
    }
    
    .crowd-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #475569;
    }

    /* Related Wisata */
    .related-section-title {
        font-size: 2rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        margin-bottom: 1.5rem;
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .r-card {
        background-color: #3b82f6;
        border-radius: 1rem;
        overflow: hidden;
        color: white;
        display: flex;
        flex-direction: column;
    }
    
    .r-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .r-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .r-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-family: 'Outfit', sans-serif;
    }
    
    .r-desc {
        font-size: 0.8rem;
        line-height: 1.5;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        flex: 1;
    }
    
    .btn-r-selengkapnya {
        background-color: white;
        color: #2563eb;
        text-align: center;
        padding: 0.6rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        transition: background 0.3s;
    }
    
    .btn-r-selengkapnya:hover {
        background-color: #f1f5f9;
    }

    @media (max-width: 992px) {
        .content-grid { flex-direction: column; }
        .left-col, .right-col { width: 100%; }
        .info-3col { grid-template-columns: 1fr; }
        .related-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="wisata-hero">
    <div class="container">
        <!-- Custom Top Navigation -->
        <div class="hero-top-nav">
            <a href="{{ route('home') }}" class="hero-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Bandung Info Center">
                <span>Bandung<br>Info Center</span>
            </a>
            
            <ul class="hero-nav-links">
                <li><a href="{{ route('home') }}" class="hero-nav-pill">Beranda</a></li>
                <li><a href="{{ route('public.berita') }}" class="hero-nav-pill">Berita</a></li>
                <li><a href="{{ route('public.wisata') }}" class="hero-nav-pill active">Wisata</a></li>
                <li><a href="{{ route('public.kuliner') }}" class="hero-nav-pill">Kuliner</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill">Sejarah</a></li>
                <li><a href="#" class="hero-nav-pill">Profil</a></li>
                <li><a href="{{ route('public.galeri') }}" class="hero-nav-pill">Kontak</a></li>
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

        <div class="hero-content">
            <div class="hero-breadcrumb">
                Beranda &nbsp;&rsaquo;&nbsp; Wisata &nbsp;&rsaquo;&nbsp; <span>{{ $wisata->nama_wisata ?? 'Gedung Sate' }}</span>
            </div>
            <h1 class="hero-title">{{ $wisata->nama_wisata ?? 'Gedung Sate' }}</h1>
        </div>
    </div>
</div>

<div class="wisata-container">
    <div class="container">
        
        <div class="top-action-bar">
            <a href="{{ route('public.wisata') }}" class="btn-back-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali Ke Daftar Wisata Bandung
            </a>
            
            <div class="meta-actions">
                <div class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                      <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                      <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                    </svg>
                    170 Kali Dibaca
                </div>
                <div class="meta-item">
                    Bagikan:
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="cursor:pointer;">
                      <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 11.825 2.066l-8.421 4.679a3.002 3.002 0 010 1.51l8.421 4.679a3 3 0 11-.729 1.31l-8.421-4.678a3 3 0 110-4.132l8.421-4.679a3 3 0 01-.096-.755z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-col">
                <img src="{{ $wisata->gambar ? asset('storage/'.$wisata->gambar) : asset('images/beranda.png') }}" alt="{{ $wisata->nama_wisata }}" class="main-img">
                
                <div class="info-boxes-grid">
                    <div class="info-box">
                        <div class="info-box-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                            Lokasi
                        </div>
                        <div class="info-box-content">
                            {{ $wisata->lokasi ?? 'Jl. Diponegoro No.22, Bandung Wetan' }}
                        </div>
                    </div>
                    
                    <div class="info-box" style="grid-row: span 2;">
                        <div class="info-box-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" /></svg>
                            Harga Tiket
                        </div>
                        <div class="info-box-content">
                            @if($wisata->harga_tiket)
                                {{ $wisata->harga_tiket }}
                            @else
                            <div class="ticket-row"><span>Dewasa</span><span>Rp.5.000</span></div>
                            <div class="ticket-row"><span>Anak - Anak</span><span>Rp.3.000</span></div>
                            <div class="ticket-row"><span>WNA</span><span>Rp.10.000</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-box-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>
                            Jam Operasional
                        </div>
                        <div class="info-box-content">
                            09.30 - 16.00 WIB
                        </div>
                    </div>
                </div>
                
                <div class="map-embed"></div>
            </div>

            <!-- Right Column -->
            <div class="right-col">
                <h2 class="section-heading">Deskripsi Wisata</h2>
                <div class="desc-text">
                    @if($wisata->deskripsi)
                        {!! nl2br(e($wisata->deskripsi)) !!}
                    @else
                        Gedung Sate memiliki berbagai cerita yang berkembang di masyarakat Bandung. Nama "Gedung Sate" berasal dari ornamen di puncak menara yang bentuknya menyerupai tusuk sate. Selain menjadi ikon Kota Bandung, gedung ini juga dikenal sebagai saksi perjuangan tujuh pemuda yang gugur saat mempertahankan Gedung Sate pada masa perjuangan kemerdekaan. Cerita tersebut membuat Gedung Sate tidak hanya memiliki nilai arsitektur dan sejarah, tetapi juga menjadi simbol perjuangan masyarakat Bandung.
                    @endif
                </div>

                <h2 class="section-heading">Galeri Foto</h2>
                <div class="gallery-grid">
                    <img src="{{ asset('images/beranda.png') }}" class="gallery-img">
                    <img src="{{ asset('images/beranda.png') }}" class="gallery-img">
                    <img src="{{ asset('images/beranda.png') }}" class="gallery-img">
                    <div class="gallery-nav">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <div class="info-3col">
                    <div>
                        <div class="info-box-title" style="margin-bottom: 1rem; color:#0f172a; font-size:1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color:#0f172a;"><path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12 17.25a.75.75 0 100-1.5.75.75 0 000 1.5zM11.25 7a.75.75 0 011.5 0v6a.75.75 0 01-1.5 0V7z"/></svg>
                            Fasilitas
                        </div>
                        <div class="feature-list">
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Area Parkir</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Taman & Ruang terbuka</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Museum gedung sate</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Ruang Pameran</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Toilet</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Spot Foto</div>
                            <div class="feature-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> Pusat Informasi</div>
                        </div>
                    </div>

                    <div class="tips-card">
                        <div class="tips-title">Tips Berkunjung</div>
                        <ul class="tips-list">
                            <li>Datang pada pagi atau siang hari agar lebih nyaman.</li>
                            <li>Gunakan pakaian yang nyaman karena terdapat area luar ruangan.</li>
                            <li>Perhatikan aturan dari area yang boleh dimasuki pengunjung.</li>
                            <li>Jika ingin mengunjungi museum, cek jadwal operasional terlebih dahulu.</li>
                            <li>Gunakan transportasi umum atau datang lebih awal untuk menghindari kepadatan.</li>
                        </ul>
                    </div>

                    <div class="weather-card">
                        <div class="weather-title">Cuaca Hari Ini</div>
                        <div class="temp">30°C</div>
                        <div class="temp-desc">Cerah Berawan</div>
                        
                        <div class="weather-details">
                            <div class="w-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" style="color:#3b82f6;"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.53 5.47a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72v5.69a.75.75 0 001.5 0v-5.69l1.72 1.72a.75.75 0 101.06-1.06l-3-3z" clip-rule="evenodd" /></svg>
                                <div>Kelembapan<br><b>60%</b></div>
                            </div>
                            <div class="w-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" style="color:#3b82f6;"><path fill-rule="evenodd" d="M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0zm7.5-5.25a.75.75 0 00-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 000-1.5h-2.25V7.5a.75.75 0 00-.75-.75z" clip-rule="evenodd" /></svg>
                                <div>Angin 6 km/jam</div>
                            </div>
                        </div>
                        
                        <div class="weather-title">Tingkat Keramaian</div>
                        <div class="crowd-box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36" height="36">
                              <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z" />
                            </svg>
                            <div class="crowd-info">
                                <div>
                                    <h4>Sedang</h4>
                                    <p>Ramai di akhir pekan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wisata Terkait Lainnya -->
        <h2 class="related-section-title">Wisata Terkait Lainnya</h2>
        <div class="related-grid">
            @php
                $related = \App\Models\Wisata::where('id_wisata', '!=', $wisata->id_wisata)->inRandomOrder()->limit(3)->get();
            @endphp
            
            @foreach($related as $rel)
            <div class="r-card">
                <img src="{{ $rel->gambar ? asset('storage/'.$rel->gambar) : asset('images/beranda.png') }}" alt="{{ $rel->nama_wisata }}" class="r-img">
                <div class="r-body">
                    <div class="r-title">{{ $rel->nama_wisata }}</div>
                    <div class="r-desc">{{ Str::limit($rel->deskripsi, 120, '...') }}</div>
                    <a href="{{ route('public.wisata.show', $rel->slug) }}" class="btn-r-selengkapnya">
                        Selengkapnya
                    </a>
                </div>
            </div>
            @endforeach
            
            @if($related->isEmpty())
                <!-- Fallback dummy cards if db is empty -->
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Jalan Braga</div>
                        <div class="r-desc">Jalan Braga merupakan kawasan ikonik di kota Bandung yang terkenal dengan bangunan bergaya kolonial, kafe, restoran, dan suasana klasik.</div>
                        <a href="#" class="btn-r-selengkapnya">Selengkapnya</a>
                    </div>
                </div>
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1599930113854-d6d7fd521f10?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Museum Asia Afrika</div>
                        <div class="r-desc">Museum Konferensi Asia Afrika merupakan museum bersejarah di Kota Bandung yang menyimpan berbagai koleksi dan informasi tentang Konferensi Asia Afrika.</div>
                        <a href="#" class="btn-r-selengkapnya">Selengkapnya</a>
                    </div>
                </div>
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1572099606223-6e29045d7de3?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Museum Geologi</div>
                        <div class="r-desc">Museum Geologi Bandung merupakan museum yang menyimpan berbagai koleksi geologi dan edukasi peninggalan bersejarah bagi bangsa.</div>
                        <a href="#" class="btn-r-selengkapnya">Selengkapnya</a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
