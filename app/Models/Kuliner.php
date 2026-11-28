<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Jarak;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kuliner extends Model
{
    use HasFactory, HasSlug;

    /**
     * Nilai yang valid untuk kolom enum `kategori_suhu`.
     */
    public const SUHU_MENGHANGATKAN = 'Menghangatkan';

    public const SUHU_MENYEGARKAN = 'Menyegarkan';

    public const SUHU_SEGALA_SUASANA = 'Segala Suasana';

    public const STATUS_AKTIF = 'Aktif';

    public const STATUS_NONAKTIF = 'Nonaktif';

    /**
     * Daftar pilihan untuk dropdown form admin.
     *
     * @return array<int, string>
     */
    public static function suhuOptions(): array
    {
        return [self::SUHU_MENGHANGATKAN, self::SUHU_MENYEGARKAN, self::SUHU_SEGALA_SUASANA];
    }

    /**
     * @return array<int, string>
     */
    public static function statusOptions(): array
    {
        return [self::STATUS_AKTIF, self::STATUS_NONAKTIF];
    }

    protected $table = 'kuliner';

    protected $primaryKey = 'id_kuliner';

    protected $fillable = [
        'id_user',
        'nama_kuliner',
        'slug',
        'kategori_suhu',
        'harga',
        'harga_minimum',
        'harga_maksimum',
        'jam_operasional',
        'kontak',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'radius_kilometer',
        'ramah_anak',
        'deskripsi',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'harga_minimum' => 'integer',
            'harga_maksimum' => 'integer',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'radius_kilometer' => 'decimal:2',
            'ramah_anak' => 'boolean',
        ];
    }

    protected $attributes = [
        'status' => self::STATUS_AKTIF,
    ];

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'nama_kuliner';
    }

    /**
     * Admin yang membuat data kuliner ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Foto-foto tambahan yang diunggah lewat panel admin.
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(KulinerFoto::class, 'id_kuliner', 'id_kuliner')
            ->orderBy('urutan')
            ->orderBy('id_foto');
    }

    /**
     * Semua path foto untuk slider: foto sampul lebih dulu, lalu foto album.
     *
     * Kolom `foto` tetap dipakai sebagai sampul (cover) agar kartu di
     * halaman daftar/beranda tidak perlu query tambahan.
     *
     * @return array<int, string>
     */
    public function semuaFoto(): array
    {
        $paths = $this->relationLoaded('fotos')
            ? $this->fotos->pluck('path')->all()
            : $this->fotos()->pluck('path')->all();

        if ($this->foto) {
            array_unshift($paths, $this->foto);
        }

        return array_values(array_unique(array_filter($paths)));
    }

    /**
     * Foto sampul utama untuk kartu publik (beranda, daftar, terkait).
     *
     * Selalu mengambil elemen pertama dari semuaFoto() supaya foto kartu di
     * halaman publik pasti sama dengan foto pertama di halaman show detail.
     */
    public function fotoUtama(): ?string
    {
        return $this->semuaFoto()[0] ?? null;
    }

    /**
     * True bila kuliner sudah punya titik lokasi (latitude + longitude).
     *
     * Tanpa koordinat, kuliner tidak bisa dihitung jarak ke lokasi mana pun,
     * sehingga fitur "terdekat" hanya memakai kuliner yang mengisi titik lokasi.
     */
    public function hasKoordinat(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Jarak dari kuliner ke titik koordinat tertentu, dalam kilometer.
     *
     * Mengembalikan null bila kuliner belum punya koordinat, supaya pemanggil
     * bisa membedakan kuliner yang belum terpetakan dari kuliner yang terpetakan.
     */
    public function distanceFrom(float $latitude, float $longitude): ?float
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        return Jarak::haversine((float) $this->latitude, (float) $this->longitude, $latitude, $longitude);
    }

    /**
     * Hanya kuliner yang tayang di halaman publik.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Harga minimum (Rp) untuk rentang harga, null bila belum diisi admin.
     */
    public function hargaMinimum(): ?int
    {
        return $this->harga_minimum;
    }

    /**
     * Harga maksimum (Rp) untuk rentang harga, null bila belum diisi admin.
     */
    public function hargaMaksimum(): ?int
    {
        return $this->harga_maksimum;
    }

    /**
     * True bila admin menyimpan dua kolom harga sekaligus (rentang harga).
     */
    public function adaHargaRange(): bool
    {
        return $this->harga_minimum !== null && $this->harga_maksimum !== null;
    }

    /**
     * Harga tunggal (Rp) untuk tampilan: nilai dari kolom terbaru, atau
     * fallback ke kolom `harga` lama bila belum diisi kolom baru.
     */
    public function hargaTunggal(): ?int
    {
        return $this->harga_minimum ?? $this->harga_maksimum ?? $this->harga;
    }

    /**
     * Teks tampilan harga: "Rp 10.000 - Rp 50.000" untuk rentang, atau
     * "Rp 10.000" untuk harga tunggal. Null bila tidak ada nilai sama sekali.
     */
    public function hargaTampilan(): ?string
    {
        if ($this->adaHargaRange()) {
            return 'Rp '.number_format($this->harga_minimum, 0, ',', '.')
                .' - Rp '.number_format($this->harga_maksimum, 0, ',', '.');
        }

        $tunggal = $this->hargaTunggal();

        return $tunggal !== null ? 'Rp '.number_format($tunggal, 0, ',', '.') : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
