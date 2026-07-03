<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBansos extends Model
{
    protected $table = 'jenis_bansos';

    protected $fillable = ['kode','nama','deskripsi','sumber_dana','nilai_bantuan','satuan','aktif'];

    protected $casts = ['aktif' => 'boolean', 'nilai_bantuan' => 'decimal:2'];

    public function penerima()
    {
        return $this->hasMany(PenerimaBansos::class, 'jenis_bansos_id');
    }

    public function getNilaiBantuanFormattedAttribute(): string
    {
        return $this->nilai_bantuan ? 'Rp ' . number_format($this->nilai_bantuan, 0, ',', '.') : '-';
    }
}
