<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Contact;
use App\Models\ContactMessage;
use App\Models\Galeri;
use App\Models\Kuliner;
use App\Models\ReaksiBerita;
use App\Models\Sejarah;
use App\Models\SmartBudgetPaket;
use App\Models\Wisata;
use App\Services\WeatherService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Beranda: ringkasan empat kategori konten.
     */
    public function home(WeatherService $weather): View
    {
        $weather = $weather->current();
        $suhu = $weather['temp'] ?? null;

        // Rekomendasi kuliner mengikuti suhu: >= 27°C menyegarkan, di bawahnya menghangatkan.
        $suhuKategori = $this->suhuRekomendasi($suhu);

        return view('public.home', [
            // Jumlah item dan kolom grid sengaja dibuat sama (4) untuk semua
            // section, agar ukuran setiap card di beranda identik.
            //
            // with()/withCount() dipakai agar relasi diambil sekaligus dalam
            // satu query. Tanpa itu, tiap baris di dalam loop view akan
            // memicu query tersendiri (N+1) dan halaman makin lama saat data
            // bertambah banyak.
            'wisatas' => Wisata::with('fotos')->latest()->take(4)->get(),
            'beritas' => Berita::with('reaksi')->latest('tanggal')->take(4)->get(),
            'sejarahs' => Sejarah::with('babs')->withCount('babs')->latest()->take(4)->get(),
            'kuliners' => $this->rekomendasiKuliner($suhuKategori),

            'weather' => $weather,
            'suhu' => $suhu,
            'suhuKategori' => $suhuKategori,
        ]);
    }

    /**
     * Kategori suhu yang disarankan untuk cuaca saat ini.
     *
     * >= 27°C -> Menyegarkan, di bawahnya -> Menghangatkan.
     * Tanpa data cuaca, default ke Menghangatkan.
     */
    private function suhuRekomendasi(?int $suhu): string
    {
        return $suhu === null || $suhu >= 27
            ? Kuliner::SUHU_MENYEGARKAN
            : Kuliner::SUHU_MENGHANGATKAN;
    }

    /**
     * Rekomendasi kuliner sesuai suhu, maksimal 4 item.
     *
     * Bila kategori sesuai terlalu sedikit, sisanya dilengkapi dengan kuliner
     * aktif lainnya supaya section di beranda tidak terlihat kosong.
     *
     * @return Collection<int, Kuliner>
     */
    private function rekomendasiKuliner(string $suhuKategori, int $limit = 4)
    {
        // Pusat kota dari config: dasar perbandingan "terdekat" untuk rekomendasi.
        $pusat = [
            'latitude' => (float) config('services.kuliner.pusat_latitude', -6.92222),
            'longitude' => (float) config('services.kuliner.pusat_longitude', 107.60694),
        ];

        $rekomendasi = Kuliner::aktif()
            ->with('fotos')
            ->where('kategori_suhu', $suhuKategori)
            ->get()
            ->sortBy(fn (Kuliner $kuliner) => $kuliner->distanceFrom($pusat['latitude'], $pusat['longitude']) ?? PHP_FLOAT_MAX)
            ->take($limit)
            ->values();

        if ($rekomendasi->count() >= $limit) {
            return $rekomendasi;
        }

        $pelengkap = Kuliner::aktif()
            ->with('fotos')
            ->when(
                $rekomendasi->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id_kuliner', $rekomendasi->modelKeys())
            )
            ->latest()
            ->take($limit - $rekomendasi->count())
            ->get()
            ->values();

        return $rekomendasi->concat($pelengkap);
    }

    /**
     * Tentukan titik referensi & radius untuk fitur "kuliner terdekat".
     *
     * Membaca input `lat`, `lng`, dan `radius` dari query string. Apabila tidak
     * ada satu pun yang valid, kembalikan null supaya halaman tanpa filter
     * tetap menampilkan semua kuliner aktif seperti biasa.
     *
     * @return array{latitude: float, longitude: float, radius_km: float}|null
     */
    private function lokasiTerdekat(Request $request): ?array
    {
        $latitude = $this->angkaDariRequest($request, 'lat', -90, 90);
        $longitude = $this->angkaDariRequest($request, 'lng', -180, 180);
        $radius = $this->angkaDariRequest($request, 'radius', 0, 500);

        $pakaiRadius = $radius !== null && $radius > 0;

        // Butuh kedua koordinat (latitude + longitude) sebagai titik
        // pengguna, atau setidaknya batas radius dari pusat kota.
        $adaKonteks = ($latitude !== null && $longitude !== null) || $pakaiRadius;

        if (! $adaKonteks) {
            return null;
        }

        // Tanpa koordinat pengguna, titik pusat kota dari config dipakai.
        // Radius default (10 km) dipakai bila pengunjung belum memilih sendiri.
        return [
            'latitude' => $latitude ?? (float) config('services.kuliner.pusat_latitude', -6.92222),
            'longitude' => $longitude ?? (float) config('services.kuliner.pusat_longitude', 107.60694),
            'radius_km' => $pakaiRadius ? $radius : (float) config('services.kuliner.default_radius_km', 10),
        ];
    }

    /**
     * Baca nilai numerik dari query string dengan validasi posisi numeriknya.
     */
    private function angkaDariRequest(Request $request, string $key, float $min, float $max): ?float
    {
        $value = $request->input($key);

        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        $number = (float) $value;

        if ($number < $min || $number > $max) {
            return null;
        }

        return $number;
    }

    /**
     * Ambil daftar kuliner aktif dalam radius, diurutkan dari terdekat.
     *
     * Semua kuliner dimuat dulu, jaraknya dihitung lewat Haversine, lalu
     * disaring & diurutkan sebelum dipaginasi. Dataset kuliner relatif kecil,
     * sehingga cara ini tetap sederhana dan menghindari query SQL yang rumit.
     *
     * @return LengthAwarePaginator<int, Kuliner>
     */
    private function kulinersTerdekat(Builder $query, array $lokasi, int $halaman, Request $request): LengthAwarePaginator
    {
        $kuliners = $query->latest()->get();

        $dalamRadius = collect();

        foreach ($kuliners as $kuliner) {
            $jarak = $kuliner->distanceFrom($lokasi['latitude'], $lokasi['longitude']);

            // Kuliner tanpa koordinat tidak bisa dihitung jaraknya.
            if ($jarak === null) {
                continue;
            }

            if ($jarak > $lokasi['radius_km']) {
                continue;
            }

            $kuliner->setAttribute('jarak_km', $jarak);

            $dalamRadius->push($kuliner);
        }

        $dalamRadius = $dalamRadius
            ->sortBy(fn (Kuliner $kuliner) => (float) $kuliner->jarak_km)
            ->values();

        $perPage = 10;
        $total = $dalamRadius->count();
        $lastPage = max((int) ceil($total / $perPage), 1);
        $halamanTerpilih = max(1, min($halaman, $lastPage));
        $items = $dalamRadius->forPage($halamanTerpilih, $perPage)->values();

        // Query string ikut dipakai supaya tautan pagination tidak kehilangan filter.
        return new LengthAwarePaginator($items, $total, $perPage, $halamanTerpilih, [
            'query' => $request->query(),
        ]);
    }

    /**
     * Halaman kontak / profil organisasi.
     */
    public function kontak(): View
    {
        return view('public.kontak', ['contact' => Contact::instance()]);
    }

    /**
     * Simpan kritik, saran, atau masukan dari halaman publik.
     *
     * Fitur ini bersifat anonim: nama & email tidak wajib diisi sama sekali
     * oleh pengunjung, sehingga tidak ada kebutuhan sistem balasan admin untuk
     * pengiriman feedback awal.
     */
    public function kontakKirim(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'in:'.implode(',', ContactMessage::KATEGORI_OPTIONS)],
            'pesan' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create([
            'kategori' => $validated['kategori'],
            'pesan' => $validated['pesan'],
        ]);

        return redirect()
            ->route('public.kontak')
            ->with('success', 'Terima kasih! Kritik, saran, atau masukan Anda berhasil dikirim.');
    }

    // ==========================================
    // KULINER (Dengan Filter Suhu Cuaca)
    // ==========================================

    public function kuliner(Request $request, WeatherService $weather): View
    {
        $weather = $weather->current();
        $suhu = $weather['temp'] ?? null;

        // Kategori yang paling cocok dengan cuaca saat ini.
        $suhuRekomendasi = $this->suhuRekomendasi($suhu);

        $suhuDipilih = $request->filled('suhu')
            && in_array($request->suhu, Kuliner::suhuOptions(), true)
                ? $request->suhu
                : $suhuRekomendasi;

        $query = Kuliner::aktif()->with('fotos')->withCount('fotos');

        // Filter hanya dipakai bila memang ada nilainya di query string,
        // supaya halaman tanpa filter tetap menampilkan semua kuliner.
        if ($request->filled('suhu')) {
            $query->where('kategori_suhu', $suhuDipilih);
        }

        // Filter Family & Kids (ramah anak): `family=1` (ramah) atau `family=0` (kurang ramah).
        $familiaTerpilih = null;

        if (in_array($request->input('family'), ['0', '1'], true)) {
            $familiaTerpilih = (int) $request->input('family');
        }

        if ($familiaTerpilih !== null) {
            $query->where('ramah_anak', $familiaTerpilih);
        }

        // Mode jarak: aktif bila ada koordinat pengguna (lat/lng) atau batas
        // radius (km) di query string. Mode ini mengurutkan & menyaring kuliner
        // dari yang terdekat ke yang terjauh.
        $lokasi = $this->lokasiTerdekat($request);

        if ($lokasi === null) {
            $kuliners = $query->latest()->paginate(10)->withQueryString();

            $modeTerdekat = false;
        } else {
            $kuliners = $this->kulinersTerdekat($query, $lokasi, (int) $request->query('page', 1), $request);

            $modeTerdekat = true;
        }

        return view('public.kuliner.index', [
            'kuliners' => $kuliners,
            'weather' => $weather,
            'suhu' => $suhu,
            'suhuRekomendasi' => $suhuRekomendasi,
            'suhuDipilih' => $suhuDipilih,
            'suhuAktif' => $request->filled('suhu'),
            'familiaTerpilih' => $familiaTerpilih,
            'modeTerdekat' => $modeTerdekat,
            'lokasiTerdekat' => $lokasi,
        ]);
    }

    public function kulinerShow(Kuliner $kuliner): View
    {
        abort_unless($kuliner->status === Kuliner::STATUS_AKTIF, 404);

        $kuliner->load('fotos');

        return view('public.kuliner.show', compact('kuliner'));
    }

    // ==========================================
    // BERITA (Dengan Audio & Reaksi)
    // ==========================================

    public function berita(): View
    {
        // with('reaksi') mencegah N+1: jumlah reaksi untuk seluruh halaman
        // diambil dalam satu query, bukan satu query per berita.
        $beritas = Berita::with('reaksi')->latest('tanggal')->paginate(10);

        return view('public.berita.index', compact('beritas'));
    }

    public function beritaShow(Berita $berita): View
    {
        // Ditambah secara langsung: satu query UPDATE sederhana (kurang dari 1 ms)
        // dan perilakunya mudah ditebak serta bisa diuji.
        $berita->increment('visitor_count');

        // Satu berita hanya boleh punya satu baris reaksi.
        // firstOrNew() tidak menulis apa pun sendiri, dan save() hanya dipanggil
        // bila barisnya memang belum pernah ada. Kalau tidak, setiap opening
        // halaman akan sia-sia melakukan UPDATE ke tabel yang tidak berubah.
        $reaksi = ReaksiBerita::firstOrNew(['id_berita' => $berita->id_berita]);

        if (! $reaksi->exists) {
            $reaksi->save();
        }

        return view('public.berita.show', compact('berita', 'reaksi'));
    }

    /**
     * Dipakai oleh request AJAX dari tombol reaksi di halaman detail.
     */
    public function beritaReact(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'tipe_reaksi' => ['required', 'in:'.implode(',', Berita::REAKSI_TIPE)],
        ]);

        $reaksi = ReaksiBerita::firstOrCreate(['id_berita' => $berita->id_berita]);
        $reaksi->incrementReaksi($validated['tipe_reaksi']);

        return response()->json([
            'status' => 'success',
            'message' => 'Reaksi berhasil disimpan!',
            'data' => $reaksi->fresh(),
        ]);
    }

    // ==========================================
    // WISATA & SMART BUDGET
    // ==========================================

    public function wisata(Request $request): View
    {
        $query = Wisata::with('fotos')->withCount('fotos')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_wisata', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%")
                    ->orWhere('kategori', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter kategori anggaran hanya dipakai bila nilainya valid, supaya
        // query string yang salah tidak membuat katalog kosong.
        $budget = $request->input('budget');

        $kategoriBudgetTerpilih = is_string($budget) && array_key_exists($budget, Wisata::BUDGET_TIERS)
            ? $budget
            : null;

        if ($kategoriBudgetTerpilih !== null) {
            $query->kategoriBudget($kategoriBudgetTerpilih);
        }

        $wisatas = $query->paginate(12)->withQueryString();
        $pakets = SmartBudgetPaket::orderBy('total_estimasi')->get();
        $kategoris = Wisata::whereNotNull('kategori')->distinct()->pluck('kategori');

        // Contoh destinasi per kategori dipakai untuk teks saran di tab
        // Smart Budget, agar rekomendasinya selalu sesuai isi katalog.
        $contohBudget = Wisata::query()
            ->whereNotNull('kategori_budget')
            ->orderBy('nama_wisata')
            ->get(['nama_wisata', 'kategori_budget'])
            ->groupBy('kategori_budget')
            ->map(fn ($items) => $items->pluck('nama_wisata')->take(3)->values()->all())
            ->all();

        return view('public.wisata.index', [
            'wisatas' => $wisatas,
            'pakets' => $pakets,
            'kategoris' => $kategoris,
            'kategoriBudgetOptions' => Wisata::BUDGET_TIERS,
            'kategoriBudgetTerpilih' => $kategoriBudgetTerpilih,
            'contohBudget' => $contohBudget,
        ]);
    }

    public function wisataShow(Wisata $wisata): View
    {
        $wisata->load('fotos');

        // Paket anggaran ikut dikirim supaya section Smart Budget bisa
        // ditampilkan di dalam halaman detail (anchor #smart-budget).
        $pakets = SmartBudgetPaket::orderBy('total_estimasi')->get();

        return view('public.wisata.show', compact('wisata', 'pakets'));
    }

    public function smartBudgetIndex(): View
    {
        $pakets = SmartBudgetPaket::orderBy('total_estimasi')->get();

        return view('public.smart-budget.index', compact('pakets'));
    }

    // ==========================================
    // SEJARAH & STORYTELLING PER BAB
    // ==========================================

    public function sejarah(): View
    {
        // withCount('babs') mengisi $topik->babs_count yang dipakai badge
        // "3 bab" di view, dan with('babs') dipakai sebagai fallback foto utama
        // bila gambar_utama belum diisi, tanpa query tambahan per topik.
        $sejarahs = Sejarah::with('babs')->withCount('babs')->latest()->paginate(10);

        return view('public.sejarah.index', compact('sejarahs'));
    }

    public function sejarahShow(Sejarah $topik): View
    {
        $topik->load('babs');
        $babs = $topik->babs;

        return view('public.sejarah.show', compact('topik', 'babs'));
    }

    public function sejarahBabDetail(Sejarah $topik, string $nomorBab): View
    {
        $bab = $topik->babs()->where('nomor_bab', (int) $nomorBab)->firstOrFail();

        return view('public.sejarah.chapter', compact('topik', 'bab'));
    }

    // ==========================================
    // GALERI (Repository Visual)
    // ==========================================

    public function galeriIndex(Request $request): View
    {
        $query = Galeri::query();

        if ($request->filled('kategori')) {
            $query->where('kategori_objek', $request->kategori);
        }

        $galeris = $query->latest()->paginate(16)->withQueryString();

        return view('public.galeri.index', compact('galeris'));
    }

    public function galeriShow(Galeri $galeri): View
    {
        // Utamakan foto dari kategori yang sama, lalu lengkapi dengan foto
        // lain supaya section "Galeri Terkait" tetap terisi.
        $terkait = Galeri::query()
            ->where('id_galeri', '!=', $galeri->id_galeri)
            ->when(
                $galeri->kategori_objek,
                fn ($query) => $query->where('kategori_objek', $galeri->kategori_objek)
            )
            ->latest()
            ->take(3)
            ->get();

        if ($terkait->count() < 3) {
            $terkait = $terkait->concat(
                Galeri::query()
                    ->whereNotIn('id_galeri', [$galeri->id_galeri, ...$terkait->modelKeys()])
                    ->latest()
                    ->take(3 - $terkait->count())
                    ->get()
            );
        }

        return view('public.galeri.show', compact('galeri', 'terkait'));
    }
}
