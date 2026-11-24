<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Autentikasi tunggal untuk admin.
 *
 * Portal publik tidak memiliki login maupun pendaftaran, jadi tidak ada
 * halaman auth yang terduplikasi.
 */
class AuthController extends Controller
{
    public const CAPTCHA_SESSION_KEY = 'login_captcha_code';

    public function showLogin(Request $request): View
    {
        $captcha = $this->generateCaptcha($request);

        return view('public.auth.login-admin', [
            'captchaSvg' => $captcha['svg'],
        ]);
    }

    public function refreshCaptcha(Request $request): JsonResponse
    {
        $captcha = $this->generateCaptcha($request);

        return response()->json([
            'svg' => $captcha['svg'],
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'digits:4'],
        ], [
            'captcha.required' => 'Kode CAPTCHA wajib diisi.',
            'captcha.digits' => 'Kode CAPTCHA harus 4 digit angka.',
        ]);

        $expectedCaptcha = (string) $request->session()->get(self::CAPTCHA_SESSION_KEY, '');
        $request->session()->forget(self::CAPTCHA_SESSION_KEY);

        if ($expectedCaptcha === '' || ! hash_equals($expectedCaptcha, (string) $validated['captcha'])) {
            throw ValidationException::withMessages([
                'captcha' => 'Kode CAPTCHA tidak sesuai. Silakan coba lagi.',
            ]);
        }

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak cocok dengan data kami.',
            ]);
        }

        // Hanya admin yang boleh masuk ke portal ini.
        if (! Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak memiliki akses admin.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * @return array{code: string, svg: string}
     */
    private function generateCaptcha(Request $request): array
    {
        $code = (string) random_int(1000, 9999);
        $request->session()->put(self::CAPTCHA_SESSION_KEY, $code);

        $digits = str_split($code);
        $digitMarkup = '';
        $xPositions = [28, 58, 88, 118];
        $colors = ['#0f172a', '#1e293b', '#0b2545', '#111827'];

        foreach ($digits as $index => $digit) {
            $x = $xPositions[$index];
            $y = random_int(32, 38);
            $rotate = random_int(-12, 12);
            $color = $colors[$index % count($colors)];
            $digitMarkup .= sprintf(
                '<text x="%d" y="%d" fill="%s" font-size="30" font-weight="700" font-family="Arial, Helvetica, sans-serif" text-anchor="middle" transform="rotate(%d %d %d)">%s</text>',
                $x,
                $y,
                $color,
                $rotate,
                $x,
                $y,
                $digit
            );
        }

        $lines = '';
        for ($i = 0; $i < 3; $i++) {
            $x1 = random_int(0, 40);
            $y1 = random_int(5, 45);
            $x2 = random_int(110, 150);
            $y2 = random_int(5, 45);
            $stroke = ['#cbd5e1', '#dbe4ee', '#e2e8f0'][$i];
            $lines .= sprintf(
                '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="%s" stroke-width="1.5" stroke-linecap="round" />',
                $x1,
                $y1,
                $x2,
                $y2,
                $stroke
            );
        }

        $dots = '';
        for ($i = 0; $i < 18; $i++) {
            $cx = random_int(4, 146);
            $cy = random_int(4, 46);
            $fill = ['#cbd5e1', '#d1d5db', '#e5e7eb'][$i % 3];
            $dots .= sprintf('<circle cx="%d" cy="%d" r="1.2" fill="%s" />', $cx, $cy, $fill);
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="150" height="50" viewBox="0 0 150 50" role="img" aria-label="Kode CAPTCHA">'
            .'<rect width="150" height="50" rx="12" fill="#f8fafc"/>'
            .'<rect x="0.5" y="0.5" width="149" height="49" rx="11.5" fill="none" stroke="#e2e8f0"/>'
            .$lines
            .$dots
            .$digitMarkup
            .'</svg>';

        return [
            'code' => $code,
            'svg' => $svg,
        ];
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
