<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\Admin\SejarahController;
use App\Http\Controllers\Admin\KulinerController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\ContactController;

// Public Routes (Disesuaikan menggunakan {slug})
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/wisata', [PublicController::class, 'wisata'])->name('public.wisata');
Route::get('/wisata/{slug}', [PublicController::class, 'wisataShow'])->name('public.wisata.show');
Route::get('/sejarah', [PublicController::class, 'sejarah'])->name('public.sejarah');
Route::get('/sejarah/{slug}', [PublicController::class, 'sejarahShow'])->name('public.sejarah.show');
Route::get('/kuliner', [PublicController::class, 'kuliner'])->name('public.kuliner');
Route::get('/kuliner/{slug}', [PublicController::class, 'kulinerShow'])->name('public.kuliner.show');
Route::get('/berita', [PublicController::class, 'berita'])->name('public.berita');
Route::get('/berita/{slug}', [PublicController::class, 'beritaShow'])->name('public.berita.show');
Route::get('/galeri', [PublicController::class, 'galeriIndex'])->name('public.galeri');

// Tambahan Laluan untuk Paparan Login Admin Kustom (Glassmorphism)
Route::get('/login-admin', function () {
    return view('public.login_admin');
})->name('login.admin');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('wisata', WisataController::class);
    Route::resource('sejarah', SejarahController::class);
    Route::resource('kuliner', KulinerController::class);
    Route::resource('berita', BeritaController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('contact', ContactController::class);
});