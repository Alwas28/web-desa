<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratDomisili extends Model
{
    protected $fillable = [
        'nama_pemohon', 'nik', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'rt', 'rw',
        'keperluan', 'status', 'nomor_surat', 'catatan',
        'diproses_oleh', 'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_lahir'   => 'date',
        'tanggal_selesai' => 'datetime',
    ];

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai'  => 'Selesai',
            'ditolak'  => 'Ditolak',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'amber',
            'diproses' => 'blue',
            'selesai'  => 'emerald',
            'ditolak'  => 'rose',
            default    => 'slate',
        };
    }
}
