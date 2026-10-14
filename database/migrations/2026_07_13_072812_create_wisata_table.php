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
        Schema::create('wisata', function (Blueprint $table) {
            $table->id('id_wisata');
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_wisata');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable()->index();
            $table->string('lokasi')->nullable();
            $table->text('deskripsi');
            $table->unsignedInteger('harga_tiket')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('quiz_tersedia')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
