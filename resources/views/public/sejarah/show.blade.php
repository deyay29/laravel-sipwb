
@extends('layouts.app')

@section('title', $topik->judul_topik)
@section('description', Str::limit(strip_tags($topik->ringkasan ?? ''), 160))

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | PENGAMBILAN MEDIA
    |--------------------------------------------------------------------------
    */

    // Gambar utama
    $posterVideo = App\Support\Media::url($topik->gambar_utama);

    // Flipbook digital
    $flipbookRaw = trim((string) ($topik->notebook ?? ''));
    $flipbookUrl = $flipbookRaw
        ? (App\Support\Media::url($flipbookRaw) ?: $flipbookRaw)
        : null;

    $flipbookValid = $flipbookUrl
        && Str::startsWith($flipbookUrl, ['http://', 'https://']);

    $flipbookHost = $flipbookValid
        ? (parse_url($flipbookUrl, PHP_URL_HOST) ?: null)
        : null;

    // Gambar sampul kartu Flipbook Digital (opsional).
    $flipbookCover = App\Support\Media::url($topik->gambar_flipbook);

    // Video storytelling
    $videoRaw = trim((string) ($topik->video_storytelling ?? ''));

    $videoStorytelling = null;

    if ($videoRaw !== '') {
        $videoStorytelling = App\Support\Media::url($videoRaw);

        // Jika Media::url belum menghasilkan URL, gunakan URL file storage
        if (!$videoStorytelling) {
            if (Str::startsWith($videoRaw, ['http://', 'https://'])) {
                $videoStorytelling = $videoRaw;
            } else {
                $videoStorytelling = \Illuminate\Support\Facades\Storage::disk('public')
                    ->url(ltrim($videoRaw, '/'));
            }
        }
    }

    // Deteksi video YouTube
    $videoEmbedUrl = null;

    if ($videoStorytelling) {
        $host = strtolower(parse_url($videoStorytelling, PHP_URL_HOST) ?? '');

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be', 'youtube-nocookie.com', 'www.youtube-nocookie.com'])) {

            $videoId = null;

            if (str_contains($host, 'youtu.be')) {
                $videoId = trim(parse_url($videoStorytelling, PHP_URL_PATH) ?? '/', '/');
            } else {
                parse_str(parse_url($videoStorytelling, PHP_URL_QUERY) ?? '', $query);

                $videoId = $query['v'] ?? null;

                if (!$videoId && preg_match('~/(?:embed|shorts|live)/([^/?]+)~', parse_url($videoStorytelling, PHP_URL_PATH) ?? '', $matches)) {
                    $videoId = $matches[1];
                }
            }

            if ($videoId) {
                $videoEmbedUrl = 'https://www.youtube-nocookie.com/embed/' . $videoId;
            }
        }

        // Deteksi Vimeo
        if (!$videoEmbedUrl && str_contains($host, 'vimeo.com')) {
            $path = trim(parse_url($videoStorytelling, PHP_URL_PATH) ?? '', '/');

            if (preg_match('/(\d+)/', $path, $matches)) {
                $videoEmbedUrl = 'https://player.vimeo.com/video/' . $matches[1];
            }
        }
    }

    $videoExtension = strtolower(pathinfo(
        parse_url($videoStorytelling ?? '', PHP_URL_PATH) ?? '',
        PATHINFO_EXTENSION
    ));

    $directVideo = $videoStorytelling && !$videoEmbedUrl;
@endphp

