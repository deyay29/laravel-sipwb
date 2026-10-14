<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Cuaca untuk wilayah yang dikonfigurasi (default: Bandung).
 *
 * Sumber data: Open-Meteo.
 *
 * 1. Nama wilayah dari config/services.php ("Bandung") diterjemahkan menjadi
 *    koordinat lewat Geocoding API, hasilnya di-cache sehingga request
 *    berikutnya tidak memanggil API lagi.
 * 2. Koordinat hasil geocoding (atau koordinat cadangan dari config) dipakai
 *    untuk mengambil prakiraan cuaca.
 *
 * Semua kegagalan jaringan ditangani dengan rapi: method mengembalikan null dan
 * pemanggil wajib menyiapkan tampilan cadangan.
 */
class WeatherService
{
    /**
     * @return array{
     *     temp: int, windspeed: float, humidity: ?int, time: ?string,
     *     condition: string, is_rain: bool, region: string
     * }|null
     */
    public function current(): ?array
    {
        $location = $this->location();

        try {
            $response = Http::timeout(5)
                ->get($location['forecast_url'], [
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                    'current_weather' => true,
                    'hourly' => 'relativehumidity_2m',
                    'timezone' => $location['timezone'],
                ]);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->ok()) {
            return null;
        }

        $data = $response->json();
        $current = is_array($data) ? ($data['current_weather'] ?? null) : null;

        if (! is_array($current)) {
            return null;
        }

        $code = (int) ($current['weathercode'] ?? -1);

        return [
            'temp' => (int) round($current['temperature'] ?? 0),
            'windspeed' => (float) ($current['windspeed'] ?? 0),
            'humidity' => $this->humidity($data),
            'time' => $current['time'] ?? null,
            'condition' => $this->describe($code),
            'is_rain' => $this->isRain($code),
            'region' => $location['label'],
        ];
    }

    /**
     * Wilayah + koordinat yang dipakai.
     *
     * @return array{region: string, label: string, latitude: float, longitude: float, timezone: string, forecast_url: string}
     */
    public function location(): array
    {
        $config = config('services.open_meteo');

        return [
            'region' => $config['region'],
            'label' => $this->geocode($config)['label'] ?? $config['region'],
            'latitude' => $this->geocode($config)['latitude'] ?? $config['latitude'],
            'longitude' => $this->geocode($config)['longitude'] ?? $config['longitude'],
            'timezone' => $config['timezone'],
            'forecast_url' => $config['forecast_url'],
        ];
    }

    /**
     * Terjemahkan nama wilayah menjadi koordinat + nama yang lebih lengkap.
     *
     * Hasil disimpan di cache. Bila API tidak bisa dihubungi, koordinat
     * cadangan dari config dipakai.
     *
     * @return array{latitude: float, longitude: float, label: string}
     */
    private function geocode(array $config): ?array
    {
        $region = $config['region'];

        $fallback = [
            'latitude' => (float) $config['latitude'],
            'longitude' => (float) $config['longitude'],
            'label' => $region,
        ];

        if (blank($region)) {
            return $fallback;
        }

        $cacheKey = 'weather.geocode.'.md5($region);
        $minutes = (int) $config['geocode_cache_minutes'];

        try {
            return Cache::remember($cacheKey, $minutes, function () use ($config, $region, $fallback) {
                $response = Http::timeout(4)->get($config['geocoding_url'], [
                    'name' => $region,
                    'count' => 1,
                    'language' => 'id',
                    'format' => 'json',
                ]);

                if (! $response->ok()) {
                    return $fallback;
                }

                $result = $response->json('results.0');

                if (! is_array($result)) {
                    return $fallback;
                }

                return [
                    'latitude' => (float) ($result['latitude'] ?? $fallback['latitude']),
                    'longitude' => (float) ($result['longitude'] ?? $fallback['longitude']),
                    'label' => $result['name'] ?? $region,
                ];
            });
        } catch (\Throwable) {
            return $fallback;
        }
    }

    /**
     * Kelembapan saat ini dari array hourly, atau null bila tidak tersedia.
     */
    private function humidity(array $data): ?int
    {
        $times = $data['hourly']['time'] ?? null;
        $values = $data['hourly']['relativehumidity_2m'] ?? null;
        $currentTime = $data['current_weather']['time'] ?? null;

        if (! is_array($times) || ! is_array($values) || $currentTime === null) {
            return null;
        }

        $index = array_search(substr((string) $currentTime, 0, 13), array_map(
            static fn ($t) => substr((string) $t, 0, 13),
            $times
        ), true);

        if ($index === false || ! isset($values[$index])) {
            return null;
        }

        return (int) $values[$index];
    }

    /**
     * Kode cuaca WMO -> keterangan bahasa Indonesia.
     */
    private function describe(int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah',
            in_array($code, [1, 2], true) => 'Sebagian Besar Cerah',
            $code === 3 => 'Berawan',
            in_array($code, [45, 48], true) => 'Berkabut',
            in_array($code, [51, 53, 55], true) => 'Gerimis Ringan',
            in_array($code, [61, 63, 65], true) => 'Hujan',
            in_array($code, [71, 73, 75, 77], true) => 'Bersalju',
            in_array($code, [80, 81, 82], true) => 'Hujan Deras',
            in_array($code, [95, 96, 99], true) => 'Hujan Badai',
            default => 'Berawan',
        };
    }

    /**
     * True bila kondisi cuacanya hujan atau badai.
     */
    private function isRain(int $code): bool
    {
        return in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82, 95, 96, 99], true);
    }

    /**
     * Icon FontAwesome yang cocok dengan kondisi cuaca.
     */
    public static function icon(?array $weather): string
    {
        return match (true) {
            $weather === null => 'fa-cloud',
            $weather['is_rain'] => 'fa-cloud-rain',
            $weather['condition'] === 'Cerah' => 'fa-sun',
            $weather['condition'] === 'Sebagian Besar Cerah' => 'fa-cloud-sun',
            $weather['condition'] === 'Berkabut' => 'fa-smog',
            $weather['condition'] === 'Bersalju' => 'fa-snowflake',
            default => 'fa-cloud',
        };
    }

    /**
     * Kelas Tailwind untuk badge suhu, mengikuti nilai temperatur.
     *
     * Dipakai kartu cuaca agar warnanya benar-benar mencerminkan suhu:
     * dingin -> biru, sejuk -> sian, hangat -> amber, panas -> oranye.
     *
     * @return array{label: string, icon: string, badge: string}
     */
    public static function temperatureTone(?int $suhu): array
    {
        return match (true) {
            $suhu === null => [
                'label' => 'Suhu tidak tersedia',
                'icon' => 'fa-temperature-half',
                'badge' => 'border-white/30 bg-white/15 text-white',
            ],
            $suhu <= 15 => [
                'label' => 'Dingin',
                'icon' => 'fa-snowflake',
                'badge' => 'border-sky-300/50 bg-sky-500/25 text-sky-100',
            ],
            $suhu <= 24 => [
                'label' => 'Sejuk',
                'icon' => 'fa-wind',
                'badge' => 'border-cyan-300/50 bg-cyan-500/25 text-cyan-100',
            ],
            $suhu <= 30 => [
                'label' => 'Hangat',
                'icon' => 'fa-sun',
                'badge' => 'border-amber-300/50 bg-amber-500/25 text-amber-100',
            ],
            default => [
                'label' => 'Panas',
                'icon' => 'fa-temperature-high',
                'badge' => 'border-orange-400/50 bg-orange-500/30 text-orange-100',
            ],
        };
    }
}
