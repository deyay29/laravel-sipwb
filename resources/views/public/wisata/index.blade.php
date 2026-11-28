@extends('layouts.app')

@section('title', 'Wisata & Petualangan')
@section('description', 'Jelajah destinasi wisata Kota Bandung, kuis interaktif berhadiah sertifikat, dan kalkulator Smart Budget.')

@section('content')
<x-public.page-hero
    title="Wisata & Petualangan"
    subtitle="Jelajah destinasi ikonik, uji wawasan kuis Bandung berhadiah sertifikat, dan hitung simulasi Smart Budget liburan Anda."
    :breadcrumb="['Beranda' => route('home'), 'Wisata' => null]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6 lg:py-12">
    
    {{-- ============================================================== --}}
    {{-- 1. KARTU BAR POIN PETUALANG (Disesuaikan Lebar Frame Katalog)    --}}
    {{-- ============================================================== --}}
    <div class="w-full mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-700 via-brand-600 to-navy-800 p-6 sm:p-8 lg:px-10 text-white shadow-xl shadow-brand-700/20">
            {{-- Latar Dekoratif --}}
            <div class="pointer-events-none absolute -right-6 -bottom-6 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -left-6 -top-6 h-36 w-36 rounded-full bg-accent-400/20 blur-2xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <p class="text-xs sm:text-sm font-semibold tracking-wide text-white/80">
                            Poin Petualang Anda
                        </p>
                    </div>
                    <div class="mt-1 flex items-baseline gap-2">
                        <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white">
                            <span id="adventure-points-header" class="adventure-points-num">30</span>
                            <span class="text-xl sm:text-2xl font-bold text-white/90">poin</span>
                        </h2>
                    </div>
                    <p id="adventure-level-badge" class="mt-1.5 text-xs font-semibold text-accent-300">
                        Level: Penjelajah Kota Bandung
                    </p>
                </div>

                {{-- Fitur Cepat Bar Poin --}}
                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                    <div class="hidden lg:flex items-center gap-3 rounded-2xl bg-white/10 px-5 py-3 border border-white/15 backdrop-blur text-xs">
                        <i class="fas fa-certificate text-amber-300 text-lg"></i>
                        <div>
                            <p class="font-display font-bold text-white">Target Kuis: 100 Poin</p>
                            <p class="text-[11px] text-white/70">10 Soal Benar = Sertifikat Resmi</p>
                        </div>
                    </div>

                    <span id="cert-unlocked-tag" class="hidden text-xs font-bold text-amber-300 uppercase tracking-wider bg-amber-400/20 px-3 py-1.5 rounded-full border border-amber-300/30">
                        ★ Bersertifikat
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 2. NAVIGASI KATEGORI PILLS (Destinasi, Quiz, Smart Budget)       --}}
    {{-- (Misi & Peringkat telah dihapus sesuai instruksi)              --}}
    {{-- ============================================================== --}}
    <div class="w-full mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-slate-200/80 pb-5">
        <div class="flex items-center gap-2.5 overflow-x-auto pb-1 scrollbar-none">
            
            {{-- Tab 1: Destinasi --}}
            <button type="button"
                    onclick="switchWisataTab('destinasi')"
                    id="tab-btn-destinasi"
                    class="wisata-tab-pill inline-flex shrink-0 items-center gap-2 rounded-full px-6 py-3 font-display text-xs font-bold transition active:scale-95 bg-brand-600 text-white shadow-md shadow-brand-600/25">
                <i class="fas fa-location-dot text-xs"></i>
                <span>Destinasi</span>
            </button>

            {{-- Tab 2: Quiz --}}
            <button type="button"
                    onclick="switchWisataTab('quiz')"
                    id="tab-btn-quiz"
                    class="wisata-tab-pill inline-flex shrink-0 items-center gap-2 rounded-full px-6 py-3 font-display text-xs font-bold transition active:scale-95 border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50">
                <i class="fas fa-circle-question text-xs text-brand-600"></i>
                <span>Quiz</span>
                <span class="rounded-full bg-accent-500/15 px-2 py-0.5 text-[10px] font-extrabold text-accent-700">100 Poin</span>
            </button>

            {{-- Tab 3: Smart Budget --}}
            <button type="button"
                    onclick="switchWisataTab('smart-budget')"
                    id="tab-btn-smart-budget"
                    class="wisata-tab-pill inline-flex shrink-0 items-center gap-2 rounded-full px-6 py-3 font-display text-xs font-bold transition active:scale-95 border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50">
                <i class="fas fa-calculator text-xs text-brand-600"></i>
                <span>Smart Budget</span>
            </button>

        </div>

        <p class="text-xs text-slate-500 hidden sm:block">
            <i class="fas fa-info-circle text-brand-600 mr-1"></i> Pilih menu untuk menjelajah destinasi, kuis, atau estimasi liburan.
        </p>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. ISI PANEL MASING-MASING KATEGORI (Frame Selaras Katalog)      --}}
    {{-- ============================================================== --}}
    
    {{-- PANEL 1: DESTINASI WISATA (Katalog Objek Wisata Bandung) --}}
    <div id="panel-destinasi" class="wisata-tab-panel">
        <span id="destinasi" class="block scroll-mt-24" aria-hidden="true"></span>
        @php
            // Semua tautan filter tetap membawa kata kunci pencarian yang aktif.
            $katalogUrl = fn (?string $tier = null) => route('public.wisata', array_filter([
                'q' => request('q'),
                'budget' => $tier,
            ])).'#destinasi';
        @endphp

        {{-- Pencarian & Filter Cepat Destinasi --}}
        <div class="mb-8 space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900">
                        Katalog Destinasi Wisata
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Temukan spot terbaik di Bandung untuk dikunjungi bersama keluarga dan teman.
                    </p>
                </div>

                <form action="{{ route('public.wisata') }}" method="GET" class="relative w-full sm:w-80">
                    @if ($kategoriBudgetTerpilih)
                        <input type="hidden" name="budget" value="{{ $kategoriBudgetTerpilih }}">
                    @endif
                    <i class="fas fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Cari wisata, museum, alam..."
                           class="w-full rounded-full border border-slate-300 bg-white py-2.5 pl-9 pr-4 text-xs text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 shadow-sm">
                </form>
            </div>

            @if ($kategoriBudgetTerpilih)
                <p class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
                    <i class="fas fa-circle-info text-brand-500"></i>
                    <span>
                        Menampilkan destinasi untuk kategori anggaran
                        <strong class="text-slate-700">{{ $kategoriBudgetOptions[$kategoriBudgetTerpilih] }}</strong>.
                    </span>
                    <a href="{{ $katalogUrl() }}" class="font-semibold text-brand-700 hover:underline">Tampilkan semua</a>
                </p>
            @endif
        </div>

        {{-- Grid Kartu Wisata --}}
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($wisatas as $wisata)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
                    <a href="{{ route('public.wisata.show', $wisata) }}" class="relative block overflow-hidden">
                        <x-public.media
                            :src="$wisata->fotoUtama()"
                            alt="{{ $wisata->nama_wisata }}"
                            icon="fa-mountain-sun"
                            tone="emerald"
                            ratio="h-48"
                            class="w-full object-cover transition duration-500 group-hover:scale-105"
                        />

                        @if ($wisata->kategori)
                            <span class="absolute left-4 top-4 rounded-full bg-slate-900/70 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white backdrop-blur">
                                {{ $wisata->kategori }}
                            </span>
                        @endif

                        @if ($wisata->quiz_tersedia)
                            <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-accent-500 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                <i class="fas fa-circle-question text-[10px]"></i>
                                Ada Kuis
                            </span>
                        @endif
                    </a>

                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="font-display text-lg font-extrabold leading-snug text-slate-900">
                            <a href="{{ route('public.wisata.show', $wisata) }}" class="transition group-hover:text-brand-700">
                                {{ Str::limit($wisata->nama_wisata, 55) }}
                            </a>
                        </h2>

                        <p class="mt-2 inline-flex items-center gap-1.5 text-xs text-slate-500">
                            <i class="fas fa-location-dot text-[10px] text-brand-500"></i>
                            {{ $wisata->lokasi ?? 'Kota Bandung' }}
                        </p>

                        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">
                            {{ Str::limit($wisata->deskripsi, 120) }}
                        </p>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-slate-400">Tiket masuk</p>
                                <p class="font-display text-base font-extrabold text-navy-800">
                                    {{ $wisata->harga_tiket ? 'Rp'.number_format($wisata->harga_tiket, 0, ',', '.') : 'Gratis' }}
                                </p>
                                <span @class([
                                    'mt-1 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide',
                                    'bg-emerald-50 text-emerald-700' => $wisata->kategori_budget === \App\Models\Wisata::BUDGET_HEMAT,
                                    'bg-sky-50 text-sky-700' => $wisata->kategori_budget === \App\Models\Wisata::BUDGET_SEDANG,
                                    'bg-violet-50 text-violet-700' => $wisata->kategori_budget === \App\Models\Wisata::BUDGET_NYAMAN,
                                ])>
                                    <i class="fas fa-wallet text-[8px]"></i>
                                    {{ $wisata->labelKategoriBudget() }}
                                </span>
                            </div>

                            <a href="{{ route('public.wisata.show', $wisata) }}"
                               class="inline-flex items-center gap-1.5 rounded-full bg-brand-600 px-5 py-2 font-display text-xs font-bold text-white transition hover:bg-brand-700">
                                Detail
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <x-public.empty-state
                    class="md:col-span-2 lg:col-span-3"
                    :title="$kategoriBudgetTerpilih
                        ? 'Belum ada destinasi kategori '.$kategoriBudgetOptions[$kategoriBudgetTerpilih]
                        : 'Belum ada data wisata'"
                    :description="$kategoriBudgetTerpilih
                        ? 'Coba pilih kategori anggaran lain, atau tampilkan seluruh destinasi.'
                        : 'Informasi wisata akan tampil di sini setelah tersedia.'"
                    icon="fa-mountain-sun"
                >
                    @if ($kategoriBudgetTerpilih)
                        <a href="{{ $katalogUrl() }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-brand-600 px-4 py-2 font-display text-xs font-bold text-white transition hover:bg-brand-700">
                            <i class="fas fa-list text-[10px]"></i>
                            Tampilkan semua destinasi
                        </a>
                    @endif
                </x-public.empty-state>
            @endforelse
        </div>

        <div class="mt-8">
            <x-public.pagination :paginator="$wisatas" />
        </div>
    </div>

    {{-- PANEL 2: QUIZ WISATA (Frame Selaras Penuh dengan Katalog) --}}
    <div id="panel-quiz" class="wisata-tab-panel hidden">
        @include('public.wisata.partials.quiz-section')
    </div>

    {{-- PANEL 3: SMART BUDGET (Frame Selaras Penuh dengan Katalog) --}}
    <div id="panel-smart-budget" class="wisata-tab-panel hidden">
        @include('public.wisata.partials.smart-budget-section')
    </div>

