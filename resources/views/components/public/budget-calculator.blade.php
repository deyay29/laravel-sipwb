{{--
    Kalkulator anggaran perjalanan (Smart Budget).

    Sifatnya interaktif tapi TANPA JavaScript: form dikirim lewat GET sehingga
    angka dihitung ulang di server. Keuntungannya: tetap jalan kalau JS gagal,
    hasilnya bisa di-bookmark/dibagikan lewat URL, dan tidak ada angka yang
    dihitung di browser (selalu sama dengan sumbernya).

    Semua harga acuan diambil dari data nyata di database
    (lihat App\Support\BudgetCalculator) — tidak ada angka tebakan.
--}}
@php
    use App\Support\BudgetCalculator;

    $input = fn (?string $k, int $default = 0) => (int) (request($k) ?? $default);

    $jumlahOrang  = max(1, $input('orang', 2));
    $jumlahHari   = max(1, $input('hari', 1));
    $jumlahMalam  = max(0, $input('malam', 0));
    $tiket        = $input('tiket') ?: null;
    $makan        = $input('makan') ?: null;
    $parkir       = $input('parkir');
    $penginapan   = $input('penginapan');

    $hasil = BudgetCalculator::hitung(
        jumlahOrang: $jumlahOrang,
        jumlahHari: $jumlahHari,
        jumlahMalam: $jumlahMalam,
        tiketPerOrang: $tiket,
        makanPerOrang: $makan,
        parkirPerHari: $parkir,
        penginapanPerMalam: $penginapan,
    );

    $daftarWisata  = BudgetCalculator::pilihanWisata();
    $daftarKuliner = BudgetCalculator::pilihanKuliner();
    $hargaTiketAcuan  = BudgetCalculator::hargaTiketAcuan();
    $hargaKulinerAcuan = BudgetCalculator::hargaKulinerAcuan();
@endphp

<div class="grid gap-6 lg:grid-cols-5">
    {{-- ============ KOLOM KIRI: FORMULIR ============ --}}
    <form method="GET" action="{{ route('public.smart-budget') }}"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_2px_16px_rgba(15,23,42,0.06)] lg:col-span-2 lg:p-6">
        <h3 class="font-display text-lg font-extrabold text-slate-900">Atur Perjalanan</h3>
        <p class="mt-1 text-xs text-slate-500">
            Harga tiket &amp; kuliner memakai data asli dari situs ini.
        </p>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <div>
                <label for="k-orang" class="mb-1 block text-xs font-semibold text-slate-600">Jumlah orang</label>
                <input id="k-orang" type="number" name="orang" min="1" max="100" value="{{ $jumlahOrang }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div>
                <label for="k-hari" class="mb-1 block text-xs font-semibold text-slate-600">Jumlah hari</label>
                <input id="k-hari" type="number" name="hari" min="1" max="30" value="{{ $jumlahHari }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div>
                <label for="k-malam" class="mb-1 block text-xs font-semibold text-slate-600">Malam menginap</label>
                <input id="k-malam" type="number" name="malam" min="0" max="30" value="{{ $jumlahMalam }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div>
                <label for="k-parkir" class="mb-1 block text-xs font-semibold text-slate-600">Transport / hari (Rp)</label>
                <input id="k-parkir" type="number" name="parkir" min="0" step="1000" value="{{ $parkir }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
        </div>

        <hr class="my-5 border-slate-100">

        <p class="flex items-start gap-1.5 text-xs text-slate-500">
            <i class="fas fa-circle-info mt-0.5 shrink-0 text-brand-500"></i>
            <span>
                Biaya di bawah <strong>tidak ditebak</strong>. Kalau dikosongkan, sistem memakai
                rata-rata harga yang benar-benar tersimpan di data wisata &amp; kuliner.
            </span>
        </p>

        <div class="mt-4 space-y-3">
            <div>
                <label for="k-wisata" class="mb-1 block text-xs font-semibold text-slate-600">Pilih wisata</label>
                <select id="k-wisata" name="tiket"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <option value="">Rata-rata data ({!! 'Rp'.number_format($hargaTiketAcuan, 0, ',', '.') !!})</option>
                    @foreach ($daftarWisata as $w)
                        <option value="{{ $w['harga'] }}" @selected($tiket === $w['harga'])>
                            {{ $w['nama'] }} — Rp{{ number_format($w['harga'], 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="k-kuliner" class="mb-1 block text-xs font-semibold text-slate-600">Pilih kuliner</label>
                <select id="k-kuliner" name="makan"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <option value="">Rata-rata data (Rp{{ number_format($hargaKulinerAcuan, 0, ',', '.') }})</option>
                    @foreach ($daftarKuliner as $k)
                        <option value="{{ $k['harga'] }}" @selected($makan === $k['harga'])>
                            {{ $k['nama'] }} — Rp{{ number_format($k['harga'], 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="k-penginapan" class="mb-1 block text-xs font-semibold text-slate-600">Penginapan / malam (Rp)</label>
                <input id="k-penginapan" type="number" name="penginapan" min="0" step="1000" value="{{ $penginapan }}"
                       placeholder="Kosongkan bila tidak menginap"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
        </div>

        <button type="submit"
                class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy-900 px-5 py-2.5 font-display text-sm font-semibold text-white transition hover:bg-navy-800">
            <i class="fas fa-calculator text-xs"></i>
            Hitung Anggaran
        </button>
    </form>

    {{-- ============ KOLOM KANAN: HASIL ============ --}}
    <div class="lg:col-span-3">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(15,23,42,0.06)]">
            <div class="bg-navy-900 px-6 py-5 text-white">
                <p class="text-xs uppercase tracking-widest text-white/60">Total estimasi</p>
                <p class="mt-1 font-display text-3xl font-extrabold sm:text-4xl">
                    Rp{{ number_format($hasil['total'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-white/60">
                    {{ $jumlahOrang }} orang
                    @if ($jumlahHari > 1)
                        &middot; {{ $jumlahHari }} hari
                    @endif
                    @if ($jumlahMalam > 0)
                        &middot; {{ $jumlahMalam }} malam
                    @endif
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach ($hasil['pos'] as $p)
                    <div class="flex flex-wrap items-start justify-between gap-3 px-6 py-4">
                        <div class="min-w-0 flex-1">
                            <p class="font-display text-sm font-bold text-slate-900">{{ $p['label'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $p['keterangan'] }}</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">{{ $p['sumber'] }}</p>
                        </div>

                        <div class="shrink-0 text-right">
                            @if ($p['terisi'])
                                <p class="font-display text-sm font-extrabold text-slate-900">
                                    Rp{{ number_format($p['total'], 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-[11px] font-semibold text-amber-600">Belum diestimasi</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="mt-3 flex items-start gap-1.5 text-[11px] leading-relaxed text-slate-400">
            <i class="fas fa-shield-halved mt-0.5 shrink-0"></i>
            <span>
                Angka dihitung dari data yang tersimpan di situs ini, bukan hasil tebakan.
                Harga akhir dapat berubah — cek langsung ke pengelola lokasi.
            </span>
        </p>
    </div>
</div>