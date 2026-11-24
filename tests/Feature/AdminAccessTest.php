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

        $this->withSession(['login_captcha_code' => '1234'])
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password',
                'captcha' => '1234',
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

    public function test_tombol_tambah_di_halaman_kelola_mengarah_ke_form_create(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['berita', 'kuliner', 'wisata', 'sejarah', 'galeri'] as $module) {
            $this->actingAs($admin)
                ->get("/admin/{$module}")
                ->assertOk()
                ->assertSee(route("admin.{$module}.create"), false);
        }
    }

    public function test_akun_bukan_admin_tidak_bisa_login(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->withSession(['login_captcha_code' => '1234'])
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'password',
                'captcha' => '1234',
            ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_salah_tidak_bisa_login(): void
    {
        $admin = User::factory()->admin()->create();

        $this->withSession(['login_captcha_code' => '1234'])
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password-salah',
                'captcha' => '1234',
            ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_gagal_jika_captcha_salah(): void
    {
        $admin = User::factory()->admin()->create();

        $this->withSession(['login_captcha_code' => '1234'])
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password',
                'captcha' => '9999',
            ])->assertSessionHasErrors('captcha');

        $this->assertGuest();
    }

    public function test_login_gagal_jika_captcha_kosong(): void
    {
        $admin = User::factory()->admin()->create();

        $this->withSession(['login_captcha_code' => '1234'])
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password',
                'captcha' => '',
            ])->assertSessionHasErrors('captcha');

        $this->assertGuest();
    }

    public function test_halaman_login_menampilkan_captcha_dan_refresh(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSee('Kode Keamanan', false)
            ->assertSee('refresh-captcha', false)
            ->assertSee('name="captcha"', false)
            ->assertSee('login\/captcha', false);
    }

    public function test_endpoint_refresh_captcha_mengembalikan_svg_baru(): void
    {
        $this->get(route('login'));

        $response = $this->getJson(route('login.captcha'));

        $response->assertOk()
            ->assertJsonStructure(['svg'])
            ->assertJson(fn ($json) => $json->whereType('svg', 'string'));
    }

    public function test_logout_mengakhiri_sesi(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('logout'))->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
