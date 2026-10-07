@extends('layouts.admin')

@section('content')
<div class="card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <h2 class="mb-4">{{ isset($berita) ? 'Edit Berita' : 'Tambah Berita Baru' }}</h2>

    <form action="{{ isset($berita) ? route('admin.berita.update', $berita->id) : route('admin.berita.store') }}" method="POST">
        @csrf
        @if(isset($berita))
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Judul Berita *</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $berita->title ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="published_at">Tanggal Terbit</label>
            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', isset($berita->published_at) ? date('Y-m-d\TH:i', strtotime($berita->published_at)) : '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="image_url">URL Gambar</label>
            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', $berita->image_url ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="content">Isi Berita *</label>
            <textarea name="content" id="content" class="form-control" rows="10" required>{{ old('content', $berita->content ?? '') }}</textarea>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection
