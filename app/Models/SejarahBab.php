<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SejarahBab extends Model
{
    use HasFactory;

    protected $table = 'sejarah_bab';

    protected $primaryKey = 'id_bab';

    protected $fillable = [
        'id_sejarah',
        'nomor_bab',
        'judul_bab',
        'isi_bab',
        'gambar',
    ];

    /**
     * Topik sejarah induknya.
     */
    public function sejarah(): BelongsTo
    {
        return $this->belongsTo(Sejarah::class, 'id_sejarah', 'id_sejarah');
    }
}
