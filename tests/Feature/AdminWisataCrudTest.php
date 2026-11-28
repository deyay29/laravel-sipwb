<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminWisataCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_wisata_dengan_banyak_foto(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.wisata.store'), [
            'nama_wisata' => 'Gedung Sate',
            'kategori' => 'Sejarah',
            'kategori_budget' => 'sedang',
            'lokasi' => 'Jl. Diponegoro, Bandung',
            'deskripsi' => 'Ikon Kota Bandung dengan arsitektur khas.',
            'harga_tiket' => 5000,
            'jam_operasional' => '08.00 - 16.00 WIB',
            'quiz_tersedia' => '0',
            'fotos' => [
                UploadedFile::fake()->image('gedung-sate-1.jpg'),
                UploadedFile::fake()->image('gedung-sate-2.png'),
                UploadedFile::fake()->image('gedung-sate-3.webp'),
            ],
        ]);

        $response->assertRedirect(route('admin.wisata.index'));

        $wisata = Wisata::where('nama_wisata', 'Gedung Sate')->firstOrFail();

        $this->assertCount(3, $wisata->fotos);
        $this->assertSame($wisata->fotos->first()->path, $wisata->gambar);
        $this->assertSame('sedang', $wisata->kategori_budget);

        foreach ($wisata->fotos as $foto) {
            Storage::disk('public')->assertExists($foto->path);
        }
    }

    public function test_admin_bisa_mengatur_kategori_anggaran_wisata(): void
    {
        $this->actingAs($this->admin())->post(route('admin.wisata.store'), [
            'nama_wisata' => 'Kebun Binatang Bandung',
            'kategori' => 'Edukasi',
            'kategori_budget' => 'nyaman',
            'deskripsi' => 'Destinasi edukasi keluarga di Kota Bandung.',
            'harga_tiket' => 25000,
        ])->assertRedirect(route('admin.wisata.index'));

        $this->assertDatabaseHas('wisata', [
            'nama_wisata' => 'Kebun Binatang Bandung',
            'kategori_budget' => 'nyaman',
        ]);
    }

    public function test_admin_bisa_menghapus_sebagian_dan_menambah_foto_wisata_baru(): void
    {
        Storage::fake('public');

        $wisata = Wisata::factory()->create();

        $fotoLama = $wisata->fotos()->create([
            'path' => UploadedFile::fake()->image('lama.jpg')->store('wisata', 'public'),
            'urutan' => 1,
        ]);

        $wisata->fotos()->create([
            'path' => UploadedFile::fake()->image('tetap.jpg')->store('wisata', 'public'),
            'urutan' => 2,
        ]);

        $wisata->update(['gambar' => $fotoLama->path]);

        $response = $this->actingAs($this->admin())->put(route('admin.wisata.update', $wisata), [
            'nama_wisata' => $wisata->nama_wisata,
            'kategori' => $wisata->kategori,
            'lokasi' => $wisata->lokasi,
            'deskripsi' => $wisata->deskripsi,
            'harga_tiket' => $wisata->harga_tiket,
            'jam_operasional' => $wisata->jam_operasional,
            'hapus_foto' => [$fotoLama->id_foto],
            'fotos' => [
                UploadedFile::fake()->image('baru.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.wisata.index'));

        $wisata->refresh();

        $this->assertCount(2, $wisata->fotos);
        Storage::disk('public')->assertMissing($fotoLama->path);
        $this->assertDatabaseMissing('wisata_foto', ['id_foto' => $fotoLama->id_foto]);
        $this->assertSame($wisata->fotos->first()->path, $wisata->gambar);
    }

    public function test_admin_bisa_menghapus_wisata_beserta_semua_fotonya(): void
    {
        Storage::fake('public');

        $wisata = Wisata::factory()->create();

        $foto = $wisata->fotos()->create([
            'path' => UploadedFile::fake()->image('hapus.jpg')->store('wisata', 'public'),
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.wisata.destroy', $wisata))
            ->assertRedirect(route('admin.wisata.index'));

        $this->assertDatabaseMissing('wisata', ['id_wisata' => $wisata->id_wisata]);
        $this->assertDatabaseMissing('wisata_foto', ['id_wisata' => $wisata->id_wisata]);
        Storage::disk('public')->assertMissing($foto->path);
    }

    public function test_halaman_edit_wisata_menampilkan_foto_tersimpan(): void
    {
        $wisata = Wisata::factory()->create();
        $wisata->fotos()->create([
            'path' => 'images/beranda.png',
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.wisata.edit', $wisata))
            ->assertOk()
            ->assertSee('Foto Wisata')
            ->assertSee('Hapus');
    }

    public function test_admin_bisa_menyimpan_soal_kuis_saat_menambah_wisata(): void
    {
        $this->actingAs($this->admin())->post(route('admin.wisata.store'), [
            'nama_wisata' => 'Gedung Sate',
            'deskripsi' => 'Ikon Kota Bandung.',
            'quiz_tersedia' => '1',
            'quiz' => [
                [
                    'pertanyaan' => 'Gedung Sate mulai dibangun pada tahun?',
                    'pilihan' => ['1920', '1945', '1955', '1901'],
                    'jawaban_benar' => '0',
                    'penjelasan' => 'Pembangunan dimulai 27 Juli 1920.',
                ],
                [
                    'pertanyaan' => 'Arsitek Gedung Sate adalah?',
                    'pilihan' => ['J. Gerber', 'Thomas Karsten', 'A.F. Aalbers', 'H.P. Berlage'],
                    'jawaban_benar' => '1',
                ],
            ],
        ])->assertRedirect(route('admin.wisata.index'));

        $wisata = Wisata::where('nama_wisata', 'Gedung Sate')->firstOrFail();

        $this->assertTrue($wisata->quiz_tersedia);
        $this->assertCount(2, $wisata->quiz);

        $soalPertama = $wisata->quiz->first();
        $this->assertSame('Gedung Sate mulai dibangun pada tahun?', $soalPertama->pertanyaan);
        $this->assertSame(['1920', '1945', '1955', '1901'], $soalPertama->pilihan);
        $this->assertSame(0, $soalPertama->jawaban_benar);
        $this->assertSame('Pembangunan dimulai 27 Juli 1920.', $soalPertama->penjelasan);
        $this->assertSame(1, $soalPertama->urutan);

        $soalKedua = $wisata->quiz->last();
        $this->assertSame('Arsitek Gedung Sate adalah?', $soalKedua->pertanyaan);
        $this->assertSame(1, $soalKedua->jawaban_benar);
        $this->assertSame(2, $soalKedua->urutan);
        $this->assertNull($soalKedua->penjelasan);
    }

    public function test_admin_bisa_mengganti_soal_kuis_saat_mengedit_wisata(): void
    {
        $wisata = Wisata::factory()->create(['quiz_tersedia' => true]);

        $wisata->quiz()->createMany([
            ['pertanyaan' => 'Soal lama pertama?', 'pilihan' => ['a', 'b', 'c', 'd'], 'jawaban_benar' => 0, 'urutan' => 1],
            ['pertanyaan' => 'Soal lama kedua?', 'pilihan' => ['a', 'b', 'c', 'd'], 'jawaban_benar' => 1, 'urutan' => 2],
        ]);

        $this->actingAs($this->admin())->put(route('admin.wisata.update', $wisata), [
            'nama_wisata' => $wisata->nama_wisata,
            'deskripsi' => $wisata->deskripsi,
            'quiz_tersedia' => '1',
            'quiz' => [
                [
                    'pertanyaan' => 'Soal pengganti?',
                    'pilihan' => ['Satu', 'Dua', 'Tiga', 'Empat'],
                    'jawaban_benar' => '3',
                ],
            ],
        ])->assertRedirect(route('admin.wisata.index'));

        $soalBaru = $wisata->quiz()->get();

        $this->assertCount(1, $soalBaru);
        $this->assertSame('Soal pengganti?', $soalBaru->first()->pertanyaan);
        $this->assertSame(['Satu', 'Dua', 'Tiga', 'Empat'], $soalBaru->first()->pilihan);
        $this->assertSame(3, $soalBaru->first()->jawaban_benar);
        $this->assertSame(1, $soalBaru->first()->urutan);
        $this->assertDatabaseMissing('quiz_wisata', ['pertanyaan' => 'Soal lama pertama?']);
    }

    public function test_menonaktifkan_kuis_tidak_menghapus_soal_tersimpan(): void
    {
        $wisata = Wisata::factory()->create(['quiz_tersedia' => true]);

        $wisata->quiz()->create([
            'pertanyaan' => 'Soal yang tetap tersimpan?',
            'pilihan' => ['a', 'b', 'c', 'd'],
            'jawaban_benar' => 2,
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())->put(route('admin.wisata.update', $wisata), [
            'nama_wisata' => $wisata->nama_wisata,
            'deskripsi' => $wisata->deskripsi,
            'quiz_tersedia' => '0',
        ])->assertRedirect(route('admin.wisata.index'));

        $this->assertFalse($wisata->fresh()->quiz_tersedia);
        $this->assertDatabaseHas('quiz_wisata', ['pertanyaan' => 'Soal yang tetap tersimpan?']);
    }

    public function test_kuis_aktif_tanpa_soal_ditolak(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.wisata.store'), [
                'nama_wisata' => 'Tangkuban Perahu',
                'deskripsi' => 'Gunung berapi aktif di Bandung.',
                'quiz_tersedia' => '1',
            ])
            ->assertSessionHasErrors('quiz');

        $this->assertDatabaseMissing('wisata', ['nama_wisata' => 'Tangkuban Perahu']);
    }

    public function test_soal_kuis_wajib_memiliki_kunci_jawaban(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.wisata.store'), [
                'nama_wisata' => 'Museum Geologi',
                'deskripsi' => 'Museum geologi Bandung.',
                'quiz_tersedia' => '1',
                'quiz' => [
                    [
                        'pertanyaan' => 'Museum Geologi didirikan tahun?',
                        'pilihan' => ['1929', '1930', '1931', '1932'],
                        'jawaban_benar' => '',
                    ],
                ],
            ])
            ->assertSessionHasErrors('quiz.0.jawaban_benar');

        $this->assertDatabaseMissing('wisata', ['nama_wisata' => 'Museum Geologi']);
    }

    public function test_soal_kuis_tetap_terisi_setelah_validasi_gagal(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.wisata.store'), [
                // deskripsi sengaja kosong agar validasi gagal.
                'nama_wisata' => 'Museum Geologi',
                'quiz_tersedia' => '1',
                'quiz' => [
                    [
                        'pertanyaan' => 'Pertanyaan contoh tetap tampil?',
                        'pilihan' => ['Satu', 'Dua', 'Tiga', 'Empat'],
                        'jawaban_benar' => '2',
                    ],
                ],
            ])
            ->assertSessionHasErrors('deskripsi');

        $this->actingAs($this->admin())
            ->get(route('admin.wisata.create'))
            ->assertOk()
            ->assertSee('Pertanyaan contoh tetap tampil?')
            ->assertSee('Empat')
            ->assertSee('Kunci Jawaban');
    }

    public function test_halaman_edit_menampilkan_soal_kuis_tersimpan(): void
    {
        $wisata = Wisata::factory()->create(['quiz_tersedia' => true]);

        $wisata->quiz()->create([
            'pertanyaan' => 'Tahun Gedung Sate dibangun?',
            'pilihan' => ['1920', '1945', '1955', '1901'],
            'jawaban_benar' => 0,
            'urutan' => 1,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.wisata.edit', $wisata))
            ->assertOk()
            ->assertSee('Tahun Gedung Sate dibangun?')
            ->assertSee('1920')
            ->assertSee('Kunci Jawaban');
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
