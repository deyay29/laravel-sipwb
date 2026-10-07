@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Kelola Berita</h1>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">Tambah Berita</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beritas as $index => $berita)
            <tr>
                <td>{{ $beritas->firstItem() + $index }}</td>
                <td>{{ $berita->title }}</td>
                <td>{{ $berita->published_at ? \Carbon\Carbon::parse($berita->published_at)->format('d M Y') : '-' }}</td>
                <td style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-secondary" style="padding: 0.5rem;">Edit</a>
                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem;">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $beritas->links() }}
</div>
@endsection
