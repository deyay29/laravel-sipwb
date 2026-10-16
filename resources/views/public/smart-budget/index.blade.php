@extends('layouts.app')

@section('title', 'Smart Budget')
@section('description', 'Contoh paket anggaran perjalanan di Kota Bandung.')

@section('content')
<x-public.page-hero
    title="Smart Budget"
    subtitle="Perkiraan biaya perjalanan di Kota Bandung, disusun agar mudah dibaca."
    :breadcrumb="['Beranda' => route('home'), 'Smart Budget' => null]"
/>

<section class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6">
    {{-- Memakai komponen yang sama dengan yang disematkan di detail wisata,
         supaya tampilan & struktur kartunya identik. --}}
    <x-public.smart-budget-section :pakets="$pakets" judul="Paket Anggaran" />
</section>
@endsection