@extends('layouts.admin')

@section('title', 'Kritik & Masukan')
@section('topbar-title', 'Kritik & Masukan')

@section('content')
<x-admin.page-header
    title="Kritik, Saran & Masukan"
    description="Daftar kritik, saran, atau masukan pengunjung yang dikirim melalui halaman Kontak."
/>

<x-admin.table-shell :paginator="$messages">
    <x-slot:head>
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No</th>
                <th class="px-5 py-3.5">Kategori</th>
                <th class="px-5 py-3.5">Isi Pesan</th>
                <th class="px-5 py-3.5">Pengirim</th>
                <th class="px-5 py-3.5">Tanggal</th>
                <th class="px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
    </x-slot:head>

    <x-slot:body>
        @forelse ($messages as $index => $row)
            <tr class="transition hover:bg-slate-50">
                <td class="px-5 py-3.5 text-slate-500">{{ $messages->firstItem() + $index }}</td>
                <td class="px-5 py-3.5">
                    <span @class([
                        'inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide',
                        'bg-rose-50 text-rose-700' => $row->kategori === \App\Models\ContactMessage::KATEGORI_KRITIK,
                        'bg-sky-50 text-sky-700' => $row->kategori === \App\Models\ContactMessage::KATEGORI_SARAN,
                        'bg-amber-50 text-amber-700' => $row->kategori === \App\Models\ContactMessage::KATEGORI_MASUKAN,
                    ])>
                        {{ \App\Models\ContactMessage::KATEGORI_LABELS[$row->kategori] ?? ucfirst($row->kategori) }}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <p class="max-w-md whitespace-pre-line text-slate-700">{{ $row->pesan }}</p>
                </td>
                <td class="px-5 py-3.5 text-slate-600">
                    <p class="font-medium text-slate-800">{{ $row->pengirimNama() }}</p>
                    @if ($row->email)
                        <p class="text-xs text-slate-400">{{ $row->email }}</p>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-slate-600">
                    {{ $row->created_at?->format('d M Y H:i') ?? '-' }}
                </td>
                <td class="px-5 py-3.5">
                    <form action="{{ route('admin.contact.messages.destroy', $row) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus pesan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-600">
                            <i class="fas fa-trash text-[10px]"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-public.empty-state
                        class="border-0"
                        title="Belum ada masukan"
                        description="Belum ada kritik, saran, atau masukan yang dikirim pengunjung."
                        icon="fa-comment-sms"
                    />
                </td>
            </tr>
        @endforelse
    </x-slot:body>
</x-admin.table-shell>
@endsection
