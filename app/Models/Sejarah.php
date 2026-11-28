<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sejarah extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'sejarah_topik';

    protected $primaryKey = 'id_sejarah';

    protected $fillable = [
        'id_user',
        'judul_topik',
        'slug',
        'ringkasan',
        'gambar_utama',
        'video_storytelling',
        'notebook',
        'gambar_flipbook',
    ];

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'judul_topik';
    }

    /**
     * Admin yang membuat topik sejarah ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Bab-bab cerita (storytelling) dari topik ini, urut nomor bab.
     */
    public function babs(): HasMany
    {
        return $this->hasMany(SejarahBab::class, 'id_sejarah', 'id_sejarah')
            ->orderBy('nomor_bab');
    }

    /**
     * Foto sampul utama topik sejarah untuk beranda, daftar, dan detail.
     *
     * Prioritas: gambar_utama -> gambar bab pertama yang tersedia -> gambar_flipbook.
     * Dengan satu sumber yang sama, foto di kartu publik selalu konsisten
     * dengan foto utama pada halaman show detail.
     */
    public function fotoUtama(): ?string
    {
        if ($this->gambar_utama) {
            return $this->gambar_utama;
        }

        $gambarBab = $this->relationLoaded('babs')
            ? $this->babs->first(fn (SejarahBab $bab) => ! empty($bab->gambar))?->gambar
            : $this->babs()->whereNotNull('gambar')->where('gambar', '!=', '')->value('gambar');

        if ($gambarBab) {
            return $gambarBab;
        }

        return $this->gambar_flipbook ?: null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
