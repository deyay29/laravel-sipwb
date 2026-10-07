@extends('layouts.app')
@section('title', 'Kuliner')

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
    .nav-links a[href="{{ route('public.kuliner') }}"] {
        background: var(--primary);
        color: white;
    }
    
    .hero-kuliner {
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=2000&auto=format&fit=crop') no-repeat center center/cover;
        height: 60vh;
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
        height: 100%;
        position: relative;
    }
    .hero-text {
        max-width: 600px;
    }
    .hero-text h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
        line-height: 1.2;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: white;
        font-size: 1.1rem;
    }
    .breadcrumb a {
        color: white;
        text-decoration: none;
    }
    .weather-widget {
    background: rgba(255, 255, 255, 0.15); /* Mengubah latar belakang menjadi transparan */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px); /* Dukungan untuk Safari */
    padding: 1.5rem 2.5rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3); /* Mempertegas garis pinggir transparan agar lebih elegan */
    text-align: center;
    color: white;
}
.weather-widget h2 {
    font-size: 2.5rem;
    margin: 0;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}
.weather-widget p {
    font-size: 1.2rem;
    margin: 0;
    color: rgba(255, 255, 255, 0.9); /* Membuat teks keterangan cuaca sedikit lembut */
}
    
    .btn-suhu {
        position: absolute;
        bottom: 2rem;
        left: 0;
        background: #b91c1c; /* red matching MENGHANGATKAN */
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 2px solid white;
    }

    /* Grid layout matching 5 columns */
    .kuliner-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }
    @media (min-width: 640px) {
        .kuliner-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 992px) {
        .kuliner-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    @media (min-width: 1200px) {
        .kuliner-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    .card-item-kuliner {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        background: var(--primary);
    }
    .card-item-kuliner img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .card-info-kuliner {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        color: white;
        flex: 1;
    }
    .card-info-kuliner h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .kuliner-meta {
        font-size: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1rem;
        color: #e2e8f0;
    }
    .meta-row {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .meta-row svg {
        min-width: 14px;
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

<section class="hero-kuliner">
    <div class="container hero-content">
        <div class="hero-text">
            <h1>Kuliner Khas Bandung</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                <span>Kuliner</span>
            </div>
        </div>
        
        <div class="weather-widget">
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19a4.5 4.5 0 0 0 .5-8.97V10a6 6 0 0 0-11.83-1.42 4.00 4.00 0 0 0-1.03 7.75"/><path d="M8 15v2"/><path d="M8 21v2"/><path d="M12 15v2"/><path d="M12 21v2"/><path d="M16 15v2"/><path d="M16 21v2"/></svg>
                22°C
            </h2>
            <p>Berawan</p>
        </div>
        
        <a href="?suhu=Menghangatkan" class="btn-suhu">
            MENGHANGATKAN 🔥
        </a>
    </div>
</section>

<section class="container mt-5 mb-5">
    <div class="kuliner-grid">
        @foreach($kuliners as $kuliner)
        <div class="card-item-kuliner">
            @if($kuliner->foto)
                <img src="{{ asset('storage/' . $kuliner->foto) }}" alt="{{ $kuliner->nama_kuliner }}">
            @else
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop" alt="Kuliner">
            @endif
            <div class="card-info-kuliner">
                <h3>{{ $kuliner->nama_kuliner }}</h3>
                <div class="kuliner-meta">
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ Str::limit($kuliner->alamat_lengkap ?? 'Bandung', 25) }}</span>
                    </div>
                    <div class="meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Buka {{ $kuliner->jam_operasional ?? '10:00 - 21:00' }}</span>
                    </div>
                </div>
                <a href="{{ route('public.kuliner.show', $kuliner->slug) }}" class="btn-outline-white">Lihat Detail</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $kuliners->links() }}
    </div>
</section>
@endsection
