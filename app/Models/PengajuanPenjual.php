<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPenjual extends Model
{
    protected $fillable = [
        'user_id', 'penduduk_id', 'alasan',
        'status', 'catatan_admin', 'ditinjau_oleh', 'ditinjau_at',
    ];

    protected $casts = [
        'ditinjau_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function ditinjauOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditinjau_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'Menunggu Review',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => $this->status,
        };
    }
}
