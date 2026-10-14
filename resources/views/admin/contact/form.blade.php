@extends('layouts.admin')

@section('title', 'Data Kontak')
@section('topbar-title', 'Profil & Kontak')

@section('content')
<div class="mx-auto max-w-3xl">
    <x-admin.page-header
        title="Profil & Kontak"
        description="Satu baris data ini dipakai oleh halaman Profil di situs publik."
    />

    <form action="{{ route('admin.contact.update', $contact) }}" method="POST"
          class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(26,42,108,0.08)] lg:p-8">
        @csrf
        @method('PUT')

        <div>
            <label for="address" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                Alamat
            </label>
            <textarea id="address"
                      name="address"
                      rows="3"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('address', $contact->address ?? '') }}</textarea>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="email" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $contact->email ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div>
                <label for="phone" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">Nomor Telepon</label>
                <input type="text"
                       id="phone"
                       name="phone"
                       value="{{ old('phone', $contact->phone ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="facebook" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">URL Facebook</label>
                <input type="url"
                       id="facebook"
                       name="facebook"
                       value="{{ old('facebook', $contact->facebook ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div>
                <label for="instagram" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">URL Instagram</label>
                <input type="url"
                       id="instagram"
                       name="instagram"
                       value="{{ old('instagram', $contact->instagram ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            </div>
        </div>

        <div>
            <label for="map_embed" class="mb-1.5 block font-display text-sm font-semibold text-slate-700">
                Kode Embed Google Maps
            </label>
            <textarea id="map_embed"
                      name="map_embed"
                      rows="4"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 font-mono text-xs leading-relaxed outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">{{ old('map_embed', $contact->map_embed ?? '') }}</textarea>
            <p class="mt-1.5 text-xs text-slate-400">Tempelkan kode &lt;iframe&gt; dari Google Maps.</p>
        </div>

        <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-6 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                <i class="fas fa-floppy-disk text-xs"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('public.kontak') }}"
               target="_blank"
               rel="noopener"
               class="inline-flex items-center gap-2 rounded-[10px] border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-eye text-xs"></i>
                Lihat Halaman Publik
            </a>
        </div>
    </form>
</div>
@endsection
