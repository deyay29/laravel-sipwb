<?php

namespace Tests\Feature;

use App\Models\Kuliner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKulinerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_kuliner_dengan_banyak_foto(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.kuliner.store'), [
            'nama_kuliner' => 'Batagor Kingsley',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
            'harga' => 25000,
            'jam_operasional' => '08.00 - 20.00 WIB',
            'kontak' => '0812-0000-1111',
            'alamat_lengkap' => 'Jl. Burangrang, Bandung',
            'deskripsi' => 'Batagor khas Bandung dengan bumbu kacang.',
            'status' => Kuliner::STATUS_AKTIF,
            'fotos' => [
                UploadedFile::fake()->image('batagor-1.jpg'),
                UploadedFile::fake()->image('batagor-2.png'),
                UploadedFile::fake()->image('batagor-3.webp'),
            ],
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $kuliner = Kuliner::where('nama_kuliner', 'Batagor Kingsley')->firstOrFail();

        $this->assertCount(3, $kuliner->fotos);
        $this->assertSame($kuliner->fotos->first()->path, $kuliner->foto);

        foreach ($kuliner->fotos as $foto) {
            Storage::disk('public')->assertExists($foto->path);
        }
    }

    public function test_admin_bisa_menyimpan_titik_lokasi_dan_radius_kuliner(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kuliner.store'), [
            'nama_kuliner' => 'Sate Kuring',
            'deskripsi' => 'Sate kudus khas Bandung dengan bumbu kacang.',
            'latitude' => -6.9206,
            'longitude' => 107.6100,
            'radius_kilometer' => 5,
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $kuliner = Kuliner::where('nama_kuliner', 'Sate Kuring')->firstOrFail();

        $this->assertEquals(-6.9206, (float) $kuliner->latitude);
        $this->assertEquals(107.61, (float) $kuliner->longitude);
        $this->assertEquals(5, (float) $kuliner->radius_kilometer);
    }

    public function test_admin_bisa_menyimpan_status_family_kids_kuliner(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kuliner.store'), [
            'nama_kuliner' => 'Cafe Keluarga',
            'deskripsi' => 'Cafe yang ramah anak untuk keluarga.',
            'ramah_anak' => true,
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $this->assertDatabaseHas('kuliner', [
            'nama_kuliner' => 'Cafe Keluarga',
            'ramah_anak' => true,
        ]);
    }

    public function test_admin_bisa_menyimpan_rentang_harga_kuliner(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kuliner.store'), [
            'nama_kuliner' => 'Sate Kacang',
            'deskripsi' => 'Sate kacang khas dengan bumbu gurih.',
            'harga_minimum' => 10000,
            'harga_maksimum' => 50000,
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $this->assertDatabaseHas('kuliner', [
            'nama_kuliner' => 'Sate Kacang',
            'harga_minimum' => 10000,
            'harga_maksimum' => 50000,
        ]);
    }

    public function test_admin_bisa_menyimpan_kategori_suhu_segala_suasana(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kuliner.store'), [
            'nama_kuliner' => 'Cafe Santai',
            'kategori_suhu' => Kuliner::SUHU_SEGALA_SUASANA,
            'deskripsi' => 'Cafe dengan suasana umum untuk semua kalangan.',
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $this->assertDatabaseHas('kuliner', [
            'nama_kuliner' => 'Cafe Santai',
            'kategori_suhu' => Kuliner::SUHU_SEGALA_SUASANA,
        ]);
    }

    public function test_admin_bisa_menghapus_sebagian_dan_menambah_foto_baru(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $kuliner = Kuliner::factory()->create();

        $fotoLama = $kuliner->fotos()->create([
            'path' => UploadedFile::fake()->image('lama.jpg')->store('kuliner', 'public'),
            'urutan' => 1,
        ]);

        $kuliner->fotos()->create([
            'path' => UploadedFile::fake()->image('tetap.jpg')->store('kuliner', 'public'),
            'urutan' => 2,
        ]);

        $kuliner->update(['foto' => $fotoLama->path]);

        $response = $this->actingAs($admin)->put(route('admin.kuliner.update', $kuliner), [
            'nama_kuliner' => $kuliner->nama_kuliner,
            'kategori_suhu' => $kuliner->kategori_suhu,
            'harga' => $kuliner->harga,
            'deskripsi' => $kuliner->deskripsi,
            'status' => $kuliner->status,
            'hapus_foto' => [$fotoLama->id_foto],
            'fotos' => [
                UploadedFile::fake()->image('baru.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.kuliner.index'));

        $kuliner->refresh();

        $this->assertCount(2, $kuliner->fotos);
        Storage::disk('public')->assertMissing($fotoLama->path);
        $this->assertDatabaseMissing('kuliner_foto', ['id_foto' => $fotoLama->id_foto]);
        $this->assertSame($kuliner->fotos->first()->path, $kuliner->foto);
    }

    public function test_admin_bisa_menghapus_kuliner_beserta_semua_fotonya(): void
    {
        Storage::fake('public');

        $kuliner = Kuliner::factory()->create();

        $foto = $kuliner->fotos()->create([
            'path' => UploadedFile::fake()->image('hapus.jpg')->store('kuliner', 'public'),
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.kuliner.destroy', $kuliner))
            ->assertRedirect(route('admin.kuliner.index'));

        $this->assertDatabaseMissing('kuliner', ['id_kuliner' => $kuliner->id_kuliner]);
        $this->assertDatabaseMissing('kuliner_foto', ['id_kuliner' => $kuliner->id_kuliner]);
        Storage::disk('public')->assertMissing($foto->path);
    }

    public function test_halaman_edit_menampilkan_foto_tersimpan(): void
    {
        $kuliner = Kuliner::factory()->create();
        $kuliner->fotos()->create([
            'path' => 'images/beranda.png',
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.kuliner.edit', $kuliner))
            ->assertOk()
            ->assertSee('Foto Kuliner')
            ->assertSee('Hapus');
    }

    public function test_halaman_kelola_kuliner_tampilkan_titik_lokasi_dan_radius(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Batagor Kingsley',
            'latitude' => -6.9206,
            'longitude' => 107.6100,
            'radius_kilometer' => 5,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.kuliner.index'))
            ->assertOk()
            ->assertSee('Batagor Kingsley')
            ->assertSee('Radius sekitar');
    }

    public function test_dashboard_admin_menampilkan_kuliner_terbaru_beserta_fotonya(): void
    {
        $kuliner = Kuliner::factory()->create(['nama_kuliner' => 'Kuliner Dashboard']);
        $kuliner->fotos()->create([
            'path' => 'images/beranda.png',
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Kuliner Terbaru')
            ->assertSee('Kuliner Dashboard');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
