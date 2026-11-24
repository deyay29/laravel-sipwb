@extends('layouts.app')

@section('title', 'Kuliner')
@section('description', 'Daftar kuliner khas Kota Bandung beserta kategori suhunya.')

@section('content')
    @php
        use App\Models\Kuliner;

        /*
         * Filter disimpan sebagai daftar (list), bukan associative array.
         * Key `null` pada associative array otomatis berubah jadi string kosong "",
         * sehingga perbandingan `is_null()` selalu bernilai false.
         */
        $filters = [
            ['value' => null, 'label' => 'Semua', 'icon' => 'fa-solid fa-temperature-half'],
            ['value' => Kuliner::SUHU_MENGHANGATKAN, 'label' => 'Menghangatkan', 'icon' => 'fa-regular fa-circle-dot'],
            ['value' => Kuliner::SUHU_MENYEGARKAN, 'label' => 'Menyegarkan', 'icon' => 'fa-solid fa-snowflake'],
            ['value' => Kuliner::SUHU_SEGALA_SUASANA, 'label' => 'Segala Cuaca', 'icon' => 'fa-solid fa-cloud-sun'],
        ];

        $filtersKeluarga = [
            ['value' => null, 'label' => 'Semua', 'icon' => 'fa-solid fa-user-group'],
            ['value' => '1', 'label' => 'Ramah Keluarga & Anak', 'icon' => 'fa-solid fa-users'],
            ['value' => '0', 'label' => 'Kurang Ramah Keluarga', 'icon' => 'fa-solid fa-user-group'],
        ];

        /*
         * Radius yang sedang dipakai untuk mode "kuliner terdekat". Diambil dari
         * controller lewat $lokasiTerdekat, jadi null saat halaman tanpa filter.
         */
        $radiusTerpilih = (float) ($lokasiTerdekat['radius_km'] ?? 0);
    @endphp

    <x-public.page-hero title="Kuliner" subtitle="Temukan kuliner khas Bandung yang cocok dengan cuaca hari ini."
        :breadcrumb="['Beranda' => route('home'), 'Kuliner' => null]">
        {{-- Kartu cuaca, penempatannya sama dengan di hero beranda --}}
        <x-slot:aside>
            <x-public.weather-card variant="glass" :weather="$weather" :rekomendasi="$suhuRekomendasi" />
        </x-slot:aside>
    </x-public.page-hero>

    <section class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6">
        {{-- ===== Filter kategori suhu & Family & Kids (ramah keluarga & anak) - Sejajar ===== --}}
        <div class="mb-10 flex items-center gap-2 sm:gap-2.5 overflow-x-auto pb-2 scrollbar-none [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            {{-- Label filter utama --}}
            <span class="font-display text-base font-bold text-slate-900 tracking-tight mr-1 shrink-0">Filters:</span>

            {{-- Kelompok Filter Kategori Suhu --}}
            @foreach ($filters as $filter)
                @php
                    $value = $filter['value'];
                    $isActive = $suhuAktif ? $suhuDipilih === $value : $value === null;
                    $isRekomendasi = $value !== null && $value === $suhuRekomendasi;
                @endphp

                <a href="{{ route('public.kuliner', array_merge(request()->except(['suhu', 'page']), $value ? ['suhu' => $value] : [])) }}"
                    @class([
                        'inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 sm:px-4.5 sm:py-2.5 font-display text-xs sm:text-sm font-semibold transition whitespace-nowrap',
                        'bg-navy-900 text-white shadow-sm hover:bg-navy-800' => $isActive,
                        'border border-slate-200/90 bg-white text-slate-700 shadow-xs hover:border-slate-300 hover:text-slate-900' => !$isActive,
                    ])>
                    @if ($filter['icon'])
                        <i class="{{ $filter['icon'] }} text-[11px]"></i>
                    @endif

                    {{ $filter['label'] }}

                    @if ($isRekomendasi)
                        <span @class([
                            'rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider',
                            'bg-white/20 text-white' => $isActive,
                            'bg-slate-100 text-slate-700' => !$isActive,
                        ])>
                            HARI INI
                        </span>
                    @endif
                </a>
            @endforeach

            {{-- Garis pembatas vertikal antara kelompok filter suhu & keluarga --}}
            <span class="mx-1 text-slate-300 font-light select-none text-base shrink-0" aria-hidden="true">|</span>

            {{-- Kelompok Filter Ramah Keluarga & Anak --}}
            @foreach ($filtersKeluarga as $filterKeluarga)
                @php
                    $valueKeluarga = $filterKeluarga['value'];
                    $isFilterKeluargaAktif =
                        $familiaTerpilih === null
                            ? $valueKeluarga === null
                            : (string) $valueKeluarga === (string) $familiaTerpilih;
                @endphp

                <a href="{{ route('public.kuliner', array_merge(request()->except(['family', 'page']), $valueKeluarga !== null ? ['family' => $valueKeluarga] : [])) }}"
                    @class([
                        'inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 sm:px-4.5 sm:py-2.5 font-display text-xs sm:text-sm font-semibold transition whitespace-nowrap',
                        'bg-navy-900 text-white shadow-sm hover:bg-navy-800' => $isFilterKeluargaAktif,
                        'border border-slate-200/90 bg-white text-slate-700 shadow-xs hover:border-slate-300 hover:text-slate-900' => !$isFilterKeluargaAktif,
                    ])>
                    @if ($filterKeluarga['icon'])
                        <i class="{{ $filterKeluarga['icon'] }} text-[11px]"></i>
                    @endif

                    {{ $filterKeluarga['label'] }}
                </a>
            @endforeach
        </div>
        {{-- ===== Filter jarak / kuliner terdekat ===== --}}
        <div class="mb-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_2px_16px_rgba(15,23,42,0.06)] sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-lg font-extrabold text-slate-900">
                        <i class="fas fa-location-crosshairs mr-2 text-brand-600"></i>Kuliner Terdekat
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Atur batas jarak dari lokasi Anda untuk menemukan kuliner khas terdekat.
                    </p>
                </div>

                <form method="GET" action="{{ route('public.kuliner') }}" class="flex flex-wrap items-end gap-3"
                    id="form-cari-jarak">
                    <div>
                        <label for="lat_kuliner" class="mb-1.5 block text-xs font-semibold text-slate-600">Latitude</label>
                        <input type="number" step="any" min="-90" max="90" id="lat_kuliner" name="lat"
                            value="{{ old('lat', $lokasiTerdekat['latitude'] ?? '') }}" placeholder="-6.9206"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 sm:w-40">
                    </div>

                    <div>
                        <label for="lng_kuliner" class="mb-1.5 block text-xs font-semibold text-slate-600">Longitude</label>
                        <input type="number" step="any" min="-180" max="180" id="lng_kuliner" name="lng"
                            value="{{ old('lng', $lokasiTerdekat['longitude'] ?? '') }}" placeholder="107.6100"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 sm:w-48">
                    </div>

                    <div>
                        <label for="radius_kuliner" class="mb-1.5 block text-xs font-semibold text-slate-600">Radius
                            (km)</label>
                        <input type="number" step="any" min="0" id="radius_kuliner" name="radius"
                            value="{{ old('radius', $lokasiTerdekat['radius_km'] ?? '') }}" placeholder="10"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 sm:w-28">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Lokasi</label>
                        <button type="button" id="akses-lokasi"
                            class="inline-flex items-center gap-2 rounded-xl border border-brand-300 bg-white px-4 py-2.5 font-display text-sm font-semibold text-brand-700 transition hover:bg-brand-50 disabled:cursor-not-allowed disabled:opacity-60">
                            <i class="fas fa-location-dot text-xs"></i> Gunakan lokasi saya
                        </button>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-navy-800 to-brand-600 px-5 py-2.5 font-display text-sm font-semibold text-white transition hover:shadow-[0_8px_24px_rgba(26,42,108,0.35)]">
                        <i class="fas fa-magnifying-glass text-xs"></i> Cari
                    </button>

                    <a href="{{ route('public.kuliner') }}"
                        class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 font-display text-sm font-semibold text-slate-600 transition hover:border-brand-500 hover:text-brand-700">
                        <i class="fas fa-rotate-left text-xs"></i> Reset
                    </a>
                </form>
            </div>

            <div class="mt-5 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Radius cepat:</span>

                @foreach ([1, 2, 5, 10, 25] as $radiusCepat)
                    <a href="{{ route('public.kuliner', ['radius' => $radiusCepat]) }}" @class([
                        'inline-flex items-center rounded-full px-4 py-1.5 font-display text-xs font-bold transition',
                        'bg-brand-600 text-white' => $radiusTerpilih === (float) $radiusCepat,
                        'border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-700' =>
                            $radiusTerpilih !== (float) $radiusCepat,
                    ])>
                        {{ $radiusCepat }} km
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ===== Banner mode jarak terdekat ===== --}}
        @if ($modeTerdekat)
            <div
                class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-brand-200 bg-brand-50 p-5">
                <p class="text-sm text-brand-900">
                    Menampilkan kuliner terdekat dari koordinat
                    <span class="font-bold">{{ $lokasiTerdekat['latitude'] }}, {{ $lokasiTerdekat['longitude'] }}</span>
                    dengan radius maksimal
                    <span class="font-bold">{{ $lokasiTerdekat['radius_km'] }} km</span>.
                </p>

                <a href="{{ route('public.kuliner') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-brand-300 bg-white px-4 py-2.5 font-display text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                    <i class="fas fa-rotate-left text-xs"></i> Tampilkan semua kuliner
                </a>
            </div>
        @endif

        {{-- ===== Daftar kuliner ===== --}}
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($kuliners as $kuliner)
                @php
                    $menghangatkan = $kuliner->kategori_suhu === Kuliner::SUHU_MENGHANGATKAN;
                    $isSegalaSuasana = $kuliner->kategori_suhu === Kuliner::SUHU_SEGALA_SUASANA;
                @endphp

                <article
                    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)] transition hover:shadow-[0_12px_32px_rgba(15,23,42,0.12)]">
                    <a href="{{ route('public.kuliner.show', $kuliner) }}" class="relative block overflow-hidden">
                        <x-public.media :src="$kuliner->foto" :alt="$kuliner->nama_kuliner" icon="fa-utensils" tone="amber"
                            ratio="aspect-[4/3]" class="transition duration-500 group-hover:scale-105" />

                        @if ($kuliner->ramah_anak)
                            <span
                                class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full border border-white/60 px-3 py-1 text-[11px] font-bold text-white backdrop-blur bg-emerald-600/85">
                                <i class="fas fa-users text-[10px]"></i>
                                Family &amp; Kids
                            </span>
                        @endif

                        @if ($kuliner->kategori_suhu)
                            <span @class([
                                'absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full border border-white/60 px-3 py-1 text-[11px] font-bold text-white backdrop-blur',
                                'bg-rose-600/85' => $menghangatkan,
                                'bg-sky-600/85' => ! $menghangatkan && ! $isSegalaSuasana,
                                'bg-slate-600/85' => $isSegalaSuasana,
                            ])>
                                <i class="fas {{ $menghangatkan ? 'fa-fire' : ($isSegalaSuasana ? 'fa-compass' : 'fa-snowflake') }} text-[10px]"></i>
                                {{ $kuliner->kategori_suhu }}
                            </span>
                        @endif

                        @if ($kuliner->fotos_count > 0)
                            <span
                                class="absolute bottom-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-navy-900/75 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur">
                                <i class="fas fa-images text-[10px]"></i>
                                {{ $kuliner->fotos_count }} foto
                            </span>
                        @endif
                    </a>

                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="line-clamp-2 font-display text-lg font-extrabold leading-snug text-slate-900">
                            <a href="{{ route('public.kuliner.show', $kuliner) }}"
                                class="transition group-hover:text-brand-700">
                                {{ $kuliner->nama_kuliner }}
                            </a>
                        </h2>

                        <p class="mt-2 inline-flex items-start gap-1.5 text-xs text-slate-500">
                            <i class="fas fa-location-dot mt-0.5 text-[10px] text-brand-500"></i>
                            <span class="line-clamp-1">{{ $kuliner->alamat_lengkap ?? 'Bandung' }}</span>
                        </p>

                        @if ($kuliner->jarak_km)
                            <p class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-brand-700">
                                <i class="fas fa-location-arrow text-[10px]"></i>
                                {{ number_format((float) $kuliner->jarak_km, 1) }} km dari lokasi Anda
                            </p>
                        @endif

                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-600">
                            {{ Str::limit($kuliner->deskripsi, 120) }}
                        </p>

                        <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div class="min-w-0">
                                <p class="text-[11px] uppercase tracking-wide text-slate-400">{{ $kuliner->adaHargaRange() ? 'Kisaran Harga' : 'Harga' }}</p>
                                <p class="mt-0.5 font-display text-sm font-bold text-navy-800">
                                    {{ $kuliner->hargaTampilan() ?? '-' }}
                                </p>
                            </div>

                            <p class="shrink-0 text-right">
                                <span class="block text-[11px] uppercase tracking-wide text-slate-400">Buka</span>
                                <span class="mt-0.5 block font-semibold text-slate-700">
                                    {{ $kuliner->jam_operasional ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </article>
            @empty
                <x-public.empty-state class="md:col-span-2 lg:col-span-3"
                    title="{{ $modeTerdekat ? 'Tidak ada kuliner dalam radius tersebut' : 'Belum ada kuliner' }}"
                    description="{{ $modeTerdekat ? 'Coba perbesar radius atau atur ulang koordinat pencarian Anda.' : 'Tidak ada kuliner yang cocok dengan filter ini.' }}"
                    icon="fa-utensils" />
            @endforelse
        </div>

        <x-public.pagination :paginator="$kuliners" />
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-cari-jarak');
            const inputLat = document.getElementById('lat_kuliner');
            const inputLng = document.getElementById('lng_kuliner');
            const tombolLokasi = document.getElementById('akses-lokasi');

            if (!form || !inputLat || !inputLng) {
                return;
            }

            // Geolokasi browser -> isi latitude & longitude lalu langsung cari.
            if (tombolLokasi && navigator.geolocation) {
                tombolLokasi.addEventListener('click', function() {
                    tombolLokasi.disabled = true;
                    tombolLokasi.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi lokasi...';

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            inputLat.value = Number(position.coords.latitude).toFixed(6);
                            inputLng.value = Number(position.coords.longitude).toFixed(6);
                            tombolLokasi.disabled = false;
                            form.submit();
                        },
                        function() {
                            tombolLokasi.disabled = false;
                            tombolLokasi.innerHTML =
                                '<i class="fas fa-location-dot"></i> Gunakan lokasi saya';
                        }, {
                            timeout: 10000,
                            maximumAge: 600000
                        }
                    );
                });
            }
        });
    </script>
@endpush
