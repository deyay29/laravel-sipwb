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

        <form action="{{ isset($kuliner) ? route('admin.kuliner.update', $kuliner) : route('admin.kuliner.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
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
                <label for="harga_minimum" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Harga Minimum (Rp)
                </label>
                <input type="number"
                       id="harga_minimum"
                       name="harga_minimum"
                       min="0"
                       step="1"
                       value="{{ old('harga_minimum', $kuliner->harga_minimum ?? '') }}"
                       placeholder="10000"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">
                    Harga minimum yang berlaku, contoh: 10.000.
                </p>
            </div>

            <div>
                <label for="harga_maksimum" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Harga Maksimum (Rp)
                </label>
                <input type="number"
                       id="harga_maksimum"
                       name="harga_maksimum"
                       min="0"
                       step="1"
                       value="{{ old('harga_maksimum', $kuliner->harga_maksimum ?? '') }}"
                       placeholder="50000"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">
                    Harga maksimum yang berlaku, contoh: 50.000. Isi dua kolom ini untuk menyimpan rentang harga.
                </p>
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
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                <p class="mb-4 font-display text-sm font-bold text-slate-700">
                    <i class="fas fa-location-dot mr-2 text-brand-600"></i>Titik Lokasi
                </p>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="latitude" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                            Latitude
                        </label>
                        <input type="number"
                               step="any"
                               min="-90"
                               max="90"
                               id="latitude"
                               name="latitude"
                               value="{{ old('latitude', $kuliner->latitude ?? '') }}"
                               placeholder="-6.9206"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        <p class="mt-1.5 text-xs text-slate-400">
                            Koordinat lintang (sumbu bujur) tempat kuliner berada, contoh: -6.9206.
                        </p>
                    </div>

                    <div>
                        <label for="longitude" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                            Longitude
                        </label>
                        <input type="number"
                               step="any"
                               min="-180"
                               max="180"
                               id="longitude"
                               name="longitude"
                               value="{{ old('longitude', $kuliner->longitude ?? '') }}"
                               placeholder="107.6100"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        <p class="mt-1.5 text-xs text-slate-400">
                            Koordinat lintang (sumbu khat) tempat kuliner berada, contoh: 107.6100.
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <label for="ramah_anak" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Family & Kids (Ramah Keluarga & Anak)
                </label>
                <label class="inline-flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5">
                    <input type="checkbox"
                           id="ramah_anak"
                           name="ramah_anak"
                           value="1"
                           @checked(old('ramah_anak', $kuliner->ramah_anak ?? false))
                           class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-sm text-slate-700">Ramah Keluarga & Anak (Family & Kids)</span>
                </label>
                <p class="mt-1.5 text-xs text-slate-400">
                    Tandai jika kuliner ini cocok untuk keluarga & anak-anak, misalnya cafe atau restoran yang ramah anak.
                </p>
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
                <label for="fotos" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Foto Kuliner
                </label>

                @php
                    $fotoTersimpan = isset($kuliner) ? $kuliner->fotos : collect();
                    $pathAlbum = $fotoTersimpan->pluck('path')->all();
                    $sampulLama = isset($kuliner) && $kuliner->foto && ! in_array($kuliner->foto, $pathAlbum, true)
                        ? $kuliner->foto
                        : null;
                @endphp

                @if ($fotoTersimpan->isNotEmpty() || $sampulLama)
                    <div class="mb-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @if ($sampulLama)
                            <div class="relative overflow-hidden rounded-xl border border-slate-200">
                                <x-public.media :src="$sampulLama" alt="Foto sampul saat ini" icon="fa-utensils"
                                    tone="amber" ratio="h-28" />

                                <span class="absolute left-2 top-2 rounded-full bg-navy-800/85 px-2 py-0.5 text-[10px] font-bold text-white">
                                    Sampul lama
                                </span>
                            </div>
                        @endif

                        @foreach ($fotoTersimpan as $index => $foto)
                            <label class="group relative block cursor-pointer overflow-hidden rounded-xl border border-slate-200">
                                <x-public.media :src="$foto->path" :alt="'Foto '.($index + 1)" icon="fa-utensils"
                                    tone="amber" ratio="h-28" />

                                @if ($index === 0)
                                    <span class="absolute left-2 top-2 rounded-full bg-emerald-600/90 px-2 py-0.5 text-[10px] font-bold text-white">
                                        Sampul
                                    </span>
                                @endif

                                <span class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 bg-navy-900/70 px-2 py-1.5 text-[10px] font-semibold text-white">
                                    <input type="checkbox"
                                           name="hapus_foto[]"
                                           value="{{ $foto->id_foto }}"
                                           class="h-3.5 w-3.5 rounded border-white/60 text-rose-600 focus:ring-rose-500">
                                    Hapus
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <p class="mb-3 text-xs text-slate-400">
                        Centang "Hapus" pada foto yang ingin dibuang. Perubahan baru tersimpan setelah tombol Simpan ditekan.
                    </p>
                @endif

                <input type="file"
                       id="fotos"
                       name="fotos[]"
                       accept="image/jpeg,image/png,image/webp"
                       multiple
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-brand-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Bisa pilih beberapa foto sekaligus (multiple). Format JPG, PNG, atau WebP, maksimal 2 MB per foto.
                </p>

                @error('fotos')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                @error('fotos.*')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div id="foto-preview" class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4"></div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('fotos');
        const preview = document.getElementById('foto-preview');

        if (!input || !preview) {
            return;
        }

        input.addEventListener('change', function () {
            preview.innerHTML = '';

            Array.from(input.files).forEach(function (file) {
                const url = URL.createObjectURL(file);
                const card = document.createElement('div');
                card.className = 'overflow-hidden rounded-xl border border-slate-200';
                card.innerHTML = '<img src="' + url + '" alt="" class="h-28 w-full object-cover">'
                    + '<p class="truncate bg-slate-50 px-2 py-1 text-[10px] text-slate-500"></p>';
                card.querySelector('p').textContent = file.name;
                preview.appendChild(card);
            });
        });
    });
</script>
@endpush
