@extends('layouts.app')
@section('title', 'Beranda')

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
    .nav-links a[href="{{ route('home') }}"] {
        background: var(--primary);
        color: white;
    }
    
   .hero-home {
    background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset("images/beranda.png") }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 80vh;
    display: flex;
    align-items: center;
    position: relative;
    color: white;
    margin-top: -70px;
    padding-top: 70px;
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 2rem;
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
    }
    .weather-widget {
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    color: white;
}

.weather-widget .weather-info h2 {
    font-size: 1.8rem;
    color: white;
    margin: 0;
    line-height: 1.1;
}

.weather-widget .weather-info p {
    font-size: 1rem;
    margin: 0;
    color: white;
    opacity: 0.9;
}
    /* Floating Menu */
    .floating-menu-container {
        display: flex;
        justify-content: center;
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }
    .floating-menu {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: flex;
        padding: 1rem;
        gap: 1rem;
    }
    .fm-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        text-decoration: none;
        color: var(--primary);
        font-weight: 600;
        transition: 0.3s;
    }
    .fm-item:hover {
        background: var(--bg-light);
        border-color: var(--primary);
    }
    .fm-item svg {
        width: 40px;
        height: 40px;
        margin-bottom: 10px;
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
    .card-item {
        min-width: 250px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        position: relative;
        flex: 1;
    }
    .card-item img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
    }
    .card-info {
        background: var(--primary);
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-info h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: white;
    }
    .card-info span {
        font-size: 0.8rem;
        color: #bfdbfe;
    }
    
</style>

<section class="hero-home">
    <div class="container hero-content">
        <div class="hero-text">
            <h3 style="color:white; font-weight:normal; margin-bottom:0.5rem;">Selamat Datang di</h3>
            <h1>Bandung Info Center</h1>
            <p>Portal Layanan Informasi Publik Kota di Bandung untuk penyediaan informasi Wisata, Sejarah, kuliner & Berita terkini untuk wisatawan & masyarakat</p>
        </div>
        <div class="weather-widget">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><path d="M17.5 19a4.5 4.5 0 0 0 .5-8.97V10a6 6 0 0 0-11.83-1.42 4.00 4.00 0 0 0-1.03 7.75"></path><path d="M8 15v2"></path><path d="M8 21v2"></path><path d="M12 15v2"></path><path d="M12 21v2"></path><path d="M16 15v2"></path><path d="M16 21v2"></path></svg>
    <div class="weather-info">
        <h2>22°C</h2>
        <p>Berawan</p>
    </div>
</div>
</section>

<div class="container floating-menu-container">
    <div class="floating-menu">
        <a href="{{ route('public.wisata') }}" class="fm-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Wisata
        </a>
        <a href="{{ route('public.sejarah') }}" class="fm-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
            Sejarah
        </a>
        <a href="{{ route('public.berita') }}" class="fm-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6Z"></path></svg>
            Berita
        </a>
        <a href="{{ route('public.kuliner') }}" class="fm-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
            Kuliner
        </a>
        <a href="{{ route('public.galeri') }}" class="fm-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path></svg>
            Galeri
        </a>
    </div>
</div>

<section class="container mt-5 mb-5">
    <!-- KULINER -->
    <div class="section-header">
        <h2 class="section-title">Rekomendasi Kuliner</h2>
        <a href="{{ route('public.kuliner') }}" class="btn-see-all">Lihat Semua</a>
    </div>
    <div class="card-list">
        @foreach($kuliners as $kuliner)
        <a href="{{ route('public.kuliner.show', $kuliner->slug) }}" class="card-item" style="text-decoration:none;">
            @if($kuliner->foto)
                <img src="{{ asset('storage/' . $kuliner->foto) }}" alt="{{ $kuliner->nama_kuliner }}">
            @else
                <!-- GAMBAR GEDUNG SATE DIPASANG DI SINI SEBAGAI PENGGANTI -->
                <img src="{{ asset('images/beranda.png') }}" alt="Gedung Sate Bandung">
            @endif
            <div class="card-info">
                <div>
                    <h3>{{ $kuliner->nama_kuliner }}</h3>
                    <span>Detail</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </a>
        @endforeach
    </div>
    <!-- WISATA -->
    <div class="section-header mt-5">
        <h2 class="section-title">Destinasi Wisata Populer</h2>
        <a href="{{ route('public.wisata') }}" class="btn-see-all">Lihat Semua</a>
    </div>
    <div class="card-list">
        @foreach($wisatas as $wisata)
        <a href="{{ route('public.wisata.show', $wisata->slug) }}" class="card-item" style="text-decoration:none;">
            @if($wisata->gambar)
                <img src="{{ asset('storage/' . $wisata->gambar) }}" alt="{{ $wisata->nama_wisata }}">
            @else
                <img src="https://images.unsplash.com/photo-1555899434-94d1368aa7af?q=80&w=600&auto=format&fit=crop" alt="Wisata">
            @endif
            <div class="card-info">
                <div>
                    <h3>{{ $wisata->nama_wisata }}</h3>
                    <span>Detail</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </a>
        @endforeach
    </div>

    <!-- BERITA -->
    <div class="section-header mt-5">
        <h2 class="section-title">Berita Terkini</h2>
        <a href="{{ route('public.berita') }}" class="btn-see-all">Lihat Semua</a>
    </div>
    <div class="card-list">
        @foreach($beritas as $berita)
        <a href="{{ route('public.berita.show', $berita->slug) }}" class="card-item" style="text-decoration:none;">
            @if($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
            @else
                <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?q=80&w=600&auto=format&fit=crop" alt="Berita">
            @endif
            <div class="card-info">
                <div>
                    <h3>{{ $berita->judul }}</h3>
                    <span>Detail</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </a>
        @endforeach
    </div>

    <!-- SEJARAH -->
    <div class="section-header mt-5">
        <h2 class="section-title">Jelajahi Sejarah</h2>
        <a href="{{ route('public.sejarah') }}" class="btn-see-all">Lihat Semua</a>
    </div>
    <div class="card-list">
        @foreach($sejarahs as $sejarah)
        <a href="{{ route('public.sejarah.show', $sejarah->slug) }}" class="card-item" style="text-decoration:none;">
            @if($sejarah->gambar_utama)
                <img src="{{ asset('storage/' . $sejarah->gambar_utama) }}" alt="{{ $sejarah->judul_topik }}">
            @else
                <img src="https://images.unsplash.com/photo-1599930113854-d6d7fd521f10?q=80&w=600&auto=format&fit=crop" alt="Sejarah">
            @endif
            <div class="card-info">
                <div>
                    <h3>{{ $sejarah->judul_topik }}</h3>
                    <span>Detail</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endsection