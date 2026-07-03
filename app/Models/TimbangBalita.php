<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimbangBalita extends Model
{
    protected $fillable = [
        'balita_id', 'tanggal', 'berat_badan', 'tinggi_badan',
        'lingkar_kepala', 'status_gizi', 'keterangan',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'berat_badan'   => 'float',
        'tinggi_badan'  => 'float',
        'lingkar_kepala'=> 'float',
    ];

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function getLabelStatusGiziAttribute(): string
    {
        return match ($this->status_gizi) {
            'normal'   => 'Normal',
            'kurang'   => 'Gizi Kurang',
            'buruk'    => 'Gizi Buruk',
            'lebih'    => 'Gizi Lebih',
            'stunting' => 'Stunting',
            default    => $this->status_gizi,
        };
    }

    public function getBadgeColorAttribute(): string
    {
        return match ($this->status_gizi) {
            'normal'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            'kurang'   => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            'buruk'    => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            'lebih'    => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
            'stunting' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
            default    => 'bg-slate-100 text-slate-600',
        };
    }
}
