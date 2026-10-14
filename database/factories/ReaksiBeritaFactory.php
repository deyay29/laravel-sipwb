<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\ReaksiBerita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReaksiBerita>
 */
class ReaksiBeritaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_berita' => Berita::factory(),
            'like_count' => fake()->numberBetween(0, 500),
            'applause_count' => fake()->numberBetween(0, 200),
            'insightful_count' => fake()->numberBetween(0, 100),
        ];
    }
}
