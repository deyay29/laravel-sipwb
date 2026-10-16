@extends('layouts.app')

@section('title', $wisata->nama_wisata)
@section('description', Str::limit(strip_tags($wisata->deskripsi), 160))

@section('content')
@php
    /*
     * Foto untuk slider: gabungkan foto utama wisata dengan foto galeri
     * kategori "Wisata". Kalau hanya ada satu, komponen merender gambar
     * tunggal tanpa tombol panah.
     */
    $fotoSlider = collect([
        ['src' => $wisata->gambar, 'alt' => $wisata->nama_wisata],
    ])->merge(
        App\Models\Galeri::query()
            ->where('kategori_objek', 'like', '%Wisata%')
            ->get()
            ->map(fn ($g) => ['src' => $g->file_gambar, 'alt' => $g->judul])
    )->values()->all();

    // Peta: dari link/koordinat bila ada, fallback ke alamat teks.
    $petaUrl = App\Support\MapEmbed::toEmbedUrl($wisata->lokasi);
@endphp

<x-public.page-hero
 :title="$wisata->nama_wisata"
 :breadcrumb="[
 'Beranda' => route('home'),
 'Wisata' => route('public.wisata'),
 Str::limit($wisata->nama_wisata, 40) => null,
 ]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">
 <a href="{{ route('public.wisata') }}"
 class="mb-8 inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
 <i class="fas fa-arrow-left text-xs"></i>
 Kembali ke daftar wisata
 </a>

 <div class="grid gap-10 lg:grid-cols-5">
 {{-- Kolom kiri: gambar + info ringkas --}}
 <aside class="space-y-5 lg:col-span-2">
 {{-- Slider foto: otomatis jadi navigasi panah bila gambarnya banyak --}}
 <x-public.image-slider
 :images="$fotoSlider"
 icon="fa-mountain-sun"
 tone="emerald"
 ratio="aspect-[4/3]"
 class="shadow-[0_4px_20px_rgba(15,23,42,0.10)]"
 />

 <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="flex items-center gap-2 font-display text-xs font-bold uppercase tracking-wide text-slate-400">
 <i class="fas fa-location-dot text-brand-500"></i>
 Lokasi
 </p>
 <p class="mt-2 text-sm leading-relaxed text-slate-700">
 {{ $wisata->lokasi ?? 'Data lokasi belum tersedia.' }}
 </p>
 </div>

 {{-- Peta: iframe embed resmi Google Maps, tanpa API key. --}}
 @if ($petaUrl)
 <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
 <iframe
 src="{{ $petaUrl }}"
 title="Peta lokasi {{ $wisata->nama_wisata }}"
 class="h-72 w-full border-0"
 loading="lazy"
 referrerpolicy="no-referrer-when-downgrade"
 allowfullscreen></iframe>
 </div>
 @endif

 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="flex items-center gap-2 font-display text-xs font-bold uppercase tracking-wide text-slate-400">
 <i class="fas fa-ticket text-brand-500"></i>
 Harga Tiket
 </p>

 @if ($wisata->harga_tiket)
 <p class="mt-2 font-display text-2xl font-extrabold text-navy-800">
 Rp{{ number_format($wisata->harga_tiket, 0, ',', '.') }}
 </p>
 @else
 <ul class="mt-2 space-y-1 text-sm text-slate-600">
 <li class="flex justify-between gap-4">
 <span>Dewasa</span><span class="font-semibold">Rp5.000</span>
 </li>
 <li class="flex justify-between gap-4">
 <span>Anak-anak</span><span class="font-semibold">Rp3.000</span>
 </li>
 <li class="flex justify-between gap-4">
 <span>WNA</span><span class="font-semibold">Rp10.000</span>
 </li>
 </ul>
 @endif
 </div>

 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="flex items-center gap-2 font-display text-xs font-bold uppercase tracking-wide text-slate-400">
 <i class="fas fa-clock text-brand-500"></i>
 Jam Operasional
 </p>
 <p class="mt-2 text-sm text-slate-700">
 {{ $wisata->jam_operasional ?? 'Belum ada data jam operasional.' }}
 </p>
 </div>

 @if ($wisata->kategori)
 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="flex items-center gap-2 font-display text-xs font-bold uppercase tracking-wide text-slate-400">
 <i class="fas fa-tag text-brand-500"></i>
 Kategori
 </p>
 <p class="mt-2 text-sm text-slate-700">{{ $wisata->kategori }}</p>
 </div>
 @endif
 </div>

 <div class="rounded-2xl border border-slate-200 bg-white p-5">
 <p class="flex items-center gap-2 font-display text-xs font-bold uppercase tracking-wide text-slate-400">
 <i class="fas fa-calculator text-brand-500"></i>
 Smart Budget
 </p>
 <p class="mt-2 text-sm leading-relaxed text-slate-700">
 Lihat estimasi biaya untuk datang ke {{ $wisata->nama_wisata }}.
 </p>
 <a href="#smart-budget"
 class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy-900 px-5 py-2.5 font-display text-sm font-semibold text-white transition hover:bg-navy-800">
 Lihat Estimasi Biaya
 <i class="fas fa-arrow-down text-xs"></i>
 </a>
 </div>

 @if ($wisata->quiz_tersedia)
 <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5">
 <i class="fas fa-circle-question mt-0.5 text-amber-500"></i>
 <div>
 <p class="font-display text-sm font-bold text-amber-900">Kuis tersedia</p>
 <p class="mt-1 text-xs leading-relaxed text-amber-800">
 Destinasi ini punya kuis interaktif untuk menguji pengetahuan pengunjung.
 </p>
 </div>
 </div>
 @endif
 </aside>

 {{-- Kolom kanan: deskripsi --}}
 <div class="lg:col-span-3">
 <h2 class="font-display text-2xl font-extrabold text-slate-900">Tentang {{ $wisata->nama_wisata }}</h2>

 <div class="mt-4 text-[1.02rem] leading-[1.9] text-slate-700">
 {!! nl2br(e($wisata->deskripsi)) !!}
 </div>

 <div class="mt-10 grid gap-5 md:grid-cols-2">
 <div class="rounded-2xl border border-slate-200 bg-white p-6">
 <h3 class="flex items-center gap-2 font-display text-base font-extrabold text-slate-900">
 <i class="fas fa-circle-check text-brand-600"></i>
 Fasilitas
 </h3>
 <ul class="mt-4 space-y-2 text-sm text-slate-600">
 @foreach (['Area parkir', 'Taman dan ruang terbuka', 'Ruang pameran', 'Toilet', 'Pusat informasi', 'Spot foto'] as $fasilitas)
 <li class="flex items-center gap-2">
 <i class="fas fa-check text-[10px] text-emerald-500"></i>
 {{ $fasilitas }}
 </li>
 @endforeach
 </ul>
 </div>

 <div class="rounded-2xl bg-brand-600 p-6 text-white">
 <h3 class="flex items-center gap-2 font-display text-base font-extrabold">
 <i class="fas fa-lightbulb"></i>
 Tips Berkunjung
 </h3>
 <ul class="mt-4 list-inside list-disc space-y-2 text-sm text-white/90">
 @foreach ([
 'Datang pada pagi atau siang hari agar lebih nyaman.',
 'Gunakan pakaian yang nyaman karena ada area luar ruangan.',
 'Perhatikan aturan area yang boleh dimasuki pengunjung.',
 "Cek jadwal operasional museum sebelum datang.",
 'Gunakan transportasi umum atau datang lebih awal.',
 ] as $tip)
 <li>{{ $tip }}</li>
 @endforeach
 </ul>
 </div>
 </div>
 </div>
 </div>

 {{-- Wisata terkait --}}
 @php
 $terkait = \App\Models\Wisata::where('id_wisata', '!=', $wisata->id_wisata)
 ->inRandomOrder()
 ->take(3)
 ->get();
 @endphp

 @if ($terkait->isNotEmpty())
 <div class="mt-16">
 <h2 class="font-display text-xl font-extrabold text-slate-900">Wisata Terkait Lainnya</h2>

 <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @foreach ($terkait as $item)
 <a href="{{ route('public.wisata.show', $item) }}"
 class="group overflow-hidden rounded-2xl bg-brand-600 text-white shadow-[0_4px_18px_rgba(37,65,160,0.25)] transition">
 <x-public.media
 :src="$item->gambar"
 :alt="$item->nama_wisata"
 icon="fa-mountain-sun"
 tone="emerald"
 ratio="h-44"
 class="transition duration-500 group-hover:scale-105"
 />

 <div class="p-5">
 <p class="font-display text-base font-extrabold">{{ $item->nama_wisata }}</p>
 <p class="mt-2 flex-1 text-sm leading-relaxed text-white/85">
 {{ Str::limit($item->deskripsi, 100) }}
 </p>
 <span class="mt-4 inline-flex items-center gap-1.5 font-display text-xs font-bold">
 Selengkapnya <i class="fas fa-arrow-right text-[10px] transition"></i>
 </span>
 </div>
 </a>
 @endforeach
 </div>
 </div>
 @endif

 {{-- Smart Budget: disematkan langsung di halaman ini, jadi tombol
      "Lihat Estimasi Biaya" hanya menggulir ke bawah (anchor), bukan
      membuka halaman /smart-budget. --}}
 <x-public.smart-budget-section :pakets="$pakets ?? []" />
</section>
@endsection
