<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Embed Google Maps TANPA API key.
 *
 * Pendekatan: ambil koordinat (lat,long) dari link Google Maps, lalu bentuk
 * URL embed resmi `https://www.google.com/maps/embed/v1/place?key=...`.
 * Karena memakai embed v1 tetap butuh API key, alternatif yang kita pakai
 * adalah mode "output=embed" dari `google.com/maps?q=...` yang bisa
 * disematkan langsung lewat <iframe> tanpa API key sama sekali.
 */
final class MapEmbed
{
    /**
     * Domain yang boleh jadi sumber iframe.
     *
     * Kenapa perlu daftar putih: nilai peta dari panel admin dirender sebagai
     * HTML mentah. Tanpa whitelist, siapa pun yang bisa menempel kode di panel
     * admin bisa menyisipkan <script> atau iframe dari domain lain.
     */
    private const ALLOWED_HOSTS = [
        'google.com',
        'www.google.com',
        'maps.google.com',
        'google.co.id',
        'www.google.co.id',
        'maps.google.co.id',
    ];

    /**
     * Ubah nilai peta dari admin menjadi markup <iframe> yang aman.
     *
     * Menerima dua bentuk input:
     *   1. Kode <iframe> Google Maps (dari menu "Share > Embed a map")
     *      -> src diambil, lalu iframe dibangun ulang dari nol.
     *   2. URL biasa, koordinat, atau alamat teks
     *      -> dinormalisasi jadi URL embed lebih dulu.
     *
     * Hasilnya SELALU iframe bersih: atribut dan isi lain dari input dibuang,
     * sehingga tidak ada markup berbahaya yang ikut terbawa.
     *
     * @return string|null markup iframe, atau null kalau tidak bisa ditampilkan
     */
    public static function toSafeIframe(?string $value): ?string
    {
        $url = static::resolveSourceUrl($value);

        if ($url === null) {
            return null;
        }

        return '<iframe src="'.e($url).'" loading="lazy"'
            .' referrerpolicy="no-referrer-when-downgrade"'
            .' style="width:100%;height:100%;min-height:380px;border:0"'
            .' allowfullscreen></iframe>';
    }

    /**
     * Ambil URL embed yang valid dari berbagai bentuk input.
     */
    protected static function resolveSourceUrl(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim($value);

        // Bentuk 1: sudah berupa kode <iframe> -> ambil src saja.
        if (str_contains($value, '<iframe')) {
            $pattern = '/<iframe[^>]*\ssrc\s*=\s*(["\'])(.*?)\1/is';

            if (! preg_match($pattern, $value, $m)) {
                return null;
            }

            $src = html_entity_decode(trim($m[2]), ENT_QUOTES);

            return static::isAllowedHost($src) ? $src : null;
        }

        // Bentuk 2: URL biasa / koordinat / alamat teks.
        $url = static::toEmbedUrl($value);

        return static::isAllowedHost($url) ? $url : null;
    }

    /**
     * Pastikan URL memakai domain Google Maps yang diizinkan (https saja).
     */
    protected static function isAllowedHost(?string $url): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (! $host || $scheme !== 'https') {
            return false;
        }

