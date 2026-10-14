<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wisata extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'wisata';

    protected $primaryKey = 'id_wisata';

    protected $fillable = [
        'id_user',
        'nama_wisata',
        'slug',
        'kategori',
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
