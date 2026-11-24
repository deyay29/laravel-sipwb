<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Kuliner;
use App\Models\Sejarah;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TehAiApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tes berjalan tanpa kunci Gemini supaya jawaban cadangan yang diuji.
        config()->set('services.teh_ai.gemini_key', null);
    }

    public function test_endpoint_context_mengembalikan_data_lengkap_empat_modul(): void
    {
        Kuliner::create([
            'nama_kuliner' => 'Batagor Pak Dedi',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
            'harga_minimum' => 15000,
            'harga_maksimum' => 30000,
            'jam_operasional' => '10.00 - 21.00 WIB',
            'alamat_lengkap' => 'Jl. Dago No. 12, Bandung',
            'deskripsi' => 'Batagor dengan sambal kacang.',
            'ramah_anak' => true,
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        Wisata::factory()->create([
            'nama_wisata' => 'Gedung Sate',
            'kategori' => 'Ikon & Sejarah',
            'harga_tiket' => 0,
            'jam_operasional' => '08.00 - 16.00 WIB',
            'lokasi' => 'Jl. Diponegoro No. 22, Bandung',
            'deskripsi' => 'Bangunan ikonik Kota Bandung.',
        ]);

        Berita::factory()->create([
            'judul' => 'Festival Kuliner Bandung',
            'kategori' => 'Kuliner',
            'tanggal' => now(),
            'isi' => '<p>Festival kuliner digelar di pusat kota Bandung.</p>',
        ]);

        $topik = Sejarah::factory()->create([
            'judul_topik' => 'Konferensi Asia Afrika',
            'ringkasan' => 'Pertemuan negara-negara Asia Afrika tahun 1955.',
        ]);
        $topik->babs()->create([
            'nomor_bab' => 1,
            'judul_bab' => 'Pembukaan',
            'isi_bab' => 'Delegasi dari berbagai negara hadir di Bandung.',
        ]);

        $response = $this->getJson('/api/teh-ai/context');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status', 'query', 'filters', 'counts', 'has_results',
                'data' => [
                    'kuliner' => ['*' => ['nama', 'kategori_suhu', 'ramah_keluarga', 'harga', 'jam_operasional', 'lokasi', 'deskripsi', 'url']],
                    'wisata' => ['*' => ['nama', 'kategori', 'harga_tiket_masuk', 'jam_operasional', 'lokasi', 'deskripsi', 'url']],
                    'berita' => ['*' => ['judul', 'kategori_berita', 'tanggal', 'ringkasan', 'url']],
                    'sejarah' => ['*' => ['judul', 'jumlah_bab', 'tanggal', 'ringkasan', 'bab', 'url']],
                ],
                'suggestions',
            ]);

        $kuliner = $response->json('data.kuliner.0');
        $this->assertSame('Batagor Pak Dedi', $kuliner['nama']);
        $this->assertSame('Menghangatkan', $kuliner['kategori_suhu']);
        $this->assertTrue($kuliner['ramah_keluarga']);
        $this->assertSame('Rp 15.000 - Rp 30.000', $kuliner['harga']);

        $this->assertSame('Gratis', $response->json('data.wisata.0.harga_tiket_masuk'));
        $this->assertSame('Festival Kuliner Bandung', $response->json('data.berita.0.judul'));
        $this->assertSame(1, $response->json('data.sejarah.0.jumlah_bab'));
    }

    public function test_pencarian_hybrid_membaca_harga_legacy_dan_harga_rentang_baru(): void
    {
        $lama = Kuliner::create([
            'nama_kuliner' => 'Kuliner Harga Lama',
            'harga' => 12000,
            'deskripsi' => 'Makanan tradisional.',
            'status' => Kuliner::STATUS_AKTIF,
        ]);
        $lama->created_at = now()->subYears(3);
        $lama->save();

        $baru = Kuliner::create([
            'nama_kuliner' => 'Kuliner Harga Baru',
            'harga_minimum' => 20000,
            'harga_maksimum' => 45000,
            'deskripsi' => 'Makanan modern.',
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $this->getJson('/api/teh-ai/context?q=12000')
            ->assertOk()
            ->assertJsonPath('data.kuliner.0.nama', 'Kuliner Harga Lama');

        $this->getJson('/api/teh-ai/context?q=45000')
            ->assertOk()
            ->assertJsonPath('data.kuliner.0.nama', 'Kuliner Harga Baru');
    }

    public function test_pencarian_ramah_keluarga_memakai_filter_boolean(): void
    {
        $ramah = Kuliner::factory()->create([
            'nama_kuliner' => 'Warung Ramah Anak',
            'deskripsi' => 'Tempat makan bersama keluarga.',
            'ramah_anak' => true,
        ]);
        $tidakRamah = Kuliner::factory()->create([
            'nama_kuliner' => 'Kafe Tenang',
            'deskripsi' => 'Suasana tenang untuk bekerja.',
            'ramah_anak' => false,
        ]);

        $response = $this->getJson('/api/teh-ai/context?q=ramah%20keluarga');

        $response->assertOk()->assertJsonPath('filters.ramah_keluarga', true);
        $this->assertContains(
            'Warung Ramah Anak',
            array_column($response->json('data.kuliner'), 'nama')
        );
        $this->assertNotContains(
            'Kafe Tenang',
            array_column($response->json('data.kuliner'), 'nama')
        );
    }

    public function test_data_tidak_ditemukan_menyediakan_saran_topik(): void
    {
        Wisata::factory()->create(['nama_wisata' => 'Taman Kota']);

        $response = $this->getJson('/api/teh-ai/context?q=zzzqqq');

        $response->assertOk()
            ->assertJsonPath('has_results', false)
            ->assertJsonPath('suggestions.0', 'Wisata: Taman Kota');
    }

    public function test_endpoint_chat_menjawab_dari_data_database_dengan_mode_fallback(): void
    {
        Kuliner::create([
            'nama_kuliner' => 'Es Teh Bandung',
            'kategori_suhu' => Kuliner::SUHU_MENYEGARKAN,
            'harga' => 10000,
            'deskripsi' => 'Minuman menyegarkan khas Bandung.',
            'jam_operasional' => '09.00 - 22.00 WIB',
            'alamat_lengkap' => 'Jl. Braga No. 5, Bandung',
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        $response = $this->postJson('/api/teh-ai/chat', ['message' => 'apa saja kuliner menyegarkan?']);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('mode', 'fallback')
            ->assertJsonPath('has_results', true);

        $this->assertStringContainsString('Es Teh Bandung', $response->json('answer'));
        $this->assertStringContainsString('Rp 10.000', $response->json('answer'));
    }

    public function test_endpoint_chat_menolak_pertanyaan_internal_panel_admin(): void
    {
        $response = $this->postJson('/api/teh-ai/chat', [
            'message' => 'Bagaimana cara login ke panel admin dan melihat rute API?',
        ]);

        $response->assertOk()
            ->assertJsonPath('mode', 'guard');

        $this->assertStringContainsString('hanya dapat membantu informasi', $response->json('answer'));
        $this->assertStringNotContainsString('/admin', $response->json('answer'));
    }

    public function test_endpoint_chat_meminta_topik_lain_bila_data_tidak_ada(): void
    {
        Berita::factory()->create(['judul' => 'Berita Bandung Hari Ini']);

        $response = $this->postJson('/api/teh-ai/chat', [
            'message' => 'informasi tentang pesawat luar angkasa di Mars',
        ]);

        $response->assertOk();

        $this->assertStringContainsString('belum menemukan', $response->json('answer'));
        $this->assertStringContainsString('Berita: Berita Bandung Hari Ini', $response->json('answer'));
    }

    public function test_widget_chat_tunggal_berada_di_pojok_kanan_bawah(): void
    {
        $html = $this->get('/')
            ->assertOk()
            ->assertSee('id="teh-ai-panel"', false)
            ->assertSee('id="teh-ai-toggle"', false)
            ->assertSee('api/teh-ai/chat', false)
            ->assertSee('fixed bottom-6 right-6 z-50', false)
            ->assertSee('fixed bottom-24 right-6 z-50', false)
            ->assertSee('h-[500px] max-h-[75vh] w-[380px]', false)
            ->assertSee('shadow-2xl', false)
            ->assertSee('rounded-2xl', false)
            ->assertSee('min-h-0 flex-1 space-y-2.5 overflow-y-auto', false)
            ->assertDontSee('chat-widget.min.js', false)
            ->assertDontSee('asisten.bandung.go.id', false)
            ->getContent();

        $this->assertSame(1, substr_count($html, 'id="teh-ai-toggle"'));
        $this->assertSame(1, substr_count($html, 'id="teh-ai-panel"'));
    }

    public function test_endpoint_topics_mengembalikan_topik_publik(): void
    {
        Sejarah::factory()->create(['judul_topik' => 'Sejarah Gedung Sate']);

        $this->getJson('/api/teh-ai/topics')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['kategori' => 'sejarah'])
            ->assertJsonFragment(['Sejarah: Sejarah Gedung Sate']);
    }

    public function test_query_spesifik_modul_hanya_mengembalikan_modul_tersebut(): void
    {
        Kuliner::factory()->create(['nama_kuliner' => 'Mie Kocok Bandung']);
        Wisata::factory()->create(['nama_wisata' => 'Taman Hutan Raya Juanda']);
        Berita::factory()->create(['judul' => 'Bandung Raih Penghargaan Kota Cerdas']);
        Sejarah::factory()->create(['judul_topik' => 'Sejarah Bandung Lautan Api']);

        // Query berita: hanya modul berita yang aktif
        $resBerita = $this->getJson('/api/teh-ai/context?q=apa%20berita%20terbaru');
        $resBerita->assertOk()
            ->assertJsonPath('counts.berita', 1)
            ->assertJsonPath('counts.wisata', 0)
            ->assertJsonPath('counts.kuliner', 0)
            ->assertJsonPath('counts.sejarah', 0)
            ->assertJsonPath('data.berita.0.judul', 'Bandung Raih Penghargaan Kota Cerdas');

        // Query wisata: hanya modul wisata yang aktif
        $resWisata = $this->getJson('/api/teh-ai/context?q=rekomendasi%20tempat%20wisata');
        $resWisata->assertOk()
            ->assertJsonPath('counts.wisata', 1)
            ->assertJsonPath('counts.berita', 0)
            ->assertJsonPath('counts.kuliner', 0)
            ->assertJsonPath('counts.sejarah', 0)
            ->assertJsonPath('data.wisata.0.nama', 'Taman Hutan Raya Juanda');

        // Query sejarah: hanya modul sejarah yang aktif
        $resSejarah = $this->getJson('/api/teh-ai/context?q=cerita%20sejarah%20bandung');
        $resSejarah->assertOk()
            ->assertJsonPath('counts.sejarah', 1)
            ->assertJsonPath('counts.berita', 0)
            ->assertJsonPath('counts.wisata', 0)
            ->assertJsonPath('counts.kuliner', 0)
            ->assertJsonPath('data.sejarah.0.judul', 'Sejarah Bandung Lautan Api');
    }

    public function test_entri_baru_langsung_muncul_paling_atas_dan_data_lama_tetap_ditemukan(): void
    {
        $lama = Berita::factory()->create([
            'judul' => 'Berita Peluncuran Program Lama',
            'tanggal' => now()->subMonths(6),
        ]);
        $lama->created_at = now()->subMonths(6);
        $lama->save();

        $baru = Berita::factory()->create([
            'judul' => 'Berita Peresmian Flyover Terbaru',
            'tanggal' => now(),
        ]);

        // Cek urutan terbaru (latest)
        $response = $this->getJson('/api/teh-ai/context?q=berita%20terbaru');
        $response->assertOk()
            ->assertJsonPath('data.berita.0.judul', 'Berita Peresmian Flyover Terbaru')
            ->assertJsonPath('data.berita.1.judul', 'Berita Peluncuran Program Lama');

        // Cek data lama tetap ditemukan saat dicari spesifik
        $resLama = $this->getJson('/api/teh-ai/context?q=Program%20Lama');
        $resLama->assertOk()
            ->assertJsonPath('data.berita.0.judul', 'Berita Peluncuran Program Lama');
    }

    public function test_field_lengkap_keempat_modul_sesuai_spesifikasi(): void
    {
        Kuliner::create([
            'nama_kuliner' => 'Surabi Imut',
            'kategori_suhu' => Kuliner::SUHU_MENGHANGATKAN,
            'harga' => 15000,
            'harga_minimum' => 10000,
            'harga_maksimum' => 25000,
            'jam_operasional' => '15.00 - 23.00 WIB',
            'alamat_lengkap' => 'Jl. Setiabudhi No. 189, Bandung',
            'deskripsi' => 'Surabi aneka rasa khas Sunda.',
            'ramah_anak' => true,
            'status' => Kuliner::STATUS_AKTIF,
        ]);

        Wisata::factory()->create([
            'nama_wisata' => 'Kawah Putih',
            'kategori' => 'Alam',
            'harga_tiket' => 28000,
            'jam_operasional' => '07.00 - 17.00 WIB',
            'lokasi' => 'Ciwidey, Bandung Selatan',
            'deskripsi' => 'Danau kawah vulkanik yang menawan.',
        ]);

        Berita::factory()->create([
            'judul' => 'Inovasi Digital Kota Bandung',
            'kategori' => 'Teknologi',
            'tanggal' => now(),
            'isi' => 'Pemerintah merilis portal layanan publik baru.',
        ]);

        $sejarah = Sejarah::factory()->create([
            'judul_topik' => 'Peristiwa Bandung Lautan Api',
            'ringkasan' => 'Peristiwa pembumihangusan Bandung Selatan tahun 1946.',
        ]);
        $sejarah->babs()->create([
            'nomor_bab' => 1,
            'judul_bab' => 'Latar Belakang',
            'isi_bab' => 'Ultimatum tentara sekutu kepada para pejuang.',
        ]);

        $response = $this->getJson('/api/teh-ai/context');
        $response->assertOk();

        // Verifikasi field lengkap Kuliner
        $kuliner = $response->json('data.kuliner.0');
        $this->assertArrayHasKey('nama', $kuliner);
        $this->assertArrayHasKey('kategori_suhu', $kuliner);
        $this->assertArrayHasKey('ramah_keluarga', $kuliner);
        $this->assertArrayHasKey('harga', $kuliner);
        $this->assertArrayHasKey('harga_tunggal', $kuliner);
        $this->assertArrayHasKey('harga_minimum', $kuliner);
        $this->assertArrayHasKey('harga_maksimum', $kuliner);
        $this->assertArrayHasKey('jam_operasional', $kuliner);
        $this->assertArrayHasKey('lokasi', $kuliner);
        $this->assertArrayHasKey('alamat', $kuliner);
        $this->assertArrayHasKey('deskripsi', $kuliner);

        // Verifikasi field lengkap Wisata
        $wisata = $response->json('data.wisata.0');
        $this->assertArrayHasKey('nama', $wisata);
        $this->assertArrayHasKey('kategori', $wisata);
        $this->assertArrayHasKey('harga_tiket', $wisata);
        $this->assertArrayHasKey('harga_tiket_masuk', $wisata);
        $this->assertArrayHasKey('jam_operasional', $wisata);
        $this->assertArrayHasKey('lokasi', $wisata);
        $this->assertArrayHasKey('deskripsi', $wisata);

        // Verifikasi field lengkap Berita
        $berita = $response->json('data.berita.0');
        $this->assertArrayHasKey('judul', $berita);
        $this->assertArrayHasKey('kategori', $berita);
        $this->assertArrayHasKey('tanggal', $berita);
        $this->assertArrayHasKey('ringkasan', $berita);
        $this->assertArrayHasKey('isi', $berita);

        // Verifikasi field lengkap Sejarah
        $sejarahData = $response->json('data.sejarah.0');
        $this->assertArrayHasKey('judul', $sejarahData);
        $this->assertArrayHasKey('jumlah_bab', $sejarahData);
        $this->assertArrayHasKey('tanggal', $sejarahData);
        $this->assertArrayHasKey('ringkasan', $sejarahData);
        $this->assertArrayHasKey('cerita', $sejarahData);
        $this->assertArrayHasKey('bab', $sejarahData);
    }

    public function test_limit_dapat_dinaikkan_hingga_15(): void
    {
        for ($i = 1; $i <= 14; $i++) {
            Wisata::factory()->create(['nama_wisata' => 'Wisata Ke-'.$i]);
        }

        $res = $this->getJson('/api/teh-ai/context?limit=15');
        $res->assertOk();
        $this->assertCount(14, $res->json('data.wisata'));
    }

    public function test_fallback_chat_hanya_menampilkan_modul_yang_ditanyakan(): void
    {
        Berita::factory()->create([
            'judul' => 'Pembukaan Rute Baru Bandros',
            'isi' => 'Bus wisata Bandros menambah koridor baru di pusat kota.',
        ]);
        Wisata::factory()->create([
            'nama_wisata' => 'Alun-Alun Bandung',
        ]);
        Kuliner::factory()->create([
            'nama_kuliner' => 'Lotek Kalipah Apo',
        ]);
        Sejarah::factory()->create([
            'judul_topik' => 'Sejarah Stasiun Hall Bandung',
        ]);

        $res = $this->postJson('/api/teh-ai/chat', [
            'message' => 'apa saja berita terbaru hari ini?',
        ]);

        $res->assertOk()
            ->assertJsonPath('mode', 'fallback')
            ->assertJsonPath('has_results', true);

        $answer = $res->json('answer');
        $this->assertStringContainsString('Pembukaan Rute Baru Bandros', $answer);
        $this->assertStringNotContainsString('Alun-Alun Bandung', $answer);
        $this->assertStringNotContainsString('Lotek Kalipah Apo', $answer);
        $this->assertStringNotContainsString('Stasiun Hall Bandung', $answer);

        // Sumber hanya berisi berita
        $sources = $res->json('sources');
        foreach ($sources as $source) {
            $this->assertSame('berita', $source['kategori']);
        }
    }
}
