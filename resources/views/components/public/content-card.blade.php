{{--
    Kartu konten seragam untuk semua section di beranda.

    Satu komponen ini dipakai oleh Rekomendasi Kuliner, Berita, Wisata, dan
    Sejarah sehingga tinggi, padding, tipografi, dan posisi meta selalu sama
    (serasi) antar section. Yang berbeda hanya `variant`, yaitu warna bodies
    mengikuti latar section.

    @param string      $variant    light | brand | dark
    @param string|null $src        path gambar (boleh null -> fallback ikon)
    @param string      $imageIcon  ikon FontAwesome saat gambar tidak ada
    @param string      $imageTone  warna fallback ikon
    @param string|null $badge      label kecil di atas judul
    @param string      $badgeTone  kelas warna badge
    @param string      $title
    @param string|null $summary    ringkasan 2 baris
    @param array       $meta       [[icon, value], ...] di baris bawah
    @param string|null $note       teks kanan bawah (mis. harga)
--}}
@props([
    'variant' => 'light',
    'href' => null,
    'src' => null,
    'imageAlt' => '',
    'imageIcon' => 'fa-image',
    'imageTone' => 'slate',
    'badge' => null,
    'badgeTone' => 'bg-brand-50 text-brand-700',
    'title' => '',
    'summary' => null,
    'meta' => [],
    'note' => null,
    'ctaIcon' => null,
])

@php
    // light  : bodies putih, teks gelap   (Berita, Wisata)
    // brand  : bodies biru, teks putih    (Rekomendasi Kuliner)
    // dark   : bodies transparan, teks putih (Cerita Sejarah di atas navy)
    $body = [
        'light' => 'bg-white',
        'brand' => 'bg-brand-600',
        'dark' => 'bg-white/5',
    ][$variant] ?? 'bg-white';

    $titleColor = [
        'light' => 'text-slate-900 group-hover:text-brand-700',
        'brand' => 'text-white',
        'dark' => 'text-white group-hover:text-accent-400',
    ][$variant] ?? 'text-slate-900';

    $mutedColor = [
        'light' => 'text-slate-500',
        'brand' => 'text-white/75',
        'dark' => 'text-slate-300',
    ][$variant] ?? 'text-slate-500';

    $divider = [
        'light' => 'border-slate-100',
        'brand' => 'border-white/20',
        'dark' => 'border-white/15',
    ][$variant] ?? 'border-slate-100';
@endphp

{{-- Class digabung dalam satu @class supaya tidak ada atribut class ganda --}}
<article @class([
    'group flex h-full flex-col overflow-hidden rounded-2xl border shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]',
    'border-white/10 bg-transparent' => $variant === 'dark',
    'border-slate-200 bg-white' => $variant !== 'dark',
    $attributes->get('class'),
])>
    {{-- Gambar: rasio seragam di semua section --}}
    <x-public.media
        :src="$src"
        :alt="$imageAlt"
        :icon="$imageIcon"
        :tone="$imageTone"
        ratio="aspect-[4/3]"
        class="transition duration-500 group-hover:scale-105"
    />

    <div class="flex flex-1 flex-col p-5 {{ $body }}">
        {{-- Badge: tingginya dijaga agar judul selalu mulai di baris yang sama --}}
        <div class="mb-2.5 h-6">
            @if ($badge)
                <span class="inline-flex max-w-full items-center gap-1.5 truncate rounded-full px-2.5 py-1 text-[11px] font-bold {{ $badgeTone }}">
                    {{ $badge }}
                </span>
            @endif
        </div>

        <h3 class="line-clamp-2 font-display text-base font-bold leading-snug transition {{ $titleColor }}">
            @if ($href)
                <a href="{{ $href }}">{{ $title }}</a>
            @else
                {{ $title }}
            @endif
        </h3>

        @if ($summary)
            <p class="mt-2 line-clamp-2 text-xs leading-relaxed {{ $mutedColor }}">{{ $summary }}</p>
        @endif

        {{-- mt-auto: baris bawah menempel ke dasar kartu -> tinggi kartu serata --}}
        <div class="mt-auto pt-4">
            <div class="flex items-center justify-between gap-3 border-t pt-4 {{ $divider }}">
                <div class="flex min-w-0 flex-wrap items-center gap-x-4 gap-y-1 text-[11px] {{ $mutedColor }}">
                    @foreach ($meta as [$icon, $value])
                        <span class="inline-flex items-center gap-1.5">
                            @if ($icon)
                                <i class="fas {{ $icon }} text-[10px]"></i>
                            @endif
                            <span class="truncate">{{ $value }}</span>
                        </span>
                    @endforeach
                </div>

                @if ($note)
                    <span @class([
                        'shrink-0 font-display text-sm font-extrabold',
                        'text-brand-700' => $variant === 'light',
                        'text-white' => $variant === 'brand',
                        'text-accent-400' => $variant === 'dark',
                    ])>
                        {{ $note }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</article>