</section>

{{-- MODAL SERTIFIKAT (Untuk Skor 100 Poin) --}}
@include('public.wisata.partials.certificate-modal')

@push('scripts')
<script>
/**
 * Pengelolaan Tab Kategori Wisata (Destinasi, Quiz, Smart Budget)
 */
const TABS = ['destinasi', 'quiz', 'smart-budget'];

function switchWisataTab(activeTabKey) {
    if (!TABS.includes(activeTabKey)) activeTabKey = 'destinasi';

    TABS.forEach(tab => {
        const btn = document.getElementById(`tab-btn-${tab}`);
        const panel = document.getElementById(`panel-${tab}`);

        if (panel) {
            panel.classList.toggle('hidden', tab !== activeTabKey);
        }

        if (btn) {
            if (tab === activeTabKey) {
                btn.className = 'wisata-tab-pill inline-flex shrink-0 items-center gap-2 rounded-full px-6 py-3 font-display text-xs font-bold transition active:scale-95 bg-brand-600 text-white shadow-md shadow-brand-600/25';
                const icon = btn.querySelector('i');
                if (icon) icon.className = icon.className.replace(/text-\w+-\d+/g, 'text-white');
            } else {
                btn.className = 'wisata-tab-pill inline-flex shrink-0 items-center gap-2 rounded-full px-6 py-3 font-display text-xs font-bold transition active:scale-95 border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50';
                const icon = btn.querySelector('i');
                if (icon && tab === 'destinasi') icon.className = 'fas fa-location-dot text-xs text-brand-600';
                if (icon && tab === 'quiz') icon.className = 'fas fa-circle-question text-xs text-brand-600';
                if (icon && tab === 'smart-budget') icon.className = 'fas fa-calculator text-xs text-brand-600';
            }
        }
    });

    if (history.pushState) {
        history.pushState(null, null, `#${activeTabKey}`);
    }
}

