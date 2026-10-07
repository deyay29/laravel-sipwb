@extends('layouts.app')
@section('title', 'Wisata')

@section('content')
<style>
    /* Hero Section Override */
    .navbar {
        position: absolute;
        top: 0;
        width: 100%;
        background: transparent;
        border-bottom: none;
        box-shadow: none;
        z-index: 100;
    }
    .nav-links a {
        background: white;
        color: var(--primary);
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 600;
        text-decoration: none;
    }
    .nav-links a[href="{{ route('public.wisata') }}"] {
        background: var(--primary);
        color: white;
    }
    
    .hero-wisata {
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1596404753516-724d17387cc2?q=80&w=2000&auto=format&fit=crop') no-repeat center center/cover;
        height: 60vh;
        display: flex;
        align-items: center;
        position: relative;
        color: white;
        margin-top: -70px;
        padding-top: 70px;
    }
    .hero-content {
        width: 100%;
    }
    .hero-text {
        max-width: 600px;
    }
    .hero-text h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }
    .hero-text p {
        font-size: 1.2rem;
        line-height: 1.5;
        color: #e2e8f0;
        margin-bottom: 1rem;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: white;
    }
    .breadcrumb a {
        color: white;
        text-decoration: none;
    }

    /* Section Styling */
    .section-title {
        color: var(--primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .btn-see-all {
        background: var(--primary);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
    }

    /* Horizontal Card List */
    .card-list {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    .card-item-wisata {
        min-width: 250px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        position: relative;
        display: flex;
        flex-direction: column;
        background: var(--bg-white);
        flex: 1;
    }
    .card-item-wisata img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .card-info-wisata {
        background: var(--primary);
        color: white;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        flex: 1;
    }
    .card-info-wisata h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        text-align: center;
    }
    .wisata-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .meta-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .meta-row svg {
        min-width: 16px;
    }
    .btn-outline-white {
        border: 1px solid white;
        color: white;
        background: transparent;
        padding: 0.5rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        margin-top: auto;
    }
    .btn-outline-white:hover {
        background: white;
        color: var(--primary);
    }

    /* Additional Features Group */
    .features-group {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }
    @media (min-width: 768px) {
        .features-group {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .feature-box {
        background: #f0f4f8;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        text-decoration: none;
        color: var(--text-dark);
        transition: 0.3s;
    }
    .feature-box:hover {
        background: #e2e8f0;
    }
    .feature-header {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: var(--primary);
        font-size: 1.2rem;
        font-weight: 700;
    }
    .feature-header svg {
        width: 32px;
        height: 32px;
    }
    .feature-box p {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
</style>

<section class="hero-wisata">
    <div class="container hero-content">
        <div class="hero-text">
            <h3 style="color:white; font-weight:normal; margin-bottom:0.5rem; font-size:1.1rem;">Selamat Datang di</h3>
            <h1>Wisata Bandung</h1>
            <p>Temukan keindahan, sejarah,<br>dan budaya terbaik di Kota Bandung</p>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                <span>Wisata</span>
            </div>
        </div>
    </div>
</section>

<section class="container mt-5 mb-5">
    <div class="section-header">
        <h2 class="section-title">Rekomendasi Wisata</h2>
        <a href="#" class="btn-see-all">Lihat Semua</a>
    </div>

    <div class="card-list">
        @foreach($wisatas as $wisata)
        <div class="card-item-wisata">
            @if($wisata->gambar)
                <img src="{{ asset('storage/' . $wisata->gambar) }}" alt="{{ $wisata->nama_wisata }}">
            @else
                <img src="https://images.unsplash.com/photo-1555899434-94d1368aa7af?q=80&w=600&auto=format&fit=crop" alt="Wisata">
            @endif
            <div class="card-info-wisata">
                <h3>{{ $wisata->nama_wisata }}</h3>
                <div class="wisata-meta">
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ Str::limit($wisata->lokasi ?? 'Bandung', 25) }}</span>
                    </div>
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Buka 08:00 - 16:00</span>
                    </div>
                </div>
                <a href="{{ route('public.wisata.show', $wisata->slug) }}" class="btn-outline-white">Lihat Detail</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="features-group">
        <a href="#" class="feature-box">
            <div class="feature-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Quiz Wisata Bandung
            </div>
            <p>Jawab beberapa pertanyaan singkat untuk mencari tahu destinasi wisata yang cocok untuk Anda</p>
        </a>
        <a href="#" class="feature-box">
            <div class="feature-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><rect width="4" height="4" x="10" y="14" rx="1"/></svg>
                Smart Budget
            </div>
            <p>Rencanakan perjalanan wisata terukur Anda dengan kalkulator estimasi biaya wisata secara akurat</p>
        </a>
        <a href="#" class="feature-box">
            <div class="feature-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Event Terkini
            </div>
            <p>Temukan event menarik yang sedang berlangsung atau akan datang di Kota Bandung</p>
        </a>
    </div>
</section>
@endsection
