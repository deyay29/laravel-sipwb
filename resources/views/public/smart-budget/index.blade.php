@extends('layouts.app')

@section('title', 'Smart Budget')
@section('description', 'Contoh paket anggaran perjalanan di Kota Bandung.')

@section('content')
<x-public.page-hero
 title="Smart Budget"
 subtitle="Perkiraan biaya perjalanan di Kota Bandung, disusun agar mudah dibaca."
 :breadcrumb="['Beranda' => route('home'), 'Smart Budget' => null]"
/>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
 @if ($pakets->isEmpty())
 <x-public.empty-state
 title="Belum ada paket anggaran"
 icon="fa-wallet"
 />
 @else
 <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
 @foreach ($pakets as $paket)
 @php
 $rincian = collect($paket->rincian_biaya ?? []);
 $terbesar = $rincian->sortByDesc('biaya')->first();
 @endphp

 <article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
 <x-public.media
 :src="$paket->foto"
 alt="{{ $paket->nama_paket }}"
 icon="fa-wallet"
 tone="navy"
 ratio="h-48"
 class="w-full object-cover"
 />

 <div class="flex flex-1 flex-col p-6">
 <span @class([
 'mb-3 self-start rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide',
 'bg-emerald-50 text-emerald-700' => $paket->kategori === 'Hemat',
 'bg-sky-50 text-sky-700' => $paket->kategori === 'Keluarga',
 'bg-violet-50 text-violet-700' => ! in_array($paket->kategori, ['Hemat', 'Keluarga'], true),
 ])>
 {{ $paket->kategori ?? 'Umum' }}
 </span>

 <h2 class="font-display text-lg font-extrabold text-slate-900">
 {{ $paket->nama_paket }}
 </h2>

 @if ($paket->deskripsi)
 <p class="mt-2 text-sm leading-relaxed text-slate-600">
 {{ $paket->deskripsi }}
 </p>
 @endif

 @if ($rincian->isNotEmpty())
 <ul class="mt-5 flex-1 space-y-2 border-t border-slate-100 pt-4">
 @foreach ($rincian as $item)
 <li class="flex items-center justify-between gap-3 text-sm">
 <span class="text-slate-600">{{ $item['item'] ?? '-' }}</span>
 <span class="shrink-0 font-semibold text-slate-800">
 Rp{{ number_format($item['biaya'] ?? 0, 0, ',', '.') }}
 </span>
 </li>
 @endforeach
 </ul>
 @endif

 <div class="mt-5 rounded-xl bg-navy-800 px-5 py-4 text-white">
 <p class="text-[11px] uppercase tracking-widest text-white/60">Total estimasi</p>
 <p class="mt-0.5 font-display text-2xl font-extrabold">
 Rp{{ number_format($paket->total_estimasi, 0, ',', '.') }}
 </p>

 @if ($terbesar)
 <p class="mt-1 text-xs text-white/60">
 Pos terbesar: {{ $terbesar['item'] ?? '-' }}
 </p>
 @endif
 </div>
 </div>
 </article>
 @endforeach
 </div>

 <p class="mt-10 text-center text-xs text-slate-400">
 Angka bersifat perkiraan dan dapat berbeda mengikuti harga saat perjalanan.
 </p>
 @endif
</section>
@endsection
