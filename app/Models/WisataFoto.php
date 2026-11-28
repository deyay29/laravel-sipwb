<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WisataFoto extends Model
{
    protected $table = 'wisata_foto';

    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_wisata',
        'path',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    /**
     * Wisata pemilik foto ini.
     */
    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'id_wisata', 'id_wisata');
    }
}
