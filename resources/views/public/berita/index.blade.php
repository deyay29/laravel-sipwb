@extends('layouts.app')

@section('title', 'Berita')
@section('description', 'Kabar dan informasi terkini Kota Bandung.')

@section('content')
<x-public.page-hero
 title="Berita"
 subtitle="Kabar terkini, pengumuman, dan liputan Kota Bandung."
 :breadcrumb="['Beranda' => route('home'), 'Berita' => null]"
/>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
 <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
 @forelse ($beritas as $berita)
 <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
 <a href="{{ route('public.berita.show', $berita) }}" class="relative block overflow-hidden">
 <x-public.media
 :src="$berita->gambar"
 alt="{{ $berita->judul }}"
 icon="fa-newspaper"
 tone="brand"
 ratio="h-48"
 class="w-full object-cover transition duration-500 group-hover:scale-105"
 />

 @if ($berita->kategori)
 <span class="absolute left-4 top-4 rounded-full bg-slate-900/70 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white backdrop-blur">
 {{ $berita->kategori }}
 </span>
 @endif
 </a>

 <div class="flex flex-1 flex-col p-6">
 <span class="text-xs text-slate-400">
 {{ $berita->tanggal?->format('d F Y') ?? '-' }}
 </span>

 <h2 class="mt-2 font-display text-lg font-extrabold leading-snug text-slate-900">
 <a href="{{ route('public.berita.show', $berita) }}" class="transition group-hover:text-brand-700">
 {{ Str::limit($berita->judul, 80) }}
 </a>
 </h2>

 <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">
 {{ Str::limit(strip_tags($berita->isi), 130) }}
 </p>

 <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-400">
 <div class="flex items-center gap-4">
 <span class="inline-flex items-center gap-1.5">
 <i class="fas fa-eye text-[10px]"></i>
 {{ number_format($berita->visitor_count, 0, ',', '.') }}
 </span>

 @if ($berita->reaksi)
 <span class="inline-flex items-center gap-1.5">
 <i class="fas fa-heart text-[10px] text-rose-400"></i>
 {{ number_format($berita->reaksi->like_count, 0, ',', '.') }}
 </span>
 @endif
 </div>

 @if ($berita->audio_url)
 <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-2.5 py-1 font-semibold text-violet-700">
 <i class="fas fa-volume-high text-[10px]"></i>
 Audio
 </span>
 @endif
 </div>
 </div>
 </article>
 @empty
 <x-public.empty-state
 class="md:col-span-2 lg:col-span-3"
 title="Belum ada berita"
 description="Nantikan pembaruan terbaru dari Kota Bandung."
 icon="fa-newspaper"
 />
 @endforelse
 </div>

 <x-public.pagination :paginator="$beritas" />
</section>
@endsection
