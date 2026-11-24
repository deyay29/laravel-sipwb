{{--
    Satu blok pertanyaan kuis wisata.

    Dipakai dua arah:
    1. Render awal di form admin (data tersimpan / input lama saat validasi gagal).
    2. Sebagai cetakan (template) untuk tombol "Tambah Pertanyaan"
       dengan indeks placeholder `__INDEX__` yang diganti JavaScript.

    Variabel yang diharapkan:
    - $key   : indeks blok, dipakai untuk attribute name dan repopulasi old().
    - $item  : data soal (array hasil old() atau model QuizWisata), boleh kosong.
    - $nomor : nomor tampilan pada header blok.
--}}
@php
    $pilihanSaatIni = old("quiz.{$key}.pilihan", $item['pilihan'] ?? []) ?? [];
    $kunciSaatIni = old("quiz.{$key}.jawaban_benar", $item['jawaban_benar'] ?? null);
    $kunciSaatIni = ($kunciSaatIni === null || $kunciSaatIni === '') ? null : (int) $kunciSaatIni;
@endphp

<div class="quiz-question-block space-y-3 rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
    <div class="flex items-center justify-between gap-3">
        <span class="inline-flex items-center gap-2 font-display text-sm font-bold text-slate-700">
            <i class="fas fa-circle-question text-xs text-brand-600"></i>
            Pertanyaan <span class="quiz-question-number">{{ $nomor }}</span>
        </span>

        <button type="button"
                class="hapus-quiz-btn inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
            <i class="fas fa-trash text-[10px]"></i> Hapus
        </button>
    </div>

    <div>
        <label for="quiz-{{ $key }}-pertanyaan" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
            Pertanyaan <span class="text-rose-500">*</span>
        </label>

        <textarea id="quiz-{{ $key }}-pertanyaan"
                  name="quiz[{{ $key }}][pertanyaan]"
                  rows="2"
                  maxlength="255"
                  required
                  placeholder="Contoh: Gedung Sate mulai dibangun pada tahun?"
                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old("quiz.{$key}.pertanyaan", $item['pertanyaan'] ?? '') }}</textarea>

        @error("quiz.{$key}.pertanyaan")
            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        @foreach (range(0, 3) as $index)
            <div>
                <label for="quiz-{{ $key }}-pilihan-{{ $index }}"
                       class="mb-1.5 flex items-center gap-1.5 font-display text-xs font-semibold text-slate-600">
                    <span class="grid h-5 w-5 place-items-center rounded-md bg-brand-50 text-[10px] font-bold text-brand-700">{{ chr(65 + $index) }}</span>
                    Pilihan {{ chr(65 + $index) }} <span class="text-rose-500">*</span>
                </label>

                <input type="text"
                       id="quiz-{{ $key }}-pilihan-{{ $index }}"
                       name="quiz[{{ $key }}][pilihan][{{ $index }}]"
                       value="{{ old("quiz.{$key}.pilihan.{$index}", $pilihanSaatIni[$index] ?? '') }}"
                       maxlength="255"
                       required
                       placeholder="Teks pilihan jawaban {{ chr(65 + $index) }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

                @error("quiz.{$key}.pilihan.{$index}")
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        <div>
            <label for="quiz-{{ $key }}-jawaban" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
                Kunci Jawaban <span class="text-rose-500">*</span>
            </label>

            <select id="quiz-{{ $key }}-jawaban"
                    name="quiz[{{ $key }}][jawaban_benar]"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <option value="" @selected($kunciSaatIni === null)>-- Pilih --</option>

                @foreach (range(0, 3) as $index)
                    <option value="{{ $index }}" @selected($kunciSaatIni === $index)>{{ chr(65 + $index) }}</option>
                @endforeach
            </select>

            @error("quiz.{$key}.jawaban_benar")
                <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="quiz-{{ $key }}-penjelasan" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
                Penjelasan <span class="font-normal text-slate-400">(opsional)</span>
            </label>

            <textarea id="quiz-{{ $key }}-penjelasan"
                      name="quiz[{{ $key }}][penjelasan]"
                      rows="2"
                      maxlength="10000"
                      placeholder="Penjelasan singkat untuk jawaban yang benar."
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old("quiz.{$key}.penjelasan", $item['penjelasan'] ?? '') }}</textarea>
        </div>
    </div>
</div>
