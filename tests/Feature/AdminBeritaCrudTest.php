<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBeritaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_berita_dengan_gambar_dan_audio(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.berita.store'), [
            'judul' => 'Berita dengan Upload Gambar',
            'kategori' => 'Wisata',
            'isi' => 'Isi berita lengkap dengan gambar sampul dan audio.',
            'gambar' => UploadedFile::fake()->image('sampul.jpg', 1200, 800),
            'audio' => UploadedFile::fake()->create('podcast.mp3', 500, 'audio/mpeg'),
        ]);

        $response->assertRedirect(route('admin.berita.index'));

        $berita = Berita::where('judul', 'Berita dengan Upload Gambar')->firstOrFail();

        $this->assertNotNull($berita->gambar);
        $this->assertNotNull($berita->audio_url);
        Storage::disk('public')->assertExists($berita->gambar);
        Storage::disk('public')->assertExists($berita->audio_url);
    }

    public function test_admin_bisa_mengganti_gambar_dan_menghapus_audio(): void
    {
        Storage::fake('public');

        $gambarLama = UploadedFile::fake()->image('lama.jpg')->store('berita', 'public');
        $audioLama = UploadedFile::fake()->create('lama.mp3', 100, 'audio/mpeg')->store('berita/audio', 'public');

        $berita = Berita::factory()->create([
            'gambar' => $gambarLama,
            'audio_url' => $audioLama,
        ]);

        $response = $this->actingAs($this->admin())->put(route('admin.berita.update', $berita), [
            'judul' => $berita->judul,
            'kategori' => $berita->kategori,
            'isi' => $berita->isi,
            'tanggal' => $berita->tanggal?->format('Y-m-d\TH:i'),
            'gambar' => UploadedFile::fake()->image('baru.png', 800, 600),
            'hapus_audio' => '1',
        ]);

        $response->assertRedirect(route('admin.berita.index'));

        $berita->refresh();

        $this->assertNotSame($gambarLama, $berita->gambar);
        $this->assertNull($berita->audio_url);
        Storage::disk('public')->assertMissing($gambarLama);
        Storage::disk('public')->assertMissing($audioLama);
        Storage::disk('public')->assertExists($berita->gambar);
    }

    public function test_admin_bisa_menghapus_berita_beserta_file_uploadnya(): void
    {
        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('hapus.jpg')->store('berita', 'public');
        $audio = UploadedFile::fake()->create('hapus.mp3', 100, 'audio/mpeg')->store('berita/audio', 'public');

        $berita = Berita::factory()->create([
            'gambar' => $gambar,
            'audio_url' => $audio,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.berita.destroy', $berita))
            ->assertRedirect(route('admin.berita.index'));

        $this->assertDatabaseMissing('berita', ['id_berita' => $berita->id_berita]);
        Storage::disk('public')->assertMissing($gambar);
        Storage::disk('public')->assertMissing($audio);
    }

    public function test_halaman_edit_berita_menampilkan_gambar_dan_audio(): void
    {
        Storage::fake('public');

        $berita = Berita::factory()->create([
            'gambar' => UploadedFile::fake()->image('edit.jpg')->store('berita', 'public'),
            'audio_url' => UploadedFile::fake()->create('edit.mp3', 100, 'audio/mpeg')->store('berita/audio', 'public'),
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.berita.edit', $berita))
            ->assertOk()
            ->assertSee('Gambar Sampul')
            ->assertSee('Audio saat ini')
            ->assertSee('Hapus audio ini');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
