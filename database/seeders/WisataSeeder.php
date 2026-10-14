<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Seeder;

class WisataSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $wisatas = [
            [
                'nama_wisata' => 'Gedung Sate',
                'kategori' => 'Ikon & Sejarah',
                'lokasi' => 'Jl. Diponegoro No.22, Bandung Wetan',
                'deskripsi' => 'Nama Gedung Sate berasal dari ornamen di puncak menara yang bentuknya menyerupai tusuk sate. Selain menjadi ikon Kota Bandung, gedung ini dikenal sebagai saksi perjuangan tujuh pemuda yang gugur saat mempertahankan Gedung Sate pada masa kemerdekaan.',
                'harga_tiket' => 10000,
                'jam_operasional' => '09.30 - 16.00 WIB',
                'gambar' => 'images/beranda.png',
                'quiz_tersedia' => true,
            ],
            [
                'nama_wisata' => 'Tangkuban Perahu',
                'kategori' => 'Alam',
                'lokasi' => 'Jl. Raya Tangkuban Parahu, Lembang',
                'deskripsi' => 'Tangkuban Perahu adalah gunung api aktif dengan bentuk kerucut terpangkas seperti perahu terbalik. Gunung ini menjadi salah satu tujuan alam paling terkenal di Bandung Utara dengan hamparan kawah yang luas.',
                'harga_tiket' => 25000,
                'jam_operasional' => '07.00 - 17.00 WIB',
                'gambar' => 'images/beranda.png',
                'quiz_tersedia' => true,
            ],
            [
                'nama_wisata' => 'Museum Geologi Bandung',
                'kategori' => 'Museum',
                'lokasi' => 'Jl. Diponegoro No.57, Bandung Wetan',
                'deskripsi' => 'Museum Geologi menyimpan berbagai koleksi geologi dan edukasi peninggalan bersejarah, mulai dari fosil, mineral, hingga batuan vulkanik khas Jawa Barat.',
                'harga_tiket' => 20000,
                'jam_operasional' => '08.00 - 16.00 WIB',
                'gambar' => 'images/beranda.png',
                'quiz_tersedia' => false,
            ],
            [
                'nama_wisata' => 'Taman Hutan Raya Bandung',
                'kategori' => 'Alam',
                'lokasi' => 'Jl. Ir. H. Juanda, Dago, Coblong',
                'deskripsi' => 'Tahura Bandung adalah kawasan hutan kota yang menjadi paru-paru hijau Kota Bandung. Area ini sering digunakan untuk piknik dan jalan-jalan bersama keluarga.',
                'harga_tiket' => null,
                'jam_operasional' => '06.00 - 18.00 WIB',
                'gambar' => 'images/beranda.png',
                'quiz_tersedia' => false,
            ],
            [
                'nama_wisata' => 'Kampung Naga',
                'kategori' => 'Budaya',
                'lokasi' => 'Nagawen, Sumedang',
                'deskripsi' => 'Kampung Naga adalah kampung adat Sunda di lereng Gunung Jayawi yang mempertahankan arsitektur, tradisi, dan adat asli sampai hari ini.',
                'harga_tiket' => 10000,
                'jam_operasional' => '08.00 - 17.00 WIB',
                'gambar' => 'images/beranda.png',
                'quiz_tersedia' => false,
            ],
        ];

        foreach ($wisatas as $data) {
            Wisata::create($data + ['id_user' => $admin->id]);
        }
    }
}
