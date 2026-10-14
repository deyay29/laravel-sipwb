<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    public function definition(): array
    {
        $judul = fake()->unique()->sentence(6);

        return [
            'id_user' => User::factory()->admin(),
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'kategori' => fake()->randomElement(['Wisata', 'Kuliner', 'Sejarah', 'Informasi']),
            'isi' => fake()->paragraphs(3, true),
            'gambar' => 'images/beranda.png',
            'audio_url' => null,
            'visitor_count' => fake()->numberBetween(0, 1000),
            'tanggal' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }
}
