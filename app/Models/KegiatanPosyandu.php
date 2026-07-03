<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KegiatanPosyandu extends Model
{
    protected $table = 'kegiatan_posyandus';

    protected $fillable = [
        'posyandu_id', 'tanggal', 'jumlah_balita', 'jumlah_bumil',
        'jumlah_lansia', 'petugas', 'keterangan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function rekams(): HasMany
    {
        return $this->hasMany(RekamKegiatan::class, 'kegiatan_posyandu_id');
    }
}
