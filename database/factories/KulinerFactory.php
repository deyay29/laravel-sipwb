<?php

namespace Database\Factories;

use App\Models\Kuliner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Kuliner>
 */
class KulinerFactory extends Factory
{
    public function definition(): array
    {
        $nama = 'Kuliner '.fake()->unique()->words(2, true);

        return [
            'id_user' => User::factory()->admin(),
            'nama_kuliner' => $nama,
            'slug' => Str::slug($nama),
            'kategori_suhu' => fake()->randomElement([Kuliner::SUHU_MENGHANGATKAN, Kuliner::SUHU_MENYEGARKAN]),
            'harga' => fake()->numberBetween(10000, 100000),
            'jam_operasional' => '10.00 - 22.00 WIB',
            'kontak' => fake()->numerify('08##-####-####'),
            'alamat_lengkap' => fake()->streetAddress().', Bandung',
            'deskripsi' => fake()->paragraph(),
            'foto' => 'images/beranda.png',
            'status' => Kuliner::STATUS_AKTIF,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Kuliner::STATUS_NONAKTIF,
        ]);
    }
}
