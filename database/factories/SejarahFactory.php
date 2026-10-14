<?php

namespace Database\Factories;

use App\Models\Sejarah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Sejarah>
 */
class SejarahFactory extends Factory
{
    public function definition(): array
    {
        $judul = fake()->unique()->words(3, true);

        return [
            'id_user' => User::factory()->admin(),
            'judul_topik' => $judul,
            'slug' => Str::slug($judul),
            'ringkasan' => fake()->paragraph(),
            'gambar_utama' => 'images/beranda.png',
        ];
    }
}
