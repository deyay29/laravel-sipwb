<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizWisata extends Model
{
    use HasFactory;

    protected $table = 'quiz_wisata';

    protected $fillable = [
        'id_wisata',
        'pertanyaan',
        'pilihan',
        'jawaban_benar',
        'penjelasan',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'pilihan' => 'array',
            'jawaban_benar' => 'integer',
            'urutan' => 'integer',
        ];
    }

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'id_wisata', 'id_wisata');
    }
}
