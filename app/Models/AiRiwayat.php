<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRiwayat extends Model
{
    protected $table = 'ai_riwayat';

    protected $fillable = ['user_id', 'tipe', 'parameter', 'konten'];

    protected $casts = ['parameter' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
