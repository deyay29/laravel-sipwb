<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambah kolom harga (minimum & maksimum) ke tabel kuliner agar admin
     * dapat menyimpan kisaran harga. Kolom `harga` lama tetap ada sebagai nilai
     * legacy, tetapi fitur baru memakai kolom `harga_minimum` / `harga_maksimum`.
     */
    public function up(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->unsignedInteger('harga_minimum')->nullable()->after('harga');
            $table->unsignedInteger('harga_maksimum')->nullable()->after('harga_minimum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->dropColumn(['harga_minimum', 'harga_maksimum']);
        });
    }
};