/**
 * Poin Petualang: Penyimpanan & Sinkronisasi Real-time
 */
function updateAdventurePointsUI(points) {
    const displays = document.querySelectorAll('.adventure-points-num');
    displays.forEach(el => {
        el.textContent = points;
    });

    const levelBadge = document.getElementById('adventure-level-badge');
    if (levelBadge) {
        if (points >= 150) {
            levelBadge.textContent = 'Level 4: Legenda Wisata Paris van Java';
        } else if (points >= 90) {
            levelBadge.textContent = 'Level 3: Pakar Wisata Bandung';
        } else if (points >= 40) {
            levelBadge.textContent = 'Level 2: Penjelajah Kota Bandung';
        } else {
            levelBadge.textContent = 'Level 1: Penjelajah Pemula';
        }
    }

    if (localStorage.getItem('bandung_wisata_quiz_passed') === 'true') {
        const certTag = document.getElementById('cert-unlocked-tag');
        if (certTag) certTag.classList.remove('hidden');
    }
}

function initAdventurePoints() {
    let savedPoints = localStorage.getItem('bandung_petualang_points');
    if (!savedPoints) {
        savedPoints = '30';
        localStorage.setItem('bandung_petualang_points', savedPoints);
    }
    updateAdventurePointsUI(parseInt(savedPoints, 10));
}

