<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function wisatas(): HasMany
    {
        return $this->hasMany(Wisata::class, 'id_user');
    }

    public function kuliners(): HasMany
    {
        return $this->hasMany(Kuliner::class, 'id_user');
    }

    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class, 'id_user');
    }

    public function sejarahs(): HasMany
    {
        return $this->hasMany(Sejarah::class, 'id_user');
    }

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class, 'id_user');
    }
}
