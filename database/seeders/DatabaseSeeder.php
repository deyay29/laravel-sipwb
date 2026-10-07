<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use App\Models\Wisata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Bandung',
            'email' => 'admin@bandung.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular User
        User::create([
            'name' => 'Warga Bandung',
            'email' => 'user@bandung.go.id',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Contact
        Contact::create([
            'address' => 'Jl. Wastukencana No.2, Babakan Ciamis, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40117',
            'email' => 'info@bandung.go.id',
            'phone' => '(022) 4209800',
            'facebook' => 'https://facebook.com/diskominfo.bdg',
            'instagram' => 'https://instagram.com/diskominfobdg',
        ]);

        // Dummy Wisata
        Wisata::create([
            'title' => 'Gedung Sate',
            'description' => 'Ikon Kota Bandung yang bersejarah, dibangun pada masa kolonial Belanda.',
            'location' => 'Bandung Wetan',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Gedung_Sate_Front_View.jpg/800px-Gedung_Sate_Front_View.jpg'
        ]);
        
        Wisata::create([
            'title' => 'Tangkuban Perahu',
            'description' => 'Gunung api aktif yang memiliki bentuk seperti perahu terbalik.',
            'location' => 'Lembang',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Tangkuban_Perahu_Crater.jpg/800px-Tangkuban_Perahu_Crater.jpg'
        ]);
    }
}
