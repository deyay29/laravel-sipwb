@extends('layouts.admin')

@section('content')
<div class="card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <h2 class="mb-4">Kelola Profil & Kontak</h2>

    <form action="{{ route('admin.contact.update', $contact->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="address">Alamat</label>
            <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $contact->address ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="phone">Nomor Telepon</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="facebook">URL Facebook</label>
            <input type="url" name="facebook" id="facebook" class="form-control" value="{{ old('facebook', $contact->facebook ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="instagram">URL Instagram</label>
            <input type="url" name="instagram" id="instagram" class="form-control" value="{{ old('instagram', $contact->instagram ?? '') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="map_embed">Kode Embed Google Maps (Iframe)</label>
            <textarea name="map_embed" id="map_embed" class="form-control" rows="4">{{ old('map_embed', $contact->map_embed ?? '') }}</textarea>
            <small class="text-muted">Masukkan kode &lt;iframe&gt; dari Google Maps.</small>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
