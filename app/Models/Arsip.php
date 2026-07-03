<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class Arsip extends Model
{
    protected $fillable = [
        'user_id', 'kategori_arsip_id', 'judul', 'slug', 'deskripsi',
        'file', 'nama_file', 'mime_type', 'ukuran',
        'status', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriArsip(): BelongsTo
    {
        return $this->belongsTo(KategoriArsip::class, 'kategori_arsip_id');
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file);
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = (int) $this->ukuran;
        if ($bytes < 1024) return "{$bytes} B";
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->nama_file ?? $this->file, PATHINFO_EXTENSION));
    }

    protected static function booted(): void
    {
        static::saving(function (self $arsip) {
            if (empty($arsip->slug)) {
                $base = Str::slug($arsip->judul);
                $slug = $base;
                $n = 1;
                while (static::where('slug', $slug)->where('id', '!=', $arsip->id ?? 0)->exists()) {
                    $slug = $base . '-' . $n++;
                }
                $arsip->slug = $slug;
            }
        });

        static::deleting(function (self $arsip) {
            if ($arsip->file && Storage::disk('public')->exists($arsip->file)) {
                Storage::disk('public')->delete($arsip->file);
            }
        });
    }
}
