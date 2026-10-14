<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kuliner extends Model
{
    use HasFactory, HasSlug;

    /**
     * Nilai yang valid untuk kolom enum `kategori_suhu`.
     */
    public const SUHU_MENGHANGATKAN = 'Menghangatkan';

    public const SUHU_MENYEGARKAN = 'Menyegarkan';

    public const STATUS_AKTIF = 'Aktif';

    public const STATUS_NONAKTIF = 'Nonaktif';

    /**
     * Daftar pilihan untuk dropdown form admin.
     *
     * @return array<int, string>
     */
    public static function suhuOptions(): array
    {
        return [self::SUHU_MENGHANGATKAN, self::SUHU_MENYEGARKAN];
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
        'jam_operasional',
        'kontak',
        'alamat_lengkap',
        'deskripsi',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
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
     * Hanya kuliner yang tayang di halaman publik.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
