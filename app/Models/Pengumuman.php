<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'user_id', 'judul', 'konten', 'gambar', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    public function getRingkasanAttribute(): string
    {
        return Str::limit(strip_tags($this->konten), 140);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $p) {
            if ($p->gambar) Storage::delete($p->gambar);
        });
    }
}
