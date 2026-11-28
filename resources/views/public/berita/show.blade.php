@extends('layouts.app')

@section('title', $berita->judul)
@section('description', Str::limit(strip_tags($berita->isi), 160))

@section('content')
{{-- Hero --}}
<x-public.page-hero
    :title="$berita->judul"
    :breadcrumb="[
        'Beranda' => route('home'),
        'Berita' => route('public.berita'),
        Str::limit($berita->judul, 40) => null,
    ]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('public.berita') }}"
           class="inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke daftar berita
        </a>

        <x-public.share-buttons :title="$berita->judul" />
    </div>

    {{-- Meta Data Artikel --}}
    <div class="mb-6 flex flex-wrap items-center gap-x-6 gap-y-3 border-b border-slate-200 pb-6 text-sm text-slate-500">
        {{-- Kategori: Jika bernilai 'Sejarah', diganti dengan 'Berita' sesuai instruksi --}}
        @php
            $displayKategori = strtolower($berita->kategori ?? '') === 'sejarah' ? 'Berita' : ($berita->kategori ?: 'Berita');
        @endphp
        <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3.5 py-1.5 font-display text-xs font-bold text-brand-700">
            <i class="fas fa-newspaper text-[10px]"></i>
            {{ $displayKategori }}
        </span>

        <span class="inline-flex items-center gap-2">
            <i class="fas fa-calendar text-brand-500"></i>
            {{ $berita->tanggal?->translatedFormat('d F Y') ?? '-' }}
        </span>

        <span class="inline-flex items-center gap-2">
            <i class="fas fa-eye text-brand-500"></i>
            {{ number_format($berita->visitor_count, 0, ',', '.') }} kali dibaca
        </span>

        @if ($berita->user)
            <span class="inline-flex items-center gap-2">
                <i class="fas fa-user text-brand-500"></i>
                {{ $berita->user->name }}
            </span>
        @endif
    </div>

    {{-- ============================================================== --}}
    {{-- VOICE AUDIO BERITA & VOICE NOTES (Persis Sesuai Gambar 1 & 2)  --}}
    {{-- ============================================================== --}}
    @php
        $audioSrc = App\Support\Media::url($berita->audio_url);
        $readText = trim(strip_tags($berita->judul . '. ' . $berita->isi));
        $wordCount = str_word_count(strip_tags($berita->isi));
        $waktuBaca = max(1, ceil($wordCount / 150));
    @endphp

    <div class="mb-8">
        {{-- Card Bar Audio Play (Persis Gambar 1: Tombol Biru Bulat + Judul + Waktu Baca + Audio Play) --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-[0_2px_14px_rgba(15,23,42,0.06)] transition hover:border-slate-300">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                
                {{-- Sisi Kiri: Judul & Waktu Baca + Tombol Play Biru --}}
                <div class="min-w-0 flex-1">
                    {{-- Judul + Logo Audio Play Biru di Samping Judul --}}
                    <div class="flex items-start gap-2.5">
                        {{-- Logo Audio Play Biru (Persis Gambar Referensi) --}}
                        <button type="button"
                                onclick="toggleVoiceAudio()"
                                aria-label="Putar / Jeda Audio"
                                title="Putar / Jeda Audio"
                                class="relative grid h-7 w-7 shrink-0 mt-0.5 place-items-center rounded-full text-white shadow-md shadow-blue-500/40 cursor-pointer hover:scale-105 transition-transform"
                                style="background: radial-gradient(circle at 35% 35%, #74b3ff, #4285f4 55%, #1d5bd1);">
                            {{-- Play Triangle --}}
                            <svg id="icon-waktu-play" class="h-3.5 w-3.5 ml-0.5" viewBox="0 0 24 24" fill="white">
                                <path d="M8 5.14v14l11-7-11-7z"/>
                            </svg>
                            <svg id="icon-waktu-pause" class="h-3.5 w-3.5 hidden" viewBox="0 0 24 24" fill="white">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                            {{-- Highlight gloss --}}
                            <span class="absolute top-0.5 left-1 h-2 w-4 rounded-full bg-white/30 blur-[2px] rotate-[-15deg] pointer-events-none"></span>
                            {{-- Pulse ring saat playing --}}
                            <span id="audio-pulse-ring" class="hidden absolute inset-0 rounded-full border-2 border-blue-400/60 animate-ping pointer-events-none"></span>
                        </button>
                        {{-- Judul Artikel --}}
                        <h3 class="font-display text-sm sm:text-base font-bold text-slate-900 leading-snug line-clamp-2">
                            {{ $berita->judul }}
                        </h3>
                    </div>
                    {{-- Waktu Baca (tanpa ikon, di bawah judul) --}}
                    <p class="mt-1.5 text-xs text-slate-500 font-medium pl-9">
                        Waktu Baca : {{ $waktuBaca }} Menit
                    </p>
                </div>

                {{-- Sisi Kanan: Audio Play Indicator (Gambar 1) + Tombol Voice Notes Tanya AI (Gambar 2) --}}
                <div class="flex items-center justify-between sm:justify-end gap-3.5 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                    
                    {{-- Status Audio Play dengan Logo SVG Gelombang Suara --}}
                    <div class="flex items-center gap-2 text-slate-800">
                        <span class="font-display text-xs sm:text-sm font-semibold text-slate-800">Audio Play</span>
                        {{-- Logo speaker + gelombang suara --}}
                        <span id="audio-speaker-icon" class="inline-flex items-center gap-0.5 text-blue-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                                <path id="audio-wave-full" d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" opacity="0.6"/>
                            </svg>
                        </span>
                    </div>

                    {{-- Tombol Voice Notes Dipinggir Audio Berita (Gambar 2: Icon Mic Google Berwarna) --}}
                    <button type="button"
                            onclick="openVoiceNotesModal()"
                            title="Tanya Jawab AI via Suara atau Teks"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200/90 bg-white hover:bg-blue-50/80 hover:border-blue-300 px-4 py-2 font-display text-xs font-bold text-slate-700 hover:text-blue-700 shadow-sm transition active:scale-95 group">
                        {{-- Icon Mic Google Berwarna (Persis Gambar 2) --}}
                        <svg class="h-4 w-4 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3s-3 1.34-3 3v6c0 1.66 1.34 3 3 3z" fill="#4285F4"/>
                            <path d="M7 11c0 1.38.56 2.63 1.46 3.54l-1.41 1.41C5.78 14.68 5 12.93 5 11h2z" fill="#FBBC05"/>
                            <path d="M12 18c-1.38 0-2.63-.56-3.54-1.46l-1.41 1.41C8.32 19.22 10.07 20 12 20c3.97 0 7.22-2.88 7.86-6.66h-2.03C17.22 15.93 14.85 18 12 18z" fill="#EA4335"/>
                            <path d="M11 19.93v2.07h2v-2.07h-2z" fill="#34A853"/>
                        </svg>
                        <span>Voice Notes (Tanya AI)</span>
                    </button>
                </div>

            </div>

            {{-- Progress Scrubber Bar Pemutar Audio --}}
            <div id="voice-player-progress-bar" class="mt-4 pt-3 border-t border-slate-100 hidden">
                <div class="flex items-center gap-3">
                    <span id="voice-time-current" class="text-xs font-mono font-semibold text-slate-500 w-10">0:00</span>
                    <div class="relative h-2 flex-1 cursor-pointer rounded-full bg-slate-200 overflow-hidden"
                         id="voice-timeline-track"
                         onclick="seekVoiceAudio(event)">
                        <div id="voice-progress-fill"
                             class="h-full rounded-full bg-blue-600 transition-all duration-150"
                             style="width: 0%;"></div>
                    </div>
                    <span id="voice-time-total" class="text-xs font-mono font-semibold text-slate-500 w-10 text-right">0:00</span>

                    <button type="button"
                            id="btn-voice-speed"
                            onclick="cycleVoiceSpeed()"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-display text-[11px] font-bold text-slate-700 hover:bg-slate-100">
                        1.0x
                    </button>
                    <button type="button"
                            onclick="stopVoiceAudio()"
                            title="Hentikan Audio"
                            class="grid h-7 w-7 place-items-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 hover:bg-rose-50 hover:text-rose-600">
                        <i class="fas fa-stop text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>


        {{-- Audio element jika ada berkas audio asli --}}
        @if ($audioSrc)
            <audio id="native-audio-player" preload="metadata" class="hidden">
                <source src="{{ $audioSrc }}">
            </audio>
        @endif
    </div>

    {{-- Gambar utama artikel --}}
    @if ($berita->gambar)
        <x-public.media
            :src="$berita->gambar"
            :alt="$berita->judul"
            icon="fa-newspaper"
            tone="brand"
            ratio="aspect-[16/9]"
            class="mb-8 rounded-2xl shadow-[0_4px_20px_rgba(15,23,42,0.10)]"
        />
    @endif

    {{-- Isi Artikel Berita --}}
    <article class="prose-none text-[1.05rem] leading-[1.9] text-slate-700">
        {!! nl2br(e($berita->isi)) !!}
    </article>

    {{-- Reaksi pembaca --}}
    <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
        <p class="font-display text-base font-extrabold text-slate-900">Bagaimana perasaan Anda?</p>
        <p class="mt-1 text-sm text-slate-500">Pilih satu reaksi untuk berita ini.</p>

        <div class="mt-5 flex flex-wrap items-center justify-center gap-3"
             data-reaksi-container
             data-url="{{ route('public.berita.react', $berita) }}"
             data-csrf="{{ csrf_token() }}">
            @foreach ([
                ['tipe' => 'like', 'icon' => 'fa-thumbs-up', 'label' => 'Suka', 'kolom' => 'like_count', 'tone' => 'text-brand-600 hover:bg-brand-50'],
                ['tipe' => 'applause', 'icon' => 'fa-hands-clapping', 'label' => 'Apresiasi', 'kolom' => 'applause_count', 'tone' => 'text-emerald-600 hover:bg-emerald-50'],
                ['tipe' => 'insightful', 'icon' => 'fa-lightbulb', 'label' => 'Mencerahkan', 'kolom' => 'insightful_count', 'tone' => 'text-amber-600 hover:bg-amber-50'],
            ] as $reaction)
                <button type="button"
                        data-tipe="{{ $reaction['tipe'] }}"
                        class="group relative inline-flex min-w-[7rem] flex-col items-center gap-1.5 overflow-visible rounded-xl border border-slate-200 px-5 py-3 transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-1 hover:shadow-lg active:scale-90 disabled:cursor-not-allowed disabled:opacity-60 {{ $reaction['tone'] }}">
                    <span class="pointer-events-none absolute inset-0 rounded-xl"></span>
                    <span class="pointer-events-none absolute left-1/2 top-1/2 h-0 w-0 -translate-x-1/2 -translate-y-1/2 rounded-full bg-current opacity-0"></span>
                    <i class="fas {{ $reaction['icon'] }} relative z-10 transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:scale-110"></i>
                    <span class="relative z-10 font-display text-xs font-semibold text-slate-600">{{ $reaction['label'] }}</span>
                    <span data-kolom="{{ $reaction['kolom'] }}" class="relative z-10 font-display text-base font-extrabold text-slate-800 transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
                        {{ number_format($reaksi->{$reaction['kolom']}, 0, ',', '.') }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Berita terkait --}}
    @php
        $terkait = \App\Models\Berita::where('id_berita', '!=', $berita->id_berita)
            ->when($berita->kategori, fn ($q) => $q->where('kategori', $berita->kategori))
            ->latest('tanggal')
            ->take(3)
            ->get();

        if ($terkait->count() < 3) {
            $terkait = \App\Models\Berita::where('id_berita', '!=', $berita->id_berita)
                ->latest('tanggal')
                ->take(3)
                ->get();
        }
    @endphp

    @if ($terkait->isNotEmpty())
        <div class="mt-14">
            <h2 class="font-display text-xl font-extrabold text-slate-900">Berita Terkait</h2>

            <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($terkait as $item)
                    <a href="{{ route('public.berita.show', $item) }}"
                       class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
                        <x-public.media
                            :src="$item->gambar"
                            alt="{{ $item->judul }}"
                            icon="fa-newspaper"
                            tone="brand"
                            ratio="h-48"
                            class="w-full object-cover transition duration-500 group-hover:scale-105"
                        />

                        <div class="p-5">
                            <p class="text-[11px] uppercase tracking-wide text-slate-400">
                                {{ $item->tanggal?->format('d M Y') ?? '-' }}
                            </p>
                            <p class="mt-1 font-display text-sm font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">
                                {{ Str::limit($item->judul, 70) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</section>

{{-- ============================================================== --}}
{{-- MODAL VOICE NOTES (TANYA JAWAB PENGGUNA DENGAN AI)             --}}
{{-- (Memuat Ikon Mic Google Gambar 2 & Ilustrasi Mendengarkan Gambar 3) --}}
{{-- ============================================================== --}}
<div id="voice-notes-modal"
     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/75 backdrop-blur-sm transition-opacity"
     role="dialog"
     aria-modal="true"
     aria-labelledby="vn-modal-title">
    
    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-slate-100">
            
            {{-- Tombol Tutup --}}
            <button type="button"
                    onclick="closeVoiceNotesModal()"
                    class="absolute right-5 top-5 grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>

            {{-- Header Modal dengan Ikon Mic Google (Persis Gambar 2) --}}
            <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-50 border border-blue-100 shadow-sm shrink-0">
                    {{-- Icon Mic Google Berwarna (Persis Gambar 2) --}}
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3s-3 1.34-3 3v6c0 1.66 1.34 3 3 3z" fill="#4285F4"/>
                        <path d="M7 11c0 1.38.56 2.63 1.46 3.54l-1.41 1.41C5.78 14.68 5 12.93 5 11h2z" fill="#FBBC05"/>
                        <path d="M12 18c-1.38 0-2.63-.56-3.54-1.46l-1.41 1.41C8.32 19.22 10.07 20 12 20c3.97 0 7.22-2.88 7.86-6.66h-2.03C17.22 15.93 14.85 18 12 18z" fill="#EA4335"/>
                        <path d="M11 19.93v2.07h2v-2.07h-2z" fill="#34A853"/>
                    </svg>
                </div>
                <div>
                    <h3 id="vn-modal-title" class="font-display text-lg font-extrabold text-slate-900">
                        Voice Notes &bull; Tanya Jawab AI Berita
                    </h3>
                    <p class="text-xs text-slate-500">
                        Ajukan pertanyaan lewat suara atau teks seputar berita ini
                    </p>
                </div>
            </div>

            {{-- Keterangan Singkat Panduan --}}
            <div class="mt-4 rounded-xl bg-blue-50/80 border border-blue-100 p-3.5 text-xs text-slate-600 leading-relaxed">
                <i class="fas fa-circle-info text-blue-600 mr-1.5"></i>
                Klik tombol <strong>Mikrofon Google</strong> di bawah untuk berbicara langsung (Voice Note), atau ketik pertanyaan seputar artikel ini. Asisten AI akan merangkum dan membacakan jawabannya secara instan.
            </div>

            {{-- Tampilan Listening Saat Merekam Suara (Persis Gambar 3: Mendengarkan... + Ilustrasi Wajah) --}}
            <div id="vn-listening-state" class="hidden my-5 rounded-2xl bg-gradient-to-b from-blue-50/70 to-white border border-blue-100 p-6 text-center transition-all shadow-inner">
                {{-- Teks "Mendengarkan..." (Persis Gambar 3) --}}
                <p class="font-display text-2xl font-bold text-slate-800 tracking-tight">Mendengarkan...</p>
                
                {{-- Ilustrasi Wajah Mendengarkan dengan Gelombang Suara Telinga (Persis Gambar 3) --}}
                <div class="relative my-4 flex items-center justify-center">
                    <svg class="h-28 w-28 mx-auto" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        {{-- Gelombang Suara Telinga Kiri (Outer soft, Inner blue) --}}
                        <path d="M 30 80 A 26 26 0 0 1 33 42" stroke="#bfdbfe" stroke-width="3.5" stroke-linecap="round" class="animate-pulse" />
                        <path d="M 40 74 A 18 18 0 0 1 42 47" stroke="#3b82f6" stroke-width="3.5" stroke-linecap="round" class="animate-pulse" style="animation-delay: 150ms;" />
                        
                        {{-- Alis Kiri & Kanan --}}
                        <path d="M 56 46 C 59 43 64 43 67 46" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round" />
                        <path d="M 78 44 C 81 41 86 41 89 44" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round" />
                        
                        {{-- Mata Kiri & Kanan (Titik Biru Solid) --}}
                        <circle cx="61" cy="53" r="3" fill="#2563eb" />
                        <circle cx="84" cy="51" r="3" fill="#2563eb" />
                        
                        {{-- Hidung Lengkung --}}
                        <path d="M 74 57 C 75 62 72 65 69 65" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        
                        {{-- Senyum Minimalis --}}
                        <path d="M 69 73 C 73 77 79 76 81 72" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round" />
                        
                        {{-- Garis Kontur Pipi & Dagu Mengalir Menghubungkan Sisi Wajah --}}
                        <path d="M 54 52 C 53 70 59 83 69 89 C 76 93 84 92 87 88" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round" />
                    </svg>
                </div>

                <p id="vn-spoken-preview" class="text-sm font-semibold text-blue-700 italic min-h-[24px]">
                    Silakan mulai berbicara...
                </p>

                <div class="mt-4 flex items-center justify-center gap-2">
                    <button type="button"
                            onclick="stopVoiceRecognition(true)"
                            class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 font-display text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                        <i class="fas fa-check"></i> Selesai &amp; Kirim Pertanyaan
                    </button>
                    <button type="button"
                            onclick="stopVoiceRecognition(false)"
                            class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-4 py-2 font-display text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                </div>
            </div>

            {{-- Riwayat Tanya Jawab AI (Chat History) --}}
            <div id="vn-chat-history" class="my-4 max-h-72 overflow-y-auto space-y-3 pr-1 text-left">
                {{-- Pesan Pembuka AI --}}
                <div class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4 border border-slate-200/80">
                    <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-blue-600 text-white text-xs font-bold shadow-sm">
                        AI
                    </div>
                    <div class="text-xs text-slate-700 space-y-1">
                        <p class="font-display font-bold text-slate-900">Halo! Saya Asisten AI Berita Bandung.</p>
                        <p class="leading-relaxed">
                            Silakan tanyakan ringkasan berita, ancaman hukuman, kronologi peristiwa, atau pihak yang terlibat. Anda bisa bertanya dengan suara (Voice Note) atau mengetik langsung.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Rekomendasi Pertanyaan Cepat --}}
            <div class="mb-3">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Contoh Pertanyaan Cepat:</p>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="askQuickQuestion('Apa ringkasan isi berita ini?')" class="rounded-full border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 px-3 py-1 text-[11px] font-medium text-slate-600 hover:text-blue-700 transition">
                        📄 Ringkasan Berita
                    </button>
                    <button type="button" onclick="askQuickQuestion('Berapa lama ancaman hukumannya?')" class="rounded-full border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 px-3 py-1 text-[11px] font-medium text-slate-600 hover:text-blue-700 transition">
                        ⚖️ Ancaman Hukuman
                    </button>
                    <button type="button" onclick="askQuickQuestion('Siapa saja pihak atau tokoh yang terlibat?')" class="rounded-full border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 px-3 py-1 text-[11px] font-medium text-slate-600 hover:text-blue-700 transition">
                        👥 Pihak Terlibat
                    </button>
                    <button type="button" onclick="askQuickQuestion('Kapan peristiwa dalam berita ini terjadi?')" class="rounded-full border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 px-3 py-1 text-[11px] font-medium text-slate-600 hover:text-blue-700 transition">
                        📅 Kapan Terjadinya?
                    </button>
                    <button type="button" onclick="askQuickQuestion('Di mana lokasi peristiwanya?')" class="rounded-full border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 px-3 py-1 text-[11px] font-medium text-slate-600 hover:text-blue-700 transition">
                        📍 Lokasi Kejadian
                    </button>
                </div>
            </div>

            {{-- Input Teks & Tombol Mic Google (Persis Gambar 2) --}}
            <form onsubmit="handleVoiceFormSubmit(event)" class="relative flex items-center gap-2 border-t border-slate-100 pt-3">
                {{-- Tombol Mikrofon Google (Persis Gambar 2) --}}
                <button type="button"
                        id="btn-trigger-mic"
                        onclick="startVoiceRecognition()"
                        title="Bicara dengan Mikrofon (Voice Note)"
                        class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100 hover:border-blue-300 transition active:scale-95 shadow-sm">
                    {{-- Icon Mic Google Berwarna (Persis Gambar 2) --}}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3s-3 1.34-3 3v6c0 1.66 1.34 3 3 3z" fill="#4285F4"/>
                        <path d="M7 11c0 1.38.56 2.63 1.46 3.54l-1.41 1.41C5.78 14.68 5 12.93 5 11h2z" fill="#FBBC05"/>
                        <path d="M12 18c-1.38 0-2.63-.56-3.54-1.46l-1.41 1.41C8.32 19.22 10.07 20 12 20c3.97 0 7.22-2.88 7.86-6.66h-2.03C17.22 15.93 14.85 18 12 18z" fill="#EA4335"/>
                        <path d="M11 19.93v2.07h2v-2.07h-2z" fill="#34A853"/>
                    </svg>
                </button>

                <input type="text"
                       id="vn-user-input"
                       placeholder="Ketik pertanyaan atau klik mikrofon untuk bicara..."
                       class="w-full rounded-2xl border border-slate-300 bg-slate-50/80 px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                <button type="submit"
                        class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-blue-600 hover:bg-blue-700 text-white shadow-md transition active:scale-95">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
/**
 * Voice Audio Berita & Voice Notes (Tanya Jawab Pengguna dengan AI)
 * Menggunakan Web Speech API (Speech Recognition & Speech Synthesis)
 */
const ARTICLE_TITLE = @json($berita->judul);
const ARTICLE_BODY = @json(strip_tags($berita->isi));
const ARTICLE_FULL_TEXT = @json($readText);
const HAS_NATIVE_AUDIO = @json((bool)$audioSrc);

// ==========================================
// 1. PEMUTAR VOICE AUDIO BERITA (Gambar 1)
// ==========================================
let isVoicePlaying = false;
let isVoicePaused = false;
let voiceSpeed = 1.0;
let voiceDurationSeconds = 0;
let voiceElapsedSeconds = 0;
let voiceTimerInterval = null;
let currentUtterance = null;
let nativeAudioEl = null;

function estimateReadingDuration(text) {
    const wordCount = text.trim().split(/\s+/).length;
    return Math.max(15, Math.round((wordCount / 140) * 60));
}

function formatAudioTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

function updatePlayerUI() {
    const playIcon = document.getElementById('icon-voice-play');
    const pauseIcon = document.getElementById('icon-voice-pause');
    const progressBar = document.getElementById('voice-player-progress-bar');
    const timeCurrent = document.getElementById('voice-time-current');
    const timeTotal = document.getElementById('voice-time-total');
    const progressFill = document.getElementById('voice-progress-fill');
    const speakerIcon = document.getElementById('audio-speaker-icon');
    const pulseRing = document.getElementById('audio-pulse-ring');
    const audioWave = document.getElementById('audio-wave-full');

    if (playIcon && pauseIcon) {
        playIcon.classList.toggle('hidden', isVoicePlaying);
        pauseIcon.classList.toggle('hidden', !isVoicePlaying);
    }

    // Pulse ring saat bermain
    if (pulseRing) {
        pulseRing.classList.toggle('hidden', !isVoicePlaying);
    }

    // Animasi gelombang speaker saat bermain
    if (audioWave) {
        audioWave.style.opacity = isVoicePlaying ? '1' : '0.4';
        audioWave.style.transition = 'opacity 0.3s';
    }

    if (speakerIcon) {
        speakerIcon.style.opacity = isVoicePlaying ? '1' : '0.7';
        speakerIcon.style.transform = isVoicePlaying ? 'scale(1.15)' : 'scale(1)';
        speakerIcon.style.transition = 'all 0.3s';
    }

    // Sync ikon mini di Waktu Baca
    const waktuPlayIcon = document.getElementById('icon-waktu-play');
    const waktuPauseIcon = document.getElementById('icon-waktu-pause');
    if (waktuPlayIcon && waktuPauseIcon) {
        waktuPlayIcon.classList.toggle('hidden', isVoicePlaying);
        waktuPauseIcon.classList.toggle('hidden', !isVoicePlaying);
    }

    if (progressBar) {
        progressBar.classList.toggle('hidden', !isVoicePlaying && !isVoicePaused && voiceElapsedSeconds === 0);
    }

    if (timeCurrent) timeCurrent.textContent = formatAudioTime(voiceElapsedSeconds);
    if (timeTotal) timeTotal.textContent = formatAudioTime(voiceDurationSeconds);

    if (progressFill && voiceDurationSeconds > 0) {
        const pct = Math.min(100, (voiceElapsedSeconds / voiceDurationSeconds) * 100);
        progressFill.style.width = `${pct}%`;
    }
}

function startTimer() {
    clearInterval(voiceTimerInterval);
    voiceTimerInterval = setInterval(() => {
        if (isVoicePlaying) {
            voiceElapsedSeconds += 1;
            if (voiceElapsedSeconds >= voiceDurationSeconds) {
                stopVoiceAudio();
            } else {
                updatePlayerUI();
            }
        }
    }, 1000 / voiceSpeed);
}

function toggleVoiceAudio() {
    if (HAS_NATIVE_AUDIO && nativeAudioEl) {
        if (isVoicePlaying) {
            nativeAudioEl.pause();
            isVoicePlaying = false;
            isVoicePaused = true;
        } else {
            nativeAudioEl.playbackRate = voiceSpeed;
            nativeAudioEl.play();
            isVoicePlaying = true;
            isVoicePaused = false;
        }
        updatePlayerUI();
        return;
    }

    if (!('speechSynthesis' in window)) {
        alert('Fitur pemutaran suara otomatis tidak didukung pada peramban ini.');
        return;
    }

    if (isVoicePlaying) {
        window.speechSynthesis.pause();
        isVoicePlaying = false;
        isVoicePaused = true;
        clearInterval(voiceTimerInterval);
        updatePlayerUI();
    } else if (isVoicePaused) {
        window.speechSynthesis.resume();
        isVoicePlaying = true;
        isVoicePaused = false;
        startTimer();
        updatePlayerUI();
    } else {
        window.speechSynthesis.cancel();
        currentUtterance = new SpeechSynthesisUtterance(ARTICLE_FULL_TEXT);
        currentUtterance.rate = voiceSpeed;
        currentUtterance.lang = 'id-ID';

        const voices = window.speechSynthesis.getVoices();
        const idVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
        if (idVoice) currentUtterance.voice = idVoice;

        currentUtterance.onend = () => stopVoiceAudio();
        currentUtterance.onerror = () => stopVoiceAudio();

        window.speechSynthesis.speak(currentUtterance);
        isVoicePlaying = true;
        isVoicePaused = false;
        voiceElapsedSeconds = 0;
        startTimer();
        updatePlayerUI();
    }
}

function stopVoiceAudio() {
    isVoicePlaying = false;
    isVoicePaused = false;
    voiceElapsedSeconds = 0;
    clearInterval(voiceTimerInterval);

    if (HAS_NATIVE_AUDIO && nativeAudioEl) {
        nativeAudioEl.pause();
        nativeAudioEl.currentTime = 0;
    } else if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }

    updatePlayerUI();
}

