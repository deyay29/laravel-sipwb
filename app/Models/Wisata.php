<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisata';          // Menyesuaikan nama tabel di database
    protected $primaryKey = 'id_wisata';  // Menyesuaikan primary key

    protected $fillable = [
        'id_admin',
        'nama_wisata',
        'slug',
        'kategori',
        'lokasi',
        'deskripsi',
        'harga_tiket',
        'gambar',
        'quiz_tersedia'   // Keunikan fitur Quiz pada wisata
    ];
}