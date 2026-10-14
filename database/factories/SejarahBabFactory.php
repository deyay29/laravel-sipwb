<?php

namespace Database\Factories;

use App\Models\Sejarah;
use App\Models\SejarahBab;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SejarahBab>
 */
class SejarahBabFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_sejarah' => Sejarah::factory(),
            'nomor_bab' => fake()->unique()->numberBetween(1, 999),
            'judul_bab' => fake()->sentence(4),
            'isi_bab' => fake()->paragraphs(2, true),
            'gambar' => 'images/beranda.png',
        ];
    }
}