function cycleVoiceSpeed() {
    const speeds = [1.0, 1.25, 1.5, 2.0];
    const currIdx = speeds.indexOf(voiceSpeed);
    voiceSpeed = speeds[(currIdx + 1) % speeds.length];

    const speedBtn = document.getElementById('btn-voice-speed');
    if (speedBtn) speedBtn.textContent = `${voiceSpeed}x`;

    if (HAS_NATIVE_AUDIO && nativeAudioEl) {
        nativeAudioEl.playbackRate = voiceSpeed;
    } else if (isVoicePlaying) {
        toggleVoiceAudio();
        isVoicePaused = false;
        toggleVoiceAudio();
    }
}

function seekVoiceAudio(event) {
    const track = document.getElementById('voice-timeline-track');
    if (!track || voiceDurationSeconds <= 0) return;

    const rect = track.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const pct = Math.max(0, Math.min(1, clickX / rect.width));

    voiceElapsedSeconds = Math.round(pct * voiceDurationSeconds);

    if (HAS_NATIVE_AUDIO && nativeAudioEl) {
        nativeAudioEl.currentTime = voiceElapsedSeconds;
    }
    updatePlayerUI();
}

// ==============================================================
// 2. MODAL VOICE NOTES (TANYA JAWAB PENGGUNA DENGAN AI)
// ==============================================================
let recognitionInstance = null;
let isRecognizing = false;

