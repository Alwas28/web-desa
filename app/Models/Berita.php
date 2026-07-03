<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table = 'beritas';

    protected $fillable = [
        'user_id', 'kategori_id', 'judul', 'slug',
        'konten', 'gambar', 'status', 'dilihat', 'published_at',
        'meta_title', 'meta_deskripsi', 'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
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
        static::saving(function (self $b) {
            if (empty($b->slug)) {
                $b->slug = Str::slug($b->judul);
            }
        });

        static::deleting(function (self $b) {
            if ($b->gambar) Storage::delete($b->gambar);
        });
    }
}
