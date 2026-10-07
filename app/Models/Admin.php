<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';          // Menunjuk ke tabel admin di database
    protected $primaryKey = 'id_admin'; // Primary key tabel admin di database Anda

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}