<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';        // Menyesuaikan dengan nama tabel galeri di database
    protected $primaryKey = 'id_galeri'; // Menyesuaikan primary key

    protected $fillable = [
        'judul',
        'slug',
        'kategori_objek',
        'file_gambar',
        'keterangan'
    ];
}