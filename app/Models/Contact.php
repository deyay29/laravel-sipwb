<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'address',
        'email',
        'phone',
        'map_embed',
        'facebook',
        'instagram',
    ];

    /**
     * Halaman kontak publik memakai satu baris singleton.
     */
    public static function instance(): self
    {
        return static::firstOr(fn () => static::create([
            'address' => 'Balai Kota Bandung, Jl. Wastukencana No.2, Bandung',
        ]));
    }
}
