<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periodes';

    protected $fillable = ['nama', 'mulai', 'selesai', 'keterangan'];

    protected $casts = [
        'mulai'   => 'date',
        'selesai' => 'date',
    ];

    public function getAktifAttribute(): bool
    {
        return is_null($this->selesai) || $this->selesai->isFuture();
    }
}
