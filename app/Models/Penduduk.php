<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penduduk extends Model
{
    protected $fillable = [
        'kk_id', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'status_perkawinan', 'pekerjaan',
        'pendidikan', 'golongan_darah', 'hubungan_keluarga',
        'status_penduduk', 'kewarganegaraan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(\App\Models\User::class);
    }

    public function sudahPunyaAkun(): bool
    {
        return $this->user()->exists();
    }

    public function isKepalaKeluarga(): bool
    {
        return $this->hubungan_keluarga === 'kepala_keluarga';
    }

    /** Semua anggota KK kecuali diri sendiri. */
    public function anggotaKeluarga(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('kk_id', $this->kk_id)
            ->where('id', '!=', $this->id)
            ->orderByRaw("FIELD(hubungan_keluarga,'istri','anak','orang_tua','mertua','famili_lain','lainnya')")
            ->get();
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    public function getKelompokUmurAttribute(): string
    {
        $u = $this->umur;
        if ($u <= 4)  return 'Balita';
        if ($u <= 12) return 'Anak-anak';
        if ($u <= 17) return 'Remaja';
        if ($u <= 59) return 'Dewasa';
        return 'Lansia';
    }

    public function getPendidikanLabelAttribute(): string
    {
        return match ($this->pendidikan) {
            'tidak_sekolah' => 'Tidak Sekolah',
            'sd'            => 'SD/Sederajat',
            'smp'           => 'SMP/Sederajat',
            'sma'           => 'SMA/Sederajat',
            'diploma'       => 'Diploma (D1-D3)',
            's1'            => 'Sarjana (S1)',
            's2'            => 'Magister (S2)',
            's3'            => 'Doktor (S3)',
            default         => $this->pendidikan,
        };
    }

    public function getStatusPerkawinanLabelAttribute(): string
    {
        return match ($this->status_perkawinan) {
            'belum_kawin' => 'Belum Kawin',
            'kawin'       => 'Kawin',
            'cerai_hidup' => 'Cerai Hidup',
            'cerai_mati'  => 'Cerai Mati',
            default       => $this->status_perkawinan,
        };
    }

    public function getHubunganLabelAttribute(): string
    {
        return match ($this->hubungan_keluarga) {
            'kepala_keluarga' => 'Kepala Keluarga',
            'istri'           => 'Istri',
            'anak'            => 'Anak',
            'orang_tua'       => 'Orang Tua',
            'mertua'          => 'Mertua',
            'famili_lain'     => 'Famili Lain',
            'lainnya'         => 'Lainnya',
            default           => $this->hubungan_keluarga,
        };
    }
}
