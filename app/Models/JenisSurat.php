<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = [
        'nama', 'kode', 'dapat_dibuat_masyarakat', 'template_isi', 'urutan', 'aktif',
    ];

    protected $casts = [
        'dapat_dibuat_masyarakat' => 'boolean',
        'aktif'                   => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
