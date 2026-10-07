@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Kelola Sejarah</h1>
    <a href="{{ route('admin.sejarah.create') }}" class="btn btn-primary">Tambah Artikel Sejarah</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sejarahs as $index => $sejarah)
            <tr>
                <td>{{ $sejarahs->firstItem() + $index }}</td>
                <td>{{ $sejarah->title }}</td>
                <td style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.sejarah.edit', $sejarah->id) }}" class="btn btn-secondary" style="padding: 0.5rem;">Edit</a>
                    <form action="{{ route('admin.sejarah.destroy', $sejarah->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
    {{ $sejarahs->links() }}
</div>
@endsection
