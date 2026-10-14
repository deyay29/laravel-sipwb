<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'galeri';

    protected $primaryKey = 'id_galeri';

    protected $fillable = [
        'id_user',
        'judul',
        'slug',
        'kategori_objek',
        'file_gambar',
        'keterangan',
    ];

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'judul';
    }

    /**
     * Admin yang mengunggah foto ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
