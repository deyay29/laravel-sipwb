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

        <form action="{{ isset($galeri) ? route('admin.galeri.update', $galeri) : route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
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
                    Foto Galeri
                </label>

                @if (isset($galeri) && $galeri->file_gambar)
                    <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="h-24 w-36 shrink-0 overflow-hidden rounded-lg border border-slate-200">
                            <x-public.media :src="$galeri->file_gambar" :alt="$galeri->judul" icon="fa-image"
                                tone="slate" ratio="h-24" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-600">Foto saat ini</p>
                            <p class="mt-1 truncate text-[11px] text-slate-400">{{ $galeri->file_gambar }}</p>
                            <p class="mt-2 text-[11px] text-slate-400">
                                Pilih file baru untuk mengganti foto ini.
                            </p>
                        </div>
                    </div>
                @endif

                <input type="file"
                       id="file_gambar"
                       name="file_gambar"
                       accept="image/jpeg,image/png,image/webp"
                       @unless (isset($galeri)) required @endunless
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-brand-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Format JPG, PNG, atau WebP, maksimal 4 MB. Foto disimpan otomatis ke folder galeri.
                </p>

                @error('file_gambar')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div id="gambar-preview" class="mt-3 hidden overflow-hidden rounded-xl border border-slate-200">
                    <img id="gambar-preview-img" src="" alt="Preview foto baru" class="h-40 w-full object-cover">
                </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('file_gambar');
        const preview = document.getElementById('gambar-preview');
        const image = document.getElementById('gambar-preview-img');

        if (!input || !preview || !image) {
            return;
        }

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (!file) {
                preview.classList.add('hidden');
                image.src = '';
                return;
            }

            image.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    });
</script>
@endpush
