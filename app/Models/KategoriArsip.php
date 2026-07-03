<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriArsip extends Model
{
    protected $table = 'kategori_arsips';

    protected $fillable = ['nama', 'slug', 'warna', 'deskripsi'];

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class, 'kategori_arsip_id');
    }
}
