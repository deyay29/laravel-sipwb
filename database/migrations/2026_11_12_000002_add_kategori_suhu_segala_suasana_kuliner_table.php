<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah nilai `Segala Suasana` ke enum `kategori_suhu` pada tabel kuliner.
     *
     * Nilai baru ini mewakili suasana kuliner yang umum / tidak terikat
     * khusus pada kategori cuaca panas atau dingin.
     */
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE kuliner MODIFY COLUMN kategori_suhu ENUM('Menghangatkan', 'Menyegarkan', 'Segala Suasana') NULL"
        );
    }

    /**
     * Kembalikan enum `kategori_suhu` ke nilai sebelumnya.
     */
    public function down(): void
    {
        DB::statement(
            "ALTER TABLE kuliner MODIFY COLUMN kategori_suhu ENUM('Menghangatkan', 'Menyegarkan') NULL"
        );
    }
};
