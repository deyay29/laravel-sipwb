@extends('layouts.admin')

@section('title', 'Galeri')
@section('topbar-title', 'Galeri')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.galeri.index') }}"
       class="mb-5 inline-flex items-center gap-2 font-display text-sm font-semibold text-slate-500 transition hover:text-brand-700">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke daftar
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        <h1 class="font-display text-xl font-extrabold text-slate-800">{{ isset($galeri) ? 'Edit Foto Galeri' : 'Tambah Foto Galeri' }}</h1>

        <form action="{{ isset($galeri) ? route('admin.galeri.update', $galeri) : route('admin.galeri.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @isset($galeri)
                @method('PUT')
            @endisset

            <div>
                <label for="judul" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Judul Foto
                </label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="{{ old('judul', $galeri->judul ?? '') }}"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="kategori_objek" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Kategori Objek
                </label>
                <input type="text"
                       id="kategori_objek"
                       name="kategori_objek"
                       value="{{ old('kategori_objek', $galeri->kategori_objek ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">Contoh: Wisata, Kuliner, Sejarah.</p>
            </div>
            <div>
                <label for="file_gambar" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Path File Gambar
                </label>
                <input type="text"
                       id="file_gambar"
                       name="file_gambar"
                       value="{{ old('file_gambar', $galeri->file_gambar ?? '') }}"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="keterangan" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Keterangan
                </label>
                <textarea id="keterangan"
                          name="keterangan"
                          rows="4"
                          
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('keterangan', $galeri->keterangan ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

                <a href="{{ route('admin.galeri.index') }}"
                   class="inline-flex items-center rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
