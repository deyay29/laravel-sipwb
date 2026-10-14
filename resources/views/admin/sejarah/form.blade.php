@extends('layouts.admin')

@section('title', 'Sejarah')
@section('topbar-title', 'Sejarah')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.sejarah.index') }}"
       class="mb-5 inline-flex items-center gap-2 font-display text-sm font-semibold text-slate-500 transition hover:text-brand-700">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke daftar
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        <h1 class="font-display text-xl font-extrabold text-slate-800">{{ isset($sejarah) ? 'Edit Topik Sejarah' : 'Tambah Topik Sejarah' }}</h1>

        <form action="{{ isset($sejarah) ? route('admin.sejarah.update', $sejarah) : route('admin.sejarah.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @isset($sejarah)
                @method('PUT')
            @endisset

            <div>
                <label for="judul_topik" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Judul Topik
                </label>
                <input type="text"
                       id="judul_topik"
                       name="judul_topik"
                       value="{{ old('judul_topik', $sejarah->judul_topik ?? '') }}"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="ringkasan" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Ringkasan
                </label>
                <textarea id="ringkasan"
                          name="ringkasan"
                          rows="8"
                          
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('ringkasan', $sejarah->ringkasan ?? '') }}</textarea>
            </div>
            <div>
                <label for="gambar_utama" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Path Gambar Utama
                </label>
                <input type="text"
                       id="gambar_utama"
                       name="gambar_utama"
                       value="{{ old('gambar_utama', $sejarah->gambar_utama ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

                <a href="{{ route('admin.sejarah.index') }}"
                   class="inline-flex items-center rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
