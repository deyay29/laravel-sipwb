<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\ReaksiBerita;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BeritaSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $now = Carbon::now();

        $beritas = [
            [
                'judul' => 'Konferensi Asia Afrika 1955 Mengenalkan Bandung ke Dunia',
                'kategori' => 'Sejarah',
                'isi' => 'Konferensi Asia Afrika berlangsung pada 18-24 April 1955 di Gedung Merdeka, Bandung. Konferensi ini diikuti 29 negara dari Asia dan Afrika, dan menghasilkan Dasasila Bandung.',
                'gambar' => 'images/beranda.png',
                'audio_url' => null,
                'tanggal' => $now->copy()->subDays(2),
            ],
            [
                'judul' => 'Jalan Braga Kembali Ramai Dikunjungi',
                'kategori' => 'Wisata',
                'isi' => 'Jalan Braga, kawasan ikonik Kota Bandung dengan bangunan bergaya kolonial, kembali ramai dikunjungi pada akhir pekan.',
                'gambar' => 'images/beranda.png',
                'audio_url' => null,
                'tanggal' => $now->copy()->subDays(5),
            ],
            [
                'judul' => 'Kuliner Sate Bandung Tembus Omzet Rp1 Miliar',
                'kategori' => 'Kuliner',
                'isi' => 'Jumlah usaha kecil yang menjual sate khas Bandung terus meningkat dari tahun lalu.',
                'gambar' => 'images/beranda.png',
                'audio_url' => null,
                'tanggal' => $now->copy()->subDays(9),
            ],
        ];

        foreach ($beritas as $data) {
            $berita = Berita::create($data + ['id_user' => $admin->id]);

            ReaksiBerita::create([
                'id_berita' => $berita->id_berita,
                'like_count' => random_int(5, 120),
                'applause_count' => random_int(2, 60),
                'insightful_count' => random_int(1, 40),
            ]);
        }
    }
}
