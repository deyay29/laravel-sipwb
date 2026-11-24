<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kuliner;
use App\Models\Sejarah;
use App\Models\Wisata;
use App\Services\WeatherService;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $counts = [
            'kuliner_total' => Kuliner::count(),
            'kuliner_aktif' => Kuliner::aktif()->count(),
            'kuliner_nonaktif' => Kuliner::where('status', Kuliner::STATUS_NONAKTIF)->count(),
            'wisata' => Wisata::count(),
            'berita' => Berita::count(),
            'sejarah' => Sejarah::count(),
            'galeri' => Galeri::count(),
        ];

        return view('admin.dashboard', [
            'counts' => $counts,
            'recentSections' => $this->recentSections(),
            'weather' => $this->getWeather(),
        ]);
    }

    /**
     * Konten terbaru untuk setiap fitur, ditampilkan sebagai kartu di dashboard.
     *
     * Semua data dipetakan ke bentuk yang sama agar view bisa memakai satu
     * markup kartu untuk Kuliner, Wisata, Berita, Sejarah, dan Galeri.
     *
     * @return array<int, array<string, mixed>>
     */
    private function recentSections(): array
    {
        return [
            [
                'title' => 'Kuliner Terbaru',
                'description' => 'Foto sampul dan jumlah foto yang sudah diunggah.',
                'indexUrl' => route('admin.kuliner.index'),
                'createUrl' => route('admin.kuliner.create'),
                'empty' => 'Belum ada kuliner.',
                'icon' => 'fa-utensils',
                'tone' => 'amber',
                'items' => Kuliner::withCount('fotos')
                    ->latest('id_kuliner')
                    ->take(6)
                    ->get()
                    ->map(fn (Kuliner $item) => [
                        'title' => $item->nama_kuliner,
                        'image' => $item->foto,
                        'url' => route('admin.kuliner.edit', $item),
                        'badge' => $item->fotos_count > 0 ? (string) $item->fotos_count : null,
                        'badgeIcon' => 'fa-images',
                        'meta' => $item->status,
                        'metaTone' => $item->status === Kuliner::STATUS_AKTIF ? 'emerald' : 'slate',
                    ])
                    ->all(),
            ],
            [
                'title' => 'Wisata Terbaru',
                'description' => 'Data wisata terbaru beserta foto yang sudah diunggah.',
                'indexUrl' => route('admin.wisata.index'),
                'createUrl' => route('admin.wisata.create'),
                'empty' => 'Belum ada wisata.',
                'icon' => 'fa-mountain-sun',
                'tone' => 'emerald',
                'items' => Wisata::withCount('fotos')
                    ->latest('id_wisata')
                    ->take(6)
                    ->get()
                    ->map(fn (Wisata $item) => [
                        'title' => $item->nama_wisata,
                        'image' => $item->gambar,
                        'url' => route('admin.wisata.edit', $item),
                        'badge' => $item->fotos_count > 0 ? (string) $item->fotos_count : null,
                        'badgeIcon' => 'fa-images',
                        'meta' => $item->kategori ?: 'Wisata',
                        'metaTone' => 'emerald',
                    ])
                    ->all(),
            ],
            [
                'title' => 'Berita Terbaru',
                'description' => 'Berita yang terakhir dipublikasikan lewat panel admin.',
                'indexUrl' => route('admin.berita.index'),
                'createUrl' => route('admin.berita.create'),
                'empty' => 'Belum ada berita.',
                'icon' => 'fa-newspaper',
                'tone' => 'brand',
                'items' => Berita::latest('id_berita')
                    ->take(6)
                    ->get()
                    ->map(fn (Berita $item) => [
                        'title' => $item->judul,
                        'image' => $item->gambar,
                        'url' => route('admin.berita.edit', $item),
                        'badge' => $item->audio_url ? 'Audio' : null,
                        'badgeIcon' => 'fa-volume-high',
                        'meta' => $item->kategori ?: 'Berita',
                        'metaTone' => 'sky',
                    ])
                    ->all(),
            ],
            [
                'title' => 'Sejarah Terbaru',
                'description' => 'Topik sejarah terbaru beserta jumlah babnya.',
                'indexUrl' => route('admin.sejarah.index'),
                'createUrl' => route('admin.sejarah.create'),
                'empty' => 'Belum ada topik sejarah.',
                'icon' => 'fa-landmark',
                'tone' => 'violet',
                'items' => Sejarah::withCount('babs')
                    ->latest('id_sejarah')
                    ->take(6)
                    ->get()
                    ->map(fn (Sejarah $item) => [
                        'title' => $item->judul_topik,
                        'image' => $item->gambar_utama,
                        'url' => route('admin.sejarah.edit', $item),
                        'badge' => $item->babs_count > 0 ? (string) $item->babs_count : null,
                        'badgeIcon' => 'fa-book-open',
                        'meta' => $item->babs_count.' bab',
                        'metaTone' => 'violet',
                    ])
                    ->all(),
            ],
            [
                'title' => 'Galeri Terbaru',
                'description' => 'Foto galeri yang terakhir diunggah.',
                'indexUrl' => route('admin.galeri.index'),
                'createUrl' => route('admin.galeri.create'),
                'empty' => 'Belum ada foto galeri.',
                'icon' => 'fa-images',
                'tone' => 'slate',
                'items' => Galeri::latest('id_galeri')
                    ->take(6)
                    ->get()
                    ->map(fn (Galeri $item) => [
                        'title' => $item->judul,
                        'image' => $item->file_gambar,
                        'url' => route('admin.galeri.edit', $item),
                        'badge' => null,
                        'badgeIcon' => 'fa-image',
                        'meta' => $item->kategori_objek ?: 'Galeri',
                        'metaTone' => 'rose',
                    ])
                    ->all(),
            ],
        ];
    }

    /**
     * Cuaca Bandung untuk kartu dashboard.
     */
    private function getWeather(): ?array
    {
        return app(WeatherService::class)->current();
    }
}
