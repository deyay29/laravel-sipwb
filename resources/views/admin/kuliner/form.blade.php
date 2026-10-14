@extends('layouts.admin')

@section('title', 'Kuliner')
@section('topbar-title', 'Kuliner')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.kuliner.index') }}"
       class="mb-5 inline-flex items-center gap-2 font-display text-sm font-semibold text-slate-500 transition hover:text-brand-700">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke daftar
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        <h1 class="font-display text-xl font-extrabold text-slate-800">{{ isset($kuliner) ? 'Edit Kuliner' : 'Tambah Kuliner' }}</h1>

        <form action="{{ isset($kuliner) ? route('admin.kuliner.update', $kuliner) : route('admin.kuliner.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @isset($kuliner)
                @method('PUT')
            @endisset

            <div>
                <label for="nama_kuliner" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Nama Kuliner
                </label>
                <input type="text"
                       id="nama_kuliner"
                       name="nama_kuliner"
                       value="{{ old('nama_kuliner', $kuliner->nama_kuliner ?? '') }}"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="kategori_suhu" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Kategori Suhu
                </label>
                <select id="kategori_suhu"
                        name="kategori_suhu"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @foreach (['' => '-- Pilih kategori --'] + array_combine(\App\Models\Kuliner::suhuOptions(), \App\Models\Kuliner::suhuOptions()) as $optValue => $optLabel)
                        <option value="{{ $optValue }}" @selected(old('kategori_suhu', $kuliner->kategori_suhu ?? '') === (string) $optValue)>
                            {{ $optLabel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="harga" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Harga Rata-rata (Rp)
                </label>
                <input type="number"
                       id="harga"
                       name="harga"
                       value="{{ old('harga', $kuliner->harga ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="jam_operasional" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Jam Operasional
                </label>
                <input type="text"
                       id="jam_operasional"
                       name="jam_operasional"
                       value="{{ old('jam_operasional', $kuliner->jam_operasional ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="kontak" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Kontak Penghubung
                </label>
                <input type="text"
                       id="kontak"
                       name="kontak"
                       value="{{ old('kontak', $kuliner->kontak ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="alamat_lengkap" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Alamat Lengkap
                </label>
                <textarea id="alamat_lengkap"
                          name="alamat_lengkap"
                          rows="3"
                          
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('alamat_lengkap', $kuliner->alamat_lengkap ?? '') }}</textarea>
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
                          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('deskripsi', $kuliner->deskripsi ?? '') }}</textarea>
            </div>
            <div>
                <label for="foto" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Path Foto
                </label>
                <input type="text"
                       id="foto"
                       name="foto"
                       value="{{ old('foto', $kuliner->foto ?? '') }}"
                       
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label for="status" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Status
                </label>
                <select id="status"
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @foreach (array_combine(\App\Models\Kuliner::statusOptions(), \App\Models\Kuliner::statusOptions()) as $optValue => $optLabel)
                        <option value="{{ $optValue }}" @selected(old('status', $kuliner->status ?? '') === (string) $optValue)>
                            {{ $optLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

                <a href="{{ route('admin.kuliner.index') }}"
                   class="inline-flex items-center rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
