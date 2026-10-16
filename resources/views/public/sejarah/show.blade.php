@extends('layouts.app')

@section('title', $topik->judul_topik)
@section('description', Str::limit(strip_tags($topik->ringkasan ?? ''), 160))

@section('content')
<x-public.page-hero
 :title="$topik->judul_topik"
 :breadcrumb="[
 'Beranda' => route('home'),
 'Sejarah' => route('public.sejarah'),
 Str::limit($topik->judul_topik, 40) => null,
 ]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">
 <a href="{{ route('public.sejarah') }}"
 class="mb-8 inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
 <i class="fas fa-arrow-left text-xs"></i>
 Kembali ke daftar sejarah Bandung
 </a>

 <div class="grid gap-10 lg:grid-cols-5">
 {{-- Kolom kiri: galeri foto --}}
 <aside class="space-y-4 lg:col-span-2">
 <x-public.media
 :src="$topik->gambar_utama"
 :alt="$topik->judul_topik"
 icon="fa-landmark"
 tone="violet"
 ratio="aspect-[4/3]"
 class="rounded-2xl shadow-[0_4px_20px_rgba(15,23,42,0.10)]"
 />

 @if ($babs->isNotEmpty())
 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="font-display text-sm font-bold text-slate-900">
 <i class="fas fa-book-open mr-1.5 text-brand-600"></i>
 Daftar Bab
 </p>

 <ul class="mt-3 space-y-1.5">
 @foreach ($babs as $bab)
 <li>
 <a href="{{ route('public.sejarah.bab', [$topik, $bab->nomor_bab]) }}"
 class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-brand-50">
 <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-600 font-display text-xs font-bold text-white">
 {{ $bab->nomor_bab }}
 </span>
 <span class="text-sm text-slate-700 transition group-hover:text-brand-700">
 {{ Str::limit($bab->judul_bab, 40) }}
 </span>
 </a>
 </li>
 @endforeach
 </ul>
 </div>
 @endif

 <a href="{{ route('public.sejarah') }}"
 class="block rounded-2xl border-2 border-slate-900 px-5 py-3.5 text-center font-display text-sm font-bold text-slate-900 transition hover:bg-slate-900 hover:text-white">
 Lihat Topik Lainnya
 </a>
 </aside>

 {{-- Kolom kanan: deskripsi --}}
 <div class="lg:col-span-3">
 <h2 class="font-display text-2xl font-extrabold text-slate-900">Deskripsi Sejarah</h2>

 <div class="mt-4 text-[1.02rem] leading-[1.9] text-slate-700">
 @if ($topik->ringkasan)
 {!! nl2br(e($topik->ringkasan)) !!}
 @else
 <p>
 Tonggak Konferensi Asia Afrika merupakan salah satu peristiwa paling bersejarah yang
 menjadikan Kota Bandung dikenal di mata dunia. Konferensi ini berlangsung pada
 18-24 April 1955 di Gedung Merdeka dan dihadiri 29 negara dari Asia dan Afrika.
 </p>
 <p class="mt-4">
 Konferensi diselenggarakan untuk mempererat kerja sama antarnegara Asia
 dan Afrika, menolak segala bentuk kolonialisme, serta mendorong perdamaian dunia.
 </p>
 @endif
 </div>

 {{-- Cuplikan bab pertama --}}
 @if ($babs->isNotEmpty())
 <div class="mt-10 space-y-4">
 <h3 class="font-display text-xl font-extrabold text-slate-900">Baca per Bab</h3>

 @foreach ($babs->take(3) as $bab)
 <a href="{{ route('public.sejarah.bab', [$topik, $bab->nomor_bab]) }}"
 class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand-500 hover:shadow-[0_10px_26px_rgba(15,23,42,0.10)]">
 <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-navy-800 font-display text-sm font-bold text-white">
 {{ $bab->nomor_bab }}
 </span>

 <div class="min-w-0">
 <p class="font-display text-base font-bold text-slate-900 transition group-hover:text-brand-700">
 {{ $bab->judul_bab }}
 </p>
 <p class="mt-1.5 text-sm leading-relaxed text-slate-600">
 {{ Str::limit($bab->isi_bab, 130) }}
 </p>
 </div>
 </a>
 @endforeach
 </div>
 @endif
 </div>
 </div>

 {{-- Sejarah terkait --}}
 @php
 $terkait = \App\Models\Sejarah::where('id_sejarah', '!=', $topik->id_sejarah)
 ->inRandomOrder()
 ->take(3)
 ->get();
 @endphp

 @if ($terkait->isNotEmpty())
 <div class="mt-16">
 <h2 class="font-display text-xl font-extrabold text-slate-900">Sejarah Terkait Lainnya</h2>

 <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @foreach ($terkait as $item)
 <a href="{{ route('public.sejarah.show', $item) }}"
 class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]">
 <x-public.media
 :src="$item->gambar_utama"
 :alt="$item->judul_topik"
 icon="fa-landmark"
 tone="violet"
 ratio="h-44"
 class="transition duration-500 group-hover:scale-105"
 />

 <div class="bg-navy-700 p-5 text-white">
 <p class="font-display text-base font-bold">{{ Str::limit($item->judul_topik, 45) }}</p>
 <p class="mt-2 text-xs leading-relaxed text-white/75">{{ Str::limit($item->ringkasan, 90) }}</p>

 <span class="mt-4 inline-flex items-center gap-1.5 font-display text-xs font-bold">
 Lihat Detail <i class="fas fa-arrow-right text-[10px] transition"></i>
 </span>
 </div>
 </a>
 @endforeach
 </div>
 </div>
 @endif
</section>
@endsection
