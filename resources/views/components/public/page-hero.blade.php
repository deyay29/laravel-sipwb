{{--
    Hero halaman publik.

    Semua halaman memakai hero yang sama: latar navy dengan foto kota,
    sehingga navbar transparan selalu kontras dan konsisten dengan beranda.

    Slot opsional:
      aside : konten di sisi kanan hero (mis. kartu cuaca di halaman kuliner)

    @param string|null           $title
    @param string|null           $subtitle
    @param string|null           $image      path gambar; null / file hilang
                                          -> memakai gambar bawaan, kalau tidak
                                          ada -> ikon
    @param array<string,?string> $breadcrumb label => url (url null = aktif)
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'image' => null,
    'breadcrumb' => [],
])

@php
    use App\Support\Media;

    // Prioritas: gambar halaman > gambar bawaan > ikon.
    $custom = Media::url($image);
    $fallback = Media::url('images/beranda.png');

    $background = $custom ?? $fallback;
@endphp

<section {{ $attributes->merge(['class' => 'relative isolate overflow-hidden bg-navy-900']) }}>
    {{-- Foto latar --}}
    <x-public.media
        :src="$background ? ($custom ? $image : 'images/beranda.png') : null"
        alt=""
        icon="fa-city"
        tone="navy"
        ratio="absolute inset-0 -z-20 h-full w-full"
    />

    {{-- Lapisan gelap agar navbar & teks tetap terbaca.
         Opacity diturunkan supaya foto kota lebih terlihat; tetap cukup
         gelap untuk teks putih di atasnya. --}}
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-navy-900/75 via-navy-800/55 to-navy-900/40"></div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-navy-900/45 via-transparent to-navy-900/30"></div>

    <div @class([
        'mx-auto max-w-[1400px] px-5 pb-16 pt-32 sm:px-6 lg:px-8 lg:pb-20 lg:pt-40',
        'flex flex-wrap items-center gap-10' => isset($aside),
    ])>
        <div @class([
            'max-w-2xl',
            'w-full' => isset($aside),
        ])>
            @if ($breadcrumb)
                <nav aria-label="Breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-sm text-white/70">
                    @foreach ($breadcrumb as $label => $url)
                        @if ($url && ! $loop->last)
                            <a href="{{ $url }}" class="transition hover:text-white">{{ $label }}</a>
                            <span class="text-white/40">/</span>
                        @else
                            <span class="font-semibold text-white">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if ($title)
                <h1 class="font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
                    {{ $title }}
                </h1>
            @endif

            @if ($subtitle)
                <p class="mt-4 text-base leading-relaxed text-white/85">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Slot aside: konten tambahan di sisi kanan hero --}}
        @isset($aside)
            <div class="w-full lg:w-auto lg:shrink-0">
                {{ $aside }}
            </div>
        @endisset
    </div>
</section>
