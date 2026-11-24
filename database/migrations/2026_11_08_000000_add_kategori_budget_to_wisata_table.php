<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Kategori anggaran dipakai untuk menyinkronkan katalog destinasi wisata
     * dengan pilihan Hemat/Sedang/Nyaman di tab Smart Budget.
     */
    public function up(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->string('kategori_budget', 20)->default('hemat')->after('kategori')->index();
        });

        // Isi data lama supaya katalog langsung punya sebaran kategori.
        DB::table('wisata')
            ->orderBy('id_wisata')
            ->get(['id_wisata', 'kategori', 'harga_tiket'])
            ->each(function ($wisata) {
                DB::table('wisata')
                    ->where('id_wisata', $wisata->id_wisata)
                    ->update([
                        'kategori_budget' => $this->tentukanKategori($wisata->kategori, $wisata->harga_tiket),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->dropColumn('kategori_budget');
        });
    }

    /**
     * Destinasi gratis tetap masuk Hemat. Destinasi alam berbayar cenderung
     * butuh transportasi/area lebih luas (Nyaman), sedangkan museum dan
     * bangunan bersejarah berbayar masuk Sedang.
     */
    private function tentukanKategori(?string $kategori, ?int $harga): string
    {
        $harga = (int) ($harga ?? 0);
        $kategori = Str::lower((string) $kategori);

        if ($harga <= 0) {
            return 'hemat';
        }

        if (Str::contains($kategori, ['alam'])) {
            return 'nyaman';
        }

        if (Str::contains($kategori, ['museum', 'sejarah', 'ikon'])) {
            return 'sedang';
        }

        return 'hemat';
    }
};
