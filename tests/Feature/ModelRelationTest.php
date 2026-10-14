<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kuliner;
use App\Models\ReaksiBerita;
use App\Models\Sejarah;
use App\Models\SejarahBab;
use App\Models\SmartBudgetPaket;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_terhubung_ke_tabel_yang_benar(): void
    {
        $this->assertSame('users', (new User)->getTable());
        $this->assertSame('wisata', (new Wisata)->getTable());
        $this->assertSame('id_wisata', (new Wisata)->getKeyName());
        $this->assertSame('kuliner', (new Kuliner)->getTable());
        $this->assertSame('berita', (new Berita)->getTable());
        $this->assertSame('reaksi_berita', (new ReaksiBerita)->getTable());
        $this->assertSame('sejarah_topik', (new Sejarah)->getTable());
        $this->assertSame('sejarah_bab', (new SejarahBab)->getTable());
        $this->assertSame('galeri', (new Galeri)->getTable());
        $this->assertSame('smart_budget_paket', (new SmartBudgetPaket)->getTable());
    }

    public function test_slug_dibuat_otomatis_dan_unik(): void
    {
        $pertama = Wisata::factory()->create(['nama_wisata' => 'Gedung Sate']);
        $kedua = Wisata::factory()->create(['nama_wisata' => 'Gedung Sate']);

        $this->assertSame('gedung-sate', $pertama->slug);
        $this->assertSame('gedung-sate-2', $kedua->slug);
    }

    public function test_relasi_user_sebagai_pembuat_konten(): void
    {
        $admin = User::factory()->admin()->create();
        $wisata = Wisata::factory()->create(['id_user' => $admin->id]);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->wisatas->contains($wisata));
        $this->assertTrue($wisata->user->is($admin));
    }

    public function test_relasi_berita_dan_reaksi(): void
    {
        $berita = Berita::factory()->create();

        $reaksi = ReaksiBerita::firstOrCreate(['id_berita' => $berita->id_berita]);

        $this->assertSame(0, $reaksi->like_count);
        $this->assertTrue($berita->reaksi->is($reaksi));
        $this->assertTrue($reaksi->berita->is($berita));

        $reaksi->incrementReaksi('like');
        $this->assertSame(1, $reaksi->fresh()->like_count);

        $this->assertFalse($reaksi->incrementReaksi('tidak_valid'));
    }

    public function test_relasi_sejarah_dan_bab(): void
    {
        $topik = Sejarah::factory()->create();
        $bab = SejarahBab::factory()->create([
            'id_sejarah' => $topik->id_sejarah,
            'nomor_bab' => 2,
        ]);
        SejarahBab::factory()->create([
            'id_sejarah' => $topik->id_sejarah,
            'nomor_bab' => 1,
        ]);

        $this->assertCount(2, $topik->babs);
        $this->assertSame(1, $topik->babs->first()->nomor_bab);
        $this->assertTrue($bab->sejarah->is($topik));
    }

    public function test_scope_aktif_kuliner(): void
    {
        Kuliner::factory()->create(['nama_kuliner' => 'Aktif Sekali']);
        Kuliner::factory()->nonaktif()->create(['nama_kuliner' => 'Nonaktif Sekali']);

        $this->assertSame(1, Kuliner::aktif()->count());
        $this->assertSame(1, Kuliner::where('status', Kuliner::STATUS_NONAKTIF)->count());
    }

    public function test_rincian_biaya_disimpan_sebagai_array(): void
    {
        $paket = SmartBudgetPaket::factory()->create();

        $this->assertIsArray($paket->fresh()->rincian_biaya);
        $this->assertArrayHasKey('item', $paket->fresh()->rincian_biaya[0]);
    }
}
