<?php

namespace Tests\Feature;

use App\Models\Sejarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSejarahCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_topik_sejarah_dengan_gambar(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Konferensi Asia Afrika',
            'ringkasan' => 'Peristiwa bersejarah yang mengenalkan Bandung ke dunia.',
            'gambar_utama' => UploadedFile::fake()->image('kaa.jpg', 1200, 800),
        ]);

        $response->assertRedirect(route('admin.sejarah.index'));

        $topik = Sejarah::where('judul_topik', 'Konferensi Asia Afrika')->firstOrFail();

        $this->assertNotNull($topik->gambar_utama);
        Storage::disk('public')->assertExists($topik->gambar_utama);
    }

    public function test_admin_bisa_mengganti_gambar_topik_sejarah(): void
    {
        Storage::fake('public');

        $gambarLama = UploadedFile::fake()->image('lama.jpg')->store('sejarah', 'public');

        $topik = Sejarah::factory()->create([
            'gambar_utama' => $gambarLama,
        ]);

        $response = $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'gambar_utama' => UploadedFile::fake()->image('baru.png', 800, 600),
        ]);

        $response->assertRedirect(route('admin.sejarah.index'));

        $topik->refresh();

        $this->assertNotSame($gambarLama, $topik->gambar_utama);
        Storage::disk('public')->assertMissing($gambarLama);
        Storage::disk('public')->assertExists($topik->gambar_utama);
    }

    public function test_admin_bisa_menghapus_gambar_tanpa_menghapus_topik(): void
    {
        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('hapus.jpg')->store('sejarah', 'public');

        $topik = Sejarah::factory()->create([
            'gambar_utama' => $gambar,
        ]);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'hapus_gambar' => '1',
        ])->assertRedirect(route('admin.sejarah.index'));

        $topik->refresh();

        $this->assertNull($topik->gambar_utama);
        Storage::disk('public')->assertMissing($gambar);
    }

    public function test_admin_bisa_menghapus_topik_sejarah_beserta_gambarnya(): void
    {
        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('topik.jpg')->store('sejarah', 'public');

        $topik = Sejarah::factory()->create([
            'gambar_utama' => $gambar,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.sejarah.destroy', $topik))
            ->assertRedirect(route('admin.sejarah.index'));

        $this->assertDatabaseMissing('sejarah_topik', ['id_sejarah' => $topik->id_sejarah]);
        Storage::disk('public')->assertMissing($gambar);
    }

    public function test_admin_bisa_menambah_video_storytelling_dan_flipbook(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Bandung Tempo Dulu',
            'ringkasan' => 'Cerita perkembangan Kota Bandung.',
            'video_storytelling' => UploadedFile::fake()->create('storytelling.mp4', 2048, 'video/mp4'),
            'notebook' => 'https://flipbook.example.com/bandung-tempo-dulu',
        ]);

        $response->assertRedirect(route('admin.sejarah.index'));

        $topik = Sejarah::where('judul_topik', 'Bandung Tempo Dulu')->firstOrFail();

        $this->assertStringStartsWith('sejarah/video/', $topik->video_storytelling);
        $this->assertSame('https://flipbook.example.com/bandung-tempo-dulu', $topik->notebook);
        Storage::disk('public')->assertExists($topik->video_storytelling);
    }

    public function test_admin_tidak_bisa_menyimpan_tautan_flipbook_dengan_skema_berbahaya(): void
    {
        $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Topik Flipbook Berbahaya',
            'notebook' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('notebook');

        $this->assertDatabaseMissing('sejarah_topik', ['judul_topik' => 'Topik Flipbook Berbahaya']);
    }

    public function test_admin_bisa_mengganti_video_dan_tautan_flipbook(): void
    {
        Storage::fake('public');

        $videoLama = UploadedFile::fake()->create('lama.mp4', 1024, 'video/mp4')
            ->store('sejarah/video', 'public');
        $notebookLama = UploadedFile::fake()->create('lama.pdf', 256, 'application/pdf')
            ->store('sejarah/notebook', 'public');

        $topik = Sejarah::factory()->create([
            'video_storytelling' => $videoLama,
            'notebook' => $notebookLama,
        ]);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'video_storytelling' => UploadedFile::fake()->create('baru.webm', 2048, 'video/webm'),
            'notebook' => 'https://flipbook.example.com/bab-baru',
        ])->assertRedirect(route('admin.sejarah.index'));

        $topik->refresh();

        $this->assertNotSame($videoLama, $topik->video_storytelling);
        $this->assertSame('https://flipbook.example.com/bab-baru', $topik->notebook);
        Storage::disk('public')->assertMissing($videoLama);
        Storage::disk('public')->assertMissing($notebookLama);
        Storage::disk('public')->assertExists($topik->video_storytelling);
    }

    public function test_admin_bisa_menghapus_video_dan_mengosongkan_tautan_flipbook(): void
    {
        Storage::fake('public');

        $video = UploadedFile::fake()->create('hapus.mp4', 1024, 'video/mp4')
            ->store('sejarah/video', 'public');
        $notebookLama = UploadedFile::fake()->create('hapus.pdf', 256, 'application/pdf')
            ->store('sejarah/notebook', 'public');

        $topik = Sejarah::factory()->create([
            'video_storytelling' => $video,
            'notebook' => $notebookLama,
        ]);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'hapus_video' => '1',
            'notebook' => null,
        ])->assertRedirect(route('admin.sejarah.index'));

        $topik->refresh();

        $this->assertNull($topik->video_storytelling);
        $this->assertNull($topik->notebook);
        Storage::disk('public')->assertMissing($video);
        Storage::disk('public')->assertMissing($notebookLama);
    }

    public function test_admin_bisa_menghapus_topik_sejarah_beserta_video_dan_notebook(): void
    {
        Storage::fake('public');

        $video = UploadedFile::fake()->create('topik.mp4', 1024, 'video/mp4')
            ->store('sejarah/video', 'public');
        $notebook = UploadedFile::fake()->create('topik.pdf', 256, 'application/pdf')
            ->store('sejarah/notebook', 'public');

        $topik = Sejarah::factory()->create([
            'video_storytelling' => $video,
            'notebook' => $notebook,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.sejarah.destroy', $topik))
            ->assertRedirect(route('admin.sejarah.index'));

        $this->assertDatabaseMissing('sejarah_topik', ['id_sejarah' => $topik->id_sejarah]);
        Storage::disk('public')->assertMissing($video);
        Storage::disk('public')->assertMissing($notebook);
    }

    public function test_halaman_edit_sejarah_menampilkan_upload_gambar(): void
    {
        $topik = Sejarah::factory()->create([
            'gambar_utama' => 'images/beranda.png',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.sejarah.edit', $topik))
            ->assertOk()
            ->assertSee('Gambar Utama')
            ->assertSee('Hapus gambar ini');
    }

    public function test_halaman_edit_sejarah_menampilkan_video_dan_flipbook_tersimpan(): void
    {
        $topik = Sejarah::factory()->create([
            'video_storytelling' => 'images/beranda.png',
            'notebook' => 'https://flipbook.example.com/kaa-1955',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.sejarah.edit', $topik))
            ->assertOk()
            ->assertSee('Video Storytelling')
            ->assertSee('Flipbook Digital')
            ->assertSee('Buka video')
            ->assertSee('https://flipbook.example.com/kaa-1955')
            ->assertSee('Buka flipbook');
    }

    public function test_admin_bisa_menambah_topik_dengan_beberapa_bab(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Sejarah Bandung Lengkap',
            'ringkasan' => 'Cerita Bandung dalam beberapa bab.',
            'babs_submitted' => '1',
            'babs' => [
                ['nomor_bab' => 1, 'judul_bab' => 'Awal Mula', 'isi_bab' => 'Isi bab pertama.'],
                ['nomor_bab' => 2, 'judul_bab' => 'Masa Kolonial', 'isi_bab' => 'Isi bab kedua.'],
                ['nomor_bab' => 3, 'judul_bab' => 'Kemerdekaan', 'isi_bab' => 'Isi bab ketiga.'],
            ],
        ]);

        $response->assertRedirect(route('admin.sejarah.index'));

        $topik = Sejarah::where('judul_topik', 'Sejarah Bandung Lengkap')->firstOrFail();

        $this->assertSame(3, $topik->babs()->count());
        $this->assertSame(
            ['Awal Mula', 'Masa Kolonial', 'Kemerdekaan'],
            $topik->babs()->pluck('judul_bab')->all()
        );
        $this->assertSame([1, 2, 3], $topik->babs()->pluck('nomor_bab')->all());
    }

    public function test_admin_bisa_mengubah_menambah_dan_menghapus_bab_sekaligus(): void
    {
        $topik = Sejarah::factory()->create();

        $topik->babs()->create(['nomor_bab' => 1, 'judul_bab' => 'Bab Satu', 'isi_bab' => 'Lama satu.']);
        $topik->babs()->create(['nomor_bab' => 2, 'judul_bab' => 'Bab Dua', 'isi_bab' => 'Lama dua.']);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'babs_submitted' => '1',
            'babs' => [
                ['nomor_bab' => 1, 'judul_bab' => 'Bab Satu Revisi', 'isi_bab' => 'Isi baru.'],
                ['nomor_bab' => 3, 'judul_bab' => 'Bab Tiga', 'isi_bab' => 'Bab baru.'],
            ],
        ])->assertRedirect(route('admin.sejarah.index'));

        $judul = $topik->babs()->pluck('judul_bab')->all();

        $this->assertCount(2, $judul);
        $this->assertContains('Bab Satu Revisi', $judul);
        $this->assertContains('Bab Tiga', $judul);
        $this->assertNotContains('Bab Dua', $judul);
    }

    public function test_admin_bisa_menghapus_semua_bab_saat_update(): void
    {
        $topik = Sejarah::factory()->create();
        $topik->babs()->create(['nomor_bab' => 1, 'judul_bab' => 'Bab Satu']);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'babs_submitted' => '1',
        ])->assertRedirect(route('admin.sejarah.index'));

        $this->assertSame(0, $topik->babs()->count());
    }

    public function test_admin_bisa_mengunggah_gambar_bab(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Topik Dengan Gambar Bab',
            'babs_submitted' => '1',
            'babs' => [
                [
                    'nomor_bab' => 1,
                    'judul_bab' => 'Bab Bergambar',
                    'gambar' => UploadedFile::fake()->create('bab.jpg', 100, 'image/jpeg'),
                ],
            ],
        ])->assertRedirect(route('admin.sejarah.index'));

        $bab = Sejarah::where('judul_topik', 'Topik Dengan Gambar Bab')->firstOrFail()->babs()->firstOrFail();

        $this->assertNotNull($bab->gambar);
        $this->assertStringStartsWith('sejarah/bab/', $bab->gambar);
        Storage::disk('public')->assertExists($bab->gambar);
    }

    public function test_admin_tidak_bisa_menyimpan_nomor_bab_duplikat(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Topik Nomor Bab Ganda',
            'babs_submitted' => '1',
            'babs' => [
                ['nomor_bab' => 1, 'judul_bab' => 'Bab Pertama'],
                ['nomor_bab' => 1, 'judul_bab' => 'Bab Kedua'],
            ],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('sejarah_topik', ['judul_topik' => 'Topik Nomor Bab Ganda']);
    }

    public function test_halaman_edit_menampilkan_daftar_bab_tersimpan(): void
    {
        $topik = Sejarah::factory()->create();
        $topik->babs()->create(['nomor_bab' => 1, 'judul_bab' => 'Bab Tersimpan Satu']);
        $topik->babs()->create(['nomor_bab' => 2, 'judul_bab' => 'Bab Tersimpan Dua']);

        $this->actingAs($this->admin())
            ->get(route('admin.sejarah.edit', $topik))
            ->assertOk()
            ->assertSee('Daftar Bab')
            ->assertSee('Bab Tersimpan Satu')
            ->assertSee('Bab Tersimpan Dua');
    }

    public function test_admin_bisa_mengunggah_gambar_cover_flipbook(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.sejarah.store'), [
            'judul_topik' => 'Topik Dengan Cover Flipbook',
            'notebook' => 'https://flipbook.example.com/cover',
            'gambar_flipbook' => UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg'),
        ])->assertRedirect(route('admin.sejarah.index'));

        $topik = Sejarah::where('judul_topik', 'Topik Dengan Cover Flipbook')->firstOrFail();

        $this->assertNotNull($topik->gambar_flipbook);
        $this->assertStringStartsWith('sejarah/flipbook/', $topik->gambar_flipbook);
        Storage::disk('public')->assertExists($topik->gambar_flipbook);
    }

    public function test_admin_bisa_menghapus_gambar_cover_flipbook(): void
    {
        Storage::fake('public');

        $cover = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg')
            ->store('sejarah/flipbook', 'public');

        $topik = Sejarah::factory()->create([
            'notebook' => 'https://flipbook.example.com/cover',
            'gambar_flipbook' => $cover,
        ]);

        $this->actingAs($this->admin())->put(route('admin.sejarah.update', $topik), [
            'judul_topik' => $topik->judul_topik,
            'ringkasan' => $topik->ringkasan,
            'notebook' => $topik->notebook,
            'hapus_gambar_flipbook' => '1',
        ])->assertRedirect(route('admin.sejarah.index'));

        $topik->refresh();

        $this->assertNull($topik->gambar_flipbook);
        Storage::disk('public')->assertMissing($cover);
    }

    public function test_halaman_edit_sejarah_menampilkan_cover_flipbook_tersimpan(): void
    {
        $topik = Sejarah::factory()->create([
            'notebook' => 'https://flipbook.example.com/cover',
            'gambar_flipbook' => 'images/beranda.png',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.sejarah.edit', $topik))
            ->assertOk()
            ->assertSee('Gambar Cover Flipbook Digital')
            ->assertSee('images/beranda.png');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
