<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'kategori',
        'pesan',
    ];

    /**
     * Kategori masukan yang tersedia dari halaman publik.
     */
    public const KATEGORI_KRITIK = 'kritik';

    public const KATEGORI_SARAN = 'saran';

    public const KATEGORI_MASUKAN = 'masukan';

    /**
     * Daftar kategori yang valid, dipakai untuk validasi & dropdown form.
     *
     * @var array<int, string>
     */
    public const KATEGORI_OPTIONS = [
        self::KATEGORI_KRITIK,
        self::KATEGORI_SARAN,
        self::KATEGORI_MASUKAN,
    ];

    /**
     * Label kategori yang tampil di sisi admin maupun publik.
     */
    public const KATEGORI_LABELS = [
        self::KATEGORI_KRITIK => 'Kritik',
        self::KATEGORI_SARAN => 'Saran',
        self::KATEGORI_MASUKAN => 'Masukan',
    ];

    /**
     * Label kategori sekaligus nilai enum-nya, misal untuk badge di tabel admin.
     *
     * @return array<string, string>
     */
    public static function kategoriTabel(): array
    {
        return self::KATEGORI_LABELS;
    }

    /**
     * Nama tampil pengirim, atau "Anonim" bila tidak diisi.
     */
    public function pengirimNama(): string
    {
        $nama = trim((string) ($this->name ?? ''));

        return $nama !== '' ? $nama : 'Anonim';
    }
}
