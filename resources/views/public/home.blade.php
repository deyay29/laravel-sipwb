@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'Portal Layanan Informasi Publik Kota Bandung: berita, wisata, kuliner, sejarah, dan kontak.')

@section('content')
@php
    use App\Models\Kuliner;

    // $weather, $suhu, dan $suhuKategori sudah dihitung di PublicController.
    $labelSuhu = $suhuKategori === Kuliner::SUHU_MENYEGARKAN ? 'Dingin' : 'Hangat';
@endphp

{{-- ============ HERO ============ --}}
<section class="relative isolate overflow-hidden bg-navy-900">
 <x-public.media
 src="images/beranda.png"
 alt=""
 icon="fa-city"
 tone="navy"
 ratio="absolute inset-0 -z-20 h-full w-full"
 class="!object-cover"
 />
 <div class="absolute inset-0 -z-10 bg-gradient-to-r from-navy-900/95 via-navy-800/80 to-navy-900/60"></div>
 <div class="absolute inset-0 -z-10 bg-gradient-to-t from-navy-900/80 via-transparent to-navy-900/50"></div>

 <div class="mx-auto flex max-w-[1400px] flex-wrap items-center gap-10 px-5 pb-40 pt-36 lg:px-8 lg:pb-44 lg:pt-44">
 <div class="max-w-xl">
 <p class="text-sm font-medium text-white/80">Selamat Datang di</p>

 <h1 class="mt-2 font-display text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
 Bandung Info Center
 </h1>

 <p class="mt-5 text-base leading-relaxed text-white/85">
 Portal Layanan Informasi Publik Kota Bandung untuk penyediaan
 informasi Wisata, Sejarah, kuliner &amp; Berita terkini untuk
 pengunjung &amp; masyarakat.
 </p>
 </div>

    {{-- Kartu cuaca: komponen yang sama dipakai di halaman kuliner --}}
    <x-public.weather-card
        variant="glass"
        class="ml-auto hidden w-auto lg:block"
        :weather="$weather"
        :rekomendasi="$suhuKategori"
    />
 </div>
 </div>
 </div>
</section>

{{-- ============ KARTU KATEGORI (melayang di atas hero) ============ --}}
<section class="relative z-10 mx-auto -mt-28 max-w-[1400px] px-5 lg:-mt-32 lg:px-8">
 <div class="grid grid-cols-2 gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-[0_20px_50px_rgba(15,23,42,0.12)] sm:grid-cols-3 lg:grid-cols-5">
 @foreach ([
 ['label' => 'Wisata', 'route' => 'public.wisata', 'icon' => 'fa-image', 'desc' => 'Destinasi unggulan'],
 ['label' => 'Sejarah', 'route' => 'public.sejarah', 'icon' => 'fa-landmark', 'desc' => 'Arsip & museum'],
 ['label' => 'Berita', 'route' => 'public.berita', 'icon' => 'fa-newspaper', 'desc' => 'Kabar terkini'],
 ['label' => 'Kuliner', 'route' => 'public.kuliner', 'icon' => 'fa-utensils', 'desc' => 'Khas Bandung'],
 ['label' => 'Kontak', 'route' => 'public.kontak', 'icon' => 'fa-headset', 'desc' => 'Hubungi kami'],
 ] as $kategori)
 <a href="{{ route($kategori['route']) }}"
 class="group flex flex-col items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-7 transition hover:border-brand-500 hover:shadow-[0_12px_28px_rgba(37,99,235,0.16)]">
 <span class="grid h-14 w-14 place-items-center rounded-2xl border-2 border-brand-600 text-xl text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white">
 <i class="fas {{ $kategori['icon'] }}"></i>
 </span>

 <span class="text-center">
 <span class="block font-display text-sm font-bold text-slate-900">{{ $kategori['label'] }}</span>
 <span class="mt-0.5 block text-[11px] text-slate-400">{{ $kategori['desc'] }}</span>
 </span>
 </a>
 @endforeach
 </div>
</section>

{{-- ============ REKOMENDASI KULINER ============ --}}
<section class="mx-auto max-w-[1400px] px-5 py-16 lg:px-8">
    <x-public.section-header
        title="Rekomendasi Kuliner"
        highlight="(Berdasarkan Suhu {{ $labelSuhu }})"
        :description="$suhu !== null ? 'Suhu saat ini '.$suhu.'°C, rekomendasi dipilih otomatis.' : 'Data cuaca belum tersedia, rekomendasi memakai kategori standar.'"
        :action="route('public.kuliner')"
        action-label="Lihat Semua"
    />

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($kuliners as $kuliner)
            <x-public.content-card
                variant="brand"
                :href="route('public.kuliner.show', $kuliner)"
                :src="$kuliner->foto"
                :image-alt="$kuliner->nama_kuliner"
                image-icon="fa-utensils"
                image-tone="amber"
                :badge="$kuliner->kategori_suhu"
                badge-tone="bg-white/20 text-white"
                :title="$kuliner->nama_kuliner"
                :summary="Str::limit($kuliner->deskripsi, 70)"
                :meta="[[$kuliner->jam_operasional ? 'far fa-clock' : null, $kuliner->jam_operasional ?? '-']]"
                :note="$kuliner->harga ? 'Rp'.number_format($kuliner->harga, 0, ',', '.') : null"
            />
        @empty
            <x-public.empty-state
                class="sm:col-span-2 lg:col-span-4"
                title="Belum ada rekomendasi kuliner"
                description="Kuliner dengan kategori suhu ini belum tersedia."
                icon="fa-utensils"
            />
        @endforelse
    </div>