/**
 * Logika Modal Sertifikat
 */
function openCertificateModal() {
    const modal = document.getElementById('certificate-modal');
    if (!modal) return;
    
    const savedName = localStorage.getItem('bandung_petualang_name');
    if (savedName) {
        document.getElementById('cert-input-name').value = savedName;
        document.getElementById('cert-display-name').textContent = savedName;
    }

    let serial = localStorage.getItem('bandung_cert_serial');
    if (!serial) {
        serial = 'BDG-WISATA-100-' + Math.floor(100000 + Math.random() * 900000);
        localStorage.setItem('bandung_cert_serial', serial);
    }
    document.getElementById('cert-serial-number').textContent = serial;

    modal.classList.remove('hidden');
}

function closeCertificateModal() {
    const modal = document.getElementById('certificate-modal');
    if (modal) modal.classList.add('hidden');
}

function updateCertificateRecipient(newName) {
    const displayName = document.getElementById('cert-display-name');
    const cleanName = newName.trim() || 'Petualang Sejati Bandung';
    if (displayName) displayName.textContent = cleanName;
    localStorage.setItem('bandung_petualang_name', cleanName);
}

function printCertificate() {
    window.print();
}

/**
 * Muat logo Bandung Info Center versi biru (images/logo-biru.png, mengikuti
 * palet brand website) lalu gambar di kop sertifikat (kiri atas).
 * Bila gambarnya gagal dimuat, proses unduhan tetap berlanjut tanpa logo.
 */
function drawCertificateLogo(ctx) {
    return new Promise((resolve) => {
        const logo = new Image();

        logo.onload = () => {
            const tinggi = 88;
            const lebar = Math.round(tinggi * (logo.naturalWidth / logo.naturalHeight)) || 140;

            ctx.drawImage(logo, 160, 92, lebar, tinggi);
            resolve();
        };

        logo.onerror = resolve;
        logo.src = '{{ asset('images/logo-biru.png') }}';
    });
}

/**
 * Unduh Sertifikat sebagai Gambar PNG beresolusi tinggi menggunakan Canvas API
 */
