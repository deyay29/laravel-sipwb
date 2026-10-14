<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReaksiBerita extends Model
{
    use HasFactory;

    protected $table = 'reaksi_berita';

    protected $primaryKey = 'id_reaksi';

    protected $fillable = [
        'id_berita',
        'like_count',
        'applause_count',
        'insightful_count',
    ];

    protected $attributes = [
        'like_count' => 0,
        'applause_count' => 0,
        'insightful_count' => 0,
    ];

    protected function casts(): array
    {
        return [
            'like_count' => 'integer',
            'applause_count' => 'integer',
            'insightful_count' => 'integer',
        ];
    }

    /**
     * Berita yang memiliki reaksi ini.
     */
    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }

    /**
     * Tambah satu pada kolom reaksi sesuai tipe yang dipilih.
     */
    public function incrementReaksi(string $tipe): bool
    {
        $kolom = $tipe.'_count';

        if (! in_array($tipe, Berita::REAKSI_TIPE, true)
            || ! in_array($kolom, $this->getFillable(), true)) {
            return false;
        }

        return (bool) $this->increment($kolom);
    }
}
