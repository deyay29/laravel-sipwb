{{--
    Gambar dengan fallback ikon.

    Dipakai di seluruh halaman publik & admin. Kalau path gambar kosong atau
    filenya memang tidak ada di disk, komponen ini otomatis menampilkan ikon
    bergradasi sebagai gantinya, sehingga tidak ada gambar rusak (broken image).

    @param string|null $src    path relatif dari database (mis. images/beranda.png)
    @param string      $alt
    @param string      $icon   ikon FontAwesome sebagai pengganti
    @param string      $tone   brand | slate | navy | amber | rose | emerald
    @param string      $ratio  class tinggi, mis. h-48 / aspect-[16/9]
--}}
@props([
    'src' => null,
    'alt' => '',
    'icon' => 'fa-image',
    'tone' => 'slate',
    'ratio' => 'h-48',
    'class' => '',
])

@php
    use App\Support\Media;

    $url = Media::url($src);

    $tones = [
        'brand' => 'from-brand-100 via-brand-50 to-white text-brand-400',
        'slate' => 'from-slate-100 via-slate-50 to-white text-slate-300',
        'navy' => 'from-navy-700 via-navy-800 to-navy-900 text-navy-400',
        'amber' => 'from-amber-100 via-amber-50 to-white text-amber-400',
        'rose' => 'from-rose-100 via-rose-50 to-white text-rose-400',
        'emerald' => 'from-emerald-100 via-emerald-50 to-white text-emerald-400',
        'violet' => 'from-violet-100 via-violet-50 to-white text-violet-400',
    ];

    $toneClass = $tones[$tone] ?? $tones['slate'];
@endphp

@if ($url)
    <img
        src="{{ $url }}"
        alt="{{ $alt }}"
        loading="lazy"
        decoding="async"
        {{ $attributes->merge(['class' => 'w-full object-cover '.$ratio.' '.$class]) }}
    >
@else
    <div
        role="img"
        aria-label="{{ $alt !== '' ? $alt : 'Gambar tidak tersedia' }}"
        {{ $attributes->merge([
            'class' => 'grid w-full place-items-center bg-gradient-to-br '.$toneClass.' '.$ratio.' '.$class,
        ]) }}
    >
        <i class="fas {{ $icon }} text-4xl"></i>
    </div>
@endif
