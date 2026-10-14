@extends('layouts.app')

@section('title', 'Wisata')
@section('description', 'Daftar destinasi wisata Kota Bandung.')

@section('content')
<x-public.page-hero
 title="Wisata"
 subtitle="Destinasi ikonik, museum, dan alam raya yang ada di Kota Bandung."
 :breadcrumb="['Beranda' => route('home'), 'Wisata' => null]"
/>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
 <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
 @forelse ($wisatas as $wisata)
 <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
 <a href="{{ route('public.wisata.show', $wisata) }}" class="relative block overflow-hidden">
 <x-public.media
 :src="$wisata->gambar"
 alt="{{ $wisata->nama_wisata }}"
 icon="fa-mountain-sun"
 tone="emerald"
 ratio="h-48"
 class="w-full object-cover transition duration-500 group-hover:scale-105"
 />

 @if ($wisata->kategori)
 <span class="absolute left-4 top-4 rounded-full bg-slate-900/70 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white backdrop-blur">
 {{ $wisata->kategori }}
 </span>
 @endif

 @if ($wisata->quiz_tersedia)
 <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-accent-500 px-3 py-1 text-[11px] font-bold text-white">
 <i class="fas fa-circle-question text-[10px]"></i>
 Kuis
 </span>
 @endif
 </a>

 <div class="flex flex-1 flex-col p-6">
 <h2 class="font-display text-lg font-extrabold leading-snug text-slate-900">
 <a href="{{ route('public.wisata.show', $wisata) }}" class="transition group-hover:text-brand-700">
 {{ Str::limit($wisata->nama_wisata, 55) }}
 </a>
 </h2>

 <p class="mt-2 inline-flex items-center gap-1.5 text-xs text-slate-500">
 <i class="fas fa-location-dot text-[10px] text-brand-500"></i>
 {{ $wisata->lokasi ?? 'Kota Bandung' }}
 </p>

 <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">
 {{ Str::limit($wisata->deskripsi, 120) }}
 </p>

 <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
 <div>
 <p class="text-[11px] uppercase tracking-wide text-slate-400">Tiket masuk</p>
 <p class="font-display text-base font-extrabold text-navy-800">
 {{ $wisata->harga_tiket ? 'Rp'.number_format($wisata->harga_tiket, 0, ',', '.') : 'Gratis' }}
 </p>
 </div>

 <a href="{{ route('public.wisata.show', $wisata) }}"
 class="inline-flex items-center gap-1.5 rounded-full bg-brand-600 px-5 py-2 font-display text-xs font-bold text-white transition hover:bg-brand-700">
 Detail
 <i class="fas fa-arrow-right text-[10px]"></i>
 </a>
 </div>
 </div>
 </article>
 @empty
 <x-public.empty-state
 class="md:col-span-2 lg:col-span-3"
 title="Belum ada data wisata"
 description="Informasi wisata akan tampil di sini setelah tersedia."
 icon="fa-mountain-sun"
 />
 @endforelse
 </div>

 <x-public.pagination :paginator="$wisatas" />
</section>
@endsection