async function downloadCertificatePNG() {
    const canvas = document.getElementById('cert-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const width = 1600;
    const height = 1100;

    const grad = ctx.createLinearGradient(0, 0, width, height);
    grad.addColorStop(0, '#fefbf3');
    grad.addColorStop(0.5, '#ffffff');
    grad.addColorStop(1, '#f8fafc');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, width, height);

    ctx.strokeStyle = '#d97706';
    ctx.lineWidth = 14;
    ctx.strokeRect(40, 40, width - 80, height - 80);

    ctx.strokeStyle = '#1e3a8a';
    ctx.lineWidth = 4;
    ctx.strokeRect(60, 60, width - 120, height - 120);

    const drawCorner = (x, y, dx, dy) => {
        ctx.fillStyle = '#d97706';
        ctx.fillRect(x, y, dx * 40, 6);
        ctx.fillRect(x, y, 6, dy * 40);
    };
    drawCorner(70, 70, 1, 1);
    drawCorner(width - 70, 70, -1, 1);
    drawCorner(70, height - 70, 1, -1);
    drawCorner(width - 70, height - 70, -1, -1);

    // Kop: logo Bandung Info Center di kiri, identitas di sebelahnya,
    // nomor seri di kanan — selaras dengan tampilan modal sertifikat.
    const serialNum = document.getElementById('cert-serial-number')?.textContent || 'BDG-WISATA-100-2026';

    await drawCertificateLogo(ctx);

    ctx.textAlign = 'left';
    ctx.fillStyle = '#1e3a8a';
    ctx.font = 'bold 26px sans-serif';
    ctx.fillText('PEMERINTAH KOTA BANDUNG', 330, 125);

    ctx.fillStyle = '#64748b';
    ctx.font = '600 18px sans-serif';
    ctx.fillText('DINAS KEBUDAYAAN & PARIWISATA • BANDUNG INFO CENTER (SIPWB)', 330, 163);

    // Badge nomor seri di kanan kop.
    ctx.font = 'bold 16px monospace';
    const serialText = 'NO: ' + serialNum;
    const serialWidth = ctx.measureText(serialText).width;
    const badgeX = width - 184 - serialWidth;

    ctx.fillStyle = 'rgba(217, 119, 6, 0.08)';
    ctx.fillRect(badgeX, 100, serialWidth + 24, 36);
    ctx.strokeStyle = 'rgba(217, 119, 6, 0.35)';
    ctx.lineWidth = 1;
    ctx.strokeRect(badgeX, 100, serialWidth + 24, 36);
    ctx.fillStyle = '#b45309';
    ctx.fillText(serialText, badgeX + 12, 124);

    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(160, 195);
    ctx.lineTo(width - 160, 195);
    ctx.stroke();

    ctx.fillStyle = '#b45309';
    ctx.font = 'italic bold 22px serif';
    ctx.fillText('CERTIFICATE OF EXCELLENCE', width / 2, 240);

    ctx.fillStyle = '#0f172a';
    ctx.font = 'bold 54px sans-serif';
    ctx.fillText('SERTIFIKAT PENGHARGAAN', width / 2, 310);

    ctx.fillStyle = '#d97706';
    ctx.fillRect(width / 2 - 100, 335, 200, 5);

    ctx.fillStyle = '#64748b';
    ctx.font = '500 20px sans-serif';
    ctx.fillText('DIBERIKAN DENGAN HORMAT KEPADA:', width / 2, 400);

    const recipientName = (document.getElementById('cert-input-name')?.value || 'Petualang Sejati Bandung').trim();
    ctx.fillStyle = '#1e3a8a';
    ctx.font = 'bold 52px sans-serif';
    ctx.fillText(recipientName, width / 2, 480);

    ctx.strokeStyle = '#f59e0b';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.moveTo(width / 2 - 280, 505);
    ctx.lineTo(width / 2 + 280, 505);
    ctx.stroke();

    ctx.fillStyle = '#334155';
    ctx.font = '22px sans-serif';
    ctx.fillText('Atas kelulusan dan keberhasilan menjawab seluruh 10 Pertanyaan Kuis Wisata Kota Bandung', width / 2, 570);
    ctx.fillText('dengan nilai sempurna 100 / 100 Poin (10 dari 10 Benar).', width / 2, 608);

    const medalX = width / 2;
    const medalY = 765;
    ctx.beginPath();
    ctx.arc(medalX, medalY, 70, 0, Math.PI * 2);
    ctx.fillStyle = '#d97706';
    ctx.fill();
    ctx.lineWidth = 6;
    ctx.strokeStyle = '#fbbf24';
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(medalX, medalY, 58, 0, Math.PI * 2);
    ctx.fillStyle = '#16224f';
    ctx.fill();

    ctx.fillStyle = '#fbbf24';
    ctx.font = 'bold 16px sans-serif';
    ctx.fillText('SKOR', medalX, medalY - 20);
    ctx.font = 'bold 36px sans-serif';
    ctx.fillStyle = '#ffffff';
    ctx.fillText('100', medalX, medalY + 14);
    ctx.fillStyle = '#fbbf24';
    ctx.font = 'bold 14px sans-serif';
    ctx.fillText('POIN', medalX, medalY + 36);

    ctx.textAlign = 'left';
    ctx.fillStyle = '#64748b';
    ctx.font = '18px sans-serif';
    ctx.fillText('Tanggal Terbit: ' + new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }), 160, 940);
    ctx.fillText('No. Seri: ' + serialNum, 160, 972);

    ctx.textAlign = 'right';
    ctx.fillStyle = '#059669';
    ctx.font = 'bold 16px sans-serif';
    ctx.fillText('✓ Terverifikasi Digital SIPWB', width - 160, 972);

    const link = document.createElement('a');
    link.download = `Sertifikat-Wisata-Bandung-${recipientName.replace(/\s+/g, '_')}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
}

// Inisialisasi Tab & Poin
document.addEventListener('DOMContentLoaded', () => {
    initAdventurePoints();

    const hash = window.location.hash.replace('#', '');
    if (hash && TABS.includes(hash)) {
        switchWisataTab(hash);
    } else {
        switchWisataTab('destinasi');
    }
});
</script>
@endpush
@endsection
