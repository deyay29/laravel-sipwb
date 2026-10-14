<?php

namespace Database\Seeders;

use App\Models\SmartBudgetPaket;
use App\Models\User;
use Illuminate\Database\Seeder;

class SmartBudgetSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $pakets = [
            [
                'nama_paket' => 'Wisata Murah Sehari',
                'kategori' => 'Hemat',
                'deskripsi' => 'Paket hemat untuk wisata satu hari di dalam Kota Bandung.',
                'total_estimasi' => 120000,
                'foto' => 'images/beranda.png',
                'rincian_biaya' => [
                    ['item' => 'Tiket masuk museum', 'biaya' => 20000],
                    ['item' => 'Makan siang', 'biaya' => 35000],
                    ['item' => 'Ongkos perjalanan', 'biaya' => 30000],
                    ['item' => 'Jajan dan oleh-oleh', 'biaya' => 35000],
                ],
            ],
            [
                'nama_paket' => 'Liburan Keluarga Akhir Pekan',
                'kategori' => 'Keluarga',
                'deskripsi' => 'Rencana liburan bersama seluruh keluarga dengan anggaran terbatas.',
                'total_estimasi' => 650000,
                'foto' => 'images/beranda.png',
                'rincian_biaya' => [
                    ['item' => 'Penginapan satu malam', 'biaya' => 350000],
                    ['item' => 'Tiket wisata', 'biaya' => 100000],
                    ['item' => 'Makan tiga kali sehari', 'biaya' => 150000],
                    ['item' => 'Transportasi', 'biaya' => 50000],
                ],
            ],
            [
                'nama_paket' => 'Jalan-Jalan Budget Lengkap',
                'kategori' => 'Menengah',
                'deskripsi' => 'Paket lengkap dengan penginapan dan layanan premium.',
                'total_estimasi' => 1500000,
                'foto' => 'images/beranda.png',
                'rincian_biaya' => [
                    ['item' => 'Hotel bintang empat', 'biaya' => 900000],
                    ['item' => 'Kuliner premium', 'biaya' => 300000],
                    ['item' => 'Transportasi dan tiket', 'biaya' => 200000],
                    ['item' => 'Belanja oleh-oleh', 'biaya' => 100000],
                ],
            ],
        ];

        foreach ($pakets as $data) {
            SmartBudgetPaket::create($data + ['id_user' => $admin->id]);
        }
    }
}
