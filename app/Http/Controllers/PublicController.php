<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\Sejarah; // Mengarah ke tabel sejarah_topik
use App\Models\SejarahBab;
use App\Models\Kuliner;
use App\Models\Berita;
use App\Models\ReaksiBerita;
use App\Models\SmartBudgetPaket;
use App\Models\Galeri;
use App\Models\Contact;

class PublicController extends Controller
{
    // ==========================================
    // 0. HOME & PROFIL
    // ==========================================
    public function home()
    {
        $wisatas = Wisata::latest()->take(4)->get();
        $beritas = Berita::latest()->take(4)->get();
        $kuliners = Kuliner::latest()->take(5)->get();
        $sejarahs = Sejarah::latest()->take(4)->get();
        return view('public.home', compact('wisatas', 'beritas', 'kuliners', 'sejarahs'));
    }


    // ==========================================
    // 1. KULINER (Dengan Filter Suhu Cuaca)
    // ==========================================
    public function kuliner(Request $request)
    {
        $query = Kuliner::where('status', 'Aktif');

        // Logika Filter Suhu Cuaca
        if ($request->has('suhu') && in_array($request->suhu, ['Menghangatkan', 'Menyegarkan'])) {
            $query->where('kategori_suhu', $request->suhu);
        }

        $kuliners = $query->latest()->paginate(10);
        return view('public.kuliner', compact('kuliners'));
    }

    public function kulinerShow($slug)
    {
        $kuliner = Kuliner::where('slug', $slug)->where('status', 'Aktif')->firstOrFail();
        return view('public.kuliner_show', compact('kuliner'));
    }

    // ==========================================
    // 2. BERITA (Dengan Audio & Reaksi)
    // ==========================================
    public function berita()
    {
        $beritas = Berita::latest('tanggal')->paginate(10);
        return view('public.berita', compact('beritas'));
    }

    public function beritaShow($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();
        
        // Logika View Counter
        $berita->increment('visitor_count');
        
        // Mengambil tabel reaksi (buat record baru jika belum ada)
        $reaksi = ReaksiBerita::firstOrCreate(
            ['id_berita' => $berita->id_berita],
            ['like_count' => 0, 'applause_count' => 0, 'insightful_count' => 0]
        );
        
        return view('public.berita_show', compact('berita', 'reaksi'));
    }

    public function beritaReact(Request $request, $id_berita)
    {
        // Digunakan untuk request via AJAX/Fetch API dari Frontend
        $request->validate([
            'tipe_reaksi' => 'required|in:like,applause,insightful'
        ]);

        $reaksi = ReaksiBerita::where('id_berita', $id_berita)->firstOrFail();
        $kolom = $request->tipe_reaksi . '_count';
        
        // Tambahkan +1 pada reaksi yang diklik
        $reaksi->increment($kolom);

        return response()->json([
            'status' => 'success',
            'message' => 'Reaksi berhasil disimpan!',
            'data' => $reaksi
        ]);
    }

    // ==========================================
    // 3. WISATA & SMART BUDGET
    // ==========================================
    public function wisata()
    {
        $wisatas = Wisata::latest()->paginate(12);
        return view('public.wisata', compact('wisatas'));
    }

    public function wisataShow($slug)
    {
        $wisata = Wisata::where('slug', $slug)->firstOrFail();
        return view('public.wisata_show', compact('wisata'));
    }

    public function smartBudgetIndex()
    {
        $pakets = SmartBudgetPaket::all();
        return view('public.smart_budget', compact('pakets'));
    }

    // ==========================================
    // 4. SEJARAH & STORYTELLING PER BAB
    // ==========================================
    public function sejarah()
    {
        $sejarahs = Sejarah::latest()->paginate(10);
        return view('public.sejarah', compact('sejarahs'));
    }

    public function sejarahShow($slug)
    {
        $topik = Sejarah::where('slug', $slug)->firstOrFail();
        $babs = $topik->babs()->orderBy('nomor_bab', 'asc')->get();
        
        return view('public.sejarah_show', compact('topik', 'babs'));
    }

    public function sejarahBabDetail($slug, $nomor_bab)
    {
        $topik = Sejarah::where('slug', $slug)->firstOrFail();
        $bab = $topik->babs()->where('nomor_bab', $nomor_bab)->firstOrFail();

        return view('public.sejarah_chapter', compact('topik', 'bab'));
    }

    // ==========================================
    // 5. GALERI (Repository Visual)
    // ==========================================
    public function galeriIndex(Request $request)
    {
        $query = Galeri::query();

        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('kategori_objek', $request->kategori);
        }

        $galeris = $query->latest()->paginate(16);
        return view('public.galeri', compact('galeris'));
    }
}