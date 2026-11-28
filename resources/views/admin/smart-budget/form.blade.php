@extends('layouts.admin')

@section('title', isset($paket) ? 'Edit Paket Smart Budget' : 'Tambah Paket Smart Budget')
@section('topbar-title', isset($paket) ? 'Edit Paket Smart Budget' : 'Tambah Paket Smart Budget')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.smart-budget.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-700">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar paket
            </a>
        </div>

        <form action="{{ isset($paket) ? route('admin.smart-budget.update', $paket) : route('admin.smart-budget.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_1px_2px_rgba(15,23,42,0.02)] sm:p-8">
            @csrf
            @if (isset($paket))
                @method('PUT')
            @endif

            <div>
                <h2 class="font-display text-lg font-bold text-slate-800">{{ isset($paket) ? 'Ubah Paket' : 'Tambah Paket Baru' }}</h2>
                <p class="text-sm text-slate-500">Lengkapi informasi paket anggaran untuk ditampilkan di halaman Smart Budget.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nama_paket" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Nama Paket</label>
                    <input type="text" id="nama_paket" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket ?? '') }}" required
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @error('nama_paket')
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Kategori</label>
                    <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $paket->kategori ?? '') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @error('kategori')
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_estimasi" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Total Estimasi (Rp)</label>
                    <input type="number" id="total_estimasi" name="total_estimasi" value="{{ old('total_estimasi', $paket->total_estimasi ?? '') }}" required min="0"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    @error('total_estimasi')
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="deskripsi" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('deskripsi', $paket->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="foto" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Foto Paket</label>
                    @if (isset($paket) && $paket->foto)
                        <div class="mb-3 overflow-hidden rounded-xl border border-slate-200">
                            <img src="{{ App\Support\Media::url($paket->foto) }}" alt="{{ $paket->nama_paket }}" class="h-56 w-full object-cover">
                        </div>
                    @endif
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                           class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-accent-500 file:px-4 file:py-2 file:font-display file:text-sm file:font-semibold file:text-white hover:file:bg-accent-600 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                    <p class="mt-1.5 text-xs text-slate-400">Format JPG, PNG, atau WebP, maksimal 4 MB.</p>
                    @error('foto')
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-sm font-semibold text-slate-700">Rincian Biaya</h3>
                        <p class="text-xs text-slate-500">Tambahkan item rincian biaya untuk paket ini.</p>
                    </div>
                    <button type="button" id="add-rincian" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand-200 hover:text-brand-700">
                        <i class="fas fa-plus text-[10px]"></i>
                        Tambah Item
                    </button>
                </div>
                <div id="rincian-list" class="space-y-2"></div>
                <input type="hidden" name="rincian_json" id="rincian_json">
                @error('rincian_json')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit" class="inline-flex items-center gap-2 rounded-[10px] bg-accent-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-accent-600">
                    <i class="fas fa-save text-xs"></i>
                    Simpan Paket
                </button>
                <a href="{{ route('admin.smart-budget.index') }}" class="inline-flex items-center gap-2 rounded-[10px] border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const rincianList = document.getElementById('rincian-list');
        const addBtn = document.getElementById('add-rincian');
        const rincianJson = document.getElementById('rincian_json');
        let items = [];

        @if (isset($paket) && is_array($paket->rincian_biaya))
            items = @json($paket->rincian_biaya);
        @endif

        function render() {
            rincianList.innerHTML = '';
            items.forEach((item, index) => {
                const row = document.createElement('div');
                row.className = 'flex flex-col gap-2 rounded-xl border border-slate-200 p-3 sm:flex-row sm:items-center';
                row.innerHTML = `
                    <input type="text" placeholder="Nama item" value="${(item.item || '').replace(/"/g, '&quot;')}"
                        class="rincian-item flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        data-index="${index}" data-field="item">
                    <input type="number" placeholder="Biaya" value="${item.biaya || 0}" min="0"
                        class="rincian-item w-full rounded-lg border border-slate-200 px-3 py-2 text-sm sm:w-40"
                        data-index="${index}" data-field="biaya">
                    <button type="button" class="remove-rincian inline-flex items-center gap-1 rounded-lg border border-rose-200 px-2.5 py-1.5 text-xs font-semibold text-rose-600" data-index="${index}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                `;
                rincianList.appendChild(row);
            });
            sync();
        }

        function sync() {
            const data = [];
            rincianList.querySelectorAll('.rincian-item').forEach((input) => {
                const idx = parseInt(input.dataset.index);
                if (!data[idx]) data[idx] = {};
                if (input.dataset.field === 'biaya') {
                    data[idx].biaya = parseInt(input.value || 0);
                } else {
                    data[idx].item = input.value;
                }
            });
            items = data.filter(Boolean);
            rincianJson.value = JSON.stringify(items);
        }

        addBtn.addEventListener('click', () => {
            items.push({ item: '', biaya: 0 });
            render();
        });

        rincianList.addEventListener('input', (e) => {
            if (e.target.classList.contains('rincian-item')) {
                sync();
            }
        });

        rincianList.addEventListener('click', (e) => {
            if (e.target.closest('.remove-rincian')) {
                const idx = parseInt(e.target.closest('.remove-rincian').dataset.index);
                items.splice(idx, 1);
                render();
            }
        });

        render();
    </script>
@endsection
