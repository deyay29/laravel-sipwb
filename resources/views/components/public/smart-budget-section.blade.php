{{--
    Section Smart Budget (daftar paket anggaran).

    Dipakai di dua tempat:
      1. Halaman /smart-budget (sendiri)
      2. Disematkan di halaman detail Wisata sebagai section dengan id
         "smart-budget", sehingga tombol "Lihat Estimasi Biaya" bisa
         menggulir ke sini (anchor internal) tanpa pindah halaman.

    Gaya card-nya sengaja dibuat netral (putih + garis abu) supaya serasi
    dengan kartu-kartu lain di halaman detail wisata.

    @param \Illuminate\Support\Collection|array $pakets
    @param string|null $judul  judul section
--}}
@props([
    'pakets' => [],
    'judul' => 'Estimasi Biaya',
])

@php
    $daftar = collect($pakets);
@endphp

<section id="smart-budget" {{ $attributes->merge(['class' => 'mt-14 scroll-mt-28']) }}>
    <h2 class="font-display text-2xl font-extrabold text-slate-900">{{ $judul }}</h2>
    <p class="mt-1 text-sm text-slate-500">
        Perkiraan biaya perjalanan di Kota Bandung, disusun agar mudah dibaca.
    </p>

    @if ($daftar->isEmpty())
        <x-public.empty-state class="mt-6" title="Belum ada paket anggaran" icon="fa-wallet" />
    @else
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($daftar as $paket)
                @php
                    $rincian = collect($paket->rincian_biaya ?? []);
                    $terbesar = $rincian->sortByDesc('biaya')->first();
                @endphp

                <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)]">
                    <x-public.media
                        :src="$paket->foto"
                        :alt="$paket->nama_paket"
                        icon="fa-wallet"
                        tone="navy"
                        ratio="aspect-[4/3]"
                    />

                    <div class="flex flex-1 flex-col p-5">
                        <span @class([
                            'mb-3 self-start rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide',
                            'bg-emerald-50 text-emerald-700' => $paket->kategori === 'Hemat',
                            'bg-sky-50 text-sky-700' => $paket->kategori === 'Keluarga',
                            'bg-violet-50 text-violet-700' => ! in_array($paket->kategori, ['Hemat', 'Keluarga'], true),
                        ])>
                            {{ $paket->kategori ?? 'Umum' }}
                        </span>

                        <h3 class="font-display text-base font-bold leading-snug text-slate-900">
                            {{ $paket->nama_paket }}
                        </h3>

                        @if ($paket->deskripsi)
                            <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-slate-500">{{ $paket->deskripsi }}</p>
                        @endif

                        @if ($rincian->isNotEmpty())
                            <ul class="mt-4 space-y-1.5 border-t border-slate-100 pt-4 text-xs">
                                @foreach ($rincian as $item)
                                    <li class="flex items-center justify-between gap-3">
                                        <span class="text-slate-600">{{ $item['item'] ?? '-' }}</span>
                                        <span class="shrink-0 font-semibold text-slate-800">
                                            Rp{{ number_format($item['biaya'] ?? 0, 0, ',', '.') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-auto rounded-xl bg-navy-900 px-5 py-4 text-white">
                            <p class="text-[11px] uppercase tracking-widest text-white/60">Total estimasi</p>
                            <p class="mt-0.5 font-display text-xl font-extrabold">
                                Rp{{ number_format($paket->total_estimasi, 0, ',', '.') }}
                            </p>

                            @if ($terbesar)
                                <p class="mt-1 text-[11px] text-white/60">
                                    Pos terbesar: {{ $terbesar['item'] ?? '-' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>