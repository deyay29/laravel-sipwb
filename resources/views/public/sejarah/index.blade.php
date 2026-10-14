@extends('layouts.app')

@section('title', 'Sejarah')
@section('description', 'Arsip sejarah dan cerita Kota Bandung.')

@section('content')
<x-public.page-hero
 title="Sejarah"
 subtitle="Arsip peristiwa yang membentuk Kota Bandung dari masa ke masa."
 :breadcrumb="['Beranda' => route('home'), 'Sejarah' => null]"
/>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
 <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
 @forelse ($sejarahs as $topik)
 <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
 <a href="{{ route('public.sejarah.show', $topik) }}" class="relative block overflow-hidden">
 <x-public.media
 :src="$topik->gambar_utama"
 alt="{{ $topik->judul_topik }}"
 icon="fa-landmark"
 tone="violet"
 ratio="h-48"
 class="w-full object-cover transition duration-500 group-hover:scale-105"
 />

 <span class="absolute left-4 top-4 rounded-full bg-slate-900/70 px-3 py-1 text-[11px] font-bold text-white backdrop-blur">
 {{ $topik->babs_count }} bab
 </span>
 </a>

 <div class="flex flex-1 flex-col p-6">
 <h2 class="font-display text-lg font-extrabold leading-snug text-slate-900">
 <a href="{{ route('public.sejarah.show', $topik) }}" class="transition group-hover:text-brand-700">
 {{ $topik->judul_topik }}
 </a>
 </h2>

 <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">
 {{ Str::limit($topik->ringkasan, 150) }}
 </p>

 <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
 <span class="text-xs text-slate-400">
 Diperbarui {{ $topik->created_at->format('d M Y') }}
 </span>

 <a href="{{ route('public.sejarah.show', $topik) }}"
 class="inline-flex items-center gap-1.5 rounded-full border border-brand-200 px-4 py-2 font-display text-xs font-bold text-brand-700 transition hover:bg-brand-50">
 Baca
 <i class="fas fa-arrow-right text-[10px]"></i>
 </a>
 </div>
 </div>
 </article>
 @empty
 <x-public.empty-state
 class="md:col-span-2 lg:col-span-3"
 title="Belum ada topik sejarah"
 icon="fa-landmark"
 />
 @endforelse
 </div>

 <x-public.pagination :paginator="$sejarahs" />
</section>
@endsection
