<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuliner extends Model
{
    use HasFactory;

    protected $table = 'kuliner';        // Menyesuaikan nama tabel di MySQL
    protected $primaryKey = 'id_kuliner'; // Menyesuaikan primary key

    protected $fillable = [
        'id_admin',
        'nama_kuliner',
        'slug',
        'kategori_suhu',
        'harga',
        'jam_operasional',
        'kontak',
        'alamat_lengkap',
        'deskripsi',
        'foto',
        'status'
    ];
}