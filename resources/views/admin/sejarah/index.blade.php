@extends('layouts.admin')

@section('title', 'Manajemen Sejarah')
@section('topbar-title', 'Sejarah & Museum')

@section('content')
<x-admin.page-header
    title="Kelola Topik Sejarah"
    description="Setiap topik dapat dipecah menjadi beberapa bab storytelling."
    :action="route('admin.sejarah.create')"
    action-label="Tambah Topik"
/>

<x-admin.table-shell :paginator="$sejarahs">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Gambar</th>
                <th class="px-5 py-3.5">Judul Topik</th>
                <th class="px-5 py-3.5">Jumlah Bab</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($sejarahs as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $sejarahs->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                    <div class="h-12 w-16 overflow-hidden rounded-lg border border-slate-200">
                        <x-public.media :src="$row->gambar_utama" :alt="$row->judul_topik" icon="fa-landmark"
                            tone="violet" ratio="h-12" />
                    </div>
                </td>
                <td class="px-5 py-3.5">
                    <p class="font-display font-semibold text-slate-800">{{ $row->judul_topik }}</p>
                    <p class="text-xs text-slate-400">{{ $row->slug }}</p>

                    @if ($row->video_storytelling || $row->notebook)
                        <div class="mt-1.5 flex flex-wrap items-center gap-2 text-[10px] font-semibold">
                            @if ($row->video_storytelling)
                                <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-0.5 text-violet-700">
                                    <i class="fas fa-video text-[9px]"></i> Video
                                </span>
                            @endif

                            @if ($row->notebook)
                                <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-2 py-0.5 text-brand-700">
                                    <i class="fas fa-book-open text-[9px]"></i> Flipbook
                                </span>
                            @endif
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                        <i class="fas fa-book-open text-[10px]"></i>
                        {{ $row->babs_count }} bab
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.sejarah.edit', $row) }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                            <i class="fas fa-pen text-[10px]"></i> Edit
                        </a>
                        <form action="{{ route('admin.sejarah.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus {{ $row->judul_topik }}?');">
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
                        title="Belum ada topik sejarah"
                        description="Tambahkan topik sejarah pertama Anda."
                        icon="fa-landmark"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
