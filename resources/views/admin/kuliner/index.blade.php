@extends('layouts.admin')

@section('title', 'Manajemen Kuliner')
@section('topbar-title', 'Manajemen Kuliner')

@section('content')
<x-admin.page-header
    title="Kelola Kuliner"
    description="Kuliner dengan kategori suhu dan status tayang."
    :action="route('admin.kuliner.index')"
    action-label="Tambah Kuliner"
/>

<x-admin.table-shell :paginator="$kuliners">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Nama Kuliner</th>
                <th class="px-5 py-3.5">Kategori Suhu</th>
                <th class="px-5 py-3.5">Harga</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($kuliners as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $kuliners->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                    <p class="font-display font-semibold text-slate-800">{{ $row->nama_kuliner }}</p>
                    <p class="text-xs text-slate-400">{{ $row->slug }}</p>
                </td>
                <td class="px-5 py-3.5">
                    @if ($row->kategori_suhu)
                        <span @class([
                            'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold',
                            'bg-rose-100 text-rose-700' => $row->kategori_suhu === \App\Models\Kuliner::SUHU_MENGHANGATKAN,
                            'bg-sky-100 text-sky-700' => $row->kategori_suhu === \App\Models\Kuliner::SUHU_MENYEGARKAN,
                        ])>
                            <i class="fas {{ $row->kategori_suhu === \App\Models\Kuliner::SUHU_MENGHANGATKAN ? 'fa-fire' : 'fa-snowflake' }} text-[10px]"></i>
                            {{ $row->kategori_suhu }}
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-slate-600">{{ $row->harga ? 'Rp'.number_format($row->harga, 0, ',', '.') : '-' }}</td>
                <td class="px-5 py-3.5">
                    <span @class([
                        'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold',
                        'bg-emerald-100 text-emerald-700' => $row->status === \App\Models\Kuliner::STATUS_AKTIF,
                        'bg-slate-200 text-slate-600' => $row->status === \App\Models\Kuliner::STATUS_NONAKTIF,
                    ])>
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $row->status }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.kuliner.edit', $row) }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                            <i class="fas fa-pen text-[10px]"></i> Edit
                        </a>
                        <form action="{{ route('admin.kuliner.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus {{ $row->nama_kuliner }}?');">
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
                        title="Belum ada data kuliner"
                        description="Tambahkan kuliner pertama Anda."
                        icon="fa-utensils"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
