<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IbuHamil extends Model
{
    protected $fillable = [
        'posyandu_id', 'nama', 'nik', 'tanggal_lahir', 'nama_suami',
        'alamat', 'hpht', 'hpl', 'status_risiko', 'status', 'keterangan',
    ];

    protected $casts = [
        'hpht'          => 'date',
        'hpl'           => 'date',
        'tanggal_lahir' => 'date',
    ];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function kunjungan(): HasMany
    {
        return $this->hasMany(KunjunganBumil::class)->latest('tanggal');
    }

    public function getUsiaKehamilanAttribute(): ?int
    {
        return $this->hpht ? (int) $this->hpht->diffInWeeks(now()) : null;
    }

    public function getLabelStatusRisikoAttribute(): string
    {
        return match ($this->status_risiko) {
            'normal'       => 'Normal',
            'risiko_rendah'=> 'Risiko Rendah',
            'risiko_tinggi'=> 'Risiko Tinggi',
            default        => $this->status_risiko,
        };
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'hamil'     => 'Sedang Hamil',
            'selesai'   => 'Selesai/Melahirkan',
            'keguguran' => 'Keguguran',
            default     => $this->status,
        };
    }
}
