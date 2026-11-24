{{--
    Satu baris bab sejarah di form admin.

    Dipakai dua arah:
    1. Render awal (data tersimpan atau input lama saat validasi gagal).
    2. Sebagai cetakan (template) untuk tombol "Tambah Bab"
       dengan indeks placeholder `__INDEX__` yang diganti JavaScript.

    Variabel yang diharapkan:
    - $key   : indeks baris, dipakai untuk attribute name dan repopulasi old().
    - $item  : data bab (array hasil old() atau model SejarahBab), boleh kosong.
    - $nomor : nomor tampilan urutan baris.
--}}
@php
    $nomorSaatIni = old("babs.{$key}.nomor_bab", data_get($item, 'nomor_bab') ?: ($nomor ?: 1));
    $judulSaatIni = old("babs.{$key}.judul_bab", data_get($item, 'judul_bab') ?? '');
    $isiSaatIni = old("babs.{$key}.isi_bab", data_get($item, 'isi_bab') ?? '');
    $gambarLama = old("babs.{$key}.gambar_lama", data_get($item, 'gambar'));
    $hapusGambar = (bool) old("babs.{$key}.hapus_gambar", false);
@endphp

<div class="bab-item-block space-y-3 rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
    <div class="flex items-center justify-between gap-3">
        <span class="inline-flex items-center gap-2 font-display text-sm font-bold text-slate-700">
            <i class="fas fa-book-open text-xs text-brand-600"></i>
            Bab <span class="bab-order-number">{{ $nomor }}</span>
        </span>

        <button type="button"
                class="hapus-bab-btn inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
            <i class="fas fa-trash text-[10px]"></i> Hapus
        </button>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        <div>
            <label for="bab-{{ $key }}-nomor" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
                Nomor Bab <span class="text-rose-500">*</span>
            </label>

            <input type="number"
                   id="bab-{{ $key }}-nomor"
                   name="babs[{{ $key }}][nomor_bab]"
                   value="{{ $nomorSaatIni }}"
                   min="1"
                   max="999"
                   required
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

            @error("babs.{$key}.nomor_bab")
                <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="bab-{{ $key }}-judul" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
                Judul Bab <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   id="bab-{{ $key }}-judul"
                   name="babs[{{ $key }}][judul_bab]"
                   value="{{ $judulSaatIni }}"
                   maxlength="255"
                   required
                   placeholder="Contoh: Awal Mula Konferensi Asia Afrika"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

            @error("babs.{$key}.judul_bab")
                <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="bab-{{ $key }}-isi" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
            Isi Bab <span class="font-normal text-slate-400">(opsional)</span>
        </label>

        <textarea id="bab-{{ $key }}-isi"
                  name="babs[{{ $key }}][isi_bab]"
                  rows="4"
                  placeholder="Tulis isi cerita bab ini..."
                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ $isiSaatIni }}</textarea>

        @error("babs.{$key}.isi_bab")
            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="bab-{{ $key }}-gambar" class="mb-1.5 block font-display text-xs font-semibold text-slate-600">
            Gambar Bab <span class="font-normal text-slate-400">(opsional)</span>
        </label>

        <input type="hidden" name="babs[{{ $key }}][gambar_lama]" value="{{ $gambarLama }}">

        @if ($gambarLama)
            <div class="mb-3 flex flex-wrap items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="h-16 w-24 shrink-0 overflow-hidden rounded-lg border border-slate-200">
                    <x-public.media :src="$gambarLama" alt="Gambar bab" icon="fa-book-open" tone="violet" ratio="h-16" />
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-[11px] text-slate-400">{{ $gambarLama }}</p>

                    <label class="mt-1 inline-flex cursor-pointer items-center gap-2 text-[11px] font-semibold text-rose-600">
                        <input type="checkbox"
                               name="babs[{{ $key }}][hapus_gambar]"
                               value="1"
                               @checked($hapusGambar)
                               class="h-3.5 w-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                        Hapus gambar ini
                    </label>
                </div>
            </div>
        @endif

        <input type="file"
               id="bab-{{ $key }}-gambar"
               name="babs[{{ $key }}][gambar]"
               accept="image/jpeg,image/png,image/webp"
               class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">

        <p class="mt-1.5 text-[11px] text-slate-400">
            Format JPG, PNG, atau WebP, maksimal 4 MB. Kosongkan bila tidak ingin mengganti gambar.
        </p>

        @error("babs.{$key}.gambar")
            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
