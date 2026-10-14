<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Contact;
use App\Models\Galeri;
use App\Models\Kuliner;
use App\Models\ReaksiBerita;
use App\Models\Sejarah;
use App\Models\SmartBudgetPaket;
use App\Models\Wisata;
use App\Services\WeatherService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
            'wisatas' => Wisata::latest()->take(4)->get(),
            'beritas' => Berita::latest('tanggal')->take(4)->get(),
            'sejarahs' => Sejarah::latest()->take(4)->get(),
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
        $recommended = Kuliner::aktif()
            ->where('kategori_suhu', $suhuKategori)
            ->latest()
            ->take($limit)
            ->get();

        if ($recommended->count() >= $limit) {
            return $recommended;
        }

        $pelengkap = Kuliner::aktif()
            ->when(
                $recommended->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id_kuliner', $recommended->modelKeys())
            )
            ->latest()
            ->take($limit - $recommended->count())
            ->get();

        return $recommended->concat($pelengkap);
    }

    /**
     * Halaman kontak / profil organisasi.
     */
    public function kontak(): View
    {
        return view('public.kontak', ['contact' => Contact::instance()]);
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

        $query = Kuliner::aktif();

        // Filter hanya dipakai bila memang ada nilainya di query string,
        // supaya halaman tanpa filter tetap menampilkan semua kuliner.
        if ($request->filled('suhu')) {
            $query->where('kategori_suhu', $suhuDipilih);
        }

        $kuliners = $query->latest()->paginate(10)->withQueryString();

        return view('public.kuliner.index', [
            'kuliners' => $kuliners,
            'weather' => $weather,
            'suhu' => $suhu,
            'suhuRekomendasi' => $suhuRekomendasi,
            'suhuDipilih' => $suhuDipilih,
            'suhuAktif' => $request->filled('suhu'),
        ]);
    }

    public function kulinerShow(Kuliner $kuliner): View
    {
        abort_unless($kuliner->status === Kuliner::STATUS_AKTIF, 404);

        return view('public.kuliner.show', compact('kuliner'));
    }

    // ==========================================
    // BERITA (Dengan Audio & Reaksi)
    // ==========================================

    public function berita(): View
    {
        $beritas = Berita::latest('tanggal')->paginate(10);

        return view('public.berita.index', compact('beritas'));
    }

    public function beritaShow(Berita $berita): View
    {
        $berita->increment('visitor_count');

        // Satu berita hanya boleh punya satu baris reaksi.
        $reaksi = ReaksiBerita::firstOrCreate(['id_berita' => $berita->id_berita]);

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

    public function wisata(): View
    {
        $wisatas = Wisata::latest()->paginate(12);

        return view('public.wisata.index', compact('wisatas'));
    }

    public function wisataShow(Wisata $wisata): View
    {
        return view('public.wisata.show', compact('wisata'));
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
        $sejarahs = Sejarah::latest()->paginate(10);

        return view('public.sejarah.index', compact('sejarahs'));
    }

    public function sejarahShow(Sejarah $topik): View
    {
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
}
