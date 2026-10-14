{{--
    Judul section + tombol aksi.

    Dipakai semua section beranda supaya jarak, ukuran judul, dan posisi
    tombol selalu identik (serasi).

    @param string      $title
    @param string|null $highlight  bagian yang diberi warna (mis. "(Berdasarkan Suhu Hangat)")
    @param string|null $description
    @param string|null $action
    @param string      $actionLabel
    @param string      $tone       light | dark  (menyesuaikan latar section)
--}}
@props([
    'title' => '',
    'highlight' => null,
    'description' => null,
    'action' => null,
    'actionLabel' => 'Lihat Semua',
    'tone' => 'light',
])

<div {{ $attributes->merge(['class' => 'mb-7 flex flex-wrap items-end justify-between gap-4']) }}>
    <div>
        <h2 @class([
            'font-display text-xl font-extrabold',
            'text-slate-900' => $tone === 'light',
            'text-white' => $tone === 'dark',
        ])>
            {{ $title }}

            @if ($highlight)
                <span class="font-semibold {{ $tone === 'dark' ? 'text-accent-400' : 'text-brand-700' }}">{{ $highlight }}</span>
            @endif
        </h2>

        @if ($description)
            <p @class([
                'mt-1 text-sm',
                'text-slate-500' => $tone === 'light',
                'text-slate-300' => $tone === 'dark',
            ])>
                {{ $description }}
            </p>
        @endif
    </div>

    @if ($action)
        <a href="{{ $action }}"
           @class([
               'inline-flex items-center gap-2 rounded-full px-6 py-3 font-display text-sm font-semibold transition',
               'bg-brand-600 text-white shadow-[0_6px_18px_rgba(37,99,235,0.3)] hover:bg-brand-700' => $tone === 'light',
               'border border-white/30 bg-white/10 text-white backdrop-blur hover:bg-white/20' => $tone === 'dark',
           ])>
            {{ $actionLabel }}
            <i class="fas fa-arrow-right text-xs"></i>
        </a>
    @endif
</div>
