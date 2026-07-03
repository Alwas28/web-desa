<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanWarga extends Model
{
    protected $table = 'laporan_warga';

    protected $fillable = [
        'user_id', 'kategori', 'judul', 'isi',
        'status', 'balasan', 'dibalas_oleh', 'dibalas_at',
        'rencana_tindak_lanjut', 'laporan_penanganan', 'ditangani_at',
    ];

    protected $casts = [
        'dibalas_at'   => 'datetime',
        'ditangani_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembalas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibalas_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diproses' => 'Diproses',
            'selesai'  => 'Selesai',
            default    => 'Menunggu',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diproses' => '#d97706',
            'selesai'  => '#059669',
            default    => '#6b7280',
        };
    }
}