        return in_array(strtolower($host), self::ALLOWED_HOSTS, true);
    }
    /**
     * Ubah link Google Maps apa pun menjadi URL yang bisa di-embed lewat
     * iframe, atau null kalau tidak bisa dibaca.
     *
     * Contoh input yang didukung:
     *   https://maps.google.com/?q=-6.8907,107.6164
     *   https://www.google.com/maps/place/Gedung+Sate/@-6.8907,107.6164,17z
     *   https://goo.gl/maps/xxxx
     *   -6.8907,107.6164            (koordinat mentah)
     *   Gedung Sate, Bandung        (alamat teks)
     */
    public static function toEmbedUrl(?string $source): ?string
    {
        if (blank($source)) {
            return null;
        }

        $source = trim($source);

        // 1) Sudah berupa URL embed -> pakai langsung.
        if (Str::contains($source, ['/maps/embed', 'google.com/maps/embed', 'maps/embed/v1'])) {
            return $source;
        }

        // 2) Ambil koordinat dari link maps apa pun.
        if ($coords = self::extractCoordinates($source)) {
            return self::embedFromCoordinates($coords);
        }

        // 3) Short link goo.gl / maps.app.goo.gl ->_follow redirect_ lalu
        //    ambil koordinat dari URL tujuan.
        if (Str::contains($source, ['goo.gl/maps', 'maps.app.goo.gl'])) {
            $resolved = self::resolveRedirect($source);

            if ($resolved && ($coords = self::extractCoordinates($resolved))) {
                return self::embedFromCoordinates($coords);
            }

            return $resolved ? self::embedFromPlace($resolved) : null;
        }

        // 4) Link google maps biasa tanpa koordinat -> pakai URL-nya langsung
        //    dengan mode output=embed supaya tetap bisa ditampilkan.
        if (Str::contains($source, ['google.com/maps', 'maps.google.com'])) {
            return self::embedFromPlace($source);
        }

        // 5) Sudah koordinat mentah atau alamat teks.
        if (self::looksLikeCoordinates($source)) {
            return self::embedFromCoordinates($source);
        }

        return self::embedFromPlace($source);
    }

    /**
     * Cari pasangan lat,long di dalam string mana pun.
     */
    protected static function extractCoordinates(string $source): ?string
    {
        // Cocokkan pola  -6.8907,107.6164  (opsional tanda, optional spasi)
        if (preg_match('/(-?\d{1,2}\.\d+)\s*,\s*(-?\d{1,3}\.\d+)/', $source, $m)) {
            return $m[1].','.$m[2];
        }

        // Pola  @-6.8907,107.6164,17z
        if (preg_match('/@(-?\d{1,2}\.\d+),(-?\d{1,3}\.\d+)/', $source, $m)) {
            return $m[1].','.$m[2];
        }

        return null;
    }

    protected static function looksLikeCoordinates(string $value): bool
    {
        return (bool) preg_match('/^-?\d{1,2}\.\d+\s*,\s*-?\d{1,3}\.\d+$/', trim($value));
    }

    /**
     * Bangun URL embed dari koordinat.
     */
    protected static function embedFromCoordinates(string $coords): string
    {
        [$lat, $lng] = array_pad(explode(',', $coords), 2, '0');

        return 'https://maps.google.com/maps?q='.rawurlencode(trim($lat).','.trim($lng))
            .'&z=15&output=embed';
    }

    /**
     * Bangun URL embed dari alamat teks atau link maps.
     */
    protected static function embedFromPlace(string $place): string
    {
        // Ambil bagian query ?q=... kalau ada supaya tidak ada parameter lain
        // yang ikut terbawa (mis. &t=maps).
        if (preg_match('/[?&]q=([^&]+)/', $place, $m)) {
            $q = urldecode($m[1]);

            return 'https://maps.google.com/maps?q='.rawurlencode($q).'&output=embed';
        }

        return 'https://maps.google.com/maps?q='.rawurlencode($place).'&output=embed';
    }

    /**
     * Ikuti redirect singkat (goo.gl) tanpa mengikuti semua hop.
     */
    protected static function resolveRedirect(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'HEAD',
                'timeout' => 3,
                'follow_location' => 0, // sneakily peek Location tanpa download body
                'ignore_errors' => true,
            ],
        ]);

        $headers = @get_headers($url, true, $context);

        if (! is_array($headers)) {
            return null;
        }

        foreach ($headers as $key => $value) {
            if (strtolower((string) $key) !== 'location') {
                continue;
            }

            $location = is_array($value) ? end($value) : $value;

            if (is_string($location) && $location !== '') {
                return $location;
            }
        }

        return null;
    }
}
