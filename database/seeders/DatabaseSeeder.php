<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = $this->seedUser('Admin Bandung', 'admin@bandung.go.id', 'admin');
        $this->seedUser('Warga Bandung', 'user@bandung.go.id', 'user');

        Contact::create([
            'address' => 'Jl. Wastukencana No.2, Babakan Ciamis, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40117',
            'email' => 'info@bandung.go.id',
            'phone' => '(022) 4209800',
            'map_embed' => 'https://www.google.com/maps?q=Balai+Kota+Bandung&output=embed',
            'facebook' => 'https://facebook.com/diskominfo.bdg',
            'instagram' => 'https://instagram.com/diskominfobdg',
        ]);

        $this->call([
            WisataSeeder::class,
            KulinerSeeder::class,
            BeritaSeeder::class,
            SejarahSeeder::class,
            GaleriSeeder::class,
            SmartBudgetSeeder::class,
        ], false, compact('admin'));
    }

    private function seedUser(string $name, string $email, string $role): User
    {
        // Password di-hash otomatis oleh cast 'hashed' pada model User.
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'role' => $role,
        ]);
    }
}
