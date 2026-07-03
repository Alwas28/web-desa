<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'encrypted'];

    protected $casts = ['encrypted' => 'boolean'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $s = static::where('key', $key)->first();
        if (! $s) {
            return $default;
        }
        if ($s->encrypted) {
            try {
                return Crypt::decryptString($s->value);
            } catch (\Exception) {
                return $default;
            }
        }
        return $s->value;
    }

    public static function set(string $key, ?string $value, bool $encrypted = false): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value'     => $encrypted ? Crypt::encryptString($value ?? '') : $value,
                'encrypted' => $encrypted,
            ]
        );
    }

    /** Return flat array key→value (skip encrypted values, replace with sentinel). */
    public static function forGroup(string $prefix, bool $maskEncrypted = true): array
    {
        return static::where('key', 'like', $prefix . '%')
            ->get()
            ->mapWithKeys(function ($s) use ($maskEncrypted) {
                $val = ($s->encrypted && $maskEncrypted) ? null : $s->value;
                return [$s->key => $val];
            })
            ->all();
    }
}
