<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress().', Bandung',
            'email' => fake()->companyEmail(),
            'phone' => fake()->numerify('(022) #######'),
            'map_embed' => null,
            'facebook' => fake()->url(),
            'instagram' => fake()->url(),
        ];
    }
}
