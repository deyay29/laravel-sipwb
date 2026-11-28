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
        Schema::create('quiz_wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_wisata')->constrained('wisata', 'id_wisata')->cascadeOnDelete();
            $table->string('pertanyaan');
            $table->json('pilihan');
            $table->unsignedTinyInteger('jawaban_benar');
            $table->text('penjelasan')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_wisata');
    }
};
