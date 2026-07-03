<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = ['nama', 'slug', 'warna', 'deskripsi'];

    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $k) {
            if (empty($k->slug)) {
                $k->slug = Str::slug($k->nama);
            }
        });
    }
}
