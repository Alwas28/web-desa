<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyaluranBansos extends Model
{
    protected $table = 'penyaluran_bansos';

    protected $fillable = [
        'penerima_bansos_id','periode','tanggal_penyaluran',
        'nilai_disalurkan','status','keterangan','dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_penyaluran' => 'date',
        'nilai_disalurkan'   => 'decimal:2',
    ];

    public function penerima()
    {
        return $this->belongsTo(PenerimaBansos::class, 'penerima_bansos_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function getNilaiFormattedAttribute(): string
    {
        return $this->nilai_disalurkan ? 'Rp ' . number_format($this->nilai_disalurkan, 0, ',', '.') : '-';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'disalurkan'   => 'Disalurkan',
            'tidak_hadir'  => 'Tidak Hadir',
            'ditolak'      => 'Ditolak',
            default        => 'Terjadwal',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'disalurkan'  => 'emerald',
            'tidak_hadir' => 'amber',
            'ditolak'     => 'red',
            default       => 'blue',
        };
    }
}
