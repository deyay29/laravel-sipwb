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

        <form action="{{ isset($wisata) ? route('admin.wisata.update', $wisata) : route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
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
                <label for="kategori_budget" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Kategori Anggaran (Smart Budget)
                </label>
                <select id="kategori_budget"
                        name="kategori_budget"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @foreach (($kategoriBudgetOptions ?? \App\Models\Wisata::BUDGET_TIERS) as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('kategori_budget', $wisata->kategori_budget ?? 'hemat') === $nilai)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-slate-400">
                    Menentukan destinasi yang tampil saat pengunjung memilih kategori Hemat, Sedang, atau Nyaman di tab Smart Budget.
                </p>
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
                <label for="fotos" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                    Foto Wisata
                </label>

                @php
                    $fotoTersimpan = isset($wisata) ? $wisata->fotos : collect();
                    $pathAlbum = $fotoTersimpan->pluck('path')->all();
                    $sampulLama = isset($wisata) && $wisata->gambar && ! in_array($wisata->gambar, $pathAlbum, true)
                        ? $wisata->gambar
                        : null;
                @endphp

                @if ($fotoTersimpan->isNotEmpty() || $sampulLama)
                    <div class="mb-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @if ($sampulLama)
                            <div class="relative overflow-hidden rounded-xl border border-slate-200">
                                <x-public.media :src="$sampulLama" alt="Foto sampul saat ini" icon="fa-mountain-sun"
                                    tone="emerald" ratio="h-28" />

                                <span class="absolute left-2 top-2 rounded-full bg-navy-800/85 px-2 py-0.5 text-[10px] font-bold text-white">
                                    Sampul lama
                                </span>
                            </div>
                        @endif

                        @foreach ($fotoTersimpan as $index => $foto)
                            <label class="group relative block cursor-pointer overflow-hidden rounded-xl border border-slate-200">
                                <x-public.media :src="$foto->path" :alt="'Foto '.($index + 1)" icon="fa-mountain-sun"
                                    tone="emerald" ratio="h-28" />

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
                       class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                <p class="mt-1.5 text-xs text-slate-400">
                    Bisa pilih beberapa foto sekaligus (multiple). Format JPG, PNG, atau WebP, maksimal 4 MB per foto.
                </p>

                @error('fotos')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                @error('fotos.*')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror

                <div id="foto-preview" class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4"></div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="quiz_tersedia" value="0">
                    <input type="checkbox"
                           name="quiz_tersedia"
                           id="quiz-tersedia"
                           value="1"
                           @checked(old('quiz_tersedia', $wisata->quiz_tersedia ?? false))
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span>
                        <span class="block font-display text-sm font-semibold text-slate-700">Aktifkan Quiz Wisata untuk destinasi ini</span>
                        <span class="mt-0.5 block text-xs text-slate-500">Jika diaktifkan, wisata ini akan muncul di tab Quiz pada halaman Wisata publik.</span>
                    </span>
                </label>
            </div>

            @php
                // Soal untuk repopulasi form: input lama saat validasi gagal,
                // atau soal yang sudah tersimpan di database.
                $quizItems = old('quiz') ?? (isset($wisata) ? $wisata->quiz->all() : []);
            @endphp

            {{-- Form dinamis soal kuis: hanya tampil saat checkbox kuis dicentang --}}
            <div id="quiz-form-section" class="hidden rounded-xl border border-brand-200 bg-brand-50/50 p-4 sm:p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-display text-sm font-bold text-slate-800">
                            <i class="fas fa-list-check text-xs text-brand-600"></i>
                            Soal Kuis <span id="quiz-section-title">{{ isset($wisata) ? $wisata->nama_wisata : 'Destinasi Ini' }}</span>
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Isi pertanyaan, empat pilihan jawaban (A-D), dan kunci jawaban yang benar.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-200 bg-white px-3 py-1 text-[11px] font-bold text-brand-700">
                        <i class="fas fa-circle-question text-[10px]"></i>
                        <span id="quiz-count-text">{{ count($quizItems) }} pertanyaan</span>
                    </span>
                </div>

                @error('quiz')
                    <p class="mt-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600">
                        {{ $message }}
                    </p>
                @enderror

                <div id="quiz-questions-container" class="mt-4 space-y-4">
                    @if (empty($quizItems))
                        <p id="quiz-empty-hint"
                           class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-xs text-slate-400">
                            <i class="fas fa-circle-question mb-1.5 block text-2xl text-slate-300"></i>
                            Belum ada pertanyaan. Klik tombol di bawah untuk menambahkan soal pertama.
                        </p>
                    @endif

                    @foreach ($quizItems as $key => $quizItem)
                        @include('admin.wisata.partials.quiz-question', ['key' => $key, 'item' => $quizItem, 'nomor' => $loop->iteration])
                    @endforeach
                </div>

                <button type="button"
                        id="tambah-quiz-btn"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-brand-400 bg-white px-4 py-2.5 font-display text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Pertanyaan
                </button>

                <p class="mt-3 text-xs text-slate-400">
                    Menonaktifkan centang kuis tidak menghapus soal yang sudah tersimpan.
                </p>

                <template id="quiz-question-template">
                    @include('admin.wisata.partials.quiz-question', ['key' => '__INDEX__', 'item' => [], 'nomor' => 0])
                </template>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-4 py-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-display text-sm font-semibold text-slate-700">Smart Budget</p>
                        <p class="mt-0.5 text-xs text-slate-500">Kelola paket anggaran yang ditampilkan di halaman Smart Budget.</p>
                    </div>
                    <a href="{{ route('admin.smart-budget.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand-200 hover:text-brand-700">
                        <i class="fas fa-calculator text-[10px]"></i>
                        Kelola Paket Smart Budget
                    </a>
                </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== Preview upload foto wisata =====
        const input = document.getElementById('fotos');
        const preview = document.getElementById('foto-preview');

        if (input && preview) {
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
        }

        // ===== Soal kuis wisata: tampil/sembunyi + input dinamis =====
        const quizToggle = document.getElementById('quiz-tersedia');
        const quizSection = document.getElementById('quiz-form-section');
        const quizContainer = document.getElementById('quiz-questions-container');
        const quizTemplate = document.getElementById('quiz-question-template');
        const tambahQuizBtn = document.getElementById('tambah-quiz-btn');

        if (!quizToggle || !quizSection || !quizContainer || !quizTemplate || !tambahQuizBtn) {
            return;
        }

        const quizEmptyHint = document.getElementById('quiz-empty-hint');
        const quizCountText = document.getElementById('quiz-count-text');

        /**
         * Tampilkan/sembunyikan form soal mengikuti checkbox "Aktifkan Quiz".
         * Saat disembunyikan semua kontrolnya dinonaktifkan supaya tidak ikut
         * tervalidasi (required) maupun terkirim saat form disimpan.
         */
        function syncQuizSection() {
            const aktif = quizToggle.checked;

            quizSection.classList.toggle('hidden', !aktif);
            quizSection.querySelectorAll('input, select, textarea, button').forEach(function (el) {
                el.disabled = !aktif;
            });
        }

        /**
         * Perbarui nomor urut tampilan tiap blok, hint kosong, dan jumlah soal.
         */
        function renumberQuizQuestions() {
            const blocks = quizContainer.querySelectorAll('.quiz-question-block');

            blocks.forEach(function (block, i) {
                const nomor = block.querySelector('.quiz-question-number');

                if (nomor) {
                    nomor.textContent = i + 1;
                }
            });

            if (quizEmptyHint) {
                quizEmptyHint.classList.toggle('hidden', blocks.length > 0);
            }

            if (quizCountText) {
                quizCountText.textContent = blocks.length + ' pertanyaan';
            }
        }

        /**
         * Indeks nama input untuk blok soal berikutnya (quiz[idx][...]).
         */
        function nextQuizIndex() {
            let max = -1;

            quizContainer.querySelectorAll('.quiz-question-block [name*="[pertanyaan]"]').forEach(function (el) {
                const match = el.name.match(/^quiz\[(\d+)\]/);

                if (match) {
                    max = Math.max(max, parseInt(match[1], 10));
                }
            });

            return max + 1;
        }

        tambahQuizBtn.addEventListener('click', function () {
            const index = nextQuizIndex();
            const clone = quizTemplate.content.cloneNode(true);

            clone.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace('__INDEX__', index);
            });

            clone.querySelectorAll('[id]').forEach(function (el) {
                el.id = el.id.replace('__INDEX__', index);
            });

            clone.querySelectorAll('label[for]').forEach(function (el) {
                el.setAttribute('for', el.getAttribute('for').replace('__INDEX__', index));
            });

            quizContainer.appendChild(clone);
            renumberQuizQuestions();

            const pertanyaanBaru = quizContainer.querySelector('[name="quiz[' + index + '][pertanyaan]"]');

            if (pertanyaanBaru) {
                pertanyaanBaru.focus();
            }
        });

        quizContainer.addEventListener('click', function (event) {
            const hapusBtn = event.target.closest('.hapus-quiz-btn');

            if (!hapusBtn) {
                return;
            }

            hapusBtn.closest('.quiz-question-block').remove();
            renumberQuizQuestions();
        });

        // Judul section mengikuti nama wisata yang sedang diketik.
        const namaInput = document.getElementById('nama_wisata');
        const quizTitle = document.getElementById('quiz-section-title');

        if (namaInput && quizTitle) {
            const updateQuizTitle = function () {
                quizTitle.textContent = namaInput.value.trim() || 'Destinasi Ini';
            };

            namaInput.addEventListener('input', updateQuizTitle);
            updateQuizTitle();
        }

        quizToggle.addEventListener('change', syncQuizSection);
        syncQuizSection();
        renumberQuizQuestions();
    });
</script>
@endpush
