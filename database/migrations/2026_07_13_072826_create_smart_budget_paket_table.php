<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Paket anggaran untuk fitur "Smart Budget". Rincian biaya disimpan
     * sebagai JSON agar mudah ditambah tanpa mengubah skema.
     */
    public function up(): void
    {
        Schema::create('smart_budget_paket', function (Blueprint $table) {
            $table->id('id_paket');
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_paket');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable()->index();
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('total_estimasi')->default(0);
            $table->string('foto')->nullable();
            $table->json('rincian_biaya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_budget_paket');
    }
};
