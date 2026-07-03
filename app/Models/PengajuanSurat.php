<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\JenisSurat;

class PengajuanSurat extends Model
{
    public const JENIS = [
        'Surat Keterangan Tidak Mampu'   => 'Surat Keterangan Tidak Mampu',
        'Surat Keterangan Usaha'          => 'Surat Keterangan Usaha',
        'Surat Keterangan Kelahiran'      => 'Surat Keterangan Kelahiran',
        'Surat Keterangan Kematian'       => 'Surat Keterangan Kematian',
        'Surat Keterangan Pindah'         => 'Surat Keterangan Pindah',
        'Surat Pengantar'                 => 'Surat Pengantar',
        'Surat Keterangan Menikah'        => 'Surat Keterangan Menikah',
        'Surat Keterangan Belum Menikah'  => 'Surat Keterangan Belum Menikah',
        'Surat Pengantar SKCK'            => 'Surat Pengantar SKCK',
        'Surat Keterangan Lainnya'        => 'Surat Keterangan Lainnya',
    ];

    public const KODE_JENIS = [
        'Surat Keterangan Tidak Mampu'   => 'SKTM',
        'Surat Keterangan Usaha'         => 'SKU',
        'Surat Keterangan Kelahiran'     => 'SKL',
        'Surat Keterangan Kematian'      => 'SKKM',
        'Surat Keterangan Pindah'        => 'SKP',
        'Surat Pengantar'                => 'SP',
        'Surat Keterangan Menikah'       => 'SKMN',
        'Surat Keterangan Belum Menikah' => 'SKBM',
        'Surat Pengantar SKCK'           => 'SKCK',
        'Surat Keterangan Lainnya'       => 'SKET',
    ];

    protected $table = 'pengajuan_surats';

    protected $fillable = [
        'penduduk_id', 'diajukan_oleh_penduduk_id', 'jenis_surat', 'keperluan', 'keterangan',
        'status', 'nomor_surat', 'nama_kades', 'jabatan_kades',
        'catatan', 'diproses_oleh', 'disetujui_oleh', 'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $surat) {
            $surat->token = Str::random(48);
        });
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    /** Siapa yang mengajukan (bisa KK head berbeda dari penerima surat). */
    public function diajukanOleh(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'diajukan_oleh_penduduk_id');
    }

    /** True jika surat ini diajukan oleh orang lain (KK head) atas nama penduduk ini. */
    public function getIsWakilAttribute(): bool
    {
        return $this->diajukan_oleh_penduduk_id !== null
            && $this->diajukan_oleh_penduduk_id !== $this->penduduk_id;
    }

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan'     => 'Menunggu',
            'diproses'     => 'Diproses',
            'menunggu_ttd' => 'Menunggu Kades',
            'disetujui'    => 'Disetujui Kades',
            'selesai'      => 'Selesai',
            'ditolak'      => 'Ditolak',
            default        => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diajukan'     => 'amber',
            'diproses'     => 'blue',
            'menunggu_ttd' => 'violet',
            'disetujui'    => 'indigo',
            'selesai'      => 'emerald',
            'ditolak'      => 'rose',
            default        => 'slate',
        };
    }

    public function generateNomorSurat(): string
    {
        $format          = Setting::get('surat.nomor_format', '{kode1}/{kode_jenis}/{tahun}/{urutan}');
        $kode1           = Setting::get('surat.nomor_kode1', '');
        $kode2           = Setting::get('surat.nomor_kode2', '');
        $panjang         = (int) Setting::get('surat.nomor_panjang', 3);
        $resetPeriode    = Setting::get('surat.nomor_reset_periode', 'tahun');
        $resetBulanMulai = (int) Setting::get('surat.nomor_reset_bulan_mulai', 1);

        $now   = now();
        $tahun = $now->year;
        $bulan = $now->month;
        $roman = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$bulan - 1];

        if ($resetPeriode === 'bulan') {
            $count = self::where('status', 'selesai')
                ->whereYear('tanggal_selesai', $tahun)
                ->whereMonth('tanggal_selesai', $bulan)
                ->count();
        } else {
            $periodStart = $bulan >= $resetBulanMulai
                ? \Carbon\Carbon::create($tahun,     $resetBulanMulai, 1)
                : \Carbon\Carbon::create($tahun - 1, $resetBulanMulai, 1);
            $count = self::where('status', 'selesai')
                ->where('tanggal_selesai', '>=', $periodStart)
                ->count();
        }

        $urutan    = str_pad($count + 1, $panjang, '0', STR_PAD_LEFT);
        $kodeJenis = JenisSurat::where('nama', $this->jenis_surat)->value('kode')
            ?? (self::KODE_JENIS[$this->jenis_surat] ?? 'SK');

        return str_replace(
            ['{kode1}', '{kode2}', '{kode_jenis}', '{tahun}', '{bulan}', '{urutan}'],
            [$kode1, $kode2, $kodeJenis, $tahun, $roman, $urutan],
            $format
        );
    }
}
