<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartBudgetPaket extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'smart_budget_paket';

    protected $primaryKey = 'id_paket';

    protected $fillable = [
        'id_user',
        'nama_paket',
        'slug',
        'kategori',
        'deskripsi',
        'total_estimasi',
        'foto',
        'rincian_biaya',
    ];

    protected function casts(): array
    {
        return [
            'total_estimasi' => 'integer',
            'rincian_biaya' => 'array',
        ];
    }

    /**
     * Kolom sumber untuk auto-generate slug.
     */
    protected function slugSource(): string
    {
        return 'nama_paket';
    }

    /**
     * Admin yang menyusun paket anggaran ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
