{{--
    Slider / carousel gambar.

    Dipakai di halaman detail (galeri Braga, dll) saat sebuah objek punya
    lebih dari satu foto. Navigasi memakai tombol panah + titik indikator,
    tanpa library eksternal. Bila hanya ada satu gambar, komponen ini
    merender gambar tunggal tanpa kontrol.

    Data gambar dikirim sebagai array of ['src' => ..., 'alt' => ..., 'caption' => ...]
    supaya komponen tidak perlu tahu asal datanya.

    @param array $images   daftar gambar: [['src','alt','caption'], ...]
    @param string $ratio   class tinggi (mis. aspect-[4/3])
    @param string $icon    ikon fallback saat gambar tidak ada
    @param string $tone    warna fallback ikon
--}}
@props([
    'images' => [],
    'ratio' => 'aspect-[4/3]',
    'icon' => 'fa-image',
    'tone' => 'slate',
    'class' => '',
])

@php
    // Buang entri tanpa gambar supaya slider tidak/show slide kosong.
    $slides = array_values(array_filter(
        $images ?? [],
        fn ($img) => ! empty($img['src'])
    ));

    $total = count($slides);
    $single = $total <= 1;
@endphp

@if ($total > 0)
    <div class="relative overflow-hidden rounded-2xl {{ $ratio }} {{ $class }}"
         data-slider
         aria-roledescription="carousel">

        {{-- Slide --}}
        @foreach ($slides as $i => $img)
            <div @class([
                'absolute inset-0 transition-opacity duration-500',
                'opacity-100' => $i === 0,
                'opacity-0 pointer-events-none' => $i !== 0,
            ]) data-slider-slide="{{ $i }}">
                <x-public.media
                    :src="$img['src'] ?? null"
                    :alt="$img['alt'] ?? ''"
                    :icon="$icon"
                    :tone="$tone"
                    ratio="h-full w-full"
                />
            </div>
        @endforeach

        @unless ($single)
            {{-- Tombol panah --}}
            <button type="button"
                    data-slider-prev
                    aria-label="Gambar sebelumnya"
                    class="absolute left-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-navy-900 shadow-lg transition hover:bg-white">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>

            <button type="button"
                    data-slider-next
                    aria-label="Gambar berikutnya"
                    class="absolute right-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-navy-900 shadow-lg transition hover:bg-white">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>

            {{-- Titik indikator --}}
            <div class="absolute inset-x-0 bottom-3 z-10 flex justify-center gap-2">
                @foreach ($slides as $i => $img)
                    <button type="button"
                            @class([
                                'h-2 rounded-full transition-all',
                                'w-6 bg-white' => $i === 0,
                                'w-2 bg-white/50 hover:bg-white/80' => $i !== 0,
                            ])
                            data-slider-dot="{{ $i }}"
                            aria-label="Ke gambar {{ $i + 1 }}"></button>
                @endforeach
            </div>
        @endunless
    </div>
@endif