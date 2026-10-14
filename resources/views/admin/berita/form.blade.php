@extends('layouts.admin')

@section('title', 'Berita')
@section('topbar-title', 'Berita')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.berita.index') }}"
       class="mb-5 inline-flex items-center gap-2 font-display text-sm font-semibold text-slate-500 transition hover:text-brand-700">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke daftar
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        <h1 class="font-display text-xl font-extrabold text-slate-800">{{ isset($berita) ? 'Edit Berita' : 'Tambah Berita' }}</h1>

        <form action="{{ isset($berita) ? route('admin.berita.update', $berita) : route('admin.berita.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @isset($berita)
                @method('PUT')
            @endisset

            <div>
                <label for="judul" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Judul Berita
                </label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="{{ old('judul', $berita->judul ?? '') }}"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="kategori" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Kategori
                </label>
                <input type="text"
                       id="kategori"
                       name="kategori"
                       value="{{ old('kategori', $berita->kategori ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="isi" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Isi Berita
                    <span class="text-rose-500">*</span>
                </label>
                <textarea id="isi"
                          name="isi"
                          rows="14"
                          required
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('isi', $berita->isi ?? '') }}</textarea>
            </div>
            <div>
                <label for="gambar" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Path Gambar
                </label>
                <input type="text"
                       id="gambar"
                       name="gambar"
                       value="{{ old('gambar', $berita->gambar ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="audio_url" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    URL Audio
                </label>
                <input type="text"
                       id="audio_url"
                       name="audio_url"
                       value="{{ old('audio_url', $berita->audio_url ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">URL file audio berita, boleh dikosongkan.</p>
            </div>
            <div>
                <label for="tanggal" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Tanggal Terbit
                </label>
                <input type="datetime-local"
                       id="tanggal"
                       name="tanggal"
                       value="{{ old('tanggal', isset($berita) && $berita->tanggal ? $berita->tanggal->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">Kosongkan untuk memakai waktu sekarang.</p>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

                <a href="{{ route('admin.berita.index') }}"
                   class="inline-flex items-center rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
