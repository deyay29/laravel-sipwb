@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Kelola Kuliner</h1>
    <a href="{{ route('admin.kuliner.create') }}" class="btn btn-primary">Tambah Kuliner</a>
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
            @foreach($kuliners as $index => $kuliner)
            <tr>
                <td>{{ $kuliners->firstItem() + $index }}</td>
                <td>{{ $kuliner->title }}</td>
                <td>{{ $kuliner->location ?? '-' }}</td>
                <td style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.kuliner.edit', $kuliner->id) }}" class="btn btn-secondary" style="padding: 0.5rem;">Edit</a>
                    <form action="{{ route('admin.kuliner.destroy', $kuliner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
    {{ $kuliners->links() }}
</div>
@endsection
