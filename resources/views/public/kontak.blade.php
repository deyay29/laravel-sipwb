@extends('layouts.app')

@section('title', 'Kontak')
@section('description', 'Alamat, nomor telepon, dan kanal resmi Bandung Info Center.')

@section('content')
<x-public.page-hero
    title="Kontak"
    subtitle="Alamat, nomor telepon, email, dan kanal resmi Bandung Info Center."
    :breadcrumb="['Beranda' => route('home'), 'Kontak' => null]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6">
    <div class="grid gap-8 lg:grid-cols-5 max-w-[1400px]">
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
                @php
                    // Nilai peta dari admin bisa berupa kode <iframe> maupun URL
                    // biasa. Keduanya dinormalisasi jadi iframe yang aman
                    // (whitelist domain Google Maps), supaya URL mentah tidak
                    // tampil sebagai teks dan tidak ada markup berbahaya yang
                    // ikut ter-render.
                    $petaIframe = \App\Support\MapEmbed::toSafeIframe($contact->map_embed);
                @endphp

                @if ($petaIframe)
                    <div class="h-full min-h-[380px]">{!! $petaIframe !!}</div>
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

    {{-- Form kirim kritik, saran, atau masukan (mode anonim) --}}
    <div class="mt-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(15,23,42,0.06)] sm:p-8">
            <h2 class="font-display text-lg font-extrabold text-slate-900">Kirim Kritik, Saran &amp; Masukan</h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">
                Tulis kritik, saran, atau masukan Anda tentang layanan ini.
                Pengiriman dilakukan secara <strong>anonim</strong>, tanpa perlu mengisi nama atau email.
            </p>

            <form action="{{ route('public.kontak.kirim') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="kategori" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="kategori"
                            name="kategori"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        <option value="">-- Pilih kategori --</option>
                        <option value="kritik">Kritik</option>
                        <option value="saran">Saran</option>
                        <option value="masukan">Masukan</option>
                    </select>
                </div>

                <div>
                    <label for="pesan" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                        Pesan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="pesan"
                              name="pesan"
                              rows="5"
                              maxlength="5000"
                              required
                              placeholder="Ceritakan kritik, saran, atau masukan Anda..."
                              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"></textarea>
                    <p class="mt-1.5 text-xs text-slate-400">
                        Kirim sebagai anonim: nama dan email tidak wajib diisi dan tidak akan diajukan kepada pengirim.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Kirim Masukan
                    </button>
                    <p class="text-xs text-slate-400">
                        Kritik, saran, atau masukan Anda akan langsung tersimpan tanpa proses balasan admin.
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