function openVoiceNotesModal() {
    const modal = document.getElementById('voice-notes-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeVoiceNotesModal() {
    const modal = document.getElementById('voice-notes-modal');
    if (modal) modal.classList.add('hidden');
    stopVoiceRecognition(false);
}

function startVoiceRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert('Pengenalan suara (Speech Recognition) tidak didukung pada peramban ini. Anda tetap dapat mengetik pertanyaan pada kolom input.');
        document.getElementById('vn-user-input')?.focus();
        return;
    }

    const listeningBox = document.getElementById('vn-listening-state');
    const spokenPreview = document.getElementById('vn-spoken-preview');
    if (listeningBox) listeningBox.classList.remove('hidden');
    if (spokenPreview) spokenPreview.textContent = 'Mendengarkan suara Anda...';

    try {
        if (recognitionInstance) recognitionInstance.abort();

        recognitionInstance = new SpeechRecognition();
        recognitionInstance.lang = 'id-ID';
        recognitionInstance.interimResults = true;
        recognitionInstance.continuous = false;

        let finalTranscript = '';

        recognitionInstance.onstart = () => {
            isRecognizing = true;
        };

        recognitionInstance.onresult = (event) => {
            let interimTranscript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                } else {
                    interimTranscript += event.results[i][0].transcript;
                }
            }
            const currentText = finalTranscript || interimTranscript;
            if (spokenPreview && currentText) {
                spokenPreview.textContent = `"${currentText}"`;
            }
        };

        recognitionInstance.onerror = (event) => {
            console.warn('Speech recognition error:', event.error);
            stopVoiceRecognition(false);
        };

        recognitionInstance.onend = () => {
            isRecognizing = false;
            if (finalTranscript.trim()) {
                submitUserQuestion(finalTranscript.trim());
                if (listeningBox) listeningBox.classList.add('hidden');
            }
        };

        recognitionInstance.start();
    } catch (err) {
        console.error(err);
        stopVoiceRecognition(false);
    }
}

