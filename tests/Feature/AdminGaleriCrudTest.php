<?php

namespace Tests\Feature;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGaleriCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_foto_galeri_lewat_upload(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.galeri.store'), [
            'judul' => 'Gedung Sate di Pagi Hari',
            'kategori_objek' => 'Wisata',
            'keterangan' => 'Tampak depan Gedung Sate saat cuaca cerah.',
            'file_gambar' => UploadedFile::fake()->image('gedung-sate.jpg', 1200, 800),
        ]);

        $response->assertRedirect(route('admin.galeri.index'));

        $galeri = Galeri::where('judul', 'Gedung Sate di Pagi Hari')->firstOrFail();

        $this->assertNotNull($galeri->file_gambar);
        $this->assertStringStartsWith('galeri/', $galeri->file_gambar);
        Storage::disk('public')->assertExists($galeri->file_gambar);
    }

    public function test_foto_galeri_wajib_diunggah_saat_menambah(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.galeri.store'), [
            'judul' => 'Tanpa Foto',
            'kategori_objek' => 'Wisata',
        ])->assertSessionHasErrors('file_gambar');

        $this->assertDatabaseMissing('galeri', ['judul' => 'Tanpa Foto']);
    }

    public function test_admin_bisa_mengganti_foto_galeri(): void
    {
        Storage::fake('public');

        $fotoLama = UploadedFile::fake()->image('lama.jpg')->store('galeri', 'public');

        $galeri = Galeri::factory()->create([
            'file_gambar' => $fotoLama,
        ]);

        $response = $this->actingAs($this->admin())->put(route('admin.galeri.update', $galeri), [
            'judul' => $galeri->judul,
            'kategori_objek' => $galeri->kategori_objek,
            'keterangan' => $galeri->keterangan,
            'file_gambar' => UploadedFile::fake()->image('baru.png', 1000, 700),
        ]);

        $response->assertRedirect(route('admin.galeri.index'));

        $galeri->refresh();

        $this->assertNotSame($fotoLama, $galeri->file_gambar);
        $this->assertStringStartsWith('galeri/', $galeri->file_gambar);
        Storage::disk('public')->assertMissing($fotoLama);
        Storage::disk('public')->assertExists($galeri->file_gambar);
    }

    public function test_edit_tanpa_file_baru_tetap_memakai_foto_lama(): void
    {
        Storage::fake('public');

        $fotoLama = UploadedFile::fake()->image('tetap.jpg')->store('galeri', 'public');

        $galeri = Galeri::factory()->create([
            'file_gambar' => $fotoLama,
        ]);

        $this->actingAs($this->admin())->put(route('admin.galeri.update', $galeri), [
            'judul' => 'Judul Galeri Diperbarui',
            'kategori_objek' => 'Kuliner',
            'keterangan' => 'Keterangan baru.',
        ])->assertRedirect(route('admin.galeri.index'));

        $galeri->refresh();

        $this->assertSame($fotoLama, $galeri->file_gambar);
        $this->assertSame('Judul Galeri Diperbarui', $galeri->judul);
        Storage::disk('public')->assertExists($fotoLama);
    }

    public function test_admin_bisa_menghapus_foto_galeri_beserta_filenya(): void
    {
        Storage::fake('public');

        $foto = UploadedFile::fake()->image('hapus.jpg')->store('galeri', 'public');

        $galeri = Galeri::factory()->create([
            'file_gambar' => $foto,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.galeri.destroy', $galeri))
            ->assertRedirect(route('admin.galeri.index'));

        $this->assertDatabaseMissing('galeri', ['id_galeri' => $galeri->id_galeri]);
        Storage::disk('public')->assertMissing($foto);
    }

    public function test_halaman_edit_galeri_menampilkan_upload_dan_foto_saat_ini(): void
    {
        $galeri = Galeri::factory()->create([
            'file_gambar' => 'images/beranda.png',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.galeri.edit', $galeri))
            ->assertOk()
            ->assertSee('Foto Galeri')
            ->assertSee('Foto saat ini')
            ->assertSee('Pilih file baru untuk mengganti foto ini.');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
