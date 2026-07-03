<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaBansos extends Model
{
    protected $table = 'penerima_bansos';

    protected $fillable = [
        'jenis_bansos_id','penduduk_id','nama','nik','no_kk',
        'alamat','rt','rw','tahun_ditetapkan','status','jenis_penerimaan','keterangan',
    ];

    public function jenisBansos()
    {
        return $this->belongsTo(JenisBansos::class, 'jenis_bansos_id');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function penyaluran()
    {
        return $this->hasMany(PenyaluranBansos::class, 'penerima_bansos_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif'    => 'Aktif',
            'nonaktif' => 'Nonaktif',
            default    => 'Menunggu',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'aktif'    => 'emerald',
            'nonaktif' => 'slate',
            default    => 'amber',
        };
    }

    public function getJenisPenerimaanLabelAttribute(): string
    {
        return $this->jenis_penerimaan === 'non_tunai' ? 'Non Tunai' : 'Tunai';
    }
}
