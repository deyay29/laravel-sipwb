@extends('layouts.admin')

@section('content')
<div class="card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <h2 class="mb-4">{{ isset($wisata) ? 'Edit Wisata' : 'Tambah Wisata Baru' }}</h2>

    <form action="{{ isset($wisata) ? route('admin.wisata.update', $wisata->id) : route('admin.wisata.store') }}" method="POST">
        @csrf
        @if(isset($wisata))
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Nama Tempat Wisata *</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $wisata->title ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="location">Lokasi</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $wisata->location ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="image_url">URL Gambar</label>
            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', $wisata->image_url ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi *</label>
            <textarea name="description" id="description" class="form-control" rows="8" required>{{ old('description', $wisata->description ?? '') }}</textarea>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection
