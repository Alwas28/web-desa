<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaWilayah extends Model
{
    protected $table = 'peta_wilayah';

    protected $fillable = ['nama','tipe','geojson','warna','opacity','keterangan','aktif'];

    protected $casts = ['aktif' => 'boolean', 'opacity' => 'float'];

    public function getTipeLabelAttribute(): string
    {
        return match($this->tipe) {
            'desa'    => 'Batas Desa',
            'dusun'   => 'Batas Dusun',
            'rt_rw'   => 'Batas RT/RW',
            'sawah'   => 'Area Sawah',
            'hutan'   => 'Area Hutan',
            default   => 'Lainnya',
        };
    }
}
