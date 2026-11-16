@extends('layouts.admin')

@section('title', 'Smart Budget')
@section('topbar-title', 'Smart Budget')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-lg font-bold text-slate-800">Daftar Paket Smart Budget</h2>
                <p class="text-sm text-slate-500">Kelola paket anggaran perjalanan untuk halaman publik.</p>
            </div>
            <a href="{{ route('admin.smart-budget.create') }}"
               class="inline-flex items-center gap-2 rounded-[10px] bg-accent-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-accent-600">
                <i class="fas fa-plus text-xs"></i>
                Tambah Paket
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="fas fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.02)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Total Estimasi</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($pakets as $paket)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 overflow-hidden rounded-lg bg-slate-100">
                                            @if ($paket->foto)
                                                <img src="{{ App\Support\Media::url($paket->foto) }}" alt="{{ $paket->nama_paket }}" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-display text-sm font-semibold text-slate-800">{{ $paket->nama_paket }}</p>
                                            <p class="text-xs text-slate-500">{{ Str::limit(strip_tags($paket->deskripsi), 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $paket->kategori ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">Rp {{ number_format($paket->total_estimasi, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.smart-budget.edit', $paket) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-brand-200 hover:text-brand-700">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.smart-budget.destroy', $paket) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Belum ada paket Smart Budget.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pakets->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $pakets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
