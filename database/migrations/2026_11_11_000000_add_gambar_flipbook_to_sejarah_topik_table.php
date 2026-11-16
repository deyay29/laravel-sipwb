<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Gambar sampul (cover) untuk kartu Flipbook Digital. Bila kosong,
     * halaman publik memakai tampilan gradasi bawaan.
     */
    public function up(): void
    {
        Schema::table('sejarah_topik', function (Blueprint $table) {
            $table->string('gambar_flipbook')->nullable()->after('notebook');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sejarah_topik', function (Blueprint $table) {
            $table->dropColumn('gambar_flipbook');
        });
    }
};
