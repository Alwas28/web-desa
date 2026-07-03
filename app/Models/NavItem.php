<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavItem extends Model
{
    protected $fillable = [
        'label', 'parent_id', 'urutan', 'aktif',
        'tipe_link', 'link_url', 'page_id', 'target',
        'arsip_filter', 'kategori_arsip_id',
    ];

    protected $casts = ['aktif' => 'boolean'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('urutan');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function kategoriArsip(): BelongsTo
    {
        return $this->belongsTo(KategoriArsip::class, 'kategori_arsip_id');
    }

    public function getLinkAttribute(): string
    {
        if ($this->tipe_link === 'page') {
            return $this->page ? '/' . $this->page->slug : '#';
        }
        if ($this->tipe_link === 'arsip') {
            return $this->arsip_filter === 'kategori' && $this->kategori_arsip_id
                ? '/arsip?kategori=' . $this->kategori_arsip_id
                : '/arsip';
        }
        return $this->link_url ?: '#';
    }
}
