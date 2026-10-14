{{--
    Satu-satunya halaman login di aplikasi ini, khusus admin.

    Portal publik tidak memiliki login maupun pendaftaran, sehingga tidak ada
    duplikasi tampilan. Latar foto sudah ditangani oleh layouts/guest.
--}}
@extends('layouts.guest')

@section('title', 'Login Admin')

@section('content')
    <div class="w-full max-w-md">
        <div class="rounded-[30px] border border-white/25 bg-white/10 p-8 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur-2xl sm:p-10">
            <div class="text-center">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Bandung Info Center"
                     class="mx-auto h-20 w-auto">

                <h1 class="mt-5 font-display text-2xl font-extrabold text-white">Login Admin</h1>
                <p class="mt-1.5 text-sm text-white/75">Panel Admin Bandung Info Center</p>
            </div>

            @if ($errors->any())
                <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-rose-300/40 bg-rose-500/20 px-4 py-3 text-left text-sm text-white">
                    <i class="fas fa-circle-exclamation mt-0.5 shrink-0 text-rose-200"></i>
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="mt-7 space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block pl-1 text-xs font-semibold text-white/90">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="email"
                           placeholder="admin@bandung.go.id"
                           class="w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm text-slate-900 shadow-inner outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/30">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block pl-1 text-xs font-semibold text-white/90">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Minimal 8 karakter"
                           class="w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm text-slate-900 shadow-inner outline-none transition placeholder:text-slate-400 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/30">
                </div>

                <label class="flex cursor-pointer items-center gap-2.5 pl-1 text-xs text-white/80">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           class="h-4 w-4 rounded border-white/40 bg-white/20 text-accent-500 focus:ring-accent-500/40">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full rounded-xl bg-accent-500 py-3 font-display text-sm font-bold text-navy-900 shadow-lg transition hover:bg-accent-400 focus:outline-none focus:ring-4 focus:ring-accent-500/40">
                    Masuk sebagai Admin
                </button>
            </form>

            <div class="mt-6 border-t border-white/15 pt-5 text-center">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 text-sm text-white/70 transition hover:text-white">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-white/50">
            Halaman ini hanya untuk admin. Pengunjung tidak perlu login.
        </p>
    </div>
@endsection
