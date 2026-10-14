<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Kuliner;
use App\Models\Sejarah;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_dasar_dapat_diakses(): void
    {
        $this->get('/')->assertOk();
        $this->get('/berita')->assertOk();
        $this->get('/kuliner')->assertOk();
        $this->get('/wisata')->assertOk();
        $this->get('/sejarah')->assertOk();
        $this->get('/galeri')->assertOk();
        $this->get('/smart-budget')->assertOk();
        $this->get('/kontak')->assertOk();
    }

    public function test_halaman_auth_bersatu_dan_khusus_admin(): void
    {
        // Hanya ada satu halaman login, khusus admin.
        $this->get('/login')->assertOk();

        // Pendaftaran sudah dihapus, /profil diarahkan ke /kontak.
        $this->get('/register')->assertNotFound();
        $this->get('/profil')->assertRedirect(route('public.kontak'));
    }

    public function test_detail_wisata_memakai_slug(): void
    {
        $wisata = Wisata::factory()->create(['nama_wisata' => 'Gedung Sate']);

        $this->get("/wisata/{$wisata->slug}")->assertOk();
        $this->get('/wisata/tidak-ada')->assertNotFound();
    }

    public function test_kuliner_nonaktif_tidak_ditampilkan(): void
    {
        $aktif = Kuliner::factory()->create(['nama_kuliner' => 'Kuliner Aktif']);
        $nonaktif = Kuliner::factory()->nonaktif()->create(['nama_kuliner' => 'Kuliner Nonaktif']);

        $this->get("/kuliner/{$aktif->slug}")->assertOk();
        $this->get("/kuliner/{$nonaktif->slug}")->assertNotFound();
    }

    public function test_filter_suhu_kuliner(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Sate Bakar',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Es Krim',
            'kategori_suhu' => Kuliner::SUHU_MENYEGARKAN,
        ]);

        $this->get('/kuliner?suhu=Menyegarkan')
            ->assertOk()
            ->assertSee('Es Krim')
            ->assertDontSee('Sate Bakar');
    }

    public function test_halaman_bab_sejarah(): void
    {
        $topik = Sejarah::factory()->create(['judul_topik' => 'Konferensi Asia Afrika']);
        $topik->babs()->create([
            'nomor_bab' => 1,
            'judul_bab' => 'Bab Pertama',
            'isi_bab' => 'Isi bab pertama.',
        ]);

        $this->get("/sejarah/{$topik->slug}")->assertOk()->assertSee('Bab Pertama');
        $this->get("/sejarah/{$topik->slug}/bab/1")->assertOk()->assertSee('Isi bab pertama.');
        $this->get("/sejarah/{$topik->slug}/bab/9")->assertNotFound();
    }

    public function test_membuka_berita_menambah_penghitung_dan_reaksi(): void
    {
        $berita = Berita::factory()->create(['visitor_count' => 0]);

        $this->get("/berita/{$berita->slug}")->assertOk();
        $this->assertSame(1, $berita->fresh()->visitor_count);
        $this->assertDatabaseHas('reaksi_berita', ['id_berita' => $berita->id_berita]);
    }
}
