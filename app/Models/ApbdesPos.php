<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApbdesPos extends Model
{
    protected $table = 'apbdes_pos';

    protected $fillable = [
        'apbdes_id', 'jenis', 'kelompok', 'uraian',
        'anggaran', 'realisasi', 'urutan',
    ];

    protected $casts = [
        'anggaran'  => 'float',
        'realisasi' => 'float',
        'urutan'    => 'integer',
    ];

    public function apbdes(): BelongsTo
    {
        return $this->belongsTo(Apbdes::class);
    }

    public static function kelompokOptions(string $jenis): array
    {
        return match($jenis) {
            'pendapatan' => [
                'Pendapatan Asli Desa',
                'Dana Desa',
                'Alokasi Dana Desa (ADD)',
                'Bagi Hasil Pajak & Retribusi Daerah',
                'Bantuan Keuangan Provinsi',
                'Bantuan Keuangan Kabupaten/Kota',
                'Pendapatan Lain-lain',
            ],
            'belanja' => [
                'Bidang Penyelenggaraan Pemerintahan Desa',
                'Bidang Pelaksanaan Pembangunan Desa',
                'Bidang Pembinaan Kemasyarakatan Desa',
                'Bidang Pemberdayaan Masyarakat Desa',
                'Bidang Penanggulangan Bencana, Keadaan Darurat & Mendesak',
            ],
            'pembiayaan' => [
                'Penerimaan Pembiayaan',
                'Pengeluaran Pembiayaan',
            ],
            default => [],
        };
    }
}
