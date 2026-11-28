<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Galeri;
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

    public function test_halaman_kuliner_tampilkan_kisaran_harga_atau_harga_tunggal(): void
    {
        Kuliner::create([
            'nama_kuliner' => 'Sate Kacang',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
            'harga_minimum' => 10000,
            'harga_maksimum' => 50000,
            'deskripsi' => 'Sate kacang khas dengan bumbu gurih.',
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        // Harga rentang: "Rp 10.000 - Rp 50.000".
        $this->get('/kuliner')
            ->assertOk()
            ->assertSee('Rp 10.000 - Rp 50.000');

        // Harga tunggal: hanya satu nilai yang diisi, jadi tampil sebagai harga.
        Kuliner::create([
            'nama_kuliner' => 'Es Krim Pondok',
            'kategori_suhu' => Kuliner::SUHU_MENYEGARKAN,
            'harga_minimum' => 15000,
            'deskripsi' => 'Es krim dengan topping.',
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $this->get('/kuliner')
            ->assertOk()
            ->assertSee('Rp 15.000');
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

    public function test_filter_kategori_suhu_segala_suasana(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Cafe Santai',
            'kategori_suhu' => Kuliner::SUHU_SEGALA_SUASANA,
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Warung Hangat',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
        ]);

        $this->get('/kuliner?suhu=Segala Suasana')
            ->assertOk()
            ->assertSee('Cafe Santai')
            ->assertDontSee('Warung Hangat');
    }

    public function test_kuliner_dapat_diurutkan_berdasarkan_jarak_terdekat(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Batagor Terdekat',
            'latitude' => -6.9111,
            'longitude' => 107.5996,
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Batagor Terjauh',
            'latitude' => -6.85,
            'longitude' => 107.70,
        ]);

        $this->get('/kuliner?lat=-6.9166&lng=107.6090')
            ->assertOk()
            ->assertSee('Batagor Terdekat')
            ->assertDontSee('Batagor Terjauh');
    }

    public function test_filter_radius_kuliner_hanya_mengikutilah_radius(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Kuliner Dekat',
            'latitude' => -6.915,
            'longitude' => 107.605,
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Kuliner Jauh',
            'latitude' => -6.75,
            'longitude' => 107.85,
        ]);

        $this->get('/kuliner?lat=-6.9166&lng=107.6090&radius=5')
            ->assertOk()
            ->assertSee('Kuliner Dekat')
            ->assertDontSee('Kuliner Jauh');
    }

    public function test_koordinat_tidak_valid_tidak_mengaktifkan_mode_jarak(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Kuliner Biasa',
        ]);

        $this->get('/kuliner?lat=abc&lng=xyz')
            ->assertOk()
            ->assertSee('Kuliner Biasa');
    }

    public function test_filter_family_kids_kuliner(): void
    {
        Kuliner::factory()->create([
            'nama_kuliner' => 'Cafe Keluarga',
            'ramah_anak' => true,
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Cafe Serius',
            'ramah_anak' => false,
        ]);

        $this->get('/kuliner?family=1')
            ->assertOk()
            ->assertSee('Cafe Keluarga')
            ->assertDontSee('Cafe Serius');

        $this->get('/kuliner?family=0')
            ->assertOk()
            ->assertSee('Cafe Serius')
            ->assertDontSee('Cafe Keluarga');
    }

    public function test_katalog_wisata_bisa_difilter_sesuai_kategori_anggaran(): void
    {
        $hemat = Wisata::factory()->create([
            'nama_wisata' => 'Taman Kota Gratis',
            'kategori_budget' => Wisata::BUDGET_HEMAT,
        ]);
        $nyaman = Wisata::factory()->create([
            'nama_wisata' => 'Resort Alam Premium',
            'kategori_budget' => Wisata::BUDGET_NYAMAN,
        ]);

        $this->get('/wisata?budget=hemat')
            ->assertOk()
            ->assertSee('Menampilkan destinasi untuk kategori anggaran')
            ->assertSee(route('public.wisata.show', $hemat), false)
            ->assertDontSee(route('public.wisata.show', $nyaman), false);

        $this->get('/wisata?budget=nyaman')
            ->assertOk()
            ->assertSee(route('public.wisata.show', $nyaman), false)
            ->assertDontSee(route('public.wisata.show', $hemat), false);
    }

    public function test_kategori_anggaran_tidak_valid_tidak_menyembunyikan_destinasi(): void
    {
        $wisata = Wisata::factory()->create([
            'nama_wisata' => 'Taman Kota Gratis',
            'kategori_budget' => Wisata::BUDGET_HEMAT,
        ]);

        $this->get('/wisata?budget=tidak-valid')
            ->assertOk()
            ->assertSee(route('public.wisata.show', $wisata), false);
    }

    public function test_tombol_bagikan_tampil_di_setiap_halaman_detail(): void
    {
        $kuliner = Kuliner::factory()->create();
        $wisata = Wisata::factory()->create();
        $berita = Berita::factory()->create();
        $topik = Sejarah::factory()->create();

        $topik->babs()->create([
            'nomor_bab' => 1,
            'judul_bab' => 'Bab Bagikan',
            'isi_bab' => 'Isi bab.',
        ]);

        $urls = [
            "/kuliner/{$kuliner->slug}",
            "/wisata/{$wisata->slug}",
            "/berita/{$berita->slug}",
            "/sejarah/{$topik->slug}",
            "/sejarah/{$topik->slug}/bab/1",
            '/galeri',
            '/smart-budget',
        ];

        foreach ($urls as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('wa.me', false)
                ->assertSee('Salin tautan');
        }
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

    public function test_detail_sejarah_menampilkan_video_storytelling_dan_flipbook(): void
    {
        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Sejarah dengan Media',
            'video_storytelling' => 'images/beranda.png',
            'notebook' => 'https://flipbook.example.com/kaa-1955',
        ]);

        $this->get("/sejarah/{$topik->slug}")
            ->assertOk()
            ->assertSee('Video Story Telling dan History Flipbook Digital')
            ->assertSee('Video Storytelling')
            ->assertSee('<video', false)
            ->assertSee('History Flip Book Digital')
            ->assertSee('https://flipbook.example.com/kaa-1955', false)
            ->assertSee('rel="noopener noreferrer"', false)
            ->assertSee('target="_blank"', false);

        $this->get('/sejarah')
            ->assertOk()
            ->assertSee('Video')
            ->assertSee('Flipbook');
    }

    public function test_detail_sejarah_menampilkan_gambar_cover_flipbook(): void
    {
        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Sejarah dengan Cover Flipbook',
            'notebook' => 'https://flipbook.example.com/cover',
            'gambar_flipbook' => 'images/beranda.png',
        ]);

        $this->get("/sejarah/{$topik->slug}")
            ->assertOk()
            ->assertSee('Sampul Flipbook Digital')
            ->assertSee('images/beranda.png', false);
    }

    public function test_detail_sejarah_menyembunyikan_media_interaktif_yang_kosong(): void
    {
        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Sejarah Tanpa Media',
            'video_storytelling' => null,
            'notebook' => null,
        ]);

        $this->get("/sejarah/{$topik->slug}")
            ->assertOk()
            ->assertDontSee('Video Story Telling dan History Flipbook Digital')
            ->assertDontSee('<video', false)
            ->assertDontSee('History Flip Book Digital');
    }

    public function test_detail_sejarah_menyembunyikan_flipbook_dengan_tautan_tidak_valid(): void
    {
        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Sejarah Flipbook Tidak Valid',
            'notebook' => 'tautan-tidak-ada',
        ]);

        $this->get("/sejarah/{$topik->slug}")
            ->assertOk()
            ->assertDontSee('History Flip Book Digital')
            ->assertDontSee('Video Story Telling dan History Flipbook Digital');
    }

    public function test_galeri_bisa_diklik_menuju_halaman_detail(): void
    {
        $galeri = Galeri::factory()->create([
            'judul' => 'Gedung Sate di Pagi Hari',
            'kategori_objek' => 'Wisata',
            'keterangan' => 'Tampak depan Gedung Sate saat cuaca cerah.',
        ]);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee(route('public.galeri.show', $galeri), false)
            ->assertSee('Lihat Foto');

        $this->get("/galeri/{$galeri->slug}")
            ->assertOk()
            ->assertSee('Gedung Sate di Pagi Hari')
            ->assertSee('Tampak depan Gedung Sate saat cuaca cerah.')
            ->assertSee('Ukuran Penuh')
            ->assertSee('Salin tautan');
    }

    public function test_galeri_memakai_slug_dan_galeri_terkait_tampil(): void
    {
        $utama = Galeri::factory()->create([
            'judul' => 'Foto Utama',
            'kategori_objek' => 'Kuliner',
        ]);

        $terkait = Galeri::factory()->create([
            'judul' => 'Foto Terkait',
            'kategori_objek' => 'Kuliner',
        ]);

        $this->get("/galeri/{$utama->slug}")
            ->assertOk()
            ->assertSee('Foto Terkait')
            ->assertSee(route('public.galeri.show', $terkait), false);

        $this->get('/galeri/tidak-ada')->assertNotFound();
    }

    public function test_membuka_berita_menambah_penghitung_dan_reaksi(): void
    {
        $berita = Berita::factory()->create(['visitor_count' => 0]);

        $this->get("/berita/{$berita->slug}")->assertOk();
        $this->assertSame(1, $berita->fresh()->visitor_count);
        $this->assertDatabaseHas('reaksi_berita', ['id_berita' => $berita->id_berita]);
    }

    public function test_foto_kuliner_di_publik_konsisten_dengan_show_detail_dan_tidak_campur_galeri(): void
    {
        $kuliner = Kuliner::factory()->create([
            'nama_kuliner' => 'Kopi Djawa Spesial',
            'foto' => null,
        ]);

        $kuliner->fotos()->create([
            'path' => 'images/kopi_djawa.jpg',
            'urutan' => 1,
        ]);

        // Foto galeri kategori Kuliner milik item lain tidak boleh masuk ke detail kuliner ini.
        Galeri::factory()->create([
            'judul' => 'Foto Galeri Lain',
            'kategori_objek' => 'Kuliner',
            'file_gambar' => 'images/batagor_kingslay.jpg',
        ]);

        $this->assertSame('images/kopi_djawa.jpg', $kuliner->fresh()->fotoUtama());

        $this->get('/')
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false);

        $this->get('/kuliner')
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false)
            ->assertDontSee('storage/kuliner/kopi_djawa.jpg', false);

        $this->get("/kuliner/{$kuliner->slug}")
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false)
            ->assertDontSee('images/batagor_kingslay.jpg', false);
    }

    public function test_foto_wisata_di_publik_konsisten_dengan_show_detail_dan_tidak_campur_galeri(): void
    {
        $wisata = Wisata::factory()->create([
            'nama_wisata' => 'Gedung Sate',
            'gambar' => null,
        ]);

        $wisata->fotos()->create([
            'path' => 'images/beranda.png',
            'urutan' => 1,
        ]);

        Galeri::factory()->create([
            'judul' => 'Foto Wisata Lain',
            'kategori_objek' => 'Wisata',
            'file_gambar' => 'images/batagor_kingslay.jpg',
        ]);

        $this->assertSame('images/beranda.png', $wisata->fresh()->fotoUtama());

        $this->get('/')
            ->assertOk()
            ->assertSee('images/beranda.png', false);

        $this->get('/wisata')
            ->assertOk()
            ->assertSee('images/beranda.png', false);

        $this->get("/wisata/{$wisata->slug}")
            ->assertOk()
            ->assertSee('images/beranda.png', false)
            ->assertDontSee('images/batagor_kingslay.jpg', false);
    }

    public function test_foto_sejarah_fallback_ke_gambar_bab_konsisten_di_semua_halaman(): void
    {
        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Sejarah Tanpa Sampul Utama',
            'gambar_utama' => null,
            'gambar_flipbook' => null,
        ]);

        $topik->babs()->create([
            'nomor_bab' => 1,
            'judul_bab' => 'Bab Pembuka',
            'isi_bab' => 'Isi bab pembuka.',
            'gambar' => 'images/kopi_djawa.jpg',
        ]);

        $this->assertSame('images/kopi_djawa.jpg', $topik->fresh()->fotoUtama());

        $this->get('/')
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false);

        $this->get('/sejarah')
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false);

        $this->get("/sejarah/{$topik->slug}")
            ->assertOk()
            ->assertSee('images/kopi_djawa.jpg', false);
    }
}
