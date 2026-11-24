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
        Schema::table('sejarah_topik', function (Blueprint $table) {
            $table->string('video_storytelling')->nullable()->after('gambar_utama');
            $table->string('notebook')->nullable()->after('video_storytelling');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sejarah_topik', function (Blueprint $table) {
            $table->dropColumn(['video_storytelling', 'notebook']);
        });
    }
};
