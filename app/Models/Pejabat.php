<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Pejabat extends Model
{
    protected $table = 'pejabats';

    protected $fillable = [
        'jabatan_id', 'periode_id', 'nama', 'foto',
        'nip', 'tempat_lahir', 'tanggal_lahir', 'pendidikan', 'no_hp', 'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    protected static function booted(): void
    {
        static::deleting(function (self $p) {
            if ($p->foto) {
                Storage::delete($p->foto);
            }
        });
    }
}
