<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReaksiBerita extends Model
{
    protected $table = 'reaksi_berita';
    protected $primaryKey = 'id_reaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_berita',
        'like_count',
        'applause_count',
        'insightful_count'
    ];

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }
}
