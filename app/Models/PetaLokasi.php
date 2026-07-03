<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaLokasi extends Model
{
    protected $table = 'peta_lokasi';

    protected $fillable = [
        'peta_kategori_id', 'nama', 'deskripsi',
        'latitude', 'longitude', 'alamat', 'aktif',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'aktif'     => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(PetaKategori::class, 'peta_kategori_id');
    }
}
