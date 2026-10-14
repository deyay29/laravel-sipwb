<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Setiap topik sejarah dipecah menjadi beberapa bab bernomor urut
     * untuk fitur storytelling.
     */
    public function up(): void
    {
        Schema::create('sejarah_bab', function (Blueprint $table) {
            $table->id('id_bab');
            $table->foreignId('id_sejarah')
                ->constrained('sejarah_topik', 'id_sejarah')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('nomor_bab');
            $table->string('judul_bab');
            $table->longText('isi_bab')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();

            $table->unique(['id_sejarah', 'nomor_bab']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sejarah_bab');
    }
};
