<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\Sejarah;
use App\Models\Kuliner;
use App\Models\Berita;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function dashboard()
    {
        $counts = [
            'kuliner_total'   => Kuliner::count(),
            'kuliner_aktif'   => Kuliner::where('status', 'aktif')->count(),
            'kuliner_nonaktif'=> Kuliner::where('status', 'nonaktif')->count(),
            'wisata'          => Wisata::count(),
            'berita'          => Berita::count(),
            'sejarah'         => Sejarah::count(),
        ];

        // Fetch weather from Open-Meteo (Bandung: lat=-6.9147, lon=107.6098)
        $weather = null;
        try {
            $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude'       => -6.9147,
                'longitude'      => 107.6098,
                'current_weather'=> true,
                'hourly'         => 'relativehumidity_2m',
            ]);
            if ($response->ok()) {
                $data = $response->json();
                $cw = $data['current_weather'] ?? null;
                if ($cw) {
                    $weather = [
                        'temp'      => round($cw['temperature']),
                        'windspeed' => $cw['windspeed'],
                        'time'      => $cw['time'],
                        'condition' => $this->getWeatherCondition($cw['weathercode']),
                    ];
                }
            }
        } catch (\Exception $e) {
            // Fallback jika API tidak tersedia
        }

        return view('admin.dashboard', compact('counts', 'weather'));
    }

    private function getWeatherCondition(int $code): string
    {
        if ($code === 0) return 'Cerah';
        if (in_array($code, [1, 2])) return 'Cerah Berawan';
        if ($code === 3) return 'Berawan';
        if (in_array($code, [45, 48])) return 'Berkabut';
        if (in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82])) return 'Hujan';
        if (in_array($code, [71, 73, 75, 77])) return 'Bersalju';
        if (in_array($code, [95, 96, 99])) return 'Badai';
        return 'Berawan';
    }
}
