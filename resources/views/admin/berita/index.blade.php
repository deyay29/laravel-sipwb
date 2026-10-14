@extends('layouts.admin')

@section('title', 'Manajemen Berita')
@section('topbar-title', 'Manajemen Berita')

@section('content')
<x-admin.page-header
    title="Kelola Berita"
    description="Berita beserta audio, jumlah dibaca, dan reaksi pembaca."
    :action="route('admin.berita.index')"
    action-label="Tambah Berita"
/>

<x-admin.table-shell :paginator="$beritas">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Judul</th>
                <th class="px-5 py-3.5">Kategori</th>
                <th class="px-5 py-3.5">Tanggal</th>
                <th class="px-5 py-3.5">Dibaca</th>
                <th class="px-5 py-3.5">Reaksi</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($beritas as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $beritas->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                    <p class="font-display font-semibold text-slate-800">{{ Str::limit($row->judul, 60) }}</p>
                    <p class="text-xs text-slate-400">{{ $row->slug }}</p>
                </td>
                <td class="px-5 py-3.5">
                    @if ($row->kategori)
                        <span class="inline-flex rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700">
                            {{ $row->kategori }}
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ $row->tanggal?->format('d M Y H:i') ?? '-' }}</td>
                <td class="px-5 py-3.5 text-slate-600">{{ number_format($row->visitor_count, 0, ',', '.') }}</td>
                <td class="px-5 py-3.5 text-xs text-slate-500">
                    @if ($row->reaksi)
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-thumbs-up text-[10px] text-brand-500"></i>
                            {{ number_format($row->reaksi->like_count, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-hands-clapping text-[10px] text-emerald-500"></i>
                            {{ number_format($row->reaksi->applause_count, 0, ',', '.') }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.berita.edit', $row) }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                            <i class="fas fa-pen text-[10px]"></i> Edit
                        </a>
                        <form action="{{ route('admin.berita.destroy', $row) }}" method="POST"
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
                <td colspan="7">
                    <x-public.empty-state
                        class="border-0"
                        title="Belum ada berita"
                        description="Tulis berita pertama Anda."
                        icon="fa-newspaper"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