function stopVoiceRecognition(shouldSubmit) {
    const listeningBox = document.getElementById('vn-listening-state');
    const spokenPreview = document.getElementById('vn-spoken-preview');

    if (recognitionInstance && isRecognizing) {
        recognitionInstance.stop();
        isRecognizing = false;
    }

    if (shouldSubmit && spokenPreview) {
        const text = spokenPreview.textContent.replace(/^"|"$/g, '').trim();
        if (text && text !== 'Mendengarkan suara Anda...' && text !== 'Silakan mulai berbicara...') {
            submitUserQuestion(text);
        }
    }

    if (listeningBox) listeningBox.classList.add('hidden');
}

function handleVoiceFormSubmit(event) {
    event.preventDefault();
    const input = document.getElementById('vn-user-input');
    if (!input || !input.value.trim()) return;
    submitUserQuestion(input.value.trim());
    input.value = '';
}

function askQuickQuestion(questionText) {
    submitUserQuestion(questionText);
}

function submitUserQuestion(question) {
    const chatContainer = document.getElementById('vn-chat-history');
    if (!chatContainer) return;

    // 1. Tampilkan Balon Pesan Pengguna
    const userBubble = document.createElement('div');
    userBubble.className = 'flex items-start justify-end gap-2.5';
    userBubble.innerHTML = `
        <div class="rounded-2xl rounded-tr-sm bg-blue-600 px-4 py-2.5 text-xs text-white max-w-[85%] shadow-sm">
            <p class="font-medium">${escapeHtml(question)}</p>
        </div>
        <div class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-slate-200 text-slate-700 text-xs font-bold">
            <i class="fas fa-user text-[10px]"></i>
        </div>
    `;
    chatContainer.appendChild(userBubble);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // 2. Loading State AI
    const loadingId = 'ai-loading-' + Date.now();
    const loadingBubble = document.createElement('div');
    loadingBubble.id = loadingId;
    loadingBubble.className = 'flex items-start gap-3 rounded-2xl bg-slate-50 p-4 border border-slate-200/80';
    loadingBubble.innerHTML = `
        <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-blue-600 text-white text-xs font-bold shadow-sm">
            AI
        </div>
        <div class="text-xs text-slate-500 flex items-center gap-2">
            <i class="fas fa-spinner fa-spin text-blue-600"></i>
            <span>Menganalisis isi berita...</span>
        </div>
    `;
    chatContainer.appendChild(loadingBubble);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // 3. Jawab Secara Pintar Berdasarkan Artikel
    setTimeout(() => {
        const aiResponseText = generateAIAnswer(question);
        loadingBubble.remove();

        const aiBubble = document.createElement('div');
        aiBubble.className = 'flex items-start gap-3 rounded-2xl bg-slate-50 p-4 border border-slate-200/80';
        aiBubble.innerHTML = `
            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-blue-600 text-white text-xs font-bold shadow-sm">
                AI
            </div>
            <div class="text-xs text-slate-800 space-y-2 flex-1">
                <div class="leading-relaxed whitespace-pre-line">${aiResponseText}</div>
                <div class="pt-2 border-t border-slate-200/60 flex items-center gap-2">
                    <button type="button"
                            onclick="speakAIAnswer(${JSON.stringify(aiResponseText.replace(/<[^>]+>/g, ''))})"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-blue-700 hover:bg-blue-50 transition shadow-sm">
                        <i class="fas fa-volume-high text-[10px]"></i>
                        <span>Dengarkan Jawaban</span>
                    </button>
                </div>
            </div>
        `;
        chatContainer.appendChild(aiBubble);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // Otomatis bacakan jawaban singkat
        speakAIAnswer(aiResponseText.replace(/<[^>]+>/g, ''));
    }, 450);
}

