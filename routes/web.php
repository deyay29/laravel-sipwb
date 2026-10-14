<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\KulinerController;
use App\Http\Controllers\Admin\SejarahController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('public.kontak');

// Alias lama /profil -> diarahkan ke /kontak.
Route::get('/profil', fn () => redirect()->route('public.kontak'));

Route::get('/wisata', [PublicController::class, 'wisata'])->name('public.wisata');
Route::get('/wisata/{wisata}', [PublicController::class, 'wisataShow'])->name('public.wisata.show');

Route::get('/kuliner', [PublicController::class, 'kuliner'])->name('public.kuliner');
Route::get('/kuliner/{kuliner}', [PublicController::class, 'kulinerShow'])->name('public.kuliner.show');

Route::get('/berita', [PublicController::class, 'berita'])->name('public.berita');
Route::get('/berita/{berita}', [PublicController::class, 'beritaShow'])->name('public.berita.show');
Route::post('/berita/{berita}/reaksi', [PublicController::class, 'beritaReact'])->name('public.berita.react');

Route::get('/sejarah', [PublicController::class, 'sejarah'])->name('public.sejarah');
Route::get('/sejarah/{topik}', [PublicController::class, 'sejarahShow'])->name('public.sejarah.show');
Route::get('/sejarah/{topik}/bab/{nomorBab}', [PublicController::class, 'sejarahBabDetail'])
    ->whereNumber('nomorBab')
    ->name('public.sejarah.bab');

Route::get('/galeri', [PublicController::class, 'galeriIndex'])->name('public.galeri');
Route::get('/smart-budget', [PublicController::class, 'smartBudgetIndex'])->name('public.smart-budget');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Hanya admin yang punya akses login. Portal publik tidak memiliki halaman
| login maupun pendaftaran, sehingga hanya ada satu tampilan autentikasi.
|
*/

// Satu-satunya halaman login di aplikasi ini (khusus admin).
Route::view('/login', 'public.auth.login-admin')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('wisata', WisataController::class)->except(['show'])->parameters(['wisata' => 'wisata']);
    Route::resource('kuliner', KulinerController::class)->except(['show'])->parameters(['kuliner' => 'kuliner']);
    Route::resource('berita', BeritaController::class)->except(['show'])->parameters(['berita' => 'berita']);
    Route::resource('sejarah', SejarahController::class)->except(['show'])->parameters(['sejarah' => 'sejarah']);
    Route::resource('galeri', GaleriController::class)->except(['show'])->parameters(['galeri' => 'galeri']);

    // Profil kontak: satu baris singleton, jadi hanya index + edit + update.
    Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('contact/{contact}/edit', [ContactController::class, 'edit'])->name('contact.edit');
    Route::put('contact/{contact}', [ContactController::class, 'update'])->name('contact.update');
});
