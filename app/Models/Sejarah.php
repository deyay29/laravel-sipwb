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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