</section>

{{-- ============ BERITA TERBARU ============ --}}
<section class="bg-white py-16">
    <div class="mx-auto max-w-[1400px] px-5 lg:px-8">
        <x-public.section-header
            title="Berita Terbaru"
            description="Kabar terkini dari Kota Bandung."
            :action="route('public.berita')"
            action-label="Lihat Semua"
        />

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($beritas as $berita)
                <x-public.content-card
                    :href="route('public.berita.show', $berita)"
                    :src="$berita->gambar"
                    :image-alt="$berita->judul"
                    image-icon="fa-newspaper"
                    image-tone="brand"
                    :badge="$berita->kategori"
                    :title="$berita->judul"
                    :meta="[
                        ['far fa-calendar', $berita->tanggal?->format('d M Y') ?? '-'],
                        ['far fa-eye', number_format($berita->visitor_count, 0, ',', '.')],
                    ]"
                />
            @empty
                <x-public.empty-state
                    class="sm:col-span-2 lg:col-span-4"
                    title="Belum ada berita"
                    icon="fa-newspaper"
                />
            @endforelse
        </div>
    </div>
</section>

{{-- ============ WISATA PILIHAN ============ --}}
<section class="mx-auto max-w-[1400px] px-5 py-16 lg:px-8">
    <x-public.section-header
        title="Wisata Pilihan"
        description="Destinasi ikonik dari seluruh penjuru kota."
        :action="route('public.wisata')"
        action-label="Lihat Semua"
    />

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($wisatas as $wisata)
            <x-public.content-card
                :href="route('public.wisata.show', $wisata)"
                :src="$wisata->gambar"
                :image-alt="$wisata->nama_wisata"
                image-icon="fa-mountain-sun"
                image-tone="emerald"
                :badge="$wisata->kategori"
                badge-tone="bg-emerald-50 text-emerald-700"
                :title="$wisata->nama_wisata"
                :summary="Str::limit($wisata->deskripsi, 80)"
                :meta="[['fas fa-location-dot', $wisata->lokasi ?? 'Bandung']]"
                :note="$wisata->harga_tiket ? 'Rp'.number_format($wisata->harga_tiket, 0, ',', '.') : 'Gratis'"
            />
        @empty
            <x-public.empty-state
                class="sm:col-span-2 lg:col-span-4"
                title="Belum ada data wiselyat"
                icon="fa-mountain-sun"
            />
        @endforelse
    </div>
</section>

{{-- ============ SEJARAH ============ --}}
<section class="bg-navy-800 py-16">
    <div class="mx-auto max-w-[1400px] px-5 lg:px-8">
        <x-public.section-header
            tone="dark"
            title="Cerita Sejarah"
            description="Telusuri tonggak perjalanan Kota Bandung."
            :action="route('public.sejarah')"
            action-label="Lihat Semua"
        />

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($sejarahs as $topik)
                <x-public.content-card
                    variant="dark"
                    :href="route('public.sejarah.show', $topik)"
                    :src="$topik->gambar_utama"
                    :image-alt="$topik->judul_topik"
                    image-icon="fa-landmark"
                    image-tone="violet"
                    badge="{{ $topik->babs_count }} bab"
                    badge-tone="bg-white/15 text-white"
                    :title="$topik->judul_topik"
                    :summary="Str::limit($topik->ringkasan, 80)"
                    :meta="[['far fa-calendar', $topik->created_at->format('d M Y')]]"
                />
            @empty
                <x-public.empty-state
                    class="sm:col-span-2 lg:col-span-4 !border-white/20 !bg-white/5"
                    title="Belum ada topik sejarah"
                    icon="fa-landmark"
                />
            @endforelse
        </div>
    </div>
</section>

{{-- ============ SMART BUDGET ============ --}}
<section class="mx-auto max-w-[1400px] px-5 py-16 lg:px-8">
 <div class="flex flex-wrap items-center justify-between gap-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_2px_16px_rgba(15,23,42,0.06)] lg:p-12">
 <div class="max-w-xl">
 <h2 class="font-display text-2xl font-extrabold text-slate-900">Rencanakan liburan hemat</h2>
 <p class="mt-2 text-sm leading-relaxed text-slate-600">
 Gunakan paket Smart Budget untuk menghitung perkiraan biaya trip Bandung sebelum berangkat.
 </p>
 </div>

 <a href="{{ route('public.smart-budget') }}"
 class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-navy-800 to-brand-600 px-8 py-4 font-display text-sm font-bold text-white transition hover:shadow-xl">
 Buka Smart Budget
 <i class="fas fa-wallet text-xs"></i>
 </a>
 </div>
</section>
@endsection
