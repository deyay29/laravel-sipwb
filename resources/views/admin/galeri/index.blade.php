@extends('layouts.admin')

@section('title', 'Manajemen Galeri')
@section('topbar-title', 'Manajemen Galeri')

@section('content')
<x-admin.page-header
    title="Kelola Galeri"
    description="Kumpulan foto yang ditampilkan di repository visual publik."
    :action="route('admin.galeri.index')"
    action-label="Tambah Foto"
/>

<x-admin.table-shell :paginator="$galeris">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Gambar</th>
                <th class="px-5 py-3.5">Judul</th>
                <th class="px-5 py-3.5">Kategori</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($galeris as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $galeris->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                <x-public.media
                    :src="$row->file_gambar"
                    alt="{{ $row->judul }}"
                    icon="fa-image"
                    tone="slate"
                    ratio="h-48"
                    class="w-20 rounded-lg object-cover"
                />
                </td>
                <td class="px-5 py-3.5">
                    <p class="font-display font-semibold text-slate-800">{{ $row->judul }}</p>
                    <p class="text-xs text-slate-400">{{ Str::limit($row->keterangan ?? '-', 50) }}</p>
                </td>
                <td class="px-5 py-3.5 text-slate-600">{{ $row->kategori_objek ?? '-' }}</td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.galeri.edit', $row) }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                            <i class="fas fa-pen text-[10px]"></i> Edit
                        </a>
                        <form action="{{ route('admin.galeri.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus {{ $row->judul }}?');">
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
                <td colspan="5">
                    <x-public.empty-state
                        class="border-0"
                        title="Belum ada foto"
                        description="Unggah foto pertama ke galeri."
                        icon="fa-images"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
