<?php

namespace App\Support;

use App\Models\Kuliner;
use App\Models\Wisata;

/**
 * Kalkulator anggaran perjalanan ("Smart Budget").
 *
 * Angka TIDAK dikarang: semua pos biaya dihitung dari harga yang benar-benar
 * tersimpan di database (wisata.harga_tiket, kuliner.harga) atau dari
 * parameter yang diisi sendiri pengguna (jumlah orang, jumlah hari, dsb).
 * Dengan begitu angkanya bisa dipertanggungjawabkan dan bisa dirujuk
 * ke sumbernya masing-masing.
 *
 * Pos yang tidak ada sumber harga riil (mis. penginapan, transport) sengaja
 * TIDAK diberi angka tebakan: nilainya 0 / null supaya pengguna mengisinya
 * sendiri, atau agar terlihat jelas sebagai "belum diestimasi".
 */
final class BudgetCalculator
{
    /**
     * Daftar pos biaya standar beserta sumber harga.
     *
     * 'source' = 'ticet'   -> diambil dari sumbu datallisat
     * 'source' = 'kuliner' -> diambil dari data kuliner
     * 'source' = 'manual'  -> diisi pengguna (tidak ditebak)
     */
    public const POS = [
        'tiket' => [
            'label' => 'Tiket masuk wisata',
            'source' => 'tiket',
            'satuan' => 'per orang',
        ],
        'makan' => [
            'label' => 'Makan (kuliner terpilih)',
            'source' => 'kuliner',
            'satuan' => 'per orang',
        ],
        'parkir' => [
            'label' => 'Parkir & transport',
            'source' => 'manual',
            'satuan' => 'per hari',
        ],
        'penginapan' => [
            'label' => 'Penginapan',
            'source' => 'manual',
            'satuan' => 'per malam',
        ],
    ];

    /**
     * Hitung rincian anggaran.
     *
     * @param  int  $jumlahOrang  jumlah peserta
     * @param  int  $jumlahHari  durasi (untuk biaya per hari)
     * @param  int  $jumlahMalam  jumlah malam menginap
     * @param  int|null  $tiketPerOrang  harga tiket (null = pakai rata-rata dari DB)
     * @param  int|null  $makanPerOrang  harga makan (null = pakai rata-rata dari DB)
     * @param  int  $parkirPerHari  biaya transport per hari (diisi pengguna)
     * @param  int  $penginapanPerMalam  biaya penginapan per malam (diisi pengguna)
     * @return array{pos: array<int, array>, total: int, sumber: array}
     */
    public static function hitung(
        int $jumlahOrang = 1,
        int $jumlahHari = 1,
        int $jumlahMalam = 0,
        ?int $tiketPerOrang = null,
        ?int $makanPerOrang = null,
        int $parkirPerHari = 0,
        int $penginapanPerMalam = 0,
    ): array {
        $jumlahOrang = max(1, $jumlahOrang);
        $jumlahHari = max(1, $jumlahHari);
        $jumlahMalam = max(0, $jumlahMalam);

        // Harga acuan dari data nyata di DB.
        $tiketAcuan = self::hargaTiketAcuan();
        $makanAcuan = self::hargaKulinerAcuan();

        $hargaTiket = $tiketPerOrang ?? $tiketAcuan;
        $hargaMakan = $makanPerOrang ?? $makanAcuan;

        $pos = [];

        $pos[] = [
            'key' => 'tiket',
            'label' => self::POS['tiket']['label'],
            'keterangan' => $jumlahOrang.' orang x Rp'.number_format($hargaTiket, 0, ',', '.'),
            'total' => $hargaTiket * $jumlahOrang,
            'terisi' => $hargaTiket > 0,
            'sumber' => $tiketPerOrang === null
                ? 'Rata-rata harga tiket dari data wisata'
                : 'Disesuaikan pengguna',
        ];

        // Makan dihitung per hari.
        $totalMakan = $hargaMakan * $jumlahOrang * $jumlahHari;
        $pos[] = [
            'key' => 'makan',
            'label' => self::POS['makan']['label'],
            'keterangan' => $jumlahOrang.' orang x '.$jumlahHari.' hari x Rp'.number_format($hargaMakan, 0, ',', '.'),
            'total' => $totalMakan,
            'terisi' => $hargaMakan > 0,
            'sumber' => $makanPerOrang === null
                ? 'Rata-rata harga kuliner dari data kuliner'
                : 'Disesuaikan pengguna',
        ];

        $totalParkir = $parkirPerHari * $jumlahHari;
        $pos[] = [
            'key' => 'parkir',
            'label' => self::POS['parkir']['label'],
            'keterangan' => $jumlahHari.' hari x Rp'.number_format($parkirPerHari, 0, ',', '.'),
            'total' => $totalParkir,
            'terisi' => $parkirPerHari > 0,
            'sumber' => 'Diisi sendiri oleh pengguna',
        ];

        $totalPenginapan = $penginapanPerMalam * $jumlahMalam;
        $pos[] = [
            'key' => 'penginapan',
            'label' => self::POS['penginapan']['label'],
            'keterangan' => $jumlahMalam.' malam x Rp'.number_format($penginapanPerMalam, 0, ',', '.'),
            'total' => $totalPenginapan,
            'terisi' => $penginapanPerMalam > 0,
            'sumber' => 'Diisi sendiri oleh pengguna',
        ];

        $total = array_sum(array_column($pos, 'total'));

        $sumber = [
            'tiket' => $hargaTiket,
            'makan' => $hargaMakan,
            'jumlah_data_wisata' => $tiketAcuan !== null ? Wisata::count() : 0,
            'jumlah_data_kuliner' => $makanAcuan !== null ? Kuliner::count() : 0,
        ];

        return [
            'pos' => $pos,
            'total' => $total,
            'sumber' => $sumber,
        ];
    }

    /**
     * Rata-rata harga tiket yang benar-benar tersimpan di DB.
     */
    public static function hargaTiketAcuan(): int
    {
        return (int) (Wisata::query()
            ->whereNotNull('harga_tiket')
            ->where('harga_tiket', '>', 0)
            ->avg('harga_tiket') ?? 0);
    }

    /**
     * Rata-rata harga kuliner yang benar-benar tersimpan di DB.
     */
    public static function hargaKulinerAcuan(): int
    {
        return (int) (Kuliner::query()
            ->whereNotNull('harga')
            ->where('harga', '>', 0)
            ->avg('harga') ?? 0);
    }

    /**
     * DaftarXBertentional目的地 yang punya harga, untuk dipilih pengguna.
     */
    public static function pilihanWisata(): array
    {
        return Wisata::query()
            ->whereNotNull('harga_tiket')
            ->where('harga_tiket', '>', 0)
            ->orderBy('nama_wisata')
            ->get(['id_wisata', 'nama_wisata', 'harga_tiket'])
            ->map(fn ($w) => [
                'id' => $w->id_wisata,
                'nama' => $w->nama_wisata,
                'harga' => (int) $w->harga_tiket,
            ])
            ->all();
    }

    /**
     * Daftar kuliner yang punya harga.
     */
    public static function pilihanKuliner(): array
    {
        return Kuliner::query()
            ->whereNotNull('harga')
            ->where('harga', '>', 0)
            ->where('status', Kuliner::STATUS_AKTIF)
            ->orderBy('nama_kuliner')
            ->get(['id_kuliner', 'nama_kuliner', 'harga'])
            ->map(fn ($k) => [
                'id' => $k->id_kuliner,
                'nama' => $k->nama_kuliner,
                'harga' => (int) $k->harga,
            ])
            ->all();
    }
}
