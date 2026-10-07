@extends('layouts.app')
@section('title', 'Galeri')

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    /* Hero Section specific to Galeri */
    .galeri-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1549473889-14f410d83298?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
        padding: 2rem 0 4rem;
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
        font-size: 1.05rem;
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
        margin-bottom: 0.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Main Content Area */
    .galeri-main-container {
        background-color: white;
        padding: 3rem 0;
    }
    
    .galeri-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3rem;
    }
    
    .header-left, .header-right {
        flex: 1;
    }
    
    .btn-kembali {
        background-color: #3b82f6;
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem; /* Rounded rect like in screenshot */
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .btn-kembali:hover {
        background-color: #2563eb;
        color: white;
        transform: translateY(-2px);
    }
    
    .section-title {
        color: #1d4ed8; /* Darker blue */
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        font-family: 'Outfit', sans-serif;
        text-align: center;
        flex: 2;
    }

    .galeri-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .galeri-item {
        border-radius: 1rem;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        background-color: #f8fafc;
    }
    
    .galeri-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .galeri-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    @media (max-width: 1200px) {
        .galeri-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 992px) {
        .galeri-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .galeri-grid { grid-template-columns: repeat(2, 1fr); }
        .galeri-header-section { flex-direction: column; gap: 1rem; text-align: center; }
        .header-left, .header-right { flex: none; width: 100%; }
        .btn-kembali { margin: 0 auto; }
    }
    @media (max-width: 576px) {
        .galeri-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="galeri-hero">
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
                <li><a href="{{ route('public.wisata') }}" class="hero-nav-pill">Wisata</a></li>
                <li><a href="{{ route('public.kuliner') }}" class="hero-nav-pill">Kuliner</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill">Sejarah</a></li>
                <li><a href="{{ route('public.galeri') }}" class="hero-nav-pill active">Galeri</a></li>
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

        <!-- Hero Content -->
        <div class="hero-content">
            <div class="hero-breadcrumb">
                Beranda &nbsp;&#10095;&nbsp; <span>Galeri</span>
            </div>
            <h1 class="hero-title">Galeri Foto Bandung</h1>
        </div>
    </div>
</div>

<!-- Main Content (Galeri Grid) -->
<div class="galeri-main-container">
    <div class="container">
        <div class="galeri-header-section">
            <div class="header-left">
                <a href="javascript:history.back()" class="btn-kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
            <h2 class="section-title">Galeri Foto (Semua Kategori)</h2>
            <div class="header-right"></div>
        </div>

        <div class="galeri-grid">
            @foreach($galeris as $galeri)
            <div class="galeri-item" title="{{ $galeri->judul }}">
                @if($galeri->file_gambar)
                    <img src="{{ asset('storage/' . $galeri->file_gambar) }}" alt="{{ $galeri->judul }}">
                @else
                    <img src="https://images.unsplash.com/photo-1513343041531-1502b6623fb9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Placeholder">
                @endif
            </div>
            @endforeach
            
            @if(count($galeris) == 0)
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 0; color: #64748b;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="margin: 0 auto 1rem; opacity: 0.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3>Belum ada foto di galeri.</h3>
                </div>
            @endif
        </div>
        
        <div class="mt-4 text-center">
            {{ $galeris->links() }}
        </div>
    </div>
</div>
@endsection
