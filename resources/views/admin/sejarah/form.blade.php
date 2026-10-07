@extends('layouts.admin')

@section('content')
<div class="card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <h2 class="mb-4">{{ isset($sejarah) ? 'Edit Sejarah' : 'Tambah Artikel Sejarah Baru' }}</h2>

    <form action="{{ isset($sejarah) ? route('admin.sejarah.update', $sejarah->id) : route('admin.sejarah.store') }}" method="POST">
        @csrf
        @if(isset($sejarah))
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Judul *</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $sejarah->title ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="image_url">URL Gambar</label>
            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', $sejarah->image_url ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Isi Artikel *</label>
            <textarea name="description" id="description" class="form-control" rows="8" required>{{ old('description', $sejarah->description ?? '') }}</textarea>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.sejarah.index') }}" class="btn btn-secondary ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection
