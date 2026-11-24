<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            // Titik lokasi (latitude/longitude) pusat kuliner, opsional.
            // Tanpa koordinat ini kuliner tidak bisa dihitung jaraknya ke pengguna lain.
            $table->decimal('latitude', 10, 8)->nullable()->after('alamat_lengkap');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');

            // Estimasi radius wilayah (kilometer) yang dikelola admin,
            // misal: batas area "dekat" dari kuliner tersebut.
            $table->decimal('radius_kilometer', 6, 2)->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_kilometer']);
        });
    }
};
