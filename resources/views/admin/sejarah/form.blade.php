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

        <form action="{{ isset($sejarah) ? route('admin.sejarah.update', $sejarah) : route('admin.sejarah.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
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
                    Gambar Utama
                </label>

                @if (isset($sejarah) && $sejarah->gambar_utama)
                    <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="h-24 w-36 shrink-0 overflow-hidden rounded-lg border border-slate-200">
                            <x-public.media :src="$sejarah->gambar_utama" :alt="$sejarah->judul_topik" icon="fa-landmark"
                                tone="violet" ratio="h-24" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-600">Gambar saat ini</p>
                            <p class="mt-1 truncate text-[11px] text-slate-400">{{ $sejarah->gambar_utama }}</p>

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
                       id="gambar_utama"
                       name="gambar_utama"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Format JPG, PNG, atau WebP, maksimal 4 MB. Kosongkan bila tidak ingin mengganti gambar.
                </p>

                @error('gambar_utama')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div id="gambar-preview" class="mt-3 hidden overflow-hidden rounded-xl border border-slate-200">
                    <img id="gambar-preview-img" src="" alt="Preview gambar baru" class="h-40 w-full object-cover">
                </div>
            </div>

            <div>
                <label for="video_storytelling" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Video Storytelling
                </label>

                @if (isset($sejarah) && $sejarah->video_storytelling)
                    @php
                        $videoLama = App\Support\Media::url($sejarah->video_storytelling);
                    @endphp

                    <div class="mb-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        @if ($videoLama)
                            <video controls preload="metadata" class="max-h-64 w-full bg-black">
                                <source src="{{ $videoLama }}">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        @else
                            <div class="grid h-32 place-items-center bg-slate-100 text-slate-300">
                                <i class="fas fa-video text-3xl"></i>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-3 p-3">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-semibold text-slate-600">
                                    {{ basename($sejarah->video_storytelling) }}
                                </p>
                                <p class="mt-0.5 text-[11px] text-slate-400">Video storytelling saat ini</p>
                            </div>

                            @if ($videoLama)
                                <a href="{{ $videoLama }}" target="_blank" rel="noopener"
                                   class="text-[11px] font-semibold text-brand-700 hover:text-brand-800">
                                    Buka video
                                </a>
                            @endif

                            <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-rose-600">
                                <input type="checkbox"
                                       name="hapus_video"
                                       value="1"
                                       class="h-3.5 w-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                Hapus
                            </label>
                        </div>
                    </div>
                @endif

                <input type="file"
                       id="video_storytelling"
                       name="video_storytelling"
                       accept="video/mp4,video/webm,video/quicktime"
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-navy-800 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-navy-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Format MP4, WebM, atau MOV, maksimal 50 MB. Kosongkan bila tidak ingin mengganti video.
                </p>

                @error('video_storytelling')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notebook" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Flipbook Digital
                </label>

                @if (isset($sejarah) && $sejarah->notebook)
                    @php
                        $flipbookSaatIni = App\Support\Media::url($sejarah->notebook) ?? $sejarah->notebook;
                    @endphp

                    <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600">
                            <i class="fas fa-book-open text-lg"></i>
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-semibold text-slate-600">
                                {{ $sejarah->notebook }}
                            </p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Tautan Flipbook Digital saat ini</p>

                            @if (Str::startsWith($flipbookSaatIni, ['http://', 'https://']))
                                <a href="{{ $flipbookSaatIni }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-semibold text-brand-700 hover:text-brand-800">
                                    <i class="fas fa-up-right-from-square text-[9px]"></i>
                                    Buka flipbook
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <input type="text"
                       id="notebook"
                       name="notebook"
                       value="{{ old('notebook', $sejarah->notebook ?? '') }}"
                       placeholder="https://contoh-flipbook.com/sejarah"
                       autocomplete="off"
                       spellcheck="false"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition placeholder:text-slate-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Tautan Flipbook Digital, harus diawali http:// atau https://. Kosongkan untuk menghapus tautan.
                </p>

                @error('notebook')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gambar_flipbook" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Gambar Cover Flipbook Digital
                </label>

                @if (isset($sejarah) && $sejarah->gambar_flipbook)
                    <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="h-24 w-36 shrink-0 overflow-hidden rounded-lg border border-slate-200">
                            <x-public.media :src="$sejarah->gambar_flipbook" :alt="$sejarah->judul_topik" icon="fa-book-open"
                                tone="violet" ratio="h-24" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-600">Gambar cover saat ini</p>
                            <p class="mt-1 truncate text-[11px] text-slate-400">{{ $sejarah->gambar_flipbook }}</p>

                            <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-rose-600">
                                <input type="checkbox"
                                       name="hapus_gambar_flipbook"
                                       value="1"
                                       class="h-3.5 w-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                Hapus gambar ini
                            </label>
                        </div>
                    </div>
                @endif

                <input type="file"
                       id="gambar_flipbook"
                       name="gambar_flipbook"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Gambar sampul untuk kartu History Flipbook Digital. Format JPG, PNG, atau WebP, maksimal 4 MB. Kosongkan bila tidak ingin mengganti.
                </p>

                @error('gambar_flipbook')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            @php
                // Bab untuk repopulasi form: input lama saat validasi gagal,
                // atau bab yang sudah tersimpan di database.
                $babItems = old('babs') ?? (isset($sejarah) ? $sejarah->babs->all() : []);
            @endphp

            {{-- Daftar bab dinamis: admin dapat menambah, mengedit, dan menghapus sekaligus --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 sm:p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-display text-sm font-bold text-slate-800">
                            <i class="fas fa-book-open text-xs text-brand-600"></i>
                            Daftar Bab (Sejarah per Bab)
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Tambahkan beberapa bab sekaligus. Setiap bab tampil sebagai daftar di halaman detail sejarah publik.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-200 bg-white px-3 py-1 text-[11px] font-bold text-brand-700">
                        <i class="fas fa-layer-group text-[10px]"></i>
                        <span id="bab-count-text">{{ count($babItems) }} bab</span>
                    </span>
                </div>

                <input type="hidden" name="babs_submitted" value="1">

                @error('babs')
                    <p class="mt-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600">
                        {{ $message }}
                    </p>
                @enderror

                <div id="bab-items-container" class="mt-4 space-y-4">
                    @if (empty($babItems))
                        <p id="bab-empty-hint"
                           class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-xs text-slate-400">
                            <i class="fas fa-book-open mb-1.5 block text-2xl text-slate-300"></i>
                            Belum ada bab. Klik tombol di bawah untuk menambahkan bab pertama.
                        </p>
                    @endif

                    @foreach ($babItems as $key => $babItem)
                        @include('admin.sejarah.partials.bab-item', ['key' => $key, 'item' => $babItem, 'nomor' => $loop->iteration])
                    @endforeach
                </div>

                <button type="button"
                        id="tambah-bab-btn"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-brand-400 bg-white px-4 py-2.5 font-display text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Bab
                </button>

                <template id="bab-item-template">
                    @include('admin.sejarah.partials.bab-item', ['key' => '__INDEX__', 'item' => [], 'nomor' => 0])
                </template>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('gambar_utama');
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

    // ===== Daftar bab: input dinamis (tambah / hapus baris) =====
    document.addEventListener('DOMContentLoaded', function () {
        const babContainer = document.getElementById('bab-items-container');
        const babTemplate = document.getElementById('bab-item-template');
        const tambahBabBtn = document.getElementById('tambah-bab-btn');

        if (!babContainer || !babTemplate || !tambahBabBtn) {
            return;
        }

        const babEmptyHint = document.getElementById('bab-empty-hint');
        const babCountText = document.getElementById('bab-count-text');

        function renumberBabItems() {
            const blocks = babContainer.querySelectorAll('.bab-item-block');

            blocks.forEach(function (block, i) {
                const nomor = block.querySelector('.bab-order-number');

                if (nomor) {
                    nomor.textContent = i + 1;
                }
            });

            if (babEmptyHint) {
                babEmptyHint.classList.toggle('hidden', blocks.length > 0);
            }

            if (babCountText) {
                babCountText.textContent = blocks.length + ' bab';
            }
        }

        function nextBabIndex() {
            let max = -1;

            babContainer.querySelectorAll('.bab-item-block [name*="[judul_bab]"]').forEach(function (el) {
                const match = el.name.match(/^babs\[(\d+)\]/);

                if (match) {
                    max = Math.max(max, parseInt(match[1], 10));
                }
            });

            return max + 1;
        }

        function nextBabNumber() {
            let max = 0;

            babContainer.querySelectorAll('.bab-item-block input[type="number"]').forEach(function (el) {
                const value = parseInt(el.value, 10);

                if (!isNaN(value)) {
                    max = Math.max(max, value);
                }
            });

            return max + 1;
        }

        tambahBabBtn.addEventListener('click', function () {
            const index = nextBabIndex();
            const clone = babTemplate.content.cloneNode(true);

            clone.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/__INDEX__/g, index);
            });

            clone.querySelectorAll('[id]').forEach(function (el) {
                el.id = el.id.replace(/__INDEX__/g, index);
            });

            clone.querySelectorAll('label[for]').forEach(function (el) {
                el.setAttribute('for', el.getAttribute('for').replace(/__INDEX__/g, index));
            });

            const nomorInput = clone.querySelector('input[type="number"]');

            if (nomorInput) {
                nomorInput.value = nextBabNumber();
            }

            babContainer.appendChild(clone);
            renumberBabItems();

            const judulBaru = babContainer.querySelector('[name="babs[' + index + '][judul_bab]"]');

            if (judulBaru) {
                judulBaru.focus();
            }
        });

        babContainer.addEventListener('click', function (event) {
            const hapusBtn = event.target.closest('.hapus-bab-btn');

            if (!hapusBtn) {
                return;
            }

            hapusBtn.closest('.bab-item-block').remove();
            renumberBabItems();
        });
    });
</script>
@endpush
