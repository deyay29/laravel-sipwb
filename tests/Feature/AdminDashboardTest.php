<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kuliner;
use App\Models\Sejarah;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_menampilkan_section_terbaru_untuk_semua_fitur(): void
    {
        Kuliner::factory()->create(['nama_kuliner' => 'Kuliner Dashboard']);
        Wisata::factory()->create(['nama_wisata' => 'Wisata Dashboard']);
        Berita::factory()->create(['judul' => 'Berita Dashboard']);
        Sejarah::factory()->create(['judul_topik' => 'Sejarah Dashboard']);
        Galeri::factory()->create(['judul' => 'Galeri Dashboard']);

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeInOrder([
                'Kuliner Terbaru',
                'Wisata Terbaru',
                'Berita Terbaru',
                'Sejarah Terbaru',
                'Galeri Terbaru',
            ])
            ->assertSee('Kuliner Dashboard')
            ->assertSee('Wisata Dashboard')
            ->assertSee('Berita Dashboard')
            ->assertSee('Sejarah Dashboard')
            ->assertSee('Galeri Dashboard');
    }

    public function test_setiap_section_hanya_menampilkan_enam_konten_terbaru(): void
    {
        foreach (range(1, 7) as $nomor) {
            Galeri::factory()->create([
                'judul' => 'Foto Galeri '.$nomor,
                'kategori_objek' => 'Wisata',
            ]);
        }

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Foto Galeri 7')
            ->assertSee('Foto Galeri 2')
            ->assertDontSee('Foto Galeri 1');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
