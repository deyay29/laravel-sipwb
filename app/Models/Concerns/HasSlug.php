<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Isi slug secara otomatis dari sumber slug yang sudah ada, atau dari
     * kolom nama/judul milik model. Dijamin unik dengan sufiks angka.
     */
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $model->slug = $model->generateUniqueSlug();
        });
    }

    /**
     * Kolom yang dipakai sebagai sumber slug.
     */
    protected function slugSource(): string
    {
        return 'slug';
    }

    protected function generateUniqueSlug(): string
    {
        $source = trim((string) $this->getAttribute($this->slugSource()));

        if ($source === '') {
            return (string) Str::uuid();
        }

        $base = Str::slug($source);
        $slug = $base;
        $suffix = 2;

        while (
            static::query()
                ->where($this->getKeyName(), '!=', $this->getKey())
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
