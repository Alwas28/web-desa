<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KunjunganBumil extends Model
{
    protected $fillable = [
        'ibu_hamil_id', 'tanggal', 'tekanan_darah',
        'berat_badan', 'usia_kehamilan', 'kunjungan_ke', 'keterangan',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'berat_badan' => 'float',
    ];

    public function ibuHamil(): BelongsTo
    {
        return $this->belongsTo(IbuHamil::class);
    }
}
