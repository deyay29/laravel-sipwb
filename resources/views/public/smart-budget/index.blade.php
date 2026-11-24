@extends('layouts.app')

@section('title', 'Smart Budget')
@section('description', 'Hitung perkiraan biaya perjalanan di Kota Bandung dari harga yang benar-benar tersimpan di situs ini.')

@section('content')
<x-public.page-hero
    title="Smart Budget"
    subtitle="Hitung sendiri perkiraan biaya perjalanan memakai harga yang benar-benar tersimpan di data wisata dan kuliner."
    :breadcrumb="['Beranda' => route('home'), 'Smart Budget' => null]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6">
    <div class="mb-6 flex justify-end">
        <x-public.share-buttons :title="'Smart Budget Perjalanan Bandung'" label="Bagikan halaman" />
    </div>

    {{-- Kalkulator: angka dihitung server-side dari data DB, bukan tebakan. --}}
    <x-public.budget-calculator />

    {{-- Paket referensi yang disusun admin (data tetap dari DB). --}}
    <div class="mt-16">
        <h2 class="font-display text-2xl font-extrabold text-slate-900">Paket Anggaran</h2>
        <p class="mt-1 text-sm text-slate-500">
            Contoh susunan anggaran yang disusun admin untuk供参考.
        </p>

        <x-public.smart-budget-section :pakets="$pakets" class="mt-6 border-t border-slate-200 pt-10" />
    </div>
</section>
@endsection