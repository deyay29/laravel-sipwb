<?php

namespace Database\Seeders;

use App\Models\Sejarah;
use App\Models\SejarahBab;
use App\Models\User;
use Illuminate\Database\Seeder;

class SejarahSeeder extends Seeder
{
    public function run(User $admin): void
    {
        $topics = [
            [
                'judul_topik' => 'Konferensi Asia Afrika',
                'ringkasan' => 'Konferensi Asia Afrika adalah peristiwa bersejarah yang menjadikan Kota Bandung dikenal di mata dunia. Konferensi berlangsung pada 18-24 April 1955 di Gedung Merdeka dan diikuti 29 negara.',
                'gambar_utama' => 'images/beranda.png',
                'babs' => [
                    [
                        'nomor_bab' => 1,
                        'judul_bab' => 'Lahirnya Ide Konferensi',
                        'isi_bab' => 'Ide ini disusun oleh negara-negara yang baru merdeka. Para pemimpin Asia dan Afrika melihat bahwa mereka punya kepentingan bersama dalam memperjuangkan kebebasan dan perdamaian.',
                    ],
                    [
                        'nomor_bab' => 2,
                        'judul_bab' => 'Dasasila Bandung',
                        'isi_bab' => 'Konferensi ditutup dengan menghasilkan Dasasila Bandung, yaitu sepuluh prinsip yang menjadi dasar hubungan antarbangsa dan rujukan diplomasi dunia hingga hari ini.',
                    ],
                    [
                        'nomor_bab' => 3,
                        'judul_bab' => 'Warisan Konferensi',
                        'isi_bab' => 'Gedung Merdeka kini menjadi museum yang menyimpan jejak sejarah Konferensi Asia Afrika.',
                    ],
                ],
            ],
            [
                'judul_topik' => 'Kota Bandung Masa Kolonial',
                'ringkasan' => 'Bandung ditetapkan sebagai ibukota kabupaten pada tahun 1810. Kota ini dibangun dengan sentuhan arsitektur kolonial Eropa.',
                'gambar_utama' => 'images/beranda.png',
                'babs' => [
                    [
                        'nomor_bab' => 1,
                        'judul_bab' => 'Bandung Tahun 1810',
                        'isi_bab' => 'Keputusan memindahkan ibukata ke Bandung menjadi awal perkembangan kota modern ini.',
                    ],
                    [
                        'nomor_bab' => 2,
                        'judul_bab' => 'Bangunan Kolonial',
                        'isi_bab' => 'Gedung tua di kawasan Dago dan Braga masih berdiri hingga kini sebagai penanda arsitektur kolonial.',
                    ],
                ],
            ],
            [
                'judul_topik' => 'Perjuangan Kemerdekaan',
                'ringkasan' => 'Bandung menjadi salah satu pusat perjuangan kemerdekaan pada masa revolusi nasional.',
                'gambar_utama' => 'images/beranda.png',
                'babs' => [
                    [
                        'nomor_bab' => 1,
                        'judul_bab' => 'Pertempuran Bandung 1946',
                        'isi_bab' => 'Pertempuran Bandung menjadi simbol perlawanan terhadap pendudukan Belanda yang kemudian berakhir.',
                    ],
                ],
            ],
        ];

        foreach ($topics as $topic) {
            $babs = $topic['babs'];
            unset($topic['babs']);

            $sejarah = Sejarah::create($topic + ['id_user' => $admin->id]);

            foreach ($babs as $bab) {
                SejarahBab::create($bab + ['id_sejarah' => $sejarah->id_sejarah]);
            }
        }
    }
}
