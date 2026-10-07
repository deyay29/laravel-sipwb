@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('topbar-title', 'Dashboard Admin')

@section('content')
<style>
    /* ===== DASHBOARD PAGE STYLES ===== */

    /* ---- Header ---- */
    .dash-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 26px;
        flex-wrap: wrap;
        gap: 14px;
        animation: fadeSlideDown 0.5s ease both;
    }
    .dash-welcome h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
        letter-spacing: -0.3px;
    }
    .dash-welcome p {
        margin-top: 6px;
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
    }
    .btn-manage-kuliner {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #1a2a6c 0%, #2d4cc8 100%);
        color: #fff;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 18px rgba(26,42,108,0.30);
        white-space: nowrap;
        letter-spacing: 0.1px;
    }
    .btn-manage-kuliner:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(26,42,108,0.40);
        color: #fff;
    }
    .btn-manage-kuliner i { transition: transform 0.2s; }
    .btn-manage-kuliner:hover i { transform: translateX(4px); }

    /* ---- Weather Card ---- */
    .weather-card {
        background: linear-gradient(130deg, #1a2a6c 0%, #2541a0 55%, #1e63c9 100%);
        border-radius: 20px;
        padding: 26px 30px 20px;
        color: #fff;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(26,42,108,0.28);
        animation: fadeSlideDown 0.5s 0.08s ease both;
    }
    .weather-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
        pointer-events: none;
    }
    .weather-card::after {
        content: '';
        position: absolute;
        bottom: -70px; right: 100px;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }
    .weather-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
    .weather-main {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .weather-icon-wrap {
        width: 64px; height: 64px;
        background: rgba(255,255,255,0.14);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.18);
        flex-shrink: 0;
    }
    .weather-temp {
        font-size: 46px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1px;
    }
    .weather-location {
        font-size: 14px;
        color: rgba(255,255,255,0.70);
        margin-top: 5px;
        font-weight: 400;
    }
    .weather-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 9px;
    }
    .btn-refresh {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(255,255,255,0.16);
        border: 1px solid rgba(255,255,255,0.28);
        border-radius: 11px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        backdrop-filter: blur(4px);
        letter-spacing: 0.2px;
    }
    .btn-refresh:hover {
        background: rgba(255,255,255,0.26);
        color: #fff;
        transform: scale(1.04);
    }
    .btn-refresh i {
        font-size: 12px;
        animation: spin 3s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    .weather-source {
        font-size: 11.5px;
        color: rgba(255,255,255,0.45);
    }
    .weather-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.12);
        flex-wrap: wrap;
        gap: 10px;
        position: relative;
        z-index: 1;
    }
    .weather-meta {
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }
    .weather-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: rgba(255,255,255,0.78);
        font-weight: 500;
    }
    .weather-meta-item i { font-size: 13px; color: rgba(255,255,255,0.55); }

    /* ---- Stats Grid ---- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }
    .stat-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px 22px 18px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.045);
        transition: transform 0.22s cubic-bezier(0.4,0,0.2,1), box-shadow 0.22s;
        cursor: default;
        border: 1px solid rgba(0,0,0,0.04);
        animation: fadeSlideUp 0.45s ease both;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.10);
    }
    .stat-icon-wrap {
        width: 46px; height: 46px;
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
        transition: transform 0.2s;
    }
    .stat-card:hover .stat-icon-wrap { transform: scale(1.1); }
    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 7px;
        letter-spacing: -1px;
        font-variant-numeric: tabular-nums;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 1.1px;
        text-transform: uppercase;
    }

    /* Color variants */
    .icon-blue   { background: #e8f0fe; color: #4285f4; }
    .icon-green  { background: #e6f9f0; color: #34a853; }
    .icon-orange { background: #fff3e0; color: #f5a623; }
    .icon-purple { background: #f3e8ff; color: #9b59b6; }
    .icon-teal   { background: #e0f5f5; color: #0097a7; }
    .icon-red    { background: #ffe8e8; color: #e53935; }
    .icon-pink   { background: #fce4ec; color: #e91e63; }
    .icon-gray   { background: #f0f2f5; color: #546e7a; }

    /* ---- Info Cards (bottom) ---- */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        animation: fadeSlideUp 0.5s 0.38s ease both;
    }
    .info-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px 26px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.045);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .info-card h3 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 13px;
        display: flex;
        align-items: center;
        gap: 9px;
    }
    .info-card p, .info-card li {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.75;
    }
    .info-card ul { list-style: none; }
    .info-card ul li {
        padding: 7px 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .info-card ul li:last-child { border-bottom: none; }
    .info-card ul li::before {
        content: '•';
        color: #cbd5e1;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .highlight-temp { font-weight: 700; color: #1e293b; }
    .highlight-link {
        color: #1a2a6c;
        font-weight: 700;
        text-decoration: none;
    }
    .highlight-link:hover { text-decoration: underline; }
    .text-muted-sm {
        font-size: 12.5px;
        color: #94a3b8;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Animations */
    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 700px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .info-grid  { grid-template-columns: 1fr; }
        .dash-header { flex-direction: column; }
        .weather-temp { font-size: 36px; }
    }
</style>

{{-- ======== HEADER ======== --}}
<div class="dash-header">
    <div class="dash-welcome">
        <h1>Selamat Datang, {{ auth()->user()->name ?? 'Admin' }} 👋</h1>
        <p>Pantau konten portal Bandung Info Center dari satu tempat.</p>
    </div>
    <a href="{{ route('admin.kuliner.index') }}" class="btn-manage-kuliner" id="btn-kelola-kuliner">
        Kelola Kuliner &nbsp;<i class="fas fa-arrow-right"></i>
    </a>
</div>

{{-- ======== WEATHER CARD ======== --}}
<div class="weather-card">
    @php
        $temp = $weather['temp'] ?? 34;
        $cond = $weather['condition'] ?? 'Berawan';
        $time = $weather['time'] ?? now()->format('H:i');
        $weatherIcon = match(true) {
            str_contains($cond, 'Cerah Berawan') => '⛅',
            str_contains($cond, 'Cerah')         => '☀️',
            str_contains($cond, 'Hujan')          => '🌧️',
            str_contains($cond, 'Badai')          => '⛈️',
            str_contains($cond, 'Kabut')          => '🌫️',
            str_contains($cond, 'Bersalju')       => '❄️',
            default                               => '☁️',
        };
        $tempCategory = $temp >= 27 ? 'Menyegarkan' : 'Menghangatkan';
    @endphp

    <div class="weather-top">
        <div class="weather-main">
            <div class="weather-icon-wrap">{{ $weatherIcon }}</div>
            <div>
                <div class="weather-temp">{{ $temp }}°C</div>
                <div class="weather-location">{{ $cond }} · Kota Bandung</div>
            </div>
        </div>
        <div class="weather-right">
            <a href="{{ route('admin.dashboard') }}" class="btn-refresh" id="btn-refresh-weather">
                <i class="fas fa-snowflake"></i> {{ $tempCategory }}
            </a>
            <div class="weather-source">Sumber: Open-Meteo · Pembaruan {{ \Carbon\Carbon::parse($time)->format('H:i') }}</div>
        </div>
    </div>

    <div class="weather-footer">
        <div class="weather-meta">
            <div class="weather-meta-item">
                <i class="fas fa-thermometer-half"></i>
                <span>Suhu saat ini</span>
            </div>
            <div class="weather-meta-item">
                <i class="fas fa-cloud"></i>
                <span>{{ $cond }}</span>
            </div>
            <div class="weather-meta-item">
                <i class="fas fa-location-dot"></i>
                <span>-6,91° LS, 107,61° BT</span>
            </div>
        </div>
    </div>
</div>

{{-- ======== STATS ROW 1 ======== --}}
<div class="stats-grid">
    <div class="stat-card" id="stat-kuliner-total" style="animation-delay:0.16s">
        <div class="stat-icon-wrap icon-blue">
            <i class="fas fa-screwdriver-wrench"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['kuliner_total'] }}">{{ $counts['kuliner_total'] }}</div>
        <div class="stat-label">Total Kuliner</div>
    </div>
    <div class="stat-card" id="stat-kuliner-aktif" style="animation-delay:0.21s">
        <div class="stat-icon-wrap icon-green">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['kuliner_aktif'] }}">{{ $counts['kuliner_aktif'] }}</div>
        <div class="stat-label">Kuliner Aktif</div>
    </div>
    <div class="stat-card" id="stat-kuliner-nonaktif" style="animation-delay:0.26s">
        <div class="stat-icon-wrap icon-orange">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['kuliner_nonaktif'] }}">{{ $counts['kuliner_nonaktif'] }}</div>
        <div class="stat-label">Kuliner Nonaktif</div>
    </div>
    <div class="stat-card" id="stat-wisata" style="animation-delay:0.31s">
        <div class="stat-icon-wrap icon-purple">
            <i class="fas fa-building-columns"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['wisata'] }}">{{ $counts['wisata'] }}</div>
        <div class="stat-label">Destinasi Wisata</div>
    </div>
