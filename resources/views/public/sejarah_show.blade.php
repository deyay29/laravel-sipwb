@extends('layouts.app')
@section('title', $topik->judul_topik ?? 'Detail Sejarah')

@section('content')
<style>
    /* Hide the default navbar from the layout */
    .navbar {
        display: none !important;
    }
    
    .sejarah-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('{{ $topik->gambar_utama ? asset('storage/'.$topik->gambar_utama) : asset('images/beranda.png') }}') center/cover no-repeat;
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
        color: white;
        margin-bottom: 0.5rem;
        font-family: 'Outfit', sans-serif;
        max-width: 800px;
        line-height: 1.2;
    }

    .sejarah-container {
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

    .image-collage {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .collage-img-1, .collage-img-2 {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .collage-img-3 {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 1rem;
        grid-column: span 2;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn-notebook {
        background-color: transparent;
        border: 2px solid #0f172a;
        color: #0f172a;
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-weight: 700;
        text-align: center;
        display: block;
        width: 100%;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-notebook:hover {
        background-color: #0f172a;
        color: white;
    }

    .section-heading {
        font-size: 2rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        color: #000;
        margin-bottom: 1.5rem;
    }
    
    .desc-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
        text-align: justify;
    }

    .event-card {
        margin-top: 2rem;
    }
    
    .event-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .event-date svg {
        color: #ef4444; /* red calendar icon */
    }
    
    .event-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .event-desc {
        font-size: 1rem;
        color: #334155;
        line-height: 1.6;
        text-align: justify;
    }

    /* Related Sejarah */
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
        border-radius: 1rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .r-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .r-body {
        background-color: #2b5c92;
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        color: white;
    }
    
    .r-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        font-family: 'Outfit', sans-serif;
        line-height: 1.4;
    }
    
    .r-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }
    
    .btn-r-detail {
        border: 1px solid white;
        background: transparent;
        color: white;
        text-align: center;
        padding: 0.6rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: background 0.3s;
        margin-top: auto;
    }
    
    .btn-r-detail:hover {
        background-color: rgba(255,255,255,0.1);
    }

    @media (max-width: 992px) {
        .content-grid { flex-direction: column; }
        .left-col, .right-col { width: 100%; }
        .related-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="sejarah-hero">
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
                <li><a href="{{ route('public.sejarah') }}" class="hero-nav-pill active">Sejarah</a></li>
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
                Beranda &nbsp;&rsaquo;&nbsp; Sejarah &nbsp;&rsaquo;&nbsp; <span>{{ $topik->judul_topik ?? 'Detail Sejarah' }}</span>
            </div>
            <h1 class="hero-title">{{ $topik->judul_topik ?? 'Detail Sejarah' }}</h1>
        </div>
    </div>
</div>

<div class="sejarah-container">
    <div class="container">
        
        <div class="top-action-bar">
            <a href="{{ route('public.sejarah') }}" class="btn-back-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali Ke Daftar Sejarah Bandung
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
                <div class="image-collage">
                    <img src="{{ $topik->gambar_utama ? asset('storage/'.$topik->gambar_utama) : asset('images/beranda.png') }}" class="collage-img-1" alt="Sejarah 1">
                    <img src="{{ $topik->gambar_utama ? asset('storage/'.$topik->gambar_utama) : asset('images/beranda.png') }}" class="collage-img-2" alt="Sejarah 2">
                    <img src="{{ $topik->gambar_utama ? asset('storage/'.$topik->gambar_utama) : asset('images/beranda.png') }}" class="collage-img-3" alt="Sejarah 3">
                </div>
                
                <a href="#" class="btn-notebook">Lihat NoteBook</a>
            </div>

            <!-- Right Column -->
            <div class="right-col">
                <h2 class="section-heading">Deskripsi Sejarah</h2>
                
                <div class="desc-text">
                    @if($topik->ringkasan)
                        {!! nl2br(e($topik->ringkasan)) !!}
                    @else
                        Tonggak Konferensi Asia Afrika merupakan salah satu peristiwa paling bersejarah yang menjadikan Kota Bandung dikenal di mata dunia. Konferensi ini berlangsung pada 18-24 April 1955 di Gedung Merdeka, Bandung, dan dihadiri oleh 29 negara dari Asia dan Afrika yang baru merdeka maupun masih berjuang memperoleh kemerdekaan.
                        <br><br>
                        Konferensi Asia Afrika diselenggarakan untuk mempererat kerja sama antarnegara Asia dan Afrika, menolak segala bentuk kolonialisme, serta mendorong perdamaian dunia. Indonesia menjadi tuan rumah bersama beberapa negara penggagas, yaitu India, Pakistan, Myanmar (Burma), dan Sri Lanka.
                        <br><br>
                        Hasil terpenting dari konferensi ini adalah lahirnya Dasasila Bandung, yaitu sepuluh prinsip yang menjadi pedoman hubungan internasional berdasarkan penghormatan terhadap kedaulatan, kemerdekaan, persamaan derajat, serta penyelesaian konflik secara damai.
                    @endif
                </div>

                <div class="event-card">
                    <div class="event-date">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd" /></svg>
                        24 April 1955
                    </div>
                    <div class="event-title">Lahirnya Dasasila Bandung</div>
                    <div class="event-desc">
                        Konferensi ditutup dengan menghasilkan Dasasila Bandung, yaitu sepuluh prinsip yang menjadi dasar hubungan antarbangsa berdasarkan saling menghormati, hidup berdampingan secara damai, dan menolak penjajahan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Sejarah Terkait Lainnya -->
        <h2 class="related-section-title">Sejarah Terkait Lainnya</h2>
        <div class="related-grid">
            @php
                $related = \App\Models\Sejarah::where('id_sejarah', '!=', $topik->id_sejarah)->inRandomOrder()->limit(3)->get();
            @endphp
            
            @foreach($related as $rel)
            <div class="r-card">
                <img src="{{ $rel->gambar_utama ? asset('storage/'.$rel->gambar_utama) : asset('images/beranda.png') }}" alt="{{ $rel->judul_topik }}" class="r-img">
                <div class="r-body">
                    <div class="r-title">{{ $rel->judul_topik }}</div>
                    <div class="r-meta">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        1810 - 1942
                    </div>
                    <a href="{{ route('public.sejarah.show', $rel->slug) }}" class="btn-r-detail">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
            
            @if($related->isEmpty())
                <!-- Dummy fallbacks if db is empty -->
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Kota Bandung Masa Kolonial Belanda</div>
                        <div class="r-meta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            1810 - 1942
                        </div>
                        <a href="#" class="btn-r-detail">Lihat Detail</a>
                    </div>
                </div>
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1541819389146-27361cd39e4a?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Mahasiswa pribumi TH Bandung</div>
                        <div class="r-meta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            1923
                        </div>
                        <a href="#" class="btn-r-detail">Lihat Detail</a>
                    </div>
                </div>
                <div class="r-card">
                    <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=600&q=80" class="r-img">
                    <div class="r-body">
                        <div class="r-title">Perjuangan Kemerdekaan di Bandung</div>
                        <div class="r-meta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            KAA 1955 | 18 - 24 April 1955
                        </div>
                        <a href="#" class="btn-r-detail">Lihat Detail</a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
