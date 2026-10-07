@extends('layouts.app')
@section('title', 'Berita')

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    /* Hero Section specific to Berita */
    .berita-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1555899434-94d1368aa7af?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
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

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        font-family: 'Outfit', sans-serif;
    }

    .hero-breadcrumb {
        font-size: 1.1rem;
        color: #cbd5e1;
        margin-bottom: 2.5rem;
    }

    .hero-breadcrumb span {
        color: white;
        font-weight: 500;
    }

    .hero-categories {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .hero-category-pill {
        background-color: white;
        color: #1e3a8a;
        padding: 0.6rem 2rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .hero-category-pill.active {
        background-color: #2563eb;
        color: white;
    }

    .hero-category-pill:hover:not(.active) {
        background-color: #f1f5f9;
        color: #1d4ed8;
    }

    /* Main Content Area */
    .berita-main-container {
        background-color: white;
        padding: 3rem 0;
    }

    .berita-list {
        max-width: 1000px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .news-card-horizontal {
        display: flex;
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        gap: 2rem;
        border: 2px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .news-card-horizontal:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #e2e8f0;
    }

    .news-image-container {
        flex: 0 0 320px;
    }

    .news-image-container img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 1rem;
    }

    .news-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 0.5rem 0;
    }

    .news-date {
        color: #475569;
        font-size: 1.05rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .news-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        line-height: 1.3;
        font-family: 'Outfit', sans-serif;
    }

    .news-excerpt {
        color: #334155;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .btn-read-more {
        background-color: #2563eb;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 9999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        align-self: flex-start;
        transition: background-color 0.3s;
        font-size: 0.95rem;
    }

    .btn-read-more:hover {
        background-color: #1d4ed8;
        color: white;
        transform: translateY(-1px);
    }
</style>

<div class="berita-hero">
    <div class="container">
        <!-- Custom Top Navigation -->
        <div class="hero-top-nav">
            <a href="{{ route('home') }}" class="hero-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Bandung Info Center">
                <span>Bandung<br>Info Center</span>
            </a>
            
            <ul class="hero-nav-links">
                <li><a href="{{ route('home') }}" class="hero-nav-pill">Beranda</a></li>
                <li><a href="{{ route('public.berita') }}" class="hero-nav-pill active">Berita</a></li>
                <li><a href="{{ route('public.wisata') }}" class="hero-nav-pill">Wisata</a></li>
                <li><a href="{{ route('public.kuliner') }}" class="hero-nav-pill">Kuliner</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill">Sejarah</a></li>
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill">Galeri</a></li>
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
    <h1 class="hero-title" style="color: white;">Berita Terbaru</h1>
    <div class="hero-breadcrumb" style="color: rgba(255, 255, 255, 0.9);">
        Beranda &rarr; <span>berita</span>
    </div>
</div>

            <!-- Categories -->
            <div class="hero-categories">
                <a href="#" class="hero-category-pill active">Semua</a>
                <a href="#" class="hero-category-pill">Pemerintahan</a>
                <a href="#" class="hero-category-pill">Infrastruktur</a>
                <a href="#" class="hero-category-pill">Ekonomi</a>
                <a href="#" class="hero-category-pill">Pendidikan</a>
                <a href="#" class="hero-category-pill">Peristiwa</a>
                <a href="#" class="hero-category-pill">Kesehatan</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content (News List) -->
<div class="berita-main-container">
    <div class="container">
        <div class="berita-list">
            @foreach($beritas as $berita)
            <div class="news-card-horizontal">
                <div class="news-image-container">
                    @if($berita->gambar)
                        <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Placeholder">
                    @endif
                </div>
                <div class="news-content">
                    <div class="news-date">
                        {{ \Carbon\Carbon::parse($berita->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    </div>
                    <h3 class="news-title">{{ $berita->judul }}</h3>
                    <p class="news-excerpt">{{ Str::limit(strip_tags($berita->isi), 120) }}</p>
                    <a href="{{ route('public.berita.show', $berita->slug) }}" class="btn-read-more">Baca Selengkapnya</a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-5 text-center">
            {{ $beritas->links() }}
        </div>
    </div>
</div>
@endsection