function generateAIAnswer(question) {
    const q = question.toLowerCase();

    // Intent: Ringkasan Berita
    if (q.includes('ringkas') || q.includes('rangkum') || q.includes('kesimpulan') || q.includes('tentang apa') || q.includes('inti')) {
        const sentences = ARTICLE_BODY.split(/[.!?]+/).filter(s => s.trim().length > 10);
        const mainSummary = sentences.slice(0, 3).join('. ') + '.';
        return `Berikut adalah ringkasan dari berita ini:\n\n• <strong>Judul</strong>: ${ARTICLE_TITLE}\n• <strong>Inti Berita</strong>: ${mainSummary}`;
    }

    // Intent: Tanggal / Kapan
    if (q.includes('kapan') || q.includes('tanggal') || q.includes('waktu') || q.includes('tahun')) {
        const dateMatch = ARTICLE_BODY.match(/\b(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember|\w+)\s+\d{4}|\d{4})\b/i);
        if (dateMatch) {
            return `Berdasarkan isi artikel, peristiwa ini tercatat pada waktu <strong>${dateMatch[0]}</strong>. Berita ini dipublikasikan pada portal resmi Bandung Info Center.`;
        }
        return `Berdasarkan artikel ini, peristiwa terkait berlangsung sesuai lini masa yang diberitakan dalam <em>"${ARTICLE_TITLE}"</em>.`;
    }

    // Intent: Pihak / Siapa
    if (q.includes('siapa') || q.includes('tokoh') || q.includes('pihak') || q.includes('pelaku') || q.includes('peserta')) {
        return `Berdasarkan laporan berita ini, pihak yang dibahas berkaitan dengan <strong>${ARTICLE_TITLE}</strong>. Rincian lengkap dapat Anda simak langsung pada paragraf utama artikel.`;
    }

    // Intent: Hukuman / Ancaman / Penjara / Vonis / Pasal
    if (q.includes('hukum') || q.includes('penjara') || q.includes('ancam') || q.includes('pasal') || q.includes('tahun penjara') || q.includes('vonis') || q.includes('tuntut')) {
        const penaltySentences = ARTICLE_BODY.split(/[.!?]+/).filter(s => {
            const lower = s.toLowerCase();
            return lower.includes('penjara') || lower.includes('ancam') || lower.includes('pasal') || lower.includes('hukum') || lower.includes('tahun') || lower.includes('jerat');
        });
        if (penaltySentences.length > 0) {
            const bestPenalty = penaltySentences.slice(0, 2).map(s => s.trim()).join('. ') + '.';
            return `Mengenai ancaman hukuman dalam berita ini:\n\n• ${bestPenalty}\n\nKasus ini ditangani sesuai prosedur hukum yang berlaku di wilayah terkait.`;
        }
    }

    // Intent: Kronologi / Motif / Mengapa / Penyebab
    if (q.includes('kronologi') || q.includes('motif') || q.includes('mengapa') || q.includes('kenapa') || q.includes('penyebab') || q.includes('alasan') || q.includes('modus')) {
        const chronoSentences = ARTICLE_BODY.split(/[.!?]+/).filter(s => {
            const lower = s.toLowerCase();
            return lower.includes('karena') || lower.includes('diduga') || lower.includes('bermula') || lower.includes('kronologi') || lower.includes('modus') || lower.includes('motif');
        });
        if (chronoSentences.length > 0) {
            return `Berdasarkan keterangan artikel mengenai latar belakang dan kronologi peristiwa:\n\n• "${chronoSentences.slice(0, 2).map(s => s.trim()).join('. ')}."`;
        }
    }

    // Intent: Tempat / Lokasi / Di mana
    if (q.includes('dimana') || q.includes('di mana') || q.includes('lokasi') || q.includes('tempat') || q.includes('gedung') || q.includes('kota') || q.includes('daerah')) {
        const locSentences = ARTICLE_BODY.split(/[.!?]+/).filter(s => {
            const lower = s.toLowerCase();
            return lower.includes('bandung') || lower.includes('jalan') || lower.includes('lokasi') || lower.includes('gedung') || lower.includes('kawasan') || lower.includes('wilayah');
        });
        if (locSentences.length > 0) {
            return `Mengenai lokasi kejadian:\n\n• "${locSentences[0].trim()}." Peristiwa ini berlangsung di wilayah Kota Bandung.`;
        }
        return `Peristiwa pada berita ini berlokasi di <strong>Kota Bandung</strong> dan kawasan sekitarnya.`;
    }

    // Default: Cari kalimat paling relevan dengan kata kunci
    const words = q.split(/\s+/).filter(w => w.length > 3);
    const sentences = ARTICLE_BODY.split(/[.!?]+/).filter(s => s.trim().length > 15);
    
    let bestSentence = '';
    let maxMatch = 0;

    sentences.forEach(s => {
        let matchCount = 0;
        words.forEach(w => {
            if (s.toLowerCase().includes(w)) matchCount++;
        });
        if (matchCount > maxMatch) {
            maxMatch = matchCount;
            bestSentence = s.trim();
        }
    });

    if (bestSentence) {
        return `Berdasarkan isi berita: "${bestSentence}." Jika ada rincian lain yang ingin Anda ketahui, silakan tanyakan kembali.`;
    }

    return `Pertanyaan Anda: <em>"${question}"</em>.\n\nBerdasarkan isi berita <strong>"${ARTICLE_TITLE}"</strong>, informasi utama berkaitan dengan: ${ARTICLE_BODY.substring(0, 180)}...`;
}

