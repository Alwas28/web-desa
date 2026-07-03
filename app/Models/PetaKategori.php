<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaKategori extends Model
{
    protected $table = 'peta_kategori';

    protected $fillable = ['nama', 'ikon', 'warna'];

    public function lokasi()
    {
        return $this->hasMany(PetaLokasi::class, 'peta_kategori_id');
    }
}
