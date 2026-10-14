<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/wisata')->assertRedirect(route('login'));
    }

    public function test_user_biasa_yang_sudah_login_tetap_ditolak(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        // Sudah login, jadi tidak boleh diarahkan ke halaman login lagi
        // (akan menyebabkan loop), tapi tetap ditolak dari panel admin.
        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect(route('home'))
            ->assertSessionHas('error');
    }

    public function test_admin_bisa_masuk_dan_membuka_halaman_kelola(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@bandung.go.id',
        ]);

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        foreach ([
            '/admin', '/admin/wisata', '/admin/kuliner', '/admin/berita',
            '/admin/sejarah', '/admin/galeri', '/admin/contact',
        ] as $url) {
            $this->get($url)->assertOk();
        }

        foreach ([
            '/admin/wisata/create', '/admin/kuliner/create', '/admin/berita/create',
            '/admin/sejarah/create', '/admin/galeri/create',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_akun_bukan_admin_tidak_bisa_login(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_salah_tidak_bisa_login(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password-salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_mengakhiri_sesi(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('logout'))->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
