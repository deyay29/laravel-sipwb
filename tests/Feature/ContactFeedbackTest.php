<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_kontak_tampilkan_form_kirim_masukan(): void
    {
        $this->get('/kontak')
            ->assertOk()
            ->assertSee('Kirim Kritik, Saran & Masukan')
            ->assertSee('name="kategori"', false)
            ->assertSee('name="pesan"', false);
    }

    public function test_pengunjung_dapat_mengirim_masukan_sebagai_anonym(): void
    {
        // Form anonim tidak mewajibkan nama & email sama sekali.
        $response = $this->post(route('public.kontak.kirim'), [
            'kategori' => ContactMessage::KATEGORI_KRITIK,
            'pesan' => 'Tempat wisata ini sangat bagus, namun parkirnya selalu penuh.',
        ]);

        $response->assertRedirect(route('public.kontak'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'kategori' => ContactMessage::KATEGORI_KRITIK,
            'pesan' => 'Tempat wisata ini sangat bagus, namun parkirnya selalu penuh.',
        ]);

        // Tidak ada nama / email yang ikut tersimpan karena anonim.
        $message = ContactMessage::query()->where('pesan', 'Tempat wisata ini sangat bagus, namun parkirnya selalu penuh.')->firstOrFail();

        $this->assertNull($message->name);
        $this->assertNull($message->email);
    }

    public function test_kritik_saran_dan_masukan_dapat_dikirim_terpisah(): void
    {
        foreach (ContactMessage::KATEGORI_OPTIONS as $kategori) {
            $this->post(route('public.kontak.kirim'), [
                'kategori' => $kategori,
                'pesan' => 'Isi masukan dari kategori '.$kategori.'.',
            ]);
        }

        $this->assertDatabaseCount('contact_messages', 3);

        $this->assertDatabaseHas('contact_messages', ['kategori' => 'kritik']);
        $this->assertDatabaseHas('contact_messages', ['kategori' => 'saran']);
        $this->assertDatabaseHas('contact_messages', ['kategori' => 'masukan']);
    }

    public function test_pesan_tanpa_kategori_atau_kategori_tidak_valid_ditolak(): void
    {
        $this->post(route('public.kontak.kirim'), [
            'kategori' => '',
            'pesan' => '',
        ])->assertSessionHasErrors(['kategori', 'pesan']);

        $this->post(route('public.kontak.kirim'), [
            'kategori' => 'tidak-ada',
            'pesan' => 'Pesan ini tetap valid panjangnya.',
        ])->assertSessionHasErrors(['kategori']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_admin_dapat_melihat_daftar_pesan_kontak(): void
    {
        ContactMessage::create([
            'kategori' => ContactMessage::KATEGORI_SARAN,
            'pesan' => 'Mohon tambahkan fitur peta pada halaman wisata.',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.contact.messages.index'))
            ->assertOk()
            ->assertSee('Mohon tambahkan fitur peta pada halaman wisata.')
            ->assertSee('Saran');
    }

    public function test_admin_dapat_menghapus_pesan_kontak(): void
    {
        $message = ContactMessage::create([
            'kategori' => ContactMessage::KATEGORI_MASUKAN,
            'pesan' => 'Sarannya sungguh masuk akal.',
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.contact.messages.destroy', $message))
            ->assertRedirect(route('admin.contact.messages.index'));

        $this->assertDatabaseMissing('contact_messages', [
            'id' => $message->id,
        ]);
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }
}
