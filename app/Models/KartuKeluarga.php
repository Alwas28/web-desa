<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluarga extends Model
{
    protected $table = 'kartu_keluargas';

    protected $fillable = ['nomor_kk', 'alamat', 'rt', 'rw', 'dusun', 'kode_pos'];

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'kk_id');
    }

    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Penduduk::class, 'kk_id')
                    ->where('hubungan_keluarga', 'kepala_keluarga');
    }
}
