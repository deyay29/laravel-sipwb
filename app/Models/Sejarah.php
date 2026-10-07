<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sejarah extends Model
{
    use HasFactory;

    protected $table = 'sejarah_topik';        // Menyesuaikan tabel topik sejarah di database
    protected $primaryKey = 'id_sejarah';     // Menyesuaikan primary key

    protected $fillable = [
        'id_admin',
        'judul_topik',
        'slug',
        'ringkasan',
        'gambar_utama'
    ];

    // Relasi ke bab-bab sejarah (jika ingin dihubungkan)
    public function babs()
    {
        return $this->hasMany(SejarahBab::class, 'id_sejarah', 'id_sejarah');
    }
}