{{--
    Kategori Smart Budget Wisata Bandung
    - Sesuai dengan tampilan pada tangkapan layar 3
    - Slider Lama Liburan & Slider Jumlah Orang
    - Pilihan Paket: Hemat, Sedang, Nyaman
    - Rincian: Penginapan, Makan, Transportasi, Tiket Wisata, Total Perkiraan
    - Ukuran frame disesuaikan penuh dengan frame katalog destinasi (w-full)
--}}
@php
    $opsiTierBudget = $kategoriBudgetOptions ?? [
        'hemat' => 'Hemat',
        'sedang' => 'Sedang',
        'nyaman' => 'Nyaman',
    ];

    $tierBudgetAwal = in_array($kategoriBudgetTerpilih ?? null, array_keys($opsiTierBudget), true)
        ? $kategoriBudgetTerpilih
        : 'sedang';

    // Teks saran disimpan di server agar bisa dirender langsung dan dipakai
    // ulang oleh JavaScript saat pengunjung mengganti kategori.
    $saranTierBudget = [
        'hemat' => [
            'title' => 'Saran Kunjungan Kategori Hemat:',
            'desc' => 'Cocok untuk destinasi gratis atau bertiket murah, kuliner kaki lima legendaris, dan transportasi publik seperti Trans Metro Bandung.',
        ],
        'sedang' => [
            'title' => 'Saran Kunjungan Kategori Sedang:',
            'desc' => 'Cocok untuk destinasi sejarah dan museum, berburu foto di kawasan kota, menikmati kafe hits, serta sewa motor atau taksi online.',
        ],
        'nyaman' => [
            'title' => 'Saran Kunjungan Kategori Nyaman:',
            'desc' => 'Cocok untuk wisata alam dan destinasi premium, reservasi restoran keluarga ternama, serta rental mobil pribadi.',
        ],
    ];

    $saranAwal = $saranTierBudget[$tierBudgetAwal];
    $contohAwal = $contohBudget[$tierBudgetAwal] ?? [];
    $teksContohAwal = $contohAwal !== []
        ? ' Contoh destinasi: '.implode(', ', $contohAwal).'.'
        : '';
@endphp