</div>

{{-- ======== STATS ROW 2 ======== --}}
<div class="stats-grid" style="margin-bottom: 22px;">
    <div class="stat-card" id="stat-berita" style="animation-delay:0.36s">
        <div class="stat-icon-wrap icon-teal">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['berita'] }}">{{ $counts['berita'] }}</div>
        <div class="stat-label">Berita</div>
    </div>
    <div class="stat-card" id="stat-sejarah" style="animation-delay:0.41s">
        <div class="stat-icon-wrap icon-orange">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-value" data-count="{{ $counts['sejarah'] }}">{{ $counts['sejarah'] }}</div>
        <div class="stat-label">Sejarah &amp; Museum</div>
    </div>
    <div class="stat-card" id="stat-ulasan" style="animation-delay:0.46s">
        <div class="stat-icon-wrap icon-pink">
            <i class="fas fa-book-open-reader"></i>
        </div>
        <div class="stat-value" data-count="0">0</div>
        <div class="stat-label">Ulasan</div>
    </div>
    <div class="stat-card" id="stat-pengaduan" style="animation-delay:0.51s">
        <div class="stat-icon-wrap icon-gray">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="stat-value" data-count="0">0</div>
        <div class="stat-label">Pengaduan Baru</div>
    </div>
</div>

{{-- ======== INFO CARDS ======== --}}
<div class="info-grid">
    <div class="info-card" id="card-cuaca-kategori">
        <h3><span style="font-size:19px;">🔥</span> Kategori Cuaca Hari Ini</h3>
        <p>
            Suhu <span class="highlight-temp">{{ $temp }}°C ({{ $cond }})</span> — sistem menampilkan rekomendasi kategori
            <a href="{{ route('admin.kuliner.index') }}" class="highlight-link">{{ $tempCategory }}</a> pada halaman publik.
        </p>
        <p class="text-muted-sm">Ambang batas: di bawah 27°C → Menghangatkan &nbsp;·&nbsp; 27°C ke atas → Menyegarkan.</p>
    </div>

    <div class="info-card" id="card-aturan-bisnis">
        <h3><span style="font-size:19px;">❄️</span> Aturan Bisnis Aktif</h3>
        <ul>
            <li>Kuliner <strong>Nonaktif</strong> otomatis disembunyikan dari halaman publik.</li>
            <li>Rekomendasi beranda mengikuti kategori cuaca terkini.</li>
            <li>Semua perubahan langsung tersimpan di database SQLite.</li>
        </ul>
    </div>
</div>

<script>
    // Counter animation for stat values
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.stat-value[data-count]');
        counters.forEach(el => {
            const target = parseInt(el.getAttribute('data-count'), 10);
            if (isNaN(target) || target === 0) return;
            let start = 0;
            const duration = 900;
            const step = Math.ceil(target / (duration / 16));
            const timer = setInterval(() => {
                start += step;
                if (start >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else {
                    el.textContent = start;
                }
            }, 16);
        });
    });
</script>

@endsection
