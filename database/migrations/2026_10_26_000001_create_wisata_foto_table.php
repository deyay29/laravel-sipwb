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
        Schema::create('wisata_foto', function (Blueprint $table) {
            $table->id('id_foto');
            $table->foreignId('id_wisata')
                ->constrained('wisata', 'id_wisata')
                ->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['id_wisata', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata_foto');
    }
};
