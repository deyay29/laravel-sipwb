@extends('layouts.app')

@section('title', 'Kontak')
@section('description', 'Alamat, nomor telepon, dan kanal resmi Bandung Info Center.')

@section('content')
<x-public.page-hero
    title="Kontak"
    subtitle="Alamat, nomor telepon, email, dan kanal resmi Bandung Info Center."
    :breadcrumb="['Beranda' => route('home'), 'Kontak' => null]"
/>

<section class="mx-auto max-w-5xl px-4 py-14 sm:px-6">
    <div class="grid gap-8 lg:grid-cols-5">
        {{-- Informasi kontak --}}
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
                <h2 class="font-display text-lg font-extrabold text-slate-900">Informasi Kontak</h2>

                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex gap-3">
                        <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600">
                            <i class="fas fa-location-dot text-xs"></i>
                        </span>
                        <div>
                            <dt class="font-display text-xs font-bold uppercase tracking-wide text-slate-400">Alamat</dt>
                            <dd class="mt-0.5 leading-relaxed text-slate-700">
                                {{ $contact->address ?? 'Belum ada data alamat.' }}
                            </dd>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <div>
                            <dt class="font-display text-xs font-bold uppercase tracking-wide text-slate-400">Email</dt>
                            <dd class="mt-0.5">
                                @if ($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="font-medium text-brand-700 transition hover:text-brand-800">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    <span class="text-slate-500">Belum ada data email.</span>
                                @endif
                            </dd>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600">
                            <i class="fas fa-phone text-xs"></i>
                        </span>
                        <div>
                            <dt class="font-display text-xs font-bold uppercase tracking-wide text-slate-400">Telepon</dt>
                            <dd class="mt-0.5 text-slate-700">
                                {{ $contact->phone ?? 'Belum ada data telepon.' }}
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>

            @if ($contact->facebook || $contact->instagram)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
                    <h2 class="font-display text-lg font-extrabold text-slate-900">Media Sosial</h2>

                    <div class="mt-4 flex flex-wrap gap-3">
                        @if ($contact->facebook)
                            <a href="{{ $contact->facebook }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 rounded-full bg-[#1877f2] px-5 py-2.5 font-display text-sm font-semibold text-white transition hover:opacity-90">
                                <i class="fab fa-facebook-f text-xs"></i>
                                Facebook
                            </a>
                        @endif

                        @if ($contact->instagram)
                            <a href="{{ $contact->instagram }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#f58529] via-[#dd2a7b] to-[#8134af] px-5 py-2.5 font-display text-sm font-semibold text-white transition hover:opacity-90">
                                <i class="fab fa-instagram text-xs"></i>
                                Instagram
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Peta --}}
        <div class="lg:col-span-3">
            <div class="h-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
                @if ($contact->map_embed)
                    <div class="h-full min-h-[380px] [&>iframe]:h-full [&>iframe]:w-full [&>iframe]:border-0">
                        {!! $contact->map_embed !!}
                    </div>
                @else
                    <div class="flex h-full min-h-[380px] flex-col items-center justify-center gap-3 px-6 text-center">
                        <span class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-2xl text-slate-400">
                            <i class="fas fa-map-location-dot"></i>
                        </span>
                        <p class="font-display text-base font-bold text-slate-800">Peta belum ditampilkan</p>
                        <p class="max-w-xs text-sm text-slate-500">
                            Admin dapat menambahkan kode embed Google Maps melalui panel admin.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
