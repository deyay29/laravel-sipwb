<?php

namespace Database\Factories;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Galeri>
 */
class GaleriFactory extends Factory
{
    public function definition(): array
    {
        $judul = fake()->unique()->words(3, true);

        return [
            'id_user' => User::factory()->admin(),
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'kategori_objek' => fake()->randomElement(['Wisata', 'Kuliner', 'Sejarah']),
            'file_gambar' => 'images/beranda.png',
            'keterangan' => fake()->sentence(),
        ];
    }
}
