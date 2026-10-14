<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Resolver path gambar yang disimpan di database.
 *
 * Kolom gambar di database hanya berisi path relatif, misalnya
 * `images/beranda.png` atau `berita/gedung-sate.jpg`. Path tersebut bisa
 * menunjuk ke folder `public/` maupun ke `storage/app/public/`.
 *
 * Media::url() mengembalikan URL yang benar-benar bisa diakses browser,
 * atau null bila file-nya tidak ada. Null dipakai oleh komponen
 * <x-public.media> untuk menampilkan ikon sebagai pengganti.
 */
final class Media
{
    /**
     * URL gambar, atau null bila path kosong / file tidak ditemukan.
     */
    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        // URL eksternal (http/https) dipakai langsung.
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        // 1. Berada di dalam folder public/.
        if (is_file(public_path($path))) {
            return asset($path);
        }

        // 2. Berada di storage/app/public dan sudah dikuantkan lewat symlink.
        if (is_file(storage_path('app/public/'.$path)) && is_link(public_path('storage'))) {
            return asset('storage/'.$path);
        }

        // 3. Dicoba sebagai data URL (base64) atau URL tanpa scheme.
        if (Str::startsWith($path, 'data:')) {
            return $path;
        }

        return null;
    }

    /**
     * True bila gambar tersedia dan bisa ditampilkan.
     */
    public static function exists(?string $path): bool
    {
        return self::url($path) !== null;
    }
}
