<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wisata extends Model
{
    use HasFactory, HasSlug;

    /**
     * Kategori anggaran yang dipakai tab Smart Budget. Nilainya dipakai
     * sebagai filter katalog destinasi wisata.
     */
    public const BUDGET_HEMAT = 'hemat';

    public const BUDGET_SEDANG = 'sedang';

    public const BUDGET_NYAMAN = 'nyaman';

    public const BUDGET_TIERS = [
        self::BUDGET_HEMAT => 'Hemat',
        self::BUDGET_SEDANG => 'Sedang',
        self::BUDGET_NYAMAN => 'Nyaman',
    ];

    protected $table = 'wisata';

    protected $primaryKey = 'id_wisata';

    protected $fillable = [
        'id_user',
        'nama_wisata',
        'slug',
        'kategori',
        'kategori_budget',
        'lokasi',
        'deskripsi',
        'harga_tiket',
        'jam_operasional',
        'gambar',
        'quiz_tersedia',
    ];

    protected function casts(): array
    {
        return [
            'harga_tiket' => 'integer',
            'quiz_tersedia' => 'boolean',
        ];
    }

    /**
     * Label kategori anggaran yang bisa dibaca pengunjung.
     */
    public function labelKategoriBudget(): string
    {
        return self::BUDGET_TIERS[$this->kategori_budget] ?? self::BUDGET_TIERS[self::BUDGET_HEMAT];
    }

    /**
     * Filter katalog destinasi berdasarkan kategori anggaran Smart Budget.
     */
    public function scopeKategoriBudget(Builder $query, ?string $kategori): Builder
    {
        if ($kategori === null || ! array_key_exists($kategori, self::BUDGET_TIERS)) {
            return $query;
        }

        return $query->where('kategori_budget', $kategori);
    }

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'nama_wisata';
    }

    /**
     * Admin yang membuat data wisata ini.
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
        return $this->hasMany(WisataFoto::class, 'id_wisata', 'id_wisata')
            ->orderBy('urutan')
            ->orderBy('id_foto');
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(QuizWisata::class, 'id_wisata', 'id_wisata')->orderBy('urutan')->orderBy('id');
    }

    /**
     * Semua path foto untuk slider: foto sampul lebih dulu, lalu foto album.
     *
     * @return array<int, string>
     */
    public function semuaFoto(): array
    {
        $paths = $this->relationLoaded('fotos')
            ? $this->fotos->pluck('path')->all()
            : $this->fotos()->pluck('path')->all();

        if ($this->gambar && ! in_array($this->gambar, $paths, true)) {
            array_unshift($paths, $this->gambar);
        }

        return array_values(array_filter($paths));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
