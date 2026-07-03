<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'user_id', 'judul', 'slug', 'konten', 'gambar',
        'status', 'dilihat', 'published_at',
        'meta_title', 'meta_deskripsi', 'meta_keywords',
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
        return Str::limit(strip_tags($this->konten), 120);
    }

    protected static function booted(): void
    {
        static::saving(function (self $p) {
            if (empty($p->slug)) {
                $p->slug = Str::slug($p->judul);
            }
        });

        static::deleting(function (self $p) {
            if ($p->gambar) Storage::delete($p->gambar);
        });
    }
}
