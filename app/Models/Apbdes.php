<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apbdes extends Model
{
    protected $table = 'apbdes';

    protected $fillable = ['user_id', 'tahun', 'status', 'catatan'];

    protected $casts = ['tahun' => 'integer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pos(): HasMany
    {
        return $this->hasMany(ApbdesPos::class)->orderBy('urutan')->orderBy('id');
    }

    public function totalAnggaran(string $jenis): float
    {
        return (float) $this->pos->where('jenis', $jenis)->sum('anggaran');
    }

    public function totalRealisasi(string $jenis): float
    {
        return (float) $this->pos->where('jenis', $jenis)->sum('realisasi');
    }
}
