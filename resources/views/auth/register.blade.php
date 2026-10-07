@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="text-center mb-4">Buat Akun Baru</h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
            </div>
            
            <p class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </p>
        </form>
    </div>
</div>
@endsection
