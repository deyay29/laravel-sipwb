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
        Schema::create('kuliner', function (Blueprint $table) {
            $table->id('id_kuliner');
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_kuliner');
            $table->string('slug')->unique();
            $table->enum('kategori_suhu', ['Menghangatkan', 'Menyegarkan'])->nullable()->index();
            $table->unsignedInteger('harga')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('kontak')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuliner');
    }
};
