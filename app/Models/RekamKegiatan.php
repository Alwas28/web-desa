<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamKegiatan extends Model
{
    protected $table = 'rekam_kegiatans';

    protected $fillable = [
        'kegiatan_posyandu_id', 'kategori', 'nama', 'tanggal_lahir',
        'jenis_kelamin', 'berat_badan', 'tinggi_badan', 'lingkar_kepala',
        'tekanan_darah', 'status_gizi', 'keterangan',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KegiatanPosyandu::class, 'kegiatan_posyandu_id');
    }

    public function getUmurAttribute(): string
    {
        if (! $this->tanggal_lahir) return '—';
        $now   = now();
        $lahir = $this->tanggal_lahir;
        $tahun = $lahir->diffInYears($now);
        $bulan = $lahir->copy()->addYears($tahun)->diffInMonths($now);
        if ($tahun >= 1) return $tahun . ' thn' . ($bulan ? ' ' . $bulan . ' bln' : '');
        return $bulan . ' bln';
    }

    public function getLabelStatusGiziAttribute(): string
    {
        return match($this->status_gizi) {
            'normal'   => 'Normal',
            'kurang'   => 'Gizi Kurang',
            'buruk'    => 'Gizi Buruk',
            'lebih'    => 'Gizi Lebih',
            'stunting' => 'Stunting',
            default    => '—',
        };
    }

    public function getBadgeStatusGiziAttribute(): string
    {
        return match($this->status_gizi) {
            'normal'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
            'kurang'   => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
            'buruk'    => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
            'lebih'    => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
            'stunting' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400',
            default    => '',
        };
    }
}
