<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'penduduk_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relasi ──────────────────────────────────────────

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function isWarga(): bool
    {
        return $this->penduduk_id !== null;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // ── Helper akses ────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->allPermissions()->contains('name', $permission);
    }

    /** Daftar semua permission yang dimiliki user (di-cache per request). */
    public function allPermissions(): \Illuminate\Support\Collection
    {
        if (! isset($this->_permCache)) {
            $this->_permCache = $this->roles()
                ->with('permissions')
                ->get()
                ->pluck('permissions')
                ->flatten()
                ->unique('id');
        }
        return $this->_permCache;
    }
}
