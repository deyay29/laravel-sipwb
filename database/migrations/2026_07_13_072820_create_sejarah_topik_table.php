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
        Schema::create('sejarah_topik', function (Blueprint $table) {
            $table->id('id_sejarah');
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul_topik');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sejarah_topik');
    }
};
