<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Berita extends Model
{
    use HasFactory, HasSlug;

    /**
     * Tipe reaksi yang bisa diklik di halaman detail berita.
     */
    public const REAKSI_TIPE = ['like', 'applause', 'insightful'];

    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'id_user',
        'judul',
        'slug',
        'kategori',
        'isi',
        'gambar',
        'audio_url',
        'visitor_count',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'visitor_count' => 'integer',
            'tanggal' => 'datetime',
        ];
    }

    protected $attributes = [
        'visitor_count' => 0,
    ];

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'judul';
    }

    /**
     * Admin yang menulis berita ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Reaksi pembaca (like / applause / insightful).
     */
    public function reaksi(): HasOne
    {
        return $this->hasOne(ReaksiBerita::class, 'id_berita', 'id_berita');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
