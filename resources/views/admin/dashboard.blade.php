@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')
@php
 use App\Services\WeatherService;

 $temp = $weather['temp'] ?? null;
 $condition = $weather['condition'] ?? 'Data cuaca tidak tersedia';
 $updatedAt = $weather['time'] ?? now()->format('H:i');
 $humidity = $weather['humidity'] ?? null;
    $region = $weather['region'] ?? 'Bandung';

 // Ikon cuaca memakai FontAwesome, sama seperti ikon lain di aplikasi.
 $weatherIcon = WeatherService::icon($weather);

 // Rekomendasi kategori kuliner berdasarkan suhu saat ini.
 $tempCategory = $temp !== null && $temp >= 27 ? 'Menyegarkan' : 'Menghangatkan';

 $stats = [
 [
 'label' => 'Total Kuliner',
 'value' => $counts['kuliner_total'],
 'icon' => 'fa-screwdriver-wrench',
 'tone' => 'blue',
 'url' => route('admin.kuliner.index'),
 ],
 [
 'label' => 'Kuliner Aktif',
 'value' => $counts['kuliner_aktif'],
 'icon' => 'fa-circle-check',
 'tone' => 'green',
 'url' => route('admin.kuliner.index'),
 ],
 [
 'label' => 'Kuliner Nonaktif',
 'value' => $counts['kuliner_nonaktif'],
 'icon' => 'fa-circle-pause',
 'tone' => 'amber',
 'url' => route('admin.kuliner.index'),
 ],
 [
 'label' => 'Wisata',
 'value' => $counts['wisata'],
 'icon' => 'fa-mountain-sun',
 'tone' => 'violet',
 'url' => route('admin.wisata.index'),
 ],
 [
 'label' => 'Berita',
 'value' => $counts['berita'],
 'icon' => 'fa-newspaper',
 'tone' => 'sky',
 'url' => route('admin.berita.index'),
 ],
 [
 'label' => 'Topik Sejarah',
 'value' => $counts['sejarah'],
 'icon' => 'fa-landmark',
 'tone' => 'rose',
 'url' => route('admin.sejarah.index'),
 ],
 ];
@endphp

{{-- ===== Header ===== --}}
<div class="mb-6 flex flex-wrap items-start justify-between gap-4">
 <div>
 <h1 class="font-display text-2xl font-extrabold text-slate-800">Dashboard</h1>
 <p class="mt-1 text-sm text-slate-500">
 Ringkasan konten portal Bandung Info Center per {{ now()->format('d F Y') }}.
 </p>
 </div>

 <a href="{{ route('admin.kuliner.index') }}"
 class="inline-flex items-center gap-2 rounded-[14px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-3 font-display text-sm font-semibold text-white shadow-[0_4px_18px_rgba(26,42,108,0.3)] transition hover:shadow-[0_10px_28px_rgba(26,42,108,0.4)]">
 Kelola Kuliner
 <i class="fas fa-arrow-right text-xs"></i>
 </a>
</div>

{{-- ===== Kartu cuaca ===== --}}
<section class="mb-6 overflow-hidden rounded-[20px] bg-gradient-to-br from-navy-800 via-navy-700 to-brand-600 text-white shadow-[0_10px_30px_rgba(26,42,108,0.25)]">
 <div class="flex flex-wrap items-center justify-between gap-5 p-6">
 <div class="flex items-center gap-4">
 <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-white/15 text-2xl text-white backdrop-blur">
 <i class="fas {{ $weatherIcon }}"></i>
 </span>
 <div>
 <p class="font-display text-4xl font-extrabold leading-none">
 {{ $temp !== null ? $temp.'°C' : '--°C' }}
 </p>
 <p class="mt-1.5 text-sm text-white/80">{{ $condition }} &middot; {{ $region }}</p>
 </div>
 </div>

 <div class="text-right">
 <span @class([
 'inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/15 px-4 py-2 font-display text-sm font-bold backdrop-blur',
 'text-sky-100' => $tempCategory === 'Menyegarkan',
 'text-orange-100' => $tempCategory === 'Menghangatkan',
 ])>
 <i class="fas {{ $tempCategory === 'Menyegarkan' ? 'fa-snowflake' : 'fa-fire' }} text-xs"></i>
 Rekomendasi: {{ $tempCategory }}
 </span>
 <p class="mt-2 text-xs text-white/60">
 Sumber: Open-Meteo &middot; Pembaruan {{ \Carbon\Carbon::parse($updatedAt)->format('H:i') }}
 </p>
 </div>
 </div>

 <div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-white/15 bg-black/10 px-6 py-3.5 text-xs text-white/70">
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-thermometer-half"></i> Suhu saat ini
 </span>
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-cloud"></i> {{ $condition }}
 </span>
 @if ($humidity !== null)
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-droplet"></i> {{ $humidity }}% kelembapan
 </span>
 @endif
 @if (! empty($weather['windspeed']))
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-wind"></i> {{ $weather['windspeed'] }} km/jam
 </span>
 @endif
 <span class="inline-flex items-center gap-2">
 <i class="fas fa-location-dot"></i> {{ $region }}
 </span>
 <a href="{{ route('admin.dashboard') }}" class="ml-auto inline-flex items-center gap-1.5 font-semibold text-white/90 hover:text-white">
 <i class="fas fa-rotate text-xs"></i> Muat ulang
 </a>
 </div>
