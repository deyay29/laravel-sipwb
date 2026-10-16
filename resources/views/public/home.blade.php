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
{{-- Pakai komponen yang sama dengan halaman lain supaya tinggi, rasio, dan
     ukuran background seragam. Kartu cuaca lewat slot aside. --}}
<x-public.page-hero
    title="Bandung Info Center"
    subtitle="Portal Layanan Informasi Publik Kota Bandung untuk penyediaan informasi Wisata, Sejarah, kuliner & Berita terkini untuk pengunjung & masyarakat."
    image="images/beranda.png"
    :breadcrumb="['Beranda' => null]"
>
    <x-slot:aside>
        <x-public.weather-card
            variant="glass"
            :weather="$weather"
            :rekomendasi="$suhuKategori"
        />
    </x-slot:aside>
</x-public.page-hero>

{{-- ============ KARTU KATEGORI (melayang di atas hero) ============ --}}
<section class="relative z-10 mx-auto -mt-10 max-w-[1400px] px-3 sm:-mt-14 sm:px-6 lg:-mt-16 lg:px-8">
    {{-- auto-fit + auto-fr: kolom menyesuaikan jumlah kartu yang ada dan
         setiap kartu meregang sama rata mengisi lebar (tidak ada kolom
         kosong menggantung di sisi kanan). --}}
    <div class="grid grid-cols-2 gap-1.5 rounded-2xl border border-slate-200 bg-white p-2 shadow-[0_12px_36px_rgba(15,23,42,0.08)] sm:auto-fit sm:grid-cols-[repeat(auto-fit,minmax(11rem,1fr))] sm:gap-3 sm:rounded-3xl sm:p-3 lg:gap-4 lg:p-4">
        @foreach ([
            ['label' => 'Wisata', 'target' => '#wisata', 'icon' => 'fa-image', 'desc' => 'Destinasi unggulan'],
            ['label' => 'Sejarah', 'target' => '#sejarah', 'icon' => 'fa-landmark', 'desc' => 'Arsip & museum'],
            ['label' => 'Berita', 'target' => '#berita', 'icon' => 'fa-newspaper', 'desc' => 'Kabar terkini'],
            ['label' => 'Kuliner', 'target' => '#kuliner', 'icon' => 'fa-utensils', 'desc' => 'Khas Bandung'],
            // {{-- Kontak memakai route, bukan anchor #kontak, karena footer
            //      penyimpan id="kontak" itu disembunyikan di layar mobile. --}}
            // ['label' => 'Kontak', 'target' => route('public.kontak'), 'icon' => 'fa-headset', 'desc' => 'Hubungi kami'],
        ] as $kategori)
            <a href="{{ $kategori['target'] }}"
               class="group flex flex-col items-center justify-center gap-1.5 rounded-xl border border-transparent py-2 px-1 text-center transition-all duration-200 hover:border-brand-300 hover:bg-brand-50/40 hover:shadow-sm active:scale-95 sm:gap-2.5 sm:rounded-2xl sm:border-slate-200 sm:bg-white sm:px-3 sm:py-3.5 lg:py-4 sm:hover:border-brand-500 sm:hover:shadow-[0_8px_20px_rgba(37,65,160,0.12)]">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl border border-brand-200 bg-brand-50 text-sm text-brand-600 transition-all duration-200 group-hover:bg-brand-600 group-hover:text-white sm:h-11 sm:w-11 sm:rounded-xl sm:border-2 sm:border-brand-600 sm:text-base sm:text-brand-700">
                    <i class="fas {{ $kategori['icon'] }}"></i>
                </span>

                <span class="text-center">
                    <span class="block font-display text-[11px] font-bold text-slate-800 transition-colors group-hover:text-brand-700 sm:text-xs lg:text-sm">{{ $kategori['label'] }}</span>
                    <span class="mt-0.5 hidden text-[11px] text-slate-400 sm:block">{{ $kategori['desc'] }}</span>
                </span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============ REKOMENDASI KULINER ============ --}}
<section id="kuliner" class="scroll-mt-20 lg:scroll-mt-24 mx-auto max-w-[1400px] px-5 py-16 lg:px-8">
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
<section id="berita" class="scroll-mt-20 lg:scroll-mt-24 bg-white py-16">
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
<section id="wisata" class="scroll-mt-20 lg:scroll-mt-24 mx-auto max-w-[1400px] px-5 py-16 lg:px-8">
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
<section id="sejarah" class="scroll-mt-20 lg:scroll-mt-24 bg-navy-800 py-16">
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
 class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-navy-800 to-brand-600 px-8 py-4 font-display text-sm font-bold text-white transition duration-200 hover:-translate-y-0.5 hover:from-navy-900 hover:to-brand-700 hover:shadow-xl active:translate-y-0">
 Buka Smart Budget
 <i class="fas fa-wallet text-xs"></i>
 </a>
 </div>
</section>
@endsection
