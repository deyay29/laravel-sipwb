<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';        // Menyesuaikan nama tabel di MySQL
    protected $primaryKey = 'id_berita'; // Menyesuaikan primary key
    public $timestamps = false;

    protected $fillable = [
        'id_admin',
        'judul',
        'slug',
        'kategori',
        'isi',
        'gambar',
        'audio_url',     // Keunikan fitur Audio Berita
        'visitor_count', // Penghitung jumlah pembaca
        'tanggal'
    ];

    // Relasi ke tabel reaksi_berita (Wajib ditambahkan agar PublicController tidak error)
    public function reaksi()
    {
        return $this->hasOne(ReaksiBerita::class, 'id_berita', 'id_berita');
    }
}