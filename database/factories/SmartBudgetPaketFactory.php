<?php

namespace Database\Factories;

use App\Models\SmartBudgetPaket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SmartBudgetPaket>
 */
class SmartBudgetPaketFactory extends Factory
{
    public function definition(): array
    {
        $nama = 'Paket '.fake()->unique()->words(2, true);

        $rincian = [
            ['item' => 'Tiket masuk', 'biaya' => fake()->numberBetween(10000, 50000)],
            ['item' => 'Makan siang', 'biaya' => fake()->numberBetween(15000, 60000)],
            ['item' => 'Transportasi', 'biaya' => fake()->numberBetween(10000, 80000)],
        ];

        return [
            'id_user' => User::factory()->admin(),
            'nama_paket' => $nama,
            'slug' => Str::slug($nama),
            'kategori' => fake()->randomElement(['Hemat', 'Keluarga', 'Menengah']),
            'deskripsi' => fake()->sentence(),
            'total_estimasi' => array_sum(array_column($rincian, 'biaya')),
            'foto' => 'images/beranda.png',
            'rincian_biaya' => $rincian,
        ];
    }
}
