@extends('layouts.app')
@section('title', $berita->judul)

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    /* Hero Section */
    .berita-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1555899434-94d1368aa7af?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
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

    /* Main Container Padding */
    .berita-detail-container {
        background-color: white;
        padding: 0 0 4rem;
    }

    /* Audio Bar */
    .audio-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .audio-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background-color: #3b82f6;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(59,130,246,0.3);
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    
    .audio-btn:hover {
        transform: scale(1.05);
    }

    .audio-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-left: 1.5rem;
        font-family: 'Outfit', sans-serif;
        color: #0f172a;
        flex: 1;
        line-height: 1.3;
    }

    .audio-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
        color: #334155;
        font-weight: 500;
    }

    /* Meta Bar */
    .meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.5rem;
    }

    .meta-items {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        font-weight: 700;
        color: #0f172a;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-item svg {
        color: #3b82f6;
    }

    .btn-kembali-blue {
        background-color: #3b82f6;
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 9999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px rgba(59,130,246,0.2);
        transition: background 0.3s;
    }
    
    .btn-kembali-blue:hover {
        background-color: #2563eb;
    }

    /* Main Grid */
    .content-grid {
        display: flex;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .left-col {
        width: 35%;
    }

    .right-col {
        width: 65%;
    }

    .article-img {
        width: 100%;
        border-radius: 1.5rem;
        object-fit: cover;
        margin-bottom: 1.5rem;
        aspect-ratio: 4/3;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .info-table {
        background-color: #f8fafc;
        border-radius: 0.5rem;
        padding: 1.5rem;
        width: 100%;
        font-size: 0.85rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .info-title-head {
        color: #2563eb;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .info-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td {
        padding: 0.5rem 0;
        color: #475569;
        vertical-align: top;
    }
    
    .info-table td:first-child {
        font-weight: 600;
        color: #1e40af;
        width: 120px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-table td:nth-child(2) {
        width: 20px;
        text-align: center;
    }

    .article-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
        text-align: justify;
    }

    /* Reactions Box */
    .reactions-container {
        display: flex;
        margin-top: 3rem;
    }
    
    .reactions-box {
        border: 2px solid #3b82f6;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 1.5rem;
    }

    .reaction-label {
        font-weight: 800;
        color: #1e40af;
        font-size: 1.1rem;
    }

    .reaction-btn {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 0.4rem 1.25rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 1rem;
        transition: transform 0.2s, background-color 0.2s;
    }
    
    .reaction-btn:hover {
        transform: scale(1.05);
        background-color: #2563eb;
    }

    /* Related News */
    .related-title {
        font-size: 2rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        margin-bottom: 2rem;
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .related-card {
        display: flex;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        gap: 1.5rem;
        background: white;
        align-items: stretch;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    
    .related-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }

    .related-img {
        width: 180px;
        border-radius: 0.5rem;
        object-fit: cover;
    }

    .related-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .related-date {
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .related-article-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
        line-height: 1.3;
        font-family: 'Outfit', sans-serif;
    }

    .btn-baca {
        background-color: #3b82f6;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        align-self: flex-start;
        margin-top: auto;
        transition: background 0.3s;
    }
    
    .btn-baca:hover {
        background-color: #2563eb;
    }

    @media (max-width: 992px) {
        .content-grid { flex-direction: column; }
        .left-col, .right-col { width: 100%; }
        .related-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .meta-bar { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .audio-bar { flex-direction: column; text-align: center; gap: 1rem; }
        .audio-title { margin: 0.5rem 0; }
        .related-card { flex-direction: column; }
        .related-img { width: 100%; height: 200px; }
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

        <!-- Hero Content -->
        <div class="hero-content">
            <h1 class="hero-title">Berita Terbaru</h1>
            <div class="hero-breadcrumb">
                Berita &nbsp;&rarr;&nbsp; <span>Detail Berita</span>
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

<div class="berita-detail-container">
    <div class="container">
        
        <!-- Audio Bar -->
        <div class="audio-bar">
            <div class="audio-btn" onclick="toggleAudio()">
                <svg id="play-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                  <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                </svg>
                <svg id="pause-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="display: none;">
                  <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                </svg>
            </div>
            
            <h2 class="audio-title">{{ $berita->judul }}</h2>
            
            <div class="audio-label">
                Audio Play
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color: #3b82f6;">
                  <path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" />
                  <path d="M15.932 7.757a.75.75 0 011.061 0 6 6 0 010 8.486.75.75 0 01-1.06-1.061 4.5 4.5 0 000-6.364.75.75 0 010-1.06z" />
                </svg>
            </div>
            
            @if($berita->audio_url)
                <audio id="berita-audio" src="{{ asset('storage/'.$berita->audio_url) }}"></audio>
            @endif
        </div>
        
        <!-- Meta Bar -->
        <div>
            <div style="font-size: 1.1rem; color: #334155; font-weight: 500; margin-bottom: 0.5rem;">
                Waktu Baca : {{ ceil(str_word_count(strip_tags($berita->isi)) / 200) }} Menit
            </div>
            <div class="meta-bar">
                <div class="meta-items">
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd" />
                        </svg>
                        {{ \Carbon\Carbon::parse($berita->tanggal)->locale('id')->translatedFormat('l, d-m-Y') }}
                    </div>
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                          <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                          <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                        </svg>
                        {{ $berita->visitor_count }} Kali Dibaca
                    </div>
                    <div class="meta-item">
                        Bagikan: 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="cursor: pointer;">
                          <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 11.825 2.066l-8.421 4.679a3.002 3.002 0 010 1.51l8.421 4.679a3 3 0 11-.729 1.31l-8.421-4.678a3 3 0 110-4.132l8.421-4.679a3 3 0 01-.096-.755z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <a href="{{ route('public.berita') }}" class="btn-kembali-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-col">
                <img src="{{ $berita->gambar ? asset('storage/'.$berita->gambar) : asset('images/beranda.png') }}" alt="{{ $berita->judul }}" class="article-img">
                
                <div class="info-table">
                    <div class="info-title-head">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                        </svg>
                        Informasi Berita
                    </div>
                    <table>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd" /></svg>
                                Tanggal
                            </td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($berita->tanggal)->locale('id')->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 005.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 00-2.122-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" /></svg>
                                Kategori
                            </td>
                            <td>:</td>
                            <td>{{ $berita->kategori ?? 'Berita Pendidikan' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                Lokasi
                            </td>
                            <td>:</td>
                            <td>Kota Bandung, Jawa Barat</td>
                        </tr>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
                                Penulis
                            </td>
                            <td>:</td>
                            <td>Admin sipwb</td>
                        </tr>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 18.375V5.625zM21 9.375A.375.375 0 0020.625 9h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 00.375-.375v-1.5zm0 3.75a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 00.375-.375v-1.5zm0 3.75a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 00.375-.375v-1.5zM4.625 9h5.25a.375.375 0 01.375.375v6.75a.375.375 0 01-.375.375h-5.25a.375.375 0 01-.375-.375v-6.75a.375.375 0 01.375-.375z" clip-rule="evenodd" /></svg>
                                Media
                            </td>
                            <td>:</td>
                            <td>sipwb.Jabar</td>
                        </tr>
                        <tr>
                            <td>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.125 4.5a4.125 4.125 0 102.338 7.524l2.007 2.006a.75.75 0 101.06-1.06l-2.006-2.007a4.125 4.125 0 00-3.399-6.463zm-2.625 4.125a2.625 2.625 0 115.25 0 2.625 2.625 0 01-5.25 0z" clip-rule="evenodd" /></svg>
                                Sumber
                            </td>
                            <td>:</td>
                            <td>www.bandung.go.id</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-col">
                <div class="article-content">
                    {!! nl2br(e($berita->isi)) !!}
                </div>
                
                <div class="reactions-container">
                    <div class="reactions-box">
                        <span class="reaction-label">Reaksi Anda :</span>
                        <button class="reaction-btn" onclick="sendReaction('like')">
                            &#128077; <span id="like-count">{{ $reaksi ? $reaksi->like_count : 0 }}</span>
                        </button>
                        <button class="reaction-btn" onclick="sendReaction('applause')">
                            &#128079; <span id="applause-count">{{ $reaksi ? $reaksi->applause_count : 0 }}</span>
                        </button>
                        <button class="reaction-btn" onclick="sendReaction('insightful')">
                            &#128161; <span id="insightful-count">{{ $reaksi ? $reaksi->insightful_count : 0 }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related News -->
        <h2 class="related-title">Berita Terkait Lainnya</h2>
        <div class="related-grid">
            @php
                $related = \App\Models\Berita::where('id_berita', '!=', $berita->id_berita)->latest()->limit(4)->get();
            @endphp
            
            @foreach($related as $rel)
            <div class="related-card">
                <img src="{{ $rel->gambar ? asset('storage/'.$rel->gambar) : asset('images/beranda.png') }}" alt="{{ $rel->judul }}" class="related-img">
                <div class="related-content">
                    <div class="related-date">{{ \Carbon\Carbon::parse($rel->tanggal)->locale('id')->translatedFormat('l, d M Y') }}</div>
                    <div class="related-article-title">{{ Str::limit($rel->judul, 65) }}</div>
                    <a href="{{ route('public.berita.show', $rel->slug) }}" class="btn-baca">
                        Baca Selengkapnya &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<script>
    function toggleAudio() {
        const audio = document.getElementById('berita-audio');
        const playIcon = document.getElementById('play-icon');
        const pauseIcon = document.getElementById('pause-icon');
        
        if (!audio) return;
        
        if (audio.paused) {
            audio.play();
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        } else {
            audio.pause();
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
        
        audio.onended = function() {
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        };
    }

    function sendReaction(type) {
        // Prepare CSRF Token usually available in Laravel (fetch from a meta tag if exists, otherwise assume standard POST without it might fail)
        // A standard fallback:
        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/berita/{{ $berita->id_berita }}/react`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ tipe_reaksi: type })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('like-count').innerText = data.data.like_count;
                document.getElementById('applause-count').innerText = data.data.applause_count;
                document.getElementById('insightful-count').innerText = data.data.insightful_count;
            }
        })
        .catch(err => console.error('Error sending reaction:', err));
    }
</script>
@endsection
