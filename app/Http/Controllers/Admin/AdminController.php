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
            'weather' => $this->getWeather(),
        ]);
    }

    /**
     * Cuaca Bandung untuk kartu dashboard.
     */
    private function getWeather(): ?array
    {
        return app(WeatherService::class)->current();
    }
}
