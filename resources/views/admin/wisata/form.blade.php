@extends('layouts.admin')

@section('title', 'Wisata')
@section('topbar-title', 'Wisata')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.wisata.index') }}"
       class="mb-5 inline-flex items-center gap-2 font-display text-sm font-semibold text-slate-500 transition hover:text-brand-700">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke daftar
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        <h1 class="font-display text-xl font-extrabold text-slate-800">{{ isset($wisata) ? 'Edit Wisata' : 'Tambah Wisata' }}</h1>

        <form action="{{ isset($wisata) ? route('admin.wisata.update', $wisata) : route('admin.wisata.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @isset($wisata)
                @method('PUT')
            @endisset

            <div>
                <label for="nama_wisata" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Nama Tempat Wisata
                </label>
                <input type="text"
                       id="nama_wisata"
                       name="nama_wisata"
                       value="{{ old('nama_wisata', $wisata->nama_wisata ?? '') }}"
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
                       value="{{ old('kategori', $wisata->kategori ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="lokasi" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Lokasi
                </label>
                <input type="text"
                       id="lokasi"
                       name="lokasi"
                       value="{{ old('lokasi', $wisata->lokasi ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="deskripsi" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Deskripsi
                    <span class="text-rose-500">*</span>
                </label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="7"
                          required
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('deskripsi', $wisata->deskripsi ?? '') }}</textarea>
            </div>
            <div>
                <label for="harga_tiket" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Harga Tiket (Rp)
                </label>
                <input type="number"
                       id="harga_tiket"
                       name="harga_tiket"
                       value="{{ old('harga_tiket', $wisata->harga_tiket ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">Kosongkan bila pengunjung tidak dikenai biaya.</p>
            </div>
            <div>
                <label for="jam_operasional" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Jam Operasional
                </label>
                <input type="text"
                       id="jam_operasional"
                       name="jam_operasional"
                       value="{{ old('jam_operasional', $wisata->jam_operasional ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">Contoh: 08.00 - 16.00 WIB</p>
            </div>
            <div>
                <label for="gambar" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Path Gambar
                </label>
                <input type="text"
                       id="gambar"
                       name="gambar"
                       value="{{ old('gambar', $wisata->gambar ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">Contoh: images/beranda.png atau foto/gedung-sate.jpg</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="quiz_tersedia" value="0">
                    <input type="checkbox"
                           name="quiz_tersedia"
                           value="1"
                           @checked(old('quiz_tersedia', $wisata->quiz_tersedia ?? false))
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span>
                        <span class="block font-display text-sm font-semibold text-slate-700">Kuis tersedia untuk wisata ini</span>
                    </span>
                </label>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

                <a href="{{ route('admin.wisata.index') }}"
                   class="inline-flex items-center rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
