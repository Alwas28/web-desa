<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdmData extends Model
{
    protected $table = 'idm_data';

    protected $fillable = [
        'user_id', 'tahun',
        'skor_iks', 'skor_ike', 'skor_ikl', 'skor_idm', 'status_idm',
        'indikators', 'catatan',
    ];

    protected $casts = [
        'tahun'      => 'integer',
        'skor_iks'   => 'float',
        'skor_ike'   => 'float',
        'skor_ikl'   => 'float',
        'skor_idm'   => 'float',
        'indikators' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function statusFromSkor(float $skor): string
    {
        return match(true) {
            $skor >= 0.8155 => 'Mandiri',
            $skor >= 0.7072 => 'Maju',
            $skor >= 0.5989 => 'Berkembang',
            $skor >= 0.4907 => 'Tertinggal',
            default         => 'Sangat Tertinggal',
        };
    }

    /** Definisi lengkap 50 indikator IDM sesuai format idm.kemendesa.go.id */
    public static function indikatorDefs(): array
    {
        return [
            'iks' => [
                ['key' => 'akses_sarkes',                    'label' => 'Akses Sarana Kesehatan'],
                ['key' => 'dokter',                          'label' => 'Dokter'],
                ['key' => 'bidan',                           'label' => 'Bidan'],
                ['key' => 'nakes_lain',                      'label' => 'Tenaga Kesehatan Lain'],
                ['key' => 'tingkat_kepesertaan_bpjs',        'label' => 'Tingkat Kepesertaan BPJS'],
                ['key' => 'akses_poskesdes',                 'label' => 'Akses Poskesdes'],
                ['key' => 'aktivitas_posyandu',              'label' => 'Aktivitas Posyandu'],
                ['key' => 'akses_sd_mi',                     'label' => 'Akses SD/MI'],
                ['key' => 'akses_smp_mts',                   'label' => 'Akses SMP/MTs'],
                ['key' => 'akses_sma_smk',                   'label' => 'Akses SMA/SMK'],
                ['key' => 'ketersediaan_paud',               'label' => 'Ketersediaan PAUD'],
                ['key' => 'ketersediaan_pkbm_paket_abc',     'label' => 'Ketersediaan PKBM/Paket ABC'],
                ['key' => 'ketersediaan_kursus',             'label' => 'Ketersediaan Kursus/Pelatihan'],
                ['key' => 'ketersediaan_taman_baca_perpus_desa', 'label' => 'Ketersediaan Taman Baca/Perpustakaan Desa'],
                ['key' => 'kebiasaan_goryong',               'label' => 'Kebiasaan Gotong Royong'],
                ['key' => 'frekuensi_goryong',               'label' => 'Frekuensi Gotong Royong'],
                ['key' => 'ketersediaan_ruang_publik',       'label' => 'Ketersediaan Ruang Publik'],
                ['key' => 'kelompok_or',                     'label' => 'Kelompok Olahraga'],
                ['key' => 'kegiatan_or',                     'label' => 'Kegiatan Olahraga'],
                ['key' => 'keragaman_agama',                 'label' => 'Keragaman Agama'],
                ['key' => 'keragaman_bahasa',                'label' => 'Keragaman Bahasa'],
                ['key' => 'keragaman_komunikasi',            'label' => 'Keragaman Komunikasi/Suku'],
                ['key' => 'poskamling',                      'label' => 'Pos Keamanan Lingkungan'],
                ['key' => 'siskamling',                      'label' => 'Sistem Keamanan Lingkungan'],
                ['key' => 'konflik',                         'label' => 'Konflik'],
                ['key' => 'pmks',                            'label' => 'PMKS'],
                ['key' => 'slb',                             'label' => 'SLB'],
                ['key' => 'akses_listrik',                   'label' => 'Akses Listrik'],
                ['key' => 'sinyal_tlp',                      'label' => 'Sinyal Telepon'],
                ['key' => 'internet_kantor_desa',            'label' => 'Internet Kantor Desa'],
                ['key' => 'akses_internet_warga',            'label' => 'Akses Internet Warga'],
                ['key' => 'akses_jamban',                    'label' => 'Akses Jamban'],
                ['key' => 'sampah',                          'label' => 'Pengelolaan Sampah'],
                ['key' => 'air_minum',                       'label' => 'Air Minum'],
                ['key' => 'air_mandi_cuci',                  'label' => 'Air Mandi & Cuci'],
            ],
            'ike' => [
                ['key' => 'keragaman_produksi',      'label' => 'Keragaman Produksi Masyarakat'],
                ['key' => 'pertokoan',               'label' => 'Pusat Pelayanan Pertokoan'],
                ['key' => 'pasar',                   'label' => 'Pasar'],
                ['key' => 'toko_warung_kelontong',   'label' => 'Toko/Warung Kelontong'],
                ['key' => 'kedai_penginapan',        'label' => 'Kedai & Penginapan'],
                ['key' => 'pos_logistik',            'label' => 'POS & Logistik'],
                ['key' => 'bank_bpr',                'label' => 'Bank & BPR'],
                ['key' => 'kredit',                  'label' => 'Fasilitas Kredit'],
                ['key' => 'lembaga_ekonomi',         'label' => 'Lembaga Ekonomi (Koperasi/BUMDes)'],
                ['key' => 'moda_transportasi_umum',  'label' => 'Moda Transportasi Umum'],
                ['key' => 'keterbukaan_wilayah',     'label' => 'Keterbukaan Wilayah'],
                ['key' => 'kualitas_jalan',          'label' => 'Kualitas Jalan'],
            ],
            'ikl' => [
                ['key' => 'kualitas_lingkungan', 'label' => 'Kualitas Lingkungan'],
                ['key' => 'rawan_bencana',       'label' => 'Potensi Rawan Bencana'],
                ['key' => 'tanggap_bencana',     'label' => 'Tanggap Bencana'],
            ],
        ];
    }
}