</section>

{{-- ===== Kartu statistik ===== --}}
<section class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
 @foreach ($stats as $stat)
 <a href="{{ $stat['url'] }}"
 class="group rounded-[14px] border border-slate-200 bg-white p-5 shadow-[0_2px_16px_rgba(26,42,108,0.08)] transition hover:shadow-[0_10px_28px_rgba(26,42,108,0.14)]">
 <span @class([
 'mb-3 grid h-10 w-10 place-items-center rounded-xl text-sm transition group-hover:scale-110',
 'bg-brand-50 text-brand-600' => $stat['tone'] === 'blue',
 'bg-emerald-50 text-emerald-600' => $stat['tone'] === 'green',
 'bg-amber-50 text-amber-600' => $stat['tone'] === 'amber',
 'bg-violet-50 text-violet-600' => $stat['tone'] === 'violet',
 'bg-sky-50 text-sky-600' => $stat['tone'] === 'sky',
 'bg-rose-50 text-rose-600' => $stat['tone'] === 'rose',
 ])>
 <i class="fas {{ $stat['icon'] }}"></i>
 </span>

 <p class="font-display text-3xl font-extrabold text-slate-800">
 {{ number_format($stat['value'], 0, ',', '.') }}
 </p>
 <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $stat['label'] }}</p>
 </a>
 @endforeach
</section>

{{-- ===== Pintasan ===== --}}
<section class="mt-6 grid gap-4 md:grid-cols-2">
 @foreach (config('nav.admin') as $item)
 @continue($item['route'] === 'admin.dashboard')

 <a href="{{ route($item['route']) }}"
 class="flex items-center gap-4 rounded-[14px] border border-slate-200 bg-white p-5 shadow-[0_2px_16px_rgba(26,42,108,0.08)] transition hover:border-brand-500">
 <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-navy-800 text-white">
 <i class="fas {{ $item['icon'] }}"></i>
 </span>
 <span class="min-w-0 flex-1">
 <span class="block font-display text-sm font-bold text-slate-800">{{ $item['label'] }}</span>
 <span class="block truncate text-xs text-slate-500">Kelola data {{ strtolower($item['label']) }}</span>
 </span>
 <i class="fas fa-chevron-right text-xs text-slate-300 transition group-hover:text-brand-600"></i>
 </a>
 @endforeach
</section>

{{-- ===== Catatan ===== --}}
<section class="mt-6 rounded-[14px] border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)]">
 <h2 class="font-display text-sm font-bold text-slate-800">Catatan Teknis</h2>
 <ul class="mt-3 space-y-1.5 text-sm text-slate-600">
 <li class="flex gap-2">
 <i class="fas fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
 Semua perubahan langsung tersimpan di database MySQL.
 </li>
 <li class="flex gap-2">
 <i class="fas fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
 Slug dibuat otomatis dan dijamin unik saat menyimpan data.
 </li>
 <li class="flex gap-2">
 <i class="fas fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
 Kolom <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">id_user</code> mencatat admin pembuat konten.
 </li>
 </ul>
</section>
@endsection