<x-public.page-hero
    :title="$topik->judul_topik"
    :breadcrumb="[
        'Beranda' => route('home'),
        'Sejarah' => route('public.sejarah'),
        Str::limit($topik->judul_topik, 40) => null,
    ]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">

    {{-- Tombol kembali dan bagikan --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">

        <a href="{{ route('public.sejarah') }}"
           class="inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">

            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke daftar sejarah Bandung

        </a>

        <x-public.share-buttons :title="$topik->judul_topik" />

    </div>

    <div class="grid gap-10 lg:grid-cols-5">

        {{-- KOLOM KIRI: GAMBAR DAN DAFTAR BAB --}}
        <aside class="space-y-4 lg:col-span-2">

            <x-public.media
                :src="$topik->gambar_utama"
                :alt="$topik->judul_topik"
                icon="fa-landmark"
                tone="violet"
                ratio="aspect-[4/3]"
                class="rounded-2xl shadow-[0_4px_20px_rgba(15,23,42,0.10)]"
            />

            {{-- Daftar Bab --}}
            @if ($babs->isNotEmpty())

                <h3 class="font-display text-xl font-extrabold text-slate-900">
                    Daftar Bab
                </h3>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">

                    <ul class="space-y-1.5">

                        @foreach ($babs as $bab)

                            <li>
                                <a href="{{ route('public.sejarah.bab', [$topik, $bab->nomor_bab]) }}"
                                   class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 transition hover:border-brand-500 hover:bg-brand-50">

                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-600 font-display text-xs font-bold text-white">
                                        {{ $bab->nomor_bab }}
                                    </span>

                                    <span class="text-sm text-slate-700 transition group-hover:text-brand-700">
                                        {{ Str::limit($bab->judul_bab, 40) }}
                                    </span>

                                </a>
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <a href="{{ route('public.sejarah') }}"
               class="block rounded-2xl border-2 border-slate-900 px-5 py-3.5 text-center font-display text-sm font-bold text-slate-900 transition hover:bg-slate-900 hover:text-white">

                Lihat Topik Lainnya

            </a>

        </aside>

        {{-- KOLOM KANAN: DESKRIPSI DAN MEDIA --}}
        <div class="lg:col-span-3">

            {{-- Deskripsi Sejarah --}}
            <h2 class="font-display text-2xl font-extrabold text-slate-900">
                Deskripsi Sejarah
            </h2>

            <div class="mt-4 text-[1.02rem] leading-[1.9] text-slate-700">

                @if ($topik->ringkasan)

                    {!! nl2br(e($topik->ringkasan)) !!}

                @else

                    <p>
                        Tonggak Konferensi Asia Afrika merupakan salah satu peristiwa paling bersejarah yang menjadikan Kota Bandung dikenal di mata dunia. Konferensi ini berlangsung pada 18-24 April 1955 di Gedung Merdeka dan dihadiri 29 negara dari Asia dan Afrika.
                    </p>

                    <p class="mt-4">
                        Konferensi diselenggarakan untuk mempererat kerja sama antarnegara Asia dan Afrika, menolak segala bentuk kolonialisme, serta mendorong perdamaian dunia.
                    </p>

                @endif

            </div>

            {{-- MEDIA INTERAKTIF --}}
            @if ($videoStorytelling || $flipbookValid)

                <section class="mt-12" aria-label="Media interaktif">

                    <div class="mb-6">

                        <h2 class="font-display text-2xl font-extrabold text-slate-900">
                            Video Story Telling dan History Flipbook Digital
                        </h2>

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Saksikan video narasi sejarah dan baca lembaran digital lengkapnya di bawah ini
                        </p>

                    </div>

                    <div class="grid gap-8 {{ $videoStorytelling && $flipbookValid ? 'md:grid-cols-2' : 'grid-cols-1' }}">

                        {{-- KARTU VIDEO STORYTELLING --}}
                        @if ($videoStorytelling)

                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">

                                <div class="relative">

                                    @if ($videoEmbedUrl)

                                        {{-- Video YouTube / Vimeo --}}
                                        <iframe
                                            src="{{ $videoEmbedUrl }}"
                                            title="Video Storytelling {{ $topik->judul_topik }}"
                                            class="aspect-video w-full bg-slate-900"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen
                                            loading="lazy">
                                        </iframe>

                                    @elseif ($directVideo)

                                        {{-- Video yang diunggah admin --}}
                                        <video
                                            controls
                                            playsinline
                                            preload="metadata"
                                            poster="{{ $posterVideo ?: '' }}"
                                            class="aspect-video w-full bg-slate-900">

                                            <source src="{{ $videoStorytelling }}">

                                            Browser Anda tidak mendukung pemutar video.

                                        </video>

                                    @endif

                                    <div class="absolute left-4 top-4 pointer-events-none">

                                        <span class="inline-flex items-center gap-2 rounded-full bg-navy-900/90 px-3 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-lg">

                                            <i class="fas fa-film"></i>
                                            Video Storytelling

                                        </span>

                                    </div>

                                </div>

                                <div class="flex items-center gap-3 border-t border-slate-100 p-4">

                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-navy-800 text-white">
                                        <i class="fas fa-video"></i>
                                    </span>

                                    <div class="min-w-0">

                                        <p class="font-display text-sm font-bold text-slate-900">
                                            Video Storytelling
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ Str::limit($topik->judul_topik, 55) }}
                                        </p>

                                    </div>

                                </div>

                            </article>

                        @endif

                        {{-- KARTU FLIPBOOK DIGITAL --}}
                        @if ($flipbookValid)

                            <article>

                                <a href="{{ $flipbookUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Buka Flipbook Digital {{ $topik->judul_topik }}"
                                   class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg transition hover:-translate-y-1 hover:shadow-xl">

                                    <div @class([
                                        'relative flex aspect-video w-full flex-col items-center justify-center gap-4 overflow-hidden px-6 text-center text-white',
                                        'bg-gradient-to-br from-navy-800 via-navy-900 to-slate-900' => ! $flipbookCover,
                                    ])>

                                        @if ($flipbookCover)
                                            <img src="{{ $flipbookCover }}"
                                                 alt="Sampul Flipbook Digital {{ $topik->judul_topik }}"
                                                 loading="lazy"
                                                 class="absolute inset-0 h-full w-full object-cover">

                                            <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-900/60 to-navy-900/20"></div>
                                        @endif

                                        <span class="relative z-10 grid h-16 w-16 place-items-center rounded-2xl bg-white/10 backdrop-blur">
                                            <i class="fas fa-book-open text-2xl"></i>
                                        </span>

                                        <div class="relative z-10">

                                            <p class="font-display text-lg font-bold">
                                                History Flip Book Digital
                                            </p>

                                            <p class="mt-1 text-sm text-white/70">
                                                Lembaran halaman digital yang dapat dijelajahi secara interaktif
                                            </p>

                                        </div>

                                        <span class="relative z-10 inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2 font-display text-xs font-bold uppercase tracking-wide shadow-lg">
                                            <i class="fas fa-up-right-from-square"></i>
                                            Buka Flipbook
                                        </span>

                                        <span class="absolute left-4 top-4 z-10 rounded-full bg-white/10 px-3 py-2 text-xs font-bold uppercase text-white backdrop-blur">
                                            <i class="fas fa-book"></i>
                                            Flipbook
                                        </span>

                                    </div>

                                    <div class="mt-auto flex items-center gap-3 border-t border-slate-100 p-4">

                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-600 text-white">
                                            <i class="fas fa-link"></i>
                                        </span>

                                        <div class="min-w-0 flex-1">

                                            <p class="font-display text-sm font-bold text-slate-900">
                                                History Flip Book Digital
                                            </p>

                                            <p class="mt-1 truncate text-xs text-slate-500">
                                                {{ $flipbookHost }} • Dibuka di tab baru
                                            </p>

                                        </div>

                                        <i class="fas fa-arrow-right text-brand-700"></i>

                                    </div>

                                </a>

                            </article>

                        @endif

                    </div>

                </section>

            @endif

        </div>

    </div>

    {{-- SEJARAH TERKAIT --}}
    @php

        $terkait = \App\Models\Sejarah::where(
            'id_sejarah',
            '!=',
            $topik->id_sejarah
        )
        ->inRandomOrder()
        ->take(3)
        ->get();

    @endphp

    @if ($terkait->isNotEmpty())

        <div class="mt-16">

            <h2 class="font-display text-xl font-extrabold text-slate-900">
                Sejarah Terkait Lainnya
            </h2>

            <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($terkait as $item)

                    <a href="{{ route('public.sejarah.show', $item) }}"
                       class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md transition hover:shadow-xl">

                        <x-public.media
                            :src="$item->gambar_utama"
                            :alt="$item->judul_topik"
                            icon="fa-landmark"
                            tone="violet"
                            ratio="h-44"
                            class="transition duration-500"
                        />

                        <div class="bg-navy-700 p-5 text-white">

                            <p class="font-display text-base font-bold">
                                {{ Str::limit($item->judul_topik, 45) }}
                            </p>

                            <p class="mt-2 text-xs leading-relaxed text-white/75">
                                {{ Str::limit($item->ringkasan, 90) }}
                            </p>

                            <span class="mt-4 inline-flex items-center gap-1.5 font-display text-xs font-bold">
                                Lihat Detail
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </span>

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

    @endif

</section>

@endsection
