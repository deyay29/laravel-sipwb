@extends('layouts.admin')

@section('title', 'Manajemen Wisata')
@section('topbar-title', 'Manajemen Wisata')

@section('content')
<x-admin.page-header
    title="Kelola Wisata"
    description="Daftar tempat wisata yang tampil di halaman publik."
    :action="route('admin.wisata.index')"
    action-label="Tambah Wisata"
/>

<x-admin.table-shell :paginator="$wisatas">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Nama Wisata</th>
                <th class="px-5 py-3.5">Kategori</th>
                <th class="px-5 py-3.5">Lokasi</th>
                <th class="px-5 py-3.5">Harga Tiket</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($wisatas as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $wisatas->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                    <p class="font-display font-semibold text-slate-800">{{ $row->nama_wisata }}</p>
                    <p class="text-xs text-slate-400">{{ $row->slug }}</p>
                </td>
                <td class="px-5 py-3.5 text-slate-600">{{ $row->kategori ?? '-' }}</td>
                <td class="px-5 py-3.5 text-slate-600">{{ $row->lokasi ?? '-' }}</td>
                <td class="px-5 py-3.5 text-slate-600">{{ $row->harga_tiket ? 'Rp'.number_format($row->harga_tiket, 0, ',', '.') : 'Gratis' }}</td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.wisata.edit', $row) }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                            <i class="fas fa-pen text-[10px]"></i> Edit
                        </a>
                        <form action="{{ route('admin.wisata.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus {{ $row->nama_wisata }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-600">
                                <i class="fas fa-trash text-[10px]"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-public.empty-state
                        class="border-0"
                        title="Belum ada data wisata"
                        description="Tambahkan tempat wisata pertama Anda."
                        icon="fa-mountain-sun"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
