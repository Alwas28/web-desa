<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Balita extends Model
{
    protected $fillable = [
        'posyandu_id', 'nama', 'nik', 'tanggal_lahir',
        'jenis_kelamin', 'nama_ibu', 'nama_ayah', 'alamat',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function timbang(): HasMany
    {
        return $this->hasMany(TimbangBalita::class)->latest('tanggal');
    }

    public function timbangTerakhir(): HasOne
    {
        return $this->hasOne(TimbangBalita::class)->latestOfMany('tanggal');
    }

    public function getUmurAttribute(): string
    {
        $bulan = $this->tanggal_lahir->diffInMonths(now());
        if ($bulan < 12) return $bulan . ' bln';
        $thn = (int) floor($bulan / 12);
        $sisa = $bulan % 12;
        return $thn . ' thn' . ($sisa > 0 ? ' ' . $sisa . ' bln' : '');
    }
}
