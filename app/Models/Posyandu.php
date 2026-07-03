<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posyandu extends Model
{
    protected $table = 'posyandus';

    protected $fillable = ['nama', 'dusun', 'lokasi', 'kader', 'jadwal', 'aktif', 'keterangan'];

    protected $casts = ['aktif' => 'boolean'];

    public function kegiatans(): HasMany
    {
        return $this->hasMany(KegiatanPosyandu::class);
    }

    public function balitas(): HasMany
    {
        return $this->hasMany(Balita::class);
    }

    public function ibuHamils(): HasMany
    {
        return $this->hasMany(IbuHamil::class);
    }
}
