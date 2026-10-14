<?php

namespace Database\Seeders;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $galeris = [
            ['Gedung Sate dari Sore', 'Wisata', 'Siluet Gedung Sate saat matahari terbenam.'],
            ['Suasana Jalan Braga', 'Wisata', 'Kawasan Braga yang dipenuhi bangunan kolonial.'],
            ['Kawah Tangkuban Perahu', 'Wisata', 'Pemandangan kawah dari titik pandang tertinggi.'],
            ['Nasi Goreng Khas Bandung', 'Kuliner', 'Sajian nasi goreng Jawa dengan sambal pedas.'],
            ['Es Campur Khas Kota', 'Kuliner', 'Es campur dengan santan dan nata de coco.'],
            ['Arsip Konferensi Asia Afrika', 'Sejarah', 'Dokumentasi kegiatan Konferensi Asia Afrika.'],
        ];

        foreach ($galeris as [$judul, $kategori, $keterangan]) {
            Galeri::create([
                'judul' => $judul,
                'kategori_objek' => $kategori,
                'file_gambar' => 'images/beranda.png',
                'keterangan' => $keterangan,
                'id_user' => $admin->id,
            ]);
        }
    }
}
