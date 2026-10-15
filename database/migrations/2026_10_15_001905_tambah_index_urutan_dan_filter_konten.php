<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan index untuk mempercepat query yang paling sering dipakai di
 * halaman publik.
 *
 * Latar belakang:
 *
 * 1. Hampir semua halaman daftar memakai latest(), yang mengurutkan berdasarkan
 *    created_at. Tanpa index, MySQL harus mengurutkan seluruh tabel setiap kali
 *    (filesort). Tabel saat ini masih kecil, jadi belum terasa, tetapi akan
 *    langsung terasa begitu data bertambah.
 * 2. Halaman kuliner memfilter berdasarkan status DAN kategori_suhu sekaligus.
 *    Index gabungan membuat MySQL menyaring keduanya lewat satu index, bukan
 *    dua kali.
 *
 * 3. Halaman berita diurutkan berdasarkan tanggal.
 */
return new class extends Migration
{
    /**
     * Tabel yang diurutkan dengan latest() / orderBy pada halaman publik.
     *
     * @var list<string>
     */
    private array $terurut = [
        'berita',
        'kuliner',
        'wisata',
        'sejarah_topik',
        'galeri',
    ];

    public function up(): void
    {
        // Urutan berita memakai kolom tanggal, bukan created_at.
        Schema::table('berita', function (Blueprint $table) {
            $table->index('tanggal', 'berita_tanggal_desc_index');
        });

        foreach (array_diff($this->terurut, ['berita']) as $tabel) {
            Schema::table($tabel, function (Blueprint $table) use ($tabel) {
                $table->index('created_at', $tabel.'_created_at_index');
            });
        }

        // Filter kombinasi yang dipakai halaman kuliner.
        Schema::table('kuliner', function (Blueprint $table) {
            $table->index(
                ['status', 'kategori_suhu'],
                'kuliner_status_kategori_suhu_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->dropIndex('kuliner_status_kategori_suhu_index');
        });

        foreach (array_diff($this->terurut, ['berita']) as $tabel) {
            Schema::table($tabel, function (Blueprint $table) use ($tabel) {
                $table->dropIndex($tabel.'_created_at_index');
            });
        }

        Schema::table('berita', function (Blueprint $table) {
            $table->dropIndex('berita_tanggal_desc_index');
        });
    }
};
