<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Satu berita hanya memiliki satu baris reaksi (penjumlahan like /
     * applause / insightful), jadi id_berita dibuat unik.
     */
    public function up(): void
    {
        Schema::create('reaksi_berita', function (Blueprint $table) {
            $table->id('id_reaksi');
            $table->foreignId('id_berita')
                ->constrained('berita', 'id_berita')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('applause_count')->default(0);
            $table->unsignedBigInteger('insightful_count')->default(0);
            $table->timestamps();

            $table->unique('id_berita');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reaksi_berita');
    }
};
