<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    protected $fillable = [
        'user_id', 'penduduk_id', 'nama', 'harga', 'satuan',
        'nomor_wa', 'deskripsi', 'foto', 'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getNomorWaLinkAttribute(): string
    {
        $nomor = preg_replace('/\D/', '', $this->nomor_wa);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        return 'https://wa.me/' . $nomor;
    }
}
