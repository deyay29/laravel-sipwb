@extends('layouts.app')
@section('title', 'Sejarah')

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
    .nav-links a[href="{{ route('public.sejarah') }}"] {
        background: var(--primary);
        color: white;
    }
    
    .hero-sejarah {
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1599930113854-d6d7fd521f10?q=80&w=2000&auto=format&fit=crop') no-repeat center center/cover;
        height: 60vh;
        display: flex;
        align-items: flex-end;
        position: relative;
        color: white;
        margin-top: -70px;
        padding-top: 70px;
        padding-bottom: 3rem;
    }
    .hero-content {
        width: 100%;
    }
    .hero-text {
        max-width: 800px;
    }
    .hero-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        line-height: 1.3;
        margin: 0;
    }

    /* Section Styling */
    .section-title {
        color: var(--primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    /* Grid layout matching 5 columns */
    .sejarah-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }
    @media (min-width: 640px) {
        .sejarah-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 992px) {
        .sejarah-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    @media (min-width: 1200px) {
        .sejarah-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    .card-item-sejarah {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        background: var(--primary);
    }
    .card-item-sejarah img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .card-info-sejarah {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        color: white;
        flex: 1;
    }
    .card-info-sejarah h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .sejarah-meta {
        font-size: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        margin-bottom: 1rem;
        color: #e2e8f0;
    }
    .meta-row {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .meta-row svg {
        min-width: 12px;
    }
    .btn-outline-white {
        border: 1px solid white;
        color: white;
        background: transparent;
        padding: 0.4rem;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: 0.3s;
        margin-top: auto;
    }
    .btn-outline-white:hover {
        background: white;
        color: var(--primary);
    }
</style>

<section class="hero-sejarah">
    <div class="container hero-content">
        <div class="hero-text">
            <h1>Pencapaian Tanpa Batas, Persahabatan<br>abadi Se-Asia Afrika</h1>
        </div>
    </div>
</section>

<section class="container mt-5 mb-5">
    <h2 class="section-title">Rekomendasi Sejarah & Museum</h2>

    <div class="sejarah-grid">
        @foreach($sejarahs as $sejarah)
        <div class="card-item-sejarah">
            @if($sejarah->gambar_utama)
                <img src="{{ asset('storage/' . $sejarah->gambar_utama) }}" alt="{{ $sejarah->judul_topik }}">
            @else
                <img src="https://images.unsplash.com/photo-1599930113854-d6d7fd521f10?q=80&w=600&auto=format&fit=crop" alt="Sejarah">
            @endif
            <div class="card-info-sejarah">
                <h3>{{ $sejarah->judul_topik }}</h3>
                <div class="sejarah-meta">
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Sejarah Bandung</span>
                    </div>
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span>{{ $sejarah->created_at ? $sejarah->created_at->format('d M Y') : '1955' }}</span>
                    </div>
                </div>
                <a href="{{ route('public.sejarah.show', $sejarah->slug) }}" class="btn-outline-white">Lihat Detail</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $sejarahs->links() }}
    </div>
</section>
@endsection
