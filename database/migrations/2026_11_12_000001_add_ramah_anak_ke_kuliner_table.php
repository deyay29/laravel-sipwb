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
            // True bila kuliner cocok untuk keluarga & anak-anak (Family & Kids).
            $table->boolean('ramah_anak')->default(false)->after('radius_kilometer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->dropColumn('ramah_anak');
        });
    }
};
