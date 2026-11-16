<?php

namespace App\Support;

final class Jarak
{
    /**
     * Banyak kilometer per derajat lintang (approksimasi meridian).
     */
    public const KM_PER_DEGREE = 111.32;

    /**
     * Banyak kilometer yang mencakup setengah lingkaran Bumi.
     */
    public const EARTH_RADIUS_KM = 6371;

    /**
     * Jarak dua titik koordinat dalam kilometer (rumus Haversine).
     *
     * Mengembalikan null bila salah satu titik tidak punya koordinat,
     * sehingga pemanggil boleh memakai hasilnya untuk menentukan apakah
     * kuliner memiliki titik lokasi atau belum.
     */
    public static function haversine(?float $latitude1, ?float $longitude1, ?float $latitude2, ?float $longitude2): ?float
    {
        if ($latitude1 === null || $longitude1 === null || $latitude2 === null || $longitude2 === null) {
            return null;
        }

        $radianLatitude1 = deg2rad($latitude1);
        $radianLatitude2 = deg2rad($latitude2);
        $deltaLatitude = deg2rad($latitude2 - $latitude1);
        $deltaLongitude = deg2rad($longitude2 - $longitude1);

        $a = sin($deltaLatitude / 2) ** 2
            + cos($radianLatitude1) * cos($radianLatitude2) * sin($deltaLongitude / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_KM * $c;
    }
}
