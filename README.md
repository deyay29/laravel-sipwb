<div align="center">

# Bandung Info Center (SIPWB)

**Portal Layanan Informasi Publik Kota Bandung**

Laravel 12 · Tailwind CSS 4 · MySQL · PHP 8.2+

</div>

---

## Daftar Isi

1. [Apa Itu Project Ini?](#1-apa-itu-project-ini)
2. [Yang Perlu Disiapkan](#2-yang-perlu-disiapkan)
3. [Cara Install Project (Langkah demi Langkah)](#3-cara-install-project-langkah-demi-langkah)
4. [Cara Menjalankan Project](#4-cara-menjalankan-project)
5. [Akun Admin](#5-akun-admin)
6. [Mengenal Struktur Project](#6-mengenal-struktur-project)
7. [Daftar Halaman](#7-daftar-halaman)
8. [Database](#8-database)
9. [Cara Kerja Git](#9-cara-kerja-git)
10. [Perintah yang Sering Digunakan](#10-perintah-yang-sering-digunakan)
11. [Troubleshooting](#11-troubleshooting)
12. [Deployment](#12-deployment)

---

## 1. Apa Itu Project Ini?

Bandung Info Center adalah **website informasi publik** untuk Kota Bandung.
Pengunjung bisa membaca berita, melihat destinasi wisata, menjelajahi kuliner khas,
menyusuri sejarah kota, dan melihat galeri foto — tanpa perlu membuat akun.

Selain itu ada **Panel Admin** yang dipakai pihak yang mengelola isi website
untuk menambah, mengubah, dan menghapus konten.

### Fitur

| Bagian | Fitur |
|---|---|
| 🏠 **Beranda** | Cuaca Kota Bandung, 5 kategori utama, rekomendasi kuliner sesuai suhu |
| 📰 **Berita** | Artikel + audio + tombol reaksi (like / apresiasi / cerah) |
| 🏞️ **Wisata** | Destinasi + lokasi + harga tiket, detail per tempat |
| 🍜 **Kuliner** | Kuliner khas dengan kategori suhu (menghangatkan / menyegarkan), difilter otomatis sesuai cuaca |
| 🏛️ **Sejarah** | Topik sejarah yang dipecah menjadi beberapa bab (storytelling) |
| 🖼️ **Galeri** | Repository foto dengan filter kategori |
| 💰 **Smart Budget** | Contoh paket anggaran perjalanan |
| 📞 **Kontak** | Alamat, telepon, email, media sosial, peta |
| 🔐 **Panel Admin** | CRUD konten + dashboard statistik + cuaca |

---

## 2. Yang Perlu Disiapkan

> Tidak perlu paham IT. Yang penting 5 hal di bawah sudah terpasang di komputer/laptopmu.

| # | Yang Dibutuhkan | Versi Minimal | Cara Cek |
|---|---|---|---|
| 1 | **PHP** | 8.2 | `php -v` |
| 2 | **Composer** | 2.0 | `composer --version` |
| 3 | **Node.js** | 20 | `node -v` |
| 4 | **npm** | 10 | `npm -v` |
| 5 | **MySQL / MariaDB** | 8.0 / 10.4 | `mysql --version` |
| 6 | **Git** | 2.30 | `git --version` |
| 7 | **XAMPP / Laragon / phpMyAdmin** *(opsional)* | — | Alternatif MySQL untuk|Windows |

### Extension PHP yang Wajib Aktif

Project ini butuh extension berikut. Semua sudah aktif secara bawaan di PHP 8.2+:

```
pdo_mysql   mbstring   openssl   tokenizer   xml   ctype   json   fileinfo   curl
```

**Cek semua extension aktif:**

```bash
php -m
```

Kalau ada yang tidak muncul di daftar atas, buka `php.ini` (tekan `Ctrl+F` di XAMPP/Laragon)
dan hapus tanda `;` di depan:

```ini
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=fileinfo
extension=curl
```

Lalu **restart Apache**.

### Kalau Belum Punya Sama Sekali

- **Windows** → pasang [XAMPP](https://www.apachefriends.org/) (berisi PHP + MySQL + Apache sekaligus).
  Setelah itu install [Composer](https://getcomposer.org/Composer-Setup.exe) dan [Node.js](https://nodejs.org/).
- **macOS** → `brew install php composer node mysql` lalu install MySQL lewat [MySQL Installer](https://dev.mysql.com/downloads/mysql/).
- **Linux (Ubuntu)** → ikuti [panduan ini](https://getcomposer.org/doc/00-intro.md#installation-linux).

---

## 3. Cara Install Project (Langkah demi Langkah)

### Langkah 1 — Buka Terminal / Command Prompt

| Sistem Operasi | Cara Buka |
|---|---|
| **Windows** | Tekan `Win + R`, ketik `cmd`, Enter. (atau klik kanan folder → *Open in Terminal*) |
| **macOS** | Buka **Terminal** lewat `Cmd + Space`, ketik `Terminal`, Enter |
| **Linux** | Tekan `Ctrl + Alt + T` |

> Semua perintah di bawah **diketik di dalam jendela ini**.

### Langkah 2 — Pergi ke Folder Project

Tentukan dulu mau ditaruh di mana. Contoh di sini: folder `laravel-sipwb` di dalam Documents.

```bash
cd Documents
```

### Langkah 3 — Clone Project dari GitHub

```bash
git clone https://github.com/deyay29/laravel-sipwb.git
```

Perintah ini **menyalin** seluruh isi project dari internet ke komputermu.
Kalau muncul permintaan username/password, masukkan akun GitHub-mu.
Laravel butuh folder bernama `laravel-sipwb`. Kalau nama project ini nanti diubah,
buka `composer.json` lalu ubah nilai pada key `"name"`.
buka `composer.json` lalu ubah nilai `"name"`.

### Langkah 4 — Masuk ke Folder Project

```bash
cd laravel-sipwb
```

### Langkah 5 — Pasang Semua Library PHP

```bash
composer install
```
Perintah ini mengunduh ratusan library (pustaka kode) yang dibutuhkan project.
Perintah ini mengunduh ratusan library (pustaka kode) yang dibutuhkan project.
**Butuh koneksi internet.** Prosesnya bisa memakan waktu 1–3 menit.

Kalau muncul error `composer` bukan perintah / `command not found`, berarti Composer
belum terpasang. Buka <https://getcomposer.org> lalu ikuti petunjuknya.

### Langkah 6 — Siapkan File Konfigurasi `.env`

`.env` adalah file yang berisi **rahasia dan pengaturan lokal**. File ini
**tidak boleh** ikut ter-*upload* ke GitHub (sudah otomatis diabaikan).

```bash
cp .env.example .env
```

**Windows (Command Prompt):**

```cmd
copy .env.example .env
```

### Langkah 7 — Buat Kunci Pengamanan (APP_KEY)

```bash
php artisan key:generate
```

Kunci ini yang meng-*encrypt* (mengunci) data sensitif seperti password.
**Jalankan perintah ini setiap kali baru membuat file `.env` baru.**

### Langkah 8 — Siapkan Database

#### 8a. Jalankan MySQL

- **XAMPP** → buka **XAMPP Control Panel** → klik **Start** pada MySQL
- **Laragon** → klik **Start All**
- **Manual** → jalankan `sudo service mysql start` (Linux) atau buka MySQL dari Start Menu

#### 8b. Buat Database Bernama `SIPWB`

**Cara 1 — lewat phpMyAdmin** (paling mudah untuk pemula):

1. Buka browser, buka <http://localhost/phpmyadmin>
2. Klik tab **SQL** di menu atas
3. Ketik kode berikut, lalu klik **Jalankan (Go)**

```sql
CREATE DATABASE SIPWB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Database terpisah untuk menjalankan pengujian (opsional)
CREATE DATABASE SIPWB_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Cara 2 — lewat Command Prompt:**

```bash
mysql -u root -p -e "CREATE DATABASE SIPWB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> MySQL di XAMPP biasanya user `root` dengan password **kosong**.
> Tekan Enter saja saat diminta password.

#### 8c. Isi Data Koneksi ke `.env`

Buka file `.env` dengan text editor (VS Code, Notepad++, dll).
Cari bagian `DB_` lalu sesuaikan:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=SIPWB
DB_USERNAME=root
DB_PASSWORD=
```

| Pengaturan | Artinya |
|---|---|
| `DB_HOST` | Alamat server MySQL. `127.0.0.1` = komputer sendiri (paling umum) |
| `DB_PORT` | Port MySQL, biasanya `3306` |
| `DB_DATABASE` | Nama database, harus `SIPWB` (huruf besar) |
| `DB_USERNAME` | User MySQL, biasanya `root` |
| `DB_PASSWORD` | Password MySQL. Kosongkan kalau XAMPP |

> **Catatan cuaca** (opsional, default sudah Bandung):

```ini
WEATHER_REGION=Bandung
WEATHER_LATITUDE=-6.92222
WEATHER_LONGITUDE=107.60694
WEATHER_TIMEZONE=Asia/Jakarta
```

Kalau `WEATHER_REGION` diganti, sistem otomatis mencari koordinat wilayah tersebut
lewat Open-Meteo API.

### Langkah 9 — Buat Tabel Database

```bash
php artisan migrate
```

Perintah ini membuat seluruh tabel yang dibutuhkan, mengikuti file di
`database/migrations/`. Jalankan ulang setiap kali ada migration baru.

### Langkah 10 — Isi Database dengan Data Contoh

```bash
php artisan db:seed
```

Sekarang database sudah terisi data contoh: berita, wisata, kuliner, sejarah,
galeri, smart budget, dan 2 akun user.

### Langkah 11 — Pasang Library Frontend

```bash
npm install
```

> Selalu jalankan `npm install` **setelah** `composer install`.

### Langkah 12 — Build Aset Frontend

```bash
npm run build
```

Mengubah file CSS/JS di `resources/` menjadi file siap pakai di `public/build/`.

**Kalau mau ikut mengembangkan tampilan secara langsung** (live reload), gunakan:

```bash
npm run dev
```

Biarkan jendela terminal itu tetap terbuka, lalu buka terminal baru untuk
menjalankan `php artisan serve`.

---

## 🎉 Instalasi Selesai

Ringkasnya, semua perintah ini cukup dijalankan **satu kali**:

```bash
git clone https://github.com/deyay29/laravel-sipwb.git
cd laravel-sipwb
composer install
cp .env.example .env
php artisan key:generate
# (isi DB_* di .env, lalu buat database SIPWB)
php artisan migrate --seed
npm install
npm run build
```

---

## 4. Cara Menjalankan Project

```bash
php artisan serve
```

Lalu buka browser ke:

```
http://127.0.0.1:8000
```

Untuk memakai **port lain** (misal karena port 8000 sudah dipakai):

```bash
php artisan serve --port=8080
```

Untuk bisa diakses dari HP atau perangkat lain di jaringan yang sama:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

> **Hentikan server:** tekan `Ctrl + C` di terminal tempat server berjalan.
### Perintahombo-developed Berguna
### Perintah Develop Berguna

| Tujuan | Perintah |
|---|---|
| Menghapus cache (bila halaman error) | `php artisan optimize:clear` |
| Menghapus semua cache saat develop | `php artisan optimize:clear` |
| Memeriksa error pada file `.blade.php` | `php artisan views:lint` |
| Melihat daftar route | `php artisan route:list` |
| Melihat isi tabel | `php artisan db:table berita` |
| Mode "pemeliharaan" | `php artisan down` / `php artisan up` |

---

## 5. Akun Admin

Hanya **admin** yang bisa masuk ke panel admin. Pengunjung tidak perlu login.

| Peran | Email | Password |
|---|---|---|
| Admin | `admin@bandung.go.id` | `password` |
| User biasa *(tidak bisa login)* | `user@bandung.go.id` | `password` |

**Cara masuk:**

1. Buka `http://127.0.0.1:8000/login`
2. Masukkan email dan password di atas
3. Otomatis masuk ke `http://127.0.0.1:8000/admin`

> ⚠️ **Ganti password ini sebelum website d-online** supaya tidak bisa ditebak orang lain.

### Membuat Akun Admin Baru

**Cara cepat (paling mudah):**

1. Masuk ke phpMyAdmin → pilih database `SIPWB` → tabel `users`
2. Klik **Insert**
3. Isi kolom seperti ini:

| Kolom | Nilai |
|---|---|
| `name` | Nama Kamu |
| `email` | `namakamu@bandung.go.id` |
| `password` | *[butuh hash — lihat cara di bawah]* |
| `role` | `admin` |
| `created_at` | `2026-01-01 00:00:00` |
| `updated_at` | `2026-01-01 00:00:00` |

**Buat hash password di terminal:**

```bash
php artisan tinker
```

Lalu ketik:

```php
echo password_hash('password-baru-kamu', PASSWORD_BCRYPT);
```

Salin hasilnya ke kolom `password`, lalu klik **Go**.

---

## 6. Mengenal Struktur Project

```
laravel-sipwb/
│
├── app/                          # Seluruh kode aplikasi
│   ├── Console/Commands/         # Perintah kustom (views:lint)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PublicController.php   # Halaman untuk pengunjung
│   │   │   ├── AuthController.php     # Login admin
│   │   │   └── Admin/                 # Halaman panel admin
│   ├── Models/                   # Definisi isi tabel database
│   ├── Models/                   # Definisi isi tabel database
│   │   └── Concerns/HasSlug.php   # Pembuat slug otomatis
│   ├── Services/WeatherService.php      # Ambil cuaca dari Open-Meteo
│   └── Support/Media.php               # Resolver path gambar
│
├── config/                       # Pengaturan (baca dari .env)
│   ├── nav.php                   # Daftar menu navbar (sumber tunggal)
│   └── services.php              # Pengaturan API cuaca
│
├── database/
│   ├── migrations/               # "Cetak biru" pembuatan tabel
│   ├── seeders/                  # Data contoh awal
│   └── factories/                # Data palsu untuk testing
│
├── public/                       # File yang bisa diakses visitor
│   ├── build/                    # Hasil build CSS & JS  (jangan diedit)
│   ├── images/                   # Logo dan foto bawaan
│   └── storage                   # Foto yang diunggah user
│
├── resources/
│   ├── css/app.css               # Token design & konfigurasi Tailwind
│   ├── js/app.js                 # JavaScript (navbar sticky, reaksi berita)
│   └── views/
│       ├── components/
│       │   ├── admin/            # Komponen UI panel admin
│       │   └── public/           # Komponen UI halaman publik
│       ├── layouts/              # "Kerangka" halaman
│       │   └── partials/         # Potongan: navbar, footer, sidebar
│       ├── admin/                # View panel admin (per fitur)
│       └── public/               # View halaman publik (per fitur)
│           ├── auth/             # Halaman login admin
│           ├── berita/           # index.blade.php + show.blade.php
│           ├── wisata/           # index.blade.php + show.blade.php
│           ├── kuliner/           # index.blade.php + show.blade.php
│           ├── sejarah/           # index / show / chapter
│           ├── galeri/           # index.blade.php
│           └── smart-budget/     # index.blade.php
│
├── routes/web.php                # Daftar URL aplikasi
├── storage/                      # Log, cache, file upload (jangan diedit)
├── tests/                        # Pengujian otomatis
│
├── .env                          # Pengaturan + rahasia (JANGAN di-commit)
├── .env.example                  # Contoh .env (boleh di-commit)
├── composer.json                 # Daftar library PHP
├── package.json                  # Daftar library JavaScript
└── phpunit.xml                   # Pengaturan pengujian otomatis
```

### Alur sederhana: Daftar Berita

Supaya lebih paham cara kerjanya:

```
Pengunjung buka /berita
        ↓
routes/web.php  →  menentukan URL ini memanggil siapa
        ↓
app/Http/Controllers/PublicController.php  →  mengambil data dari database
        ↓
app/Models/Berita.php  →  menjelaskan bentuk tabel `berita`
        ↓
resources/views/public/berita/index.blade.php  →  mengubah data jadi HTML
        ↓
layouts/app.blade.php  →  membungkus dengan navbar & footer
```

---

## 7. Daftar Halaman

### Halaman Publik (tanpa login)

| URL | Isi |
|---|---|
| `/` | Beranda |
| `/berita` | Daftar berita |
| `/berita/{slug}` | Detail berita |
| `/wisata` | Daftar wisata |
| `/wisata/{slug}` | Detail Destinasi |
| `/kuliner` | Daftar kuliner + cuaca |
| `/sejarah` | Daftar topik sejarah |
| `/sejarah/{slug}` | Detail topik sejarah |
| `/sejarah/{slug}/bab/{nomor_bab}` | Detail satu bab |
| `/galeri` | Galeri foto |
| `/smart-budget` | Paket anggaran |
| `/kontak` | Kontak & peta |
| `/login` | Login admin (satu-satunya halaman auth) |

### Panel Admin (`/admin/*`)

| URL | Isi |
|---|---|
| `/admin` | Dashboard + cuaca + statistik |
| `/admin/wisata` | Kelola wisata |
| `/admin/kuliner` | Kelola kuliner |
| `/admin/berita` | Kelola berita |
| `/admin/sejarah` | Kelola topik sejarah |
| `/admin/galeri` | Kelola galeri foto |
| `/admin/contact` | Kelola data kontak |

> Semua URL admin otomatis memakai **slug**, bukan angka. Jadi kalau slug berita
> diubah, URL lamanya ikut berubah.

---

## 8. Database

### Daftar Tabel

| Tabel | Isi |
|---|---|
| `users` | Akun (admin & user) |
| `wisata` | Destinasi wisata |
| `kuliner` | Kuliner + kategori suhu + status tayang |
| `berita` | Berita |
| `reaksi_berita` | Jumlah like / apresiasi / cerah per berita |
| `sejarah_topik` | Topik sejarah |
| `sejarah_bab` | Bab-bab dari sebuah topik sejarah |
| `galeri` | Foto galeri |
| `smart_budget_paket` | Paket anggaran |
| `contacts` | Data kontak (satu baris) |

### Relasi Antar Tabel

```
users ──┬─< wiselyat
        ├─< kuliner
        ├─< berita ──1:1── reaksi_berita
        ├─< sejarah_topik ──1:N── sejarah_bab
        ├─< galeri
        └─< smart_budget_paket
```

### Perintah Database yang Sering Dipakai

| Tujuan | Perintah |
|---|---|
| Buat tabel baru | `php artisan migrate` |
| **Hapus & buat ulang** semua tabel | `php artisan migrate:fresh` |
| Isi ulang data contoh | `php artisan migrate:fresh --seed` |
| Lihat status migration | `php artisan migrate:status` |
| Lihat isi tabel | `php artisan db:table berita` |
| Hapus semua tabel (PERINGATAN) | `php artisan db:wipe` |

> ⚠️ **`migrate:fresh` menghapus SEMUA isi database.** Jangan dipakai di server produksi.

### Menambah Kolom Tabel

```bash
# 1. Buat file migration baru
php artisan make:migration tambah_kolom_harga_di_wisata

# 2. Edit file yang baru dibuat di database/migrations/
#    tambahkan di dalam up():
$table->integer('harga')->nullable();

# 3. Jalankan
php artisan migrate
```

> ⚠️ Jangan pernah mengubah file migration yang **sudah pernah dijalankan** di
> server produksi — buat migration baru saja.

---

## 9. Cara Kerja Git

> **Apa itu Git?** Git adalah alat untuk mencatat riwayat perubahan file.
> Analoginya seperti "Fitur Undo" tetapi untuk seluruh proyek, lengkap dengan
> catatan siapa mengubah apa dan kapan.

### Konsep Penting

| Istilah | Arti |
|---|---|
| **Repository / repo** | Folder project yang diamankan Git |
| **Commit** | Satu "potret" keadaan project pada waktu tertentu |
| **Branch / cabang** | Jalur kerja terpisah, misal `fitur-galeri` |
| **Remote** | Salinan project di internet (di sini: GitHub) |
| **Push** | Mengirim commit lokal ke remote |
| **Pull** | Mengambil commit terbaru dari remote |
| **Merge** | Menggabungkan isi dua branch |

---

### Mengambil Project (Clone)

```bash
git clone https://github.com/deyay29/laravel-sipwb.git
cd laravel-sipwb
```

---

### Alur Kerja Harian

```bash
# 1. Selalu ambil perubahan terbaru dulu
git pull

# 2. ... kerjakan perubahan di editor ...

# 3. Lihat apa yang berubah
git status

# 4. Simpan perubahan
git add .

# 5. Tulis catatan perubahan (WAJIB diisi)
git commit -m "Tambah halaman detail berita"

# 6. Kirim ke GitHub
git push
```

---

### `git status` — Melihat Keadaan

```bash
git status
```

| Keluaran | Artinya |
|---|---|
| **Changes not staged for commit** | File diubah tapi belum dimasukkan daftar |
| **Untracked files** | File baru yang belum pernah `git add` |
| **Changes to be committed** | Sudah `git add`, siap di-*commit* |

---

### `git add` — Menandai File untuk Disimpan

```bash
git add .              # semua file yang berubah
git add app/Models/    # hanya folder tertentu
git add README.md      # hanya satu file
git add -p             # pilih baris per baris (untuk yang sudah biasa)
```

---

### `git commit` — Menyimpan Potret Perubahan

```bash
git commit -m "Pesan singkat yang jelas"
```

**Contoh pesan commit yang baik:**

```
Tambah widget cuaca di halaman kuliner
Perbaiki bug gambar tidak muncul di berita
Hapus tombol floating saat hover
Perbarui README.md
```

**Contoh yang buruk (jangan):**

```
update
fix
bentar
.
```

---

### `git push` — Mengirim ke GitHub

```bash
# Kalau branch utama sudah pernah di-*push* sebelumnya:
git push

# Kalau pertama kali / branch baru:
git push -u origin master
```

Kalau muncul error `Permission denied`, berarti GitHub belum tahu siapa kamu.
Daftarkan dulu (sekali saja):

```bash
git config --global user.name "Nama Lengkap Kamu"
git config --global user.email "email@github.com"
```

Lalu pakai **Personal Access Token** (bukan password) saat GitHub meminta.
Buat token di: GitHub → *Settings* → *Developer settings* → *Personal access tokens*
→ *Tokens (classic)* → *Generate new token* → centang `repo` → salin tokennya.

---

### `git pull` — Mengambil Perubahan dari GitHub

```bash
git pull
```

**Kapan wajib dilakukan:**

- Setiap kali mulai mengerjakan
- Setelah ada pull request dari rekan tim
- Setelah teamed-up apa pun

---

### Menjalankan Pekerjaan di Branch Terpisah (Penting)

Branch dipakai agar perubahan kita tidak langsung mencampur dengan kode utama.

```bash
# 1. Ambil perubahan terbaru dulu
git pull

# 2. Buat branch baru
git checkout -b tambah-halaman-galeri

# 3. ... kerjakan perubahan ...

# 4. Simpan
git add .
git commit -m "Tambah halaman galeri"

# 5. Kirim branch ke GitHub
git push -u origin tambah-halaman-galeri

# 6. Pindah ke branch utama
git checkout master

# 7. Gabungkan branch tadi ke branch utama
git merge tambah-halaman-galeri

# 8. Kirim
git push
```

---

### Membatalkan Perubahan

| Tujuan | Perintah |
|---|---|
| Batalkan perubahan 1 file | `git restore nama-file.php` |
| Batalkan semua perubahan | `git restore .` |
| Lihat daftar commit | `git log --oneline` |
| Lihat perubahan di commit | `git show <kode-commit>` |
| Kembalikan ke commit sebelumnya (**berbahaya**) | `git reset --hard <kode-commit>` |
| Simpan perubahan tapi_RELEASE | `git stash` |
| Ambil kembali perubahan yang disimpan | `git stash pop` |

---

### Prinsip Penting Git

1. **Jangan pernah** menjalankan `git add .` tanpa melihat `git status` lebih dulu.
2. **Selalu** `git pull` sebelum mulai kerja.
3. **Selalu** `git status` sebelum `git commit`.
4. **Jangan pernah** commit file `.env` (berisi password database).
5. Buat **branch terpisah** untuk setiap fitur besar.
6. Commit kecil-kecil lebih baik daripada satu commit besar.

---

### File yang Tidak Boleh Di-commit

Sudah otomatis diatur di `.gitignore`:

```
.env          # berisi password
/vendor/      # library PHP
/node_modules/ # library JavaScript
/public/build # hasil build
/storage/     # log & file upload
```

> Kalau terlanjur commit `.env`, segera hapus dari riwayat GitHub dan
> **ganti password database**.

---

## 10. Perintah yang Sering Digunakan

### Project

| Perintah | Arti |
|---|---|
| `php artisan serve` | Jalankan server lokal |
| `php artisan migrate` | Buat tabel database |
| `php artisan db:seed` | Isi data contoh |
| `php artisan optimize:clear` | Hapus semua cache |
| `php artisan views:lint` | Periksa error file `.blade.php` |
| `php artisan tinker` | Playground PHP (menjalankan kode PHP langsung) |

### Testing

```bash
php artisan test
```

Meng-running seluruh pengujian otomatis. Hasil saat ini: **22 test lulus**.

> ⚠️ Pengujian memakai database terpisah bernama `SIPWB_testing` supaya
> **data development-mu tidak ikut terhapus**.

---

### Frontend

| Perintah | Arti |
|---|---|
| `npm install` | Pasang library JavaScript |
| `npm run build` | Bangun file CSS/JS untuk produksi |
| `npm run dev` | Mode pengembangan dengan live reload |

---

### Membersihkan Cache (solusi untuk hampir semua error)

```bash
php artisan optimize:clear
```

Lalu buka ulang `http://127.0.0.1:8000`.

---

## 11. Troubleshooting

### Halaman Tampil 500 Error

```bash
php artisan optimize:clear
php artisan storage:link
```

Kalau masih error, buka `storage/logs/laravel.log` — pesan errornya ada di baris terakhir.

### "SQLSTATE[HY000] [1045] Access denied"

Password atau user MySQL salah.

1. Cek `DB_USERNAME` dan `DB_PASSWORD` di `.env`
2. Coba login manual: `mysql -u root -p`

### "SQLSTATE[HY000] [1049] Unknown database 'SIPWB'"

Database belum dibuat.

```sql
CREATE DATABASE SIPWB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### "Base table or view not found"

Tabel belum dibuat.

```bash
php artisan migrate
```

### Gambar Tidak Tampil

```bash
php artisan storage:link
npm run build
php artisan optimize:clear
```

Kalau masih tidak tampil, cek juga isi kolom gambar di database. Project ini
otomatis menampilkan **ikon** jika file gambarnya memang tidak ada.

### Tampilan Berantakan (CSS Tidak Load)

```bash
npm install
npm run build
php artisan optimize:clear
```

### Halaman Lama Tidak Berubah

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### 404 Not Found

- Cek URL-nya benar (termasuk **huruf besar/kecil** — URL bersifat case-sensitive)
- Cek daftar route: `php artisan route:list`

### Error "Class not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Port 8000 Sudah Dipakai

```bash
php artisan serve --port=8080

```
### Lupa Perubahan-nya dari Mana

```bash
git status
git log --oneline
git diff
```

---

## 12. Deployment

> Ringkasan saja. Untuk deploy ke server sungguhan, disarankan mengikuti
> [Panduan Deployment Laravel](https://laravel.com/docs/12.x/deployment).

### Daftar Periksa Sebelum Upload

```bash
# Pastikan tidak ada file sensitif yang ikut ter-commit
git status

# Pastikan test lulus
php artisan test

# Pastikan tidak ada error di kode
./vendor/bin/pint --test
```

### Checklist Deployment

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` sudah di-*generate* di server
- [ ] Password database di `.env` server sudah diganti
- [ ] Akun admin default **sudah ganti password**
- [ ] `php artisan migrate --force` sudah dijalankan
- [ ] `npm run build` sudah dijalankan
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] `storage/` dan `bootstrap/cache/` writable oleh web server

---

## Contributors

Dibangun untuk PORTAL INFORMASI PUBLIK KOTA BANDUNG.

---

## License

MIT License — bebas digunakan untuk keperluan belajar maupun komersial.

---

<div align="center">

**Bandung Info Center** · Sistem Layanan Informasi Publik Kota Bandung

Dibuat dengan Laravel 12, Tailwind CSS 4, dan MySQL

</div>
