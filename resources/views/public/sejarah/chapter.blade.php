@extends('layouts.app')

@section('title', $bab->judul_bab)
@section('description', Str::limit(strip_tags($bab->isi_bab ?? ''), 160))

@section('content')
<x-public.page-hero
    :title="'Bab '.$bab->nomor_bab.' — '.$bab->judul_bab"
    :breadcrumb="[
        'Beranda' => route('home'),
        'Sejarah' => route('public.sejarah'),
        Str::limit($topik->judul_topik, 30) => route('public.sejarah.show', $topik),
        'Bab '.$bab->nomor_bab => null,
    ]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">
    <a href="{{ route('public.sejarah.show', $topik) }}"
       class="mb-8 inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
        <i class="fas fa-arrow-left text-xs"></i>
        Kembali ke {{ $topik->judul_topik }}
    </a>

    <div class="grid gap-10 lg:grid-cols-4">
        {{-- Daftar bab --}}
        <aside class="lg:col-span-1">
            <div class="sticky top-24 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <p class="border-b border-slate-100 bg-slate-50 px-5 py-3.5 font-display text-sm font-bold text-slate-800">
                    Daftar Bab
                </p>

                <ul>
                    @foreach ($topik->babs as $item)
                        <li class="border-b border-slate-100 last:border-b-0">
                            <a href="{{ route('public.sejarah.bab', [$topik, $item->nomor_bab]) }}"
                               @class([
                                   'flex items-start gap-3 px-5 py-3.5 text-sm transition',
                                   'bg-brand-600 font-semibold text-white' => $item->id_bab === $bab->id_bab,
                                   'text-slate-600 hover:bg-brand-50 hover:text-brand-700' => $item->id_bab !== $bab->id_bab,
                               ])>
                                <span @class([
                                    'grid h-7 w-7 shrink-0 place-items-center rounded-lg font-display text-[11px] font-bold',
                                    'bg-white/20 text-white' => $item->id_bab === $bab->id_bab,
                                    'bg-brand-50 text-brand-700' => $item->id_bab !== $bab->id_bab,
                                ])>
                                    {{ $item->nomor_bab }}
                                </span>

                                <span class="leading-snug">{{ $item->judul_bab }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Isi bab --}}
        <article class="lg:col-span-3">
            <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-4 py-1.5 font-display text-xs font-bold text-brand-700">
                <i class="fas fa-book-open text-[10px]"></i>
                Bab {{ $bab->nomor_bab }}
            </span>

            <h1 class="mt-4 font-display text-3xl font-extrabold leading-tight text-slate-900">
                {{ $bab->judul_bab }}
            </h1>

            @if ($bab->gambar)
                <x-public.media
                    :src="$bab->gambar"
                    :alt="$bab->judul_bab"
                    icon="fa-book-open"
                    tone="violet"
                    ratio="aspect-[21/9]"
                    class="mt-6 rounded-2xl"
                />
            @endif

            <div class="mt-6 text-[1.05rem] leading-[1.95] text-slate-700">
                {!! nl2br(e($bab->isi_bab)) !!}
            </div>

            {{-- Navigasi bab --}}
            @php
                $sebelumnya = $topik->babs->where('nomor_bab', '<', $bab->nomor_bab)->last();
                $selnjutnya = $topik->babs->where('nomor_bab', '>', $bab->nomor_bab)->first();
            @endphp

            <nav aria-label="Navigasi bab" class="mt-14 flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-6">
                @if ($sebelumnya)
                    <a href="{{ route('public.sejarah.bab', [$topik, $sebelumnya->nomor_bab]) }}"
                       class="group inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 transition hover:border-brand-500">
                        <i class="fas fa-chevron-left text-xs text-slate-400 transition group-hover:text-brand-600"></i>
                        <span class="text-left">
                            <span class="block text-[11px] text-slate-400">Bab sebelumnya</span>
                            <span class="block font-display text-sm font-bold text-slate-800">
                                {{ Str::limit($sebelumnya->judul_bab, 32) }}
                            </span>
                        </span>
                    </a>
                @else
                    <span></span>
                @endif

                @if ($selnjutnya)
                    <a href="{{ route('public.sejarah.bab', [$topik, $selnjutnya->nomor_bab]) }}"
                       class="group inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-right transition hover:border-brand-500">
                        <span>
                            <span class="block text-[11px] text-slate-400">Bab selanjutnya</span>
                            <span class="block font-display text-sm font-bold text-slate-800">
                                {{ Str::limit($selnjutnya->judul_bab, 32) }}
                            </span>
                        </span>
                        <i class="fas fa-chevron-right text-xs text-slate-400 transition group-hover:text-brand-600"></i>
                    </a>
                @endif
            </nav>
        </article>
    </div>
</section>
@endsection
