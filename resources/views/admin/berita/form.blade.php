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

        <form action="{{ isset($berita) ? route('admin.berita.update', $berita) : route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
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
                    Gambar Sampul
                </label>

                @if (isset($berita) && $berita->gambar)
                    <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="h-24 w-36 shrink-0 overflow-hidden rounded-lg border border-slate-200">
                            <x-public.media :src="$berita->gambar" :alt="$berita->judul" icon="fa-newspaper"
                                tone="brand" ratio="h-24" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-600">Gambar saat ini</p>
                            <p class="mt-1 truncate text-[11px] text-slate-400">{{ $berita->gambar }}</p>

                            <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-rose-600">
                                <input type="checkbox"
                                       name="hapus_gambar"
                                       value="1"
                                       class="h-3.5 w-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                Hapus gambar ini
                            </label>
                        </div>
                    </div>
                @endif

                <input type="file"
                       id="gambar"
                       name="gambar"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-brand-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">
                    Format JPG, PNG, atau WebP, maksimal 4 MB. Kosongkan bila tidak ingin mengganti gambar.
                </p>

                @error('gambar')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div id="gambar-preview" class="mt-3 hidden overflow-hidden rounded-xl border border-slate-200">
                    <img id="gambar-preview-img" src="" alt="Preview gambar baru" class="h-40 w-full object-cover">
                </div>
            </div>
            <div>
                <label for="audio" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Audio Berita
                </label>

                @if (isset($berita) && $berita->audio_url)
                    @php
                        $audioLama = App\Support\Media::url($berita->audio_url);
                    @endphp

                    <div class="mb-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="mb-2 text-xs font-semibold text-slate-600">Audio saat ini</p>

                        @if ($audioLama)
                            <audio controls preload="none" class="w-full">
                                <source src="{{ $audioLama }}">
                                Browser Anda tidak mendukung pemutar audio.
                            </audio>
                        @else
                            <p class="truncate text-[11px] text-slate-400">{{ $berita->audio_url }}</p>
                        @endif

                        <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-rose-600">
                            <input type="checkbox"
                                   name="hapus_audio"
                                   value="1"
                                   class="h-3.5 w-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            Hapus audio ini
                        </label>
                    </div>
                @endif

                <input type="file"
                       id="audio"
                       name="audio"
                       accept="audio/mpeg,audio/mp3,audio/wav,audio/ogg,audio/mp4,audio/aac"
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <p class="mt-1.5 text-xs text-slate-400">
                    Format MP3, WAV, OGG, M4A, atau AAC, maksimal 10 MB.
                </p>

                @error('audio')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div class="mt-3">
                    <label for="audio_url" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
                        Atau URL Audio
                    </label>
                    <input type="text"
                           id="audio_url"
                           name="audio_url"
                           value="{{ old('audio_url', isset($berita) && $berita->audio_url && Str::startsWith($berita->audio_url, ['http://', 'https://']) ? $berita->audio_url : '') }}"
                           placeholder="https://contoh.com/audio.mp3"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    <p class="mt-1.5 text-xs text-slate-400">
                        Boleh dikosongkan. Jika memilih file upload sekaligus mengisi URL, file upload yang dipakai.
                    </p>
                </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('gambar');
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
