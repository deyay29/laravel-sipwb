@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Kumpulan foto Kota Bandung.')

@section('content')
<x-public.page-hero
    title="Galeri"
    subtitle="Kumpulan foto wisata, kuliner, dan sejarah Kota Bandung."
    :breadcrumb="['Beranda' => route('home'), 'Galeri' => null]"
/>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
    @php
        $kategoris = App\Models\Galeri::query()
            ->whereNotNull('kategori_objek')
            ->distinct()
            ->orderBy('kategori_objek')
            ->pluck('kategori_objek');
    @endphp

    {{-- Filter kategori objek --}}
    @if ($kategoris->isNotEmpty())
        <div class="mb-10 flex flex-wrap items-center gap-3">
            <span class="font-display text-sm font-semibold text-slate-600">Saring menurut kategori:</span>

            <a href="{{ route('public.galeri') }}"
               @class([
                   'rounded-full px-5 py-2.5 font-display text-sm font-semibold transition',
                   'bg-brand-600 text-white shadow-[0_4px_14px_rgba(37,99,235,0.3)]' => ! request()->filled('kategori'),
                   'border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-700' => request()->filled('kategori'),
               ])>
                Semua
            </a>

            @foreach ($kategoris as $kategori)
                <a href="{{ route('public.galeri', ['kategori' => $kategori]) }}"
                   @class([
                       'rounded-full px-5 py-2.5 font-display text-sm font-semibold transition',
                       'bg-brand-600 text-white shadow-[0_4px_14px_rgba(37,99,235,0.3)]' => request('kategori') === $kategori,
                       'border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-700' => request('kategori') !== $kategori,
                   ])>
                    {{ $kategori }}
                </a>
            @endforeach
        </div>
    @endif

    @if ($galeris->isEmpty())
        {{--
            Empty state harus DI LUAR wrapper `columns-*`. Container masonry
            membelah isi ke beberapa kolom, sehingga ikon / judul / keterangan
            akan terpisah-pisah saat data kosong.
        --}}
        <x-public.empty-state
            title="Belum ada foto"
            description="Galeri akan terisi setelah foto diunggah melalui panel admin."
            icon="fa-images"
        />
    @else
        {{-- Masonry: tinggi tiap foto bebas, mengisi kolom sedekat mungkin --}}
        <div class="columns-1 gap-6 sm:columns-2 lg:columns-3">
            @foreach ($galeris as $galeri)
                <figure class="group mb-6 break-inside-avoid overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
                    <x-public.media
                        :src="$galeri->file_gambar"
                        :alt="$galeri->judul"
                        icon="fa-image"
                        tone="slate"
                        ratio="aspect-[4/3]"
                        class="transition duration-500 group-hover:scale-105"
                    />

                    <figcaption class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="font-display text-base font-bold text-slate-900">
                                {{ Str::limit($galeri->judul, 50) }}
                            </h2>

                            @if ($galeri->kategori_objek)
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    {{ $galeri->kategori_objek }}
                                </span>
                            @endif
                        </div>

                        @if ($galeri->keterangan)
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                {{ Str::limit($galeri->keterangan, 110) }}
                            </p>
                        @endif
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <x-public.pagination :paginator="$galeris" />
    @endif
</section>
@endsection
