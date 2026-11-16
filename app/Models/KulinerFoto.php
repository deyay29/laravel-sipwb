<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KulinerFoto extends Model
{
    protected $table = 'kuliner_foto';

    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_kuliner',
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
     * Kuliner pemilik foto ini.
     */
    public function kuliner(): BelongsTo
    {
        return $this->belongsTo(Kuliner::class, 'id_kuliner', 'id_kuliner');
    }
}
