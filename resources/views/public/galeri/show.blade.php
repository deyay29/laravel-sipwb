@extends('layouts.app')

@section('title', $galeri->judul)
@section('description', Str::limit(strip_tags($galeri->keterangan ?? ''), 160))

@section('content')
    @php
        $fotoUrl = App\Support\Media::url($galeri->file_gambar);
    @endphp

    <x-public.page-hero
        :title="$galeri->judul"
        :subtitle="$galeri->kategori_objek"
        :image="$galeri->file_gambar"
        :breadcrumb="[
            'Beranda' => route('home'),
            'Galeri' => route('public.galeri'),
            Str::limit($galeri->judul, 40) => null,
        ]"
    />

    <section class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('public.galeri') }}"
               class="inline-flex items-center gap-2 font-display text-sm font-semibold text-brand-700 transition hover:text-brand-800">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke galeri
            </a>

            <x-public.share-buttons :title="$galeri->judul" />
        </div>

        <div class="grid gap-10 lg:grid-cols-5">
            {{-- Foto utama --}}
            <div class="lg:col-span-3">
                <figure class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_4px_20px_rgba(15,23,42,0.10)]">
                    @if ($fotoUrl)
                        <a href="{{ $fotoUrl }}"
                           target="_blank"
                           rel="noopener"
                           title="Buka foto ukuran penuh"
                           class="group block overflow-hidden">
                            <img src="{{ $fotoUrl }}"
                                 alt="{{ $galeri->judul }}"
                                 loading="eager"
                                 decoding="async"
                                 class="max-h-[70vh] w-full object-contain bg-slate-900 transition duration-500 group-hover:scale-[1.01]">
                        </a>
                    @else
                        <x-public.media
                            :src="$galeri->file_gambar"
                            :alt="$galeri->judul"
                            icon="fa-image"
                            tone="slate"
                            ratio="aspect-[4/3]"
                        />
                    @endif

                    <figcaption class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-5 py-4">
                        <p class="text-xs text-slate-500">
                            <i class="fas fa-circle-info text-brand-500"></i>
                            Klik foto untuk membuka ukuran penuh.
                        </p>

                        @if ($fotoUrl)
                            <a href="{{ $fotoUrl }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 font-display text-xs font-semibold text-slate-700 transition hover:border-brand-500 hover:text-brand-700">
                                <i class="fas fa-up-right-from-square text-[10px]"></i>
                                Ukuran Penuh
                            </a>
                        @endif
                    </figcaption>
                </figure>
            </div>

            {{-- Informasi foto --}}
            <aside class="space-y-5 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($galeri->kategori_objek)
                            <span class="rounded-full bg-brand-50 px-3 py-1 font-display text-xs font-bold text-brand-700">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $galeri->kategori_objek }}
                            </span>
                        @endif

                        @if ($galeri->created_at)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                <i class="far fa-calendar mr-1"></i>
                                {{ $galeri->created_at->format('d M Y') }}
                            </span>
                        @endif
                    </div>

                    <h2 class="mt-4 font-display text-2xl font-extrabold text-slate-900">
                        {{ $galeri->judul }}
                    </h2>

                    <div class="mt-4 text-[1.02rem] leading-[1.9] text-slate-700">
                        @if ($galeri->keterangan)
                            {!! nl2br(e($galeri->keterangan)) !!}
                        @else
                            <p class="text-slate-500">Belum ada keterangan untuk foto ini.</p>
                        @endif
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <x-public.share-buttons :title="$galeri->judul" label="Bagikan foto ini" />
                    </div>
                </div>

                <a href="{{ route('public.galeri') }}"
                   class="block rounded-2xl border-2 border-slate-900 px-5 py-3.5 text-center font-display text-sm font-bold text-slate-900 transition hover:bg-slate-900 hover:text-white">
                    Lihat Semua Galeri
                </a>
            </aside>
        </div>

        {{-- Galeri terkait --}}
        @if ($terkait->isNotEmpty())
            <div class="mt-16">
                <h2 class="font-display text-xl font-extrabold text-slate-900">Galeri Terkait Lainnya</h2>

                <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($terkait as $item)
                        <a href="{{ route('public.galeri.show', $item) }}"
                           class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]">
                            <x-public.media
                                :src="$item->file_gambar"
                                :alt="$item->judul"
                                icon="fa-image"
                                tone="slate"
                                ratio="h-48"
                                class="transition duration-500 group-hover:scale-105"
                            />

                            <div class="bg-brand-600 p-5 text-white">
                                <p class="font-display text-base font-bold">{{ Str::limit($item->judul, 45) }}</p>

                                @if ($item->kategori_objek)
                                    <p class="mt-2 text-xs text-white/75">
                                        <i class="fas fa-tag"></i>
                                        {{ $item->kategori_objek }}
                                    </p>
                                @endif

                                <span class="mt-4 inline-flex items-center gap-1.5 font-display text-xs font-bold">
                                    Lihat Foto <i class="fas fa-arrow-right text-[10px] transition group-hover:translate-x-1"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
