@extends('layouts.app')

@section('title', $berita->judul)
@section('description', Str::limit(strip_tags($berita->isi), 160))

@section('content')
{{-- Hero --}}
<x-public.page-hero
 :title="$berita->judul"
 :image="$berita->gambar"
 :breadcrumb="[
 'Beranda' => route('home'),
 'Berita' => route('public.berita'),
 Str::limit($berita->judul, 40) => null,
 ]"
/>

<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6">
<a href="{{ route('public.berita') }}"
   class="mb-8 inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
    <i class="fas fa-arrow-left text-xs"></i>
    Kembali ke daftar berita
</a>

 {{-- Meta --}}
 <div class="mb-8 flex flex-wrap items-center gap-x-6 gap-y-3 border-b border-slate-200 pb-6 text-sm text-slate-500">
 @if ($berita->kategori)
 <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3.5 py-1.5 font-display text-xs font-bold text-brand-700">
 <i class="fas fa-tag text-[10px]"></i>
 {{ $berita->kategori }}
 </span>
 @endif

 <span class="inline-flex items-center gap-2">
 <i class="fas fa-calendar text-brand-500"></i>
 {{ $berita->tanggal?->translatedFormat('d F Y') ?? '-' }}
 </span>

 <span class="inline-flex items-center gap-2">
 <i class="fas fa-eye text-brand-500"></i>
 {{ number_format($berita->visitor_count, 0, ',', '.') }} kali dibaca
 </span>

 @if ($berita->user)
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-user text-brand-500"></i>
 {{ $berita->user->name }}
 </span>
 @endif
 </div>

 {{-- Audio --}}
 @if ($berita->audio_url)
 <div class="mb-8 flex items-center gap-4 rounded-2xl border border-violet-200 bg-violet-50 p-5">
 <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-violet-600 text-white">
 <i class="fas fa-headphones"></i>
 </span>
 <div class="min-w-0 flex-1">
 <p class="font-display text-sm font-bold text-violet-900">Dengarkan Berita Ini</p>
 <audio controls preload="none" class="mt-2 w-full">
 <source src="{{ $berita->audio_url }}">
 Browser Anda tidak mendukung pemutar audio.
 </audio>
 </div>
 </div>
 @endif

    {{-- Gambar utama: lewat komponen media, jadi path hilang / file tidak
         ada otomatis diganti ikon - bukan gambar rusak. --}}
    @if ($berita->gambar)
        <x-public.media
            :src="$berita->gambar"
            :alt="$berita->judul"
            icon="fa-newspaper"
            tone="brand"
            ratio="aspect-[16/9]"
            class="mb-8 rounded-2xl shadow-[0_4px_20px_rgba(15,23,42,0.10)]"
        />
    @endif

 {{-- Isi --}}
 <article class="prose-none text-[1.05rem] leading-[1.9] text-slate-700">
 {!! nl2br(e($berita->isi)) !!}
 </article>

 {{-- Reaksi pembaca --}}
 <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
 <p class="font-display text-base font-extrabold text-slate-900">Bagaimana perasaan Anda?</p>
 <p class="mt-1 text-sm text-slate-500">Pilih satu reaksi untuk berita ini.</p>

 <div class="mt-5 flex flex-wrap items-center justify-center gap-3"
 data-reaksi-container
 data-url="{{ route('public.berita.react', $berita) }}"
 data-csrf="{{ csrf_token() }}">
 @foreach ([
 ['tipe' => 'like', 'icon' => 'fa-thumbs-up', 'label' => 'Suka', 'kolom' => 'like_count', 'tone' => 'text-brand-600 hover:bg-brand-50'],
 ['tipe' => 'applause', 'icon' => 'fa-hands-clapping', 'label' => 'Apresiasi', 'kolom' => 'applause_count', 'tone' => 'text-emerald-600 hover:bg-emerald-50'],
 ['tipe' => 'insightful', 'icon' => 'fa-lightbulb', 'label' => 'Mencerahkan', 'kolom' => 'insightful_count', 'tone' => 'text-amber-600 hover:bg-amber-50'],
 ] as $reaction)
 <button type="button"
 data-tipe="{{ $reaction['tipe'] }}"
 class="inline-flex min-w-[7rem] flex-col items-center gap-1.5 rounded-xl border border-slate-200 px-5 py-3 transition disabled:cursor-not-allowed disabled:opacity-60 {{ $reaction['tone'] }}">
 <i class="fas {{ $reaction['icon'] }}"></i>
 <span class="font-display text-xs font-semibold text-slate-600">{{ $reaction['label'] }}</span>
 <span data-kolom="{{ $reaction['kolom'] }}" class="font-display text-base font-extrabold text-slate-800">
 {{ number_format($reaksi->{$reaction['kolom']}, 0, ',', '.') }}
 </span>
 </button>
 @endforeach
 </div>
 </div>

 {{-- Berita terkait --}}
 @php
 $terkait = \App\Models\Berita::where('id_berita', '!=', $berita->id_berita)
 ->when($berita->kategori, fn ($q) => $q->where('kategori', $berita->kategori))
 ->latest('tanggal')
 ->take(3)
 ->get();

 if ($terkait->count() < 3) {
 $terkait = \App\Models\Berita::where('id_berita', '!=', $berita->id_berita)
 ->latest('tanggal')
 ->take(3)
 ->get();
 }
 @endphp

 @if ($terkait->isNotEmpty())
 <div class="mt-14">
 <h2 class="font-display text-xl font-extrabold text-slate-900">Berita Terkait</h2>

 <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @foreach ($terkait as $item)
 <a href="{{ route('public.berita.show', $item) }}"
 class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
 <x-public.media
 :src="$item->gambar"
 alt="{{ $item->judul }}"
 icon="fa-newspaper"
 tone="brand"
 ratio="h-48"
 class="w-full object-cover transition duration-500 group-hover:scale-105"
 />

 <div class="p-5">
 <p class="text-[11px] uppercase tracking-wide text-slate-400">
 {{ $item->tanggal?->format('d M Y') ?? '-' }}
 </p>
 <p class="mt-1 font-display text-sm font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">
 {{ Str::limit($item->judul, 70) }}
 </p>
 </div>
 </a>
 @endforeach
 </div>
 </div>
 @endif

</section>
@endsection
