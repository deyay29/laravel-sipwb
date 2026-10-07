@extends('layouts.app')
@section('title', 'Profil & Kontak')

@section('content')
<div class="container mt-4 mb-5">
    <div style="max-width: 800px; margin: 0 auto; background: var(--bg-white); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <h1 class="mb-4 text-center">Profil & Kontak Kami</h1>
        
        @if($contact)
            <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                <div>
                    <h3>Informasi Kontak</h3>
                    <ul style="list-style: none; padding-left: 0; margin-top: 1rem; font-size: 1.1rem;">
                        <li style="margin-bottom: 1rem;"><strong>Alamat:</strong> <br> {{ $contact->address ?? 'Belum ada data' }}</li>
                        <li style="margin-bottom: 1rem;"><strong>Email:</strong> <br> <a href="mailto:{{ $contact->email }}">{{ $contact->email ?? 'Belum ada data' }}</a></li>
                        <li style="margin-bottom: 1rem;"><strong>Telepon:</strong> <br> {{ $contact->phone ?? 'Belum ada data' }}</li>
                    </ul>
                    
                    <h3 class="mt-4">Media Sosial</h3>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        @if($contact->facebook)
                            <a href="{{ $contact->facebook }}" target="_blank" class="btn btn-secondary">Facebook</a>
                        @endif
                        @if($contact->instagram)
                            <a href="{{ $contact->instagram }}" target="_blank" class="btn btn-secondary">Instagram</a>
                        @endif
                    </div>
                </div>
                
                @if($contact->map_embed)
                <div>
                    <h3>Lokasi Kami</h3>
                    <div style="margin-top: 1rem; width: 100%; border-radius: var(--radius-md); overflow: hidden;">
                        {!! $contact->map_embed !!}
                    </div>
                </div>
                @endif
            </div>
        @else
            <p class="text-center">Informasi kontak belum tersedia.</p>
        @endif
    </div>
</div>
@endsection