function speakAIAnswer(text) {
    if (!('speechSynthesis' in window)) return;
    window.speechSynthesis.cancel();
    const utt = new SpeechSynthesisUtterance(text);
    utt.lang = 'id-ID';
    utt.rate = 1.05;
    const voices = window.speechSynthesis.getVoices();
    const idVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
    if (idVoice) utt.voice = idVoice;
    window.speechSynthesis.speak(utt);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => {
    nativeAudioEl = document.getElementById('native-audio-player');

    if (HAS_NATIVE_AUDIO && nativeAudioEl) {
        nativeAudioEl.addEventListener('loadedmetadata', () => {
            voiceDurationSeconds = Math.round(nativeAudioEl.duration) || estimateReadingDuration(ARTICLE_FULL_TEXT);
            updatePlayerUI();
        });
        nativeAudioEl.addEventListener('timeupdate', () => {
            voiceElapsedSeconds = Math.round(nativeAudioEl.currentTime);
            updatePlayerUI();
        });
        nativeAudioEl.addEventListener('ended', () => {
            stopVoiceAudio();
        });
    }

    voiceDurationSeconds = estimateReadingDuration(ARTICLE_FULL_TEXT);
    updatePlayerUI();

    window.addEventListener('beforeunload', () => {
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
    });
});
</script>
@endpush
@endsection
