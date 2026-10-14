{{--
    Widget cuaca + rekomendasi suhu.

    Dipakai di beranda (kartu kecil transparan di atas hero) dan di halaman
    kuliner (kartu lebar dengan tombol filter), sehingga logika maupun
    tampilannya tidak diduplikasi.

    @param array|null  $weather    hasil WeatherService::current()
    @param string      $rekomendasi  Menghangatkan | Menyegarkan
    @param string      $variant    glass | card | inline
    @param string|null $action     URL tombol aksi (opsional)
    @param string      $actionLabel
--}}
@props([
    'weather' => null,
    'rekomendasi' => 'Menghangatkan',
    'variant' => 'card',
    'action' => null,
    'actionLabel' => 'Lihat yang direkomendasikan',
])

@php
    use App\Models\Kuliner;
    use App\Services\WeatherService;

    $icon = WeatherService::icon($weather);
    $suhu = $weather['temp'] ?? null;
    $condition = $weather['condition'] ?? 'Data cuaca tidak tersedia';

    // Nama wilayah berasal dari Geocoding API (mis. "Kota Bandung").
    $region = $weather['region'] ?? 'Bandung';

    // Label badge mengikuti rekomendasi kategori (Cuaca dingin / Cuaca panas).
    $menyegarkan = $rekomendasi === Kuliner::SUHU_MENYEGARKAN;

    $humidity = $weather['humidity'] ?? null;
    $wind = $weather['windspeed'] ?? null;

    // Badge suhu: warna, ikon, dan label semua mengikuti nilai temperatur.
    $tone = WeatherService::temperatureTone($suhu);

    // Ikon pada kartu ikut menyesuaikan tonebadge.
    $iconTone = match (true) {
        $suhu === null => 'bg-white/15 text-white',
        $suhu <= 24 => 'bg-sky-500/25 text-sky-100',
        $suhu <= 30 => 'bg-amber-500/25 text-amber-100',
        default => 'bg-orange-500/30 text-orange-100',
    };

    /*
     * glass : transparan (efek kaca), sama seperti kartu cuaca di hero
     *         beranda. Selalu dipakai di atas hero yang warnanya gelap.
     * card  : kartu putih + bayangan
     * inline: baris ringkas untuk disisipkan di dalam seksi lain
     */
    $shell = match ($variant) {
        'glass' => 'border border-white/25 bg-white/10 backdrop-blur-xl',
        'inline' => 'border border-slate-200 bg-white',
        default => 'border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)]',
    };

    $titleColor = $variant === 'glass' ? 'text-white' : 'text-slate-900';
    $mutedColor = $variant === 'glass' ? 'text-white/75' : 'text-slate-500';
    $iconWrap = $variant === 'glass'
        ? $iconTone
        : 'bg-slate-100 text-slate-600';

    // Varian inline dibuat rapat: ikon kecil + teks satu baris.
    $isInline = $variant === 'inline';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl '.$shell]) }}>
    <div @class([
        'flex flex-wrap items-center justify-between gap-5',
        'gap-4 px-4 py-3' => $isInline,
        'p-5 sm:p-6' => ! $isInline,
    ])>
        <div class="flex items-center gap-4">
            <span @class([
                'grid shrink-0 place-items-center',
                'h-14 w-14 rounded-2xl text-2xl' => ! $isInline,
                'h-9 w-9 rounded-xl text-sm' => $isInline,
                $iconWrap,
            ])>
                <i class="fas {{ $icon }}"></i>
            </span>

            <div>
                <p @class([
                    'font-display font-extrabold leading-none',
                    'text-3xl' => ! $isInline,
                    'text-lg' => $isInline,
                    $titleColor,
                ])>
                    {{ $suhu !== null ? $suhu.'°C' : '--°C' }}
                </p>
                <p @class([
                    $mutedColor,
                    'mt-1.5 text-sm' => ! $isInline,
                    'mt-0.5 text-xs' => $isInline,
                ])>
                    Cuaca di {{ $region }} &mdash; {{ $condition }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{--
                Label: "Cuaca dingin" / "Cuaca panas" (sesuai rekomendasi).
                Background + ikon: mengikuti nilai suhu sebenarnya
                (biru < 25°C, sian 16-24°C, amber 25-30°C, oranye > 30°C).
            --}}
            <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 font-display text-sm font-bold {{ $tone['badge'] }}">
                <i class="fas {{ $tone['icon'] }} text-[11px]"></i>
                {{ $menyegarkan ? 'Cuaca panas' : 'Cuaca dingin' }}
            </span>

            @if ($action && ! $isInline)
                <a href="{{ $action }}"
                   class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2 font-display text-sm font-semibold text-white transition hover:bg-brand-700">
                    {{ $actionLabel }}
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- Detail cuaca: kelembapan + kecepatan angin --}}
    @if (! $isInline && ($humidity !== null || ! empty($wind)))
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-t {{ $variant === 'glass' ? 'border-white/15' : 'border-slate-100' }} px-5 py-3 text-xs {{ $mutedColor }} sm:px-6">
            @if ($humidity !== null)
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-droplet"></i>
                    {{ $humidity }}% kelembapan
                </span>
            @endif

            @if (! empty($wind))
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-wind"></i>
                    {{ $wind }} km/jam
                </span>
            @endif

            <span class="inline-flex items-center gap-2">
                <i class="fas fa-location-dot"></i>
                {{ $region }}
            </span>
        </div>
    @endif
</div>
