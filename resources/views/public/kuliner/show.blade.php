@extends('layouts.app')

@section('title', $kuliner->nama_kuliner)
@section('description', Str::limit(strip_tags($kuliner->deskripsi), 160))

@section('content')
@php $menghangatkan = $kuliner->kategori_suhu === \App\Models\Kuliner::SUHU_MENGHANGATKAN; @endphp

<x-public.page-hero
 :title="$kuliner->nama_kuliner"
 :image="$kuliner->foto"
 :breadcrumb="[
 'Beranda' => route('home'),
 'Kuliner' => route('public.kuliner'),
 Str::limit($kuliner->nama_kuliner, 40) => null,
 ]"
/>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
 <a href="{{ route('public.kuliner') }}"
 class="mb-8 inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
 <i class="fas fa-arrow-left text-xs"></i>
 Kembali ke daftar kuliner
 </a>

 <div class="grid gap-10 lg:grid-cols-5">
 {{-- Kolom kiri --}}
 <aside class="space-y-5 lg:col-span-2">
 <x-public.media
 :src="$kuliner->foto"
 :alt="$kuliner->nama_kuliner"
 icon="fa-utensils"
 tone="amber"
 ratio="aspect-[4/3]"
 class="rounded-2xl border-4 border-brand-600 shadow-[0_10px_28px_rgba(37,99,235,0.22)]"
 />

 @if ($kuliner->alamat_lengkap)
 <div class="relative overflow-hidden rounded-2xl border border-slate-200">
 <div class="grid h-52 place-items-center bg-gradient-to-br from-slate-100 to-slate-200">
 <i class="fas fa-map-location-dot text-4xl text-slate-300"></i>
 </div>

 <a href="https://maps.google.com/?q={{ urlencode($kuliner->alamat_lengkap) }}"
 target="_blank"
 rel="noopener"
 class="absolute bottom-4 left-1/2 inline-flex -translate-x-1/2 items-center gap-2 whitespace-nowrap rounded-xl bg-brand-600 px-5 py-2.5 font-display text-sm font-semibold text-white shadow-lg transition hover:bg-brand-700">
 <i class="fas fa-location-dot text-xs"></i>
 Buka Maps
 </a>
 </div>
 @endif

 @if ($kuliner->kategori_suhu)
 <div @class([
 'flex items-start gap-3 rounded-2xl border p-5',
 'border-rose-200 bg-rose-50' => $menghangatkan,
 'border-sky-200 bg-sky-50' => ! $menghangatkan,
 ])>
 <i @class([
 'mt-0.5',
 'fas fa-fire text-rose-500' => $menghangatkan,
 'fas fa-snowflake text-sky-500' => ! $menghangatkan,
 ])></i>
 <div>
 <p @class([
 'font-display text-sm font-bold',
 'text-rose-900' => $menghangatkan,
 'text-sky-900' => ! $menghangatkan,
 ])>
 Kategori Suhu: {{ $kuliner->kategori_suhu }}
 </p>
 <p @class([
 'mt-1 text-xs leading-relaxed',
 'text-rose-800' => $menghangatkan,
 'text-sky-800' => ! $menghangatkan,
 ])>
 @if ($menghangatkan)
 Cocok saat cuaca dingin atau ketika dibutuhkan.
 @else
 Cocok saat cuaca panas atau ketika dibutuhkan.
 @endif
 </p>
 </div>
 </div>
 @endif
 </aside>

 {{-- Kolom kanan --}}
 <div class="lg:col-span-3">
 {{-- Statistik --}}
 <div class="mb-8 flex flex-wrap items-center gap-x-8 gap-y-3 border-b border-slate-200 pb-6 text-sm text-slate-500">
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-eye text-brand-500"></i>
 {{ number_format($kuliner->visitor_count ?? 0, 0, ',', '.') }} kali dibaca
 </span>
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-star text-accent-500"></i>
 Rating 4.5
 </span>
 </div>

 {{-- Kartu info --}}
 <div class="grid gap-4 sm:grid-cols-3">
 @foreach ([
 ['icon' => 'fa-location-dot', 'label' => 'Lokasi', 'value' => $kuliner->alamat_lengkap ?? '-'],
 ['icon' => 'fa-clock', 'label' => 'Jam Buka', 'value' => $kuliner->jam_operasional ?? '-'],
 ['icon' => 'fa-phone', 'label' => 'Kontak', 'value' => $kuliner->kontak ?? '-'],
 ] as $info)
 <div class="rounded-2xl border-2 border-navy-700 p-5 text-center">
 <p class="flex items-center justify-center gap-2 font-display text-sm font-bold text-navy-700">
 <i class="fas {{ $info['icon'] }} text-brand-600"></i>
 {{ $info['label'] }}
 </p>
 <p class="mt-2.5 text-xs leading-relaxed text-slate-600">{{ $info['value'] }}</p>
 </div>
 @endforeach
 </div>

 {{-- Harga --}}
 @if ($kuliner->harga)
 <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-navy-800 px-6 py-5 text-white">
 <div>
 <p class="text-[11px] uppercase tracking-widest text-white/60">Harga rata-rata</p>
 <p class="mt-1 font-display text-2xl font-extrabold">
 Rp{{ number_format($kuliner->harga, 0, ',', '.') }}
 </p>
 </div>
 <p class="max-w-xs text-xs leading-relaxed text-white/70">
 Harga dapat berubah sewaktu-waktu. Pastikan konfirmasi ke tempat sebelum datang.
 </p>
 </div>
 @endif

 {{-- Deskripsi --}}
 <h2 class="mt-10 font-display text-2xl font-extrabold text-brand-700">{{ $kuliner->nama_kuliner }}</h2>

 <div class="mt-4 text-[1.02rem] leading-[1.9] text-slate-700">
 {!! nl2br(e($kuliner->deskripsi)) !!}
 </div>
 </div>
 </div>

 {{-- Kuliner terkait --}}
 @php
 $terkait = \App\Models\Kuliner::aktif()
 ->where('id_kuliner', '!=', $kuliner->id_kuliner)
 ->inRandomOrder()
 ->take(3)
 ->get();
 @endphp

 @if ($terkait->isNotEmpty())
 <div class="mt-16">
 <h2 class="font-display text-xl font-extrabold text-slate-900">Kuliner Terkait Lainnya</h2>

 <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @foreach ($terkait as $item)
 <a href="{{ route('public.kuliner.show', $item) }}"
 class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]">
 <x-public.media
 :src="$item->foto"
 :alt="$item->nama_kuliner"
 icon="fa-utensils"
 tone="amber"
 ratio="h-48"
 class="transition duration-500 group-hover:scale-105"
 />

 <div class="bg-brand-600 p-5 text-white">
 <div class="flex items-start justify-between gap-3">
 <p class="font-display text-base font-bold">{{ $item->nama_kuliner }}</p>
 <span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold">
 <i class="fas fa-star text-accent-400 text-[10px]"></i>
 4.7
 </span>
 </div>

 <p class="mt-2.5 text-xs text-white/75">
 <i class="fas fa-location-dot"></i>
 {{ Str::limit($item->alamat_lengkap ?? 'Bandung', 35) }}
 </p>
 <p class="mt-1 text-xs text-white/75">
 <i class="far fa-clock"></i>
 {{ $item->jam_operasional ?? '-' }}
 </p>

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