<div id="smart-budget-tab-content" class="w-full">
    <div class="w-full">
        
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            {{-- ==================== KOLOM KIRI: INPUT SLIDER & TIER (col-span-12 lg:col-span-6) ==================== --}}
            <div class="lg:col-span-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-[0_4px_24px_rgba(15,23,42,0.06)] space-y-6">
                
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-display text-lg font-extrabold text-slate-900">
                        Atur Rencana Perjalanan
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Sesuaikan durasi hari dan jumlah rombongan liburan Anda.
                    </p>
                </div>

                {{-- 1. Input Slider Lama Liburan --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label for="sb-range-days" class="font-display text-sm font-bold text-slate-800">
                            Lama liburan: <span id="sb-display-days" class="text-brand-600 font-extrabold">2</span> hari
                        </label>
                        <span class="text-[11px] font-semibold text-slate-400">1 - 14 hari</span>
                    </div>
                    <input type="range"
                           id="sb-range-days"
                           min="1"
                           max="14"
                           value="2"
                           step="1"
                           oninput="calculateSmartBudget()"
                           class="h-2.5 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 accent-brand-600 focus:outline-none">
                </div>

                {{-- 2. Input Slider Jumlah Orang --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label for="sb-range-people" class="font-display text-sm font-bold text-slate-800">
                            Jumlah orang: <span id="sb-display-people" class="text-brand-600 font-extrabold">2</span>
                        </label>
                        <span class="text-[11px] font-semibold text-slate-400">1 - 10 orang</span>
                    </div>
                    <input type="range"
                           id="sb-range-people"
                           min="1"
                           max="10"
                           value="2"
                           step="1"
                           oninput="calculateSmartBudget()"
                           class="h-2.5 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 accent-brand-600 focus:outline-none">
                </div>

                {{-- 3. Pilihan Gaya Anggaran (Pills Hemat, Sedang, Nyaman - Screenshot 3) --}}
                <div>
                    <label class="font-display text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">
                        Pilihan Gaya Liburan:
                    </label>
                    <div class="grid grid-cols-3 gap-2.5 rounded-2xl bg-slate-100/80 p-1.5 border border-slate-200/60">
                        <button type="button"
                                id="sb-btn-tier-hemat"
                                onclick="setBudgetTier('hemat')"
                                @class([
                                    'sb-tier-btn rounded-xl py-2.5 text-center font-display text-xs font-bold transition',
                                    'bg-brand-600 text-white shadow-sm' => $tierBudgetAwal === 'hemat',
                                    'text-slate-600 hover:text-slate-900' => $tierBudgetAwal !== 'hemat',
                                ])>
                            Hemat
                        </button>
                        <button type="button"
                                id="sb-btn-tier-sedang"
                                onclick="setBudgetTier('sedang')"
                                @class([
                                    'sb-tier-btn rounded-xl py-2.5 text-center font-display text-xs font-bold transition',
                                    'bg-brand-600 text-white shadow-sm' => $tierBudgetAwal === 'sedang',
                                    'text-slate-600 hover:text-slate-900' => $tierBudgetAwal !== 'sedang',
                                ])>
                            Sedang
                        </button>
                        <button type="button"
                                id="sb-btn-tier-nyaman"
                                onclick="setBudgetTier('nyaman')"
                                @class([
                                    'sb-tier-btn rounded-xl py-2.5 text-center font-display text-xs font-bold transition',
                                    'bg-brand-600 text-white shadow-sm' => $tierBudgetAwal === 'nyaman',
                                    'text-slate-600 hover:text-slate-900' => $tierBudgetAwal !== 'nyaman',
                                ])>
                            Nyaman
                        </button>
                    </div>
                </div>

                {{-- Rekomendasi Wisata Sesuai Budget --}}
                <div class="rounded-2xl bg-brand-50/70 border border-brand-100 p-5 flex items-start gap-3">
                    <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand-600 text-white text-xs">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="text-xs leading-relaxed text-slate-600">
                        <p id="sb-tier-recommendation-title" class="font-display font-bold text-slate-900">
                            {{ $saranAwal['title'] }}
                        </p>
                        <p id="sb-tier-recommendation-desc" class="mt-0.5 text-slate-600">
                            {{ $saranAwal['desc'].$teksContohAwal }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- ==================== KOLOM KANAN: RINCIAN BIAYA & TOTAL (col-span-12 lg:col-span-6) ==================== --}}
            <div class="lg:col-span-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-[0_4px_24px_rgba(15,23,42,0.06)] space-y-5">
                
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-lg font-extrabold text-slate-900">
                            Estimasi Rincian Biaya
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Kalkulasi otomatis berdasarkan parameter pilihan.
                        </p>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-full bg-blue-50 text-brand-600 text-sm">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>

                {{-- 4. Rincian Biaya (Breakdown - Persis Screenshot 3) --}}
                <div class="space-y-4 pt-2">
                    
                    {{-- Penginapan --}}
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 text-sm">
                        <span class="text-slate-600 font-medium flex items-center gap-2">
                            <i class="fas fa-hotel text-slate-400 text-xs w-4"></i> Penginapan
                        </span>
                        <span id="sb-cost-penginapan" class="font-display font-bold text-slate-800">
                            Rp 550.000
                        </span>
                    </div>

                    {{-- Makan --}}
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 text-sm">
                        <span class="text-slate-600 font-medium flex items-center gap-2">
                            <i class="fas fa-utensils text-slate-400 text-xs w-4"></i> Makan
                        </span>
                        <span id="sb-cost-makan" class="font-display font-bold text-slate-800">
                            Rp 500.000
                        </span>
                    </div>

                    {{-- Transportasi --}}
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 text-sm">
                        <span class="text-slate-600 font-medium flex items-center gap-2">
                            <i class="fas fa-car text-slate-400 text-xs w-4"></i> Transportasi
                        </span>
                        <span id="sb-cost-transport" class="font-display font-bold text-slate-800">
                            Rp 200.000
                        </span>
                    </div>

                    {{-- Tiket Wisata --}}
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 text-sm">
                        <span class="text-slate-600 font-medium flex items-center gap-2">
                            <i class="fas fa-ticket text-slate-400 text-xs w-4"></i> Tiket wisata
                        </span>
                        <span id="sb-cost-tiket" class="font-display font-bold text-slate-800">
                            Rp 240.000
                        </span>
                    </div>

                    {{-- Garis Pemisah & Total Perkiraan --}}
                    <div class="pt-4 flex items-center justify-between">
                        <span class="font-display text-base font-extrabold text-slate-900">Total perkiraan</span>
                        <span id="sb-cost-total" class="font-display text-2xl font-extrabold text-brand-600">
                            Rp 1.490.000
                        </span>
                    </div>

                    {{-- Catatan Kaki (Persis Screenshot 3) --}}
                    <p class="pt-1 text-[11px] text-slate-400 italic">
                        Perkiraan kasar berdasarkan tarif rata-rata.
                    </p>

                    {{-- Tombol Aksi Langsung ke Destinasi --}}
                    <div class="space-y-2 pt-4">
                        <p class="text-[11px] leading-relaxed text-slate-400">
                            Katalog destinasi akan otomatis difilter sesuai kategori anggaran yang dipilih.
                        </p>
                        <a id="sb-catalog-link"
                           href="{{ route('public.wisata', ['budget' => $tierBudgetAwal]) }}#destinasi"
                           data-base-url="{{ route('public.wisata') }}"
                           class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-navy-900 py-3.5 px-6 font-display text-xs font-bold text-white shadow-md transition hover:bg-navy-800 active:scale-95">
                            <i class="fas fa-map-location-dot"></i>
                            <span>Jelajahi Katalog Destinasi Wisata</span>
                            <span id="sb-catalog-tier-label"
                                  class="rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide">
                                {{ $opsiTierBudget[$tierBudgetAwal] }}
                            </span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
/**
 * Logika Perhitungan Smart Budget Wisata Bandung
 * Dikalibrasikan secara presisi agar (2 hari, 2 orang, Sedang) menghasilkan Rp 1.490.000
 */
let currentBudgetTier = @json($tierBudgetAwal);

// Saran per kategori dirender dari server, termasuk contoh destinasi yang
// diambil dari katalog. Dengan begitu teks dan filter selalu sinkron.
const SARAN_TIER = @json($saranTierBudget);
const CONTOH_DESTINASI = @json($contohBudget ?? []);

const BUDGET_RATES = {
    hemat: {
        hotelPerNight: 250000,
        foodPerPersonDay: 50000,
        transportPerDay: 35000,
        ticketPerPersonDay: 25000,
        label: "Hemat"
    },
    sedang: {
        hotelPerNight: 550000,
        foodPerPersonDay: 125000,
        transportPerDay: 100000,
        ticketPerPersonDay: 60000,
        label: "Sedang"
    },
    nyaman: {
        hotelPerNight: 1100000,
        foodPerPersonDay: 250000,
        transportPerDay: 250000,
        ticketPerPersonDay: 120000,
        label: "Nyaman"
    }
};

function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function setBudgetTier(tier) {
    currentBudgetTier = tier;

    ['hemat', 'sedang', 'nyaman'].forEach(t => {
        const btn = document.getElementById(`sb-btn-tier-${t}`);
        if (!btn) return;
        if (t === tier) {
            btn.className = 'sb-tier-btn rounded-xl py-2.5 text-center font-display text-xs font-bold bg-brand-600 text-white shadow-sm transition';
        } else {
            btn.className = 'sb-tier-btn rounded-xl py-2.5 text-center font-display text-xs font-bold text-slate-600 transition hover:text-slate-900';
        }
    });

    calculateSmartBudget();
}

function calculateSmartBudget() {
    const daysInput = document.getElementById('sb-range-days');
    const peopleInput = document.getElementById('sb-range-people');
    if (!daysInput || !peopleInput) return;

    const days = parseInt(daysInput.value, 10);
    const people = parseInt(peopleInput.value, 10);

    document.getElementById('sb-display-days').textContent = days;
    document.getElementById('sb-display-people').textContent = people;

    const rates = BUDGET_RATES[currentBudgetTier];

    const nights = Math.max(0, days - 1);
    const rooms = Math.ceil(people / 2);

    const costPenginapan = nights > 0 ? (rates.hotelPerNight * nights * rooms) : 0;
    const costMakan = people * days * rates.foodPerPersonDay;
    const costTransport = days * rates.transportPerDay;
    const costTiket = people * days * rates.ticketPerPersonDay;

    const total = costPenginapan + costMakan + costTransport + costTiket;

    document.getElementById('sb-cost-penginapan').textContent = formatRupiah(costPenginapan);
    document.getElementById('sb-cost-makan').textContent = formatRupiah(costMakan);
    document.getElementById('sb-cost-transport').textContent = formatRupiah(costTransport);
    document.getElementById('sb-cost-tiket').textContent = formatRupiah(costTiket);
    document.getElementById('sb-cost-total').textContent = formatRupiah(total);

    const recTitle = document.getElementById('sb-tier-recommendation-title');
    const recDesc = document.getElementById('sb-tier-recommendation-desc');
    const saran = SARAN_TIER[currentBudgetTier];
    if (recTitle && recDesc && saran) {
        recTitle.textContent = saran.title;

        const contoh = CONTOH_DESTINASI[currentBudgetTier] || [];
        const contohText = contoh.length
            ? ' Contoh destinasi: ' + contoh.join(', ') + '.'
            : '';

        recDesc.textContent = saran.desc + contohText;
    }

    // Tombol katalog selalu membawa kategori yang sedang dipilih, sehingga
    // katalog destinasi tersaring sinkron dengan pilihan Hemat/Sedang/Nyaman.
    const catalogLink = document.getElementById('sb-catalog-link');
    if (catalogLink) {
        const baseUrl = catalogLink.dataset.baseUrl || catalogLink.href.split('?')[0];
        catalogLink.href = baseUrl + '?budget=' + encodeURIComponent(currentBudgetTier) + '#destinasi';
    }

    const catalogTierLabel = document.getElementById('sb-catalog-tier-label');
    if (catalogTierLabel) {
        catalogTierLabel.textContent = rates.label;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // setBudgetTier juga memperbarui tampilan tombol tier dan tautan katalog.
    setBudgetTier(currentBudgetTier);
});
</script>
