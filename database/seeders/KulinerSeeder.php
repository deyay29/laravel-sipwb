<?php

namespace Database\Seeders;

use App\Models\Kuliner;
use App\Models\User;
use Illuminate\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $kuliners = [
            [
                'nama_kuliner' => 'Mie Yamin Bandung',
                'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
                'harga' => 25000,
                'jam_operasional' => '10.00 - 23.00 WIB',
                'kontak' => '0812-1111-2222',
                'alamat_lengkap' => 'Jl. Trunojoyo, Bandung',
                'deskripsi' => 'Mie yamin khas Bandung dengan kuah kaldu gurih, dilengkapi bakso, sabuk, dan kerupuk. Rasanya yang hangat cocok dimakan saat cuaca dingin.',
                'foto' => 'images/beranda.png',
                'status' => Kuliner::STATUS_AKTIF,
            ],
            [
                'nama_kuliner' => 'Nasi Goreng Jawa Bandung',
                'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
                'harga' => 20000,
                'jam_operasional' => '09.00 - 22.00 WIB',
                'kontak' => '0812-3333-4444',
                'alamat_lengkap' => 'Jl. Cijerah, Bandung Kulon',
                'deskripsi' => 'Nasi goreng Jawa dengan bumbu kacang dan sambal pedas. Legendaris dan digandrungi warga Bandung sejak puluhan tahun lalu.',
                'foto' => 'images/beranda.png',
                'status' => Kuliner::STATUS_AKTIF,
            ],
            [
                'nama_kuliner' => 'Es Campur Pak Raden',
                'kategori_suhu' => Kuliner::SUHU_MENYEGARKAN,
                'harga' => 12000,
                'jam_operasional' => '11.00 - 21.00 WIB',
                'kontak' => '0812-5555-6666',
                'alamat_lengkap' => 'Jl. Suryasari, Bandung',
                'deskripsi' => 'Es campur khas Bandung dengan es serut, nata de coco, kacang hijau, dan santan kental. Pilihan tepat saat cuaca sedang panas.',
                'foto' => 'images/beranda.png',
                'status' => Kuliner::STATUS_AKTIF,
            ],
            [
                'nama_kuliner' => 'Kafe Roko Roko',
                'kategori_suhu' => Kuliner::SUHU_MENYEGARKAN,
                'harga' => 45000,
                'jam_operasional' => '08.00 - 22.00 WIB',
                'kontak' => '0812-7777-8888',
                'alamat_lengkap' => 'Jl. Asia Afrika, Bandung',
                'deskripsi' => 'Kafe dengan suasana vintage di dekat Gedung Merdeka, menyediakan kopi tubruk khas dan makanan ringan.',
                'foto' => 'images/beranda.png',
                'status' => Kuliner::STATUS_AKTIF,
            ],
            [
                'nama_kuliner' => 'Sate Lembang',
                'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
                'harga' => 30000,
                'jam_operasional' => '15.00 - 23.00 WIB',
                'kontak' => '0812-9999-0000',
                'alamat_lengkap' => 'Jl. Raya Lembang, Bandung Utara',
                'deskripsi' => 'Sate khas Bandung dibakar dengan bumbu kacang, ditemani nasi dan lalapan.',
                'foto' => 'images/beranda.png',
                'status' => Kuliner::STATUS_NONAKTIF,
            ],
        ];

        foreach ($kuliners as $data) {
            Kuliner::create($data + ['id_user' => $admin->id]);
        }
    }
}
