@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Kelola Wisata</h1>
    <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary">Tambah Wisata</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wisatas as $index => $wisata)
            <tr>
                <td>{{ $wisatas->firstItem() + $index }}</td>
                <td>{{ $wisata->title }}</td>
                <td>{{ $wisata->location ?? '-' }}</td>
                <td style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.wisata.edit', $wisata->id) }}" class="btn btn-secondary" style="padding: 0.5rem;">Edit</a>
                    <form action="{{ route('admin.wisata.destroy', $wisata->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
    {{ $wisatas->links() }}
</div>
@endsection
