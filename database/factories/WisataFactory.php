<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wisata>
 */
class WisataFactory extends Factory
{
    public function definition(): array
    {
        $nama = fake()->unique()->words(3, true).' '.fake()->randomElement(['Wisata', 'Taman', 'Museum']);

        return [
            'id_user' => User::factory()->admin(),
            'nama_wisata' => $nama,
            'slug' => Str::slug($nama),
            'kategori' => fake()->randomElement(['Alam', 'Museum', 'Budaya', 'Ikon & Sejarah']),
            'lokasi' => fake()->city().', Bandung',
            'deskripsi' => fake()->paragraph(),
            'harga_tiket' => fake()->numberBetween(0, 50000),
            'jam_operasional' => '08.00 - 17.00 WIB',
            'gambar' => 'images/beranda.png',
            'quiz_tersedia' => fake()->boolean(),
        ];
    }
}
