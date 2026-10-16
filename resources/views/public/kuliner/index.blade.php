@extends('layouts.app')

@section('title', 'Kuliner')
@section('description', 'Daftar kuliner khas Kota Bandung beserta kategori suhunya.')

@section('content')
@php
    use App\Models\Kuliner;

    /*
     * Filter disimpan sebagai daftar (list), bukan associative array.
     * Key `null` pada associative array otomatis berubah jadi string kosong "",
     * sehingga perbandingan `is_null()` selalu bernilai false.
     */
    $filters = [
        ['value' => null, 'label' => 'Semua', 'icon' => null],
        ['value' => Kuliner::SUHU_MENGHANGATKAN, 'label' => 'Menghangatkan', 'icon' => 'fa-fire'],
        ['value' => Kuliner::SUHU_MENYEGARKAN, 'label' => 'Menyegarkan', 'icon' => 'fa-snowflake'],
    ];
@endphp

<x-public.page-hero
    title="Kuliner"
    subtitle="Temukan kuliner khas Bandung yang cocok dengan cuaca hari ini."
    :breadcrumb="['Beranda' => route('home'), 'Kuliner' => null]"
>
    {{-- Kartu cuaca, penempatannya sama dengan di hero beranda --}}
    <x-slot:aside>
        <x-public.weather-card
            variant="glass"
            :weather="$weather"
            :rekomendasi="$suhuRekomendasi"
        />
    </x-slot:aside>
</x-public.page-hero>

<section class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6">
    {{-- ===== Filter kategori suhu ===== --}}
    <div class="mb-10 flex flex-wrap items-center gap-3">
        <span class="font-display text-sm font-semibold text-slate-600">Saring menurut suhu:</span>

        @foreach ($filters as $filter)
            @php
                $value = $filter['value'];

                $isActive = $suhuAktif ? $suhuDipilih === $value : $value === null;

                // Tandai pilihan yang cocok dengan cuaca saat ini.
                $isRekomendasi = $value !== null && $value === $suhuRekomendasi;
            @endphp

            <a href="{{ $value ? route('public.kuliner', ['suhu' => $value]) : route('public.kuliner') }}"
               @class([
                   'inline-flex items-center gap-2 rounded-full px-5 py-2.5 font-display text-sm font-semibold transition',
                   'bg-brand-600 text-white shadow-[0_4px_14px_rgba(37,65,160,0.3)]' => $isActive,
                   'border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-700' => ! $isActive,
               ])>
                @if ($filter['icon'])
                    <i class="fas {{ $filter['icon'] }} text-[11px]"></i>
                @endif

                {{ $filter['label'] }}

                @if ($isRekomendasi)
                    <span @class([
                        'rounded-full px-2 py-0.5 text-[10px] font-bold uppercase',
                        'bg-white/20 text-white' => $isActive,
                        'bg-brand-100 text-brand-700' => ! $isActive,
                    ])>
                        Hari ini
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- ===== Daftar kuliner ===== --}}
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($kuliners as $kuliner)
            @php
                $menghangatkan = $kuliner->kategori_suhu === Kuliner::SUHU_MENGHANGATKAN;
            @endphp

            <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]">
                <a href="{{ route('public.kuliner.show', $kuliner) }}" class="relative block overflow-hidden">
                    <x-public.media
                        :src="$kuliner->foto"
                        :alt="$kuliner->nama_kuliner"
                        icon="fa-utensils"
                        tone="amber"
                        ratio="aspect-[4/3]"
                        class="transition duration-500 group-hover:scale-105"
                    />

                    @if ($kuliner->kategori_suhu)
                        <span @class([
                            'absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full border border-white/60 px-3 py-1 text-[11px] font-bold text-white backdrop-blur',
                            'bg-rose-600/85' => $menghangatkan,
                            'bg-sky-600/85' => ! $menghangatkan,
                        ])>
                            <i class="fas {{ $menghangatkan ? 'fa-fire' : 'fa-snowflake' }} text-[10px]"></i>
                            {{ $kuliner->kategori_suhu }}
                        </span>
                    @endif
                </a>

                <div class="flex flex-1 flex-col p-6">
                    <h2 class="line-clamp-2 font-display text-lg font-extrabold leading-snug text-slate-900">
                        <a href="{{ route('public.kuliner.show', $kuliner) }}" class="transition group-hover:text-brand-700">
                            {{ $kuliner->nama_kuliner }}
                        </a>
                    </h2>

                    <p class="mt-2 inline-flex items-start gap-1.5 text-xs text-slate-500">
                        <i class="fas fa-location-dot mt-0.5 text-[10px] text-brand-500"></i>
                        <span class="line-clamp-1">{{ $kuliner->alamat_lengkap ?? 'Bandung' }}</span>
                    </p>

                    <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-600">
                        {{ Str::limit($kuliner->deskripsi, 120) }}
                    </p>

                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <div class="min-w-0">
                            <p class="text-[11px] uppercase tracking-wide text-slate-400">Harga</p>
                            <p class="mt-0.5 font-display text-sm font-bold text-navy-800">
                                {{ $kuliner->harga ? 'Rp'.number_format($kuliner->harga, 0, ',', '.') : '-' }}
                            </p>
                        </div>

                        <p class="shrink-0 text-right">
                            <span class="block text-[11px] uppercase tracking-wide text-slate-400">Buka</span>
                            <span class="mt-0.5 block font-semibold text-slate-700">
                                {{ $kuliner->jam_operasional ?? '-' }}
                            </span>
                        </p>
                    </div>
                </div>
            </article>
        @empty
            <x-public.empty-state
                class="md:col-span-2 lg:col-span-3"
                title="Belum ada kuliner"
                description="Tidak ada kuliner yang cocok dengan filter ini."
                icon="fa-utensils"
            />
        @endforelse
    </div>

    <x-public.pagination :paginator="$kuliners" />
</section>
@endsection
