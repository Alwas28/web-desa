<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratPengaturanController extends Controller
{
    public const DEFAULT_TEMPLATE =
        "PEMERINTAH DAERAH KABUPATEN {nama_kabupaten}\n" .
        "DESA {nama_desa} KECAMATAN {kecamatan}\n" .
        "Alamat: {alamat}, Email: {email_resmi}, Website: {website}";

    public const PLACEHOLDER_MAP = [
        '{nama_desa}'      => 'desa.nama',
        '{kecamatan}'      => 'desa.kecamatan',
        '{kabupaten}'      => 'desa.kabupaten',
        '{nama_kabupaten}' => 'desa.kabupaten',
        '{provinsi}'       => 'desa.provinsi',
        '{alamat}'         => 'desa.alamat',
        '{kode_pos}'       => 'desa.kode_pos',
        '{kepala_desa}'    => 'desa.kepala',
        '{email_resmi}'    => 'desa.email',
        '{email}'          => 'desa.email',
        '{telepon}'        => 'desa.whatsapp',
        '{website}'        => 'desa.website',
    ];

    public const FONTS = [
        'Inter'             => 'Inter, sans-serif',
        'Times New Roman'   => "'Times New Roman', Times, serif",
        'Arial'             => 'Arial, Helvetica, sans-serif',
        'Georgia'           => 'Georgia, serif',
        'Calibri'           => 'Calibri, sans-serif',
        'Palatino Linotype' => "'Palatino Linotype', Palatino, serif",
    ];

    public static function renderTemplate(string $template, array $desa): string
    {
        $search  = array_keys(self::PLACEHOLDER_MAP);
        $replace = array_map(fn ($key) => $key !== '' ? ($desa[$key] ?? '') : '', self::PLACEHOLDER_MAP);
        return str_replace($search, $replace, $template);
    }

    public function index()
    {
        $desa        = Setting::forGroup('desa.');
        $nom         = Setting::forGroup('surat.nomor_');
        $kopTemplate = Setting::get('surat.kop_template', self::DEFAULT_TEMPLATE);
        $kopAlign    = Setting::get('surat.kop_align', 'center');
        $logoPos     = Setting::get('surat.logo_position', 'above');
        $kopFont     = Setting::get('surat.kop_font', 'Inter');
        $kopSizes    = [
            'l1' => (int) Setting::get('surat.kop_size_l1', 11),
            'l2' => (int) Setting::get('surat.kop_size_l2', 18),
            'l3' => (int) Setting::get('surat.kop_size_l3', 11),
            'l4' => (int) Setting::get('surat.kop_size_l4', 11),
            'l5' => (int) Setting::get('surat.kop_size_l5', 11),
        ];
        $logoPath    = $desa['desa.logo'] ?? null;
        $jenisList   = JenisSurat::aktif()->orderBy('urutan')->orderBy('nama')->get();

        // ── Reset periode & counter ────────────────────────────────────────
        $resetPeriode    = Setting::get('surat.nomor_reset_periode', 'tahun');
        $resetBulanMulai = (int) Setting::get('surat.nomor_reset_bulan_mulai', 1);

        $bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $bulanShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

        $now = now();
        if ($resetPeriode === 'bulan') {
            $periodStart = $now->copy()->startOfMonth();
            $nextReset   = $now->copy()->addMonth()->startOfMonth();
        } else {
            $periodStart = $now->month >= $resetBulanMulai
                ? \Carbon\Carbon::create($now->year,     $resetBulanMulai, 1)
                : \Carbon\Carbon::create($now->year - 1, $resetBulanMulai, 1);
            $nextReset = $periodStart->copy()->addYear();
        }

        $periodLabel    = $periodStart->day . ' ' . $bulanNames[$periodStart->month - 1] . ' ' . $periodStart->year . ' – sekarang';
        $nextResetLabel = $nextReset->day . ' ' . $bulanNames[$nextReset->month - 1] . ' ' . $nextReset->year;

        $multiYear    = ($periodStart->year !== $now->year);
        $periodMonths = [];
        $cur = $periodStart->copy()->startOfMonth();
        while ($cur->lte($now->copy()->endOfMonth())) {
            $periodMonths[] = [
                'year'  => $cur->year,
                'month' => $cur->month,
                'label' => $bulanShort[$cur->month - 1] . ($multiYear ? ' \'' . $cur->format('y') : ''),
            ];
            $cur->addMonth();
        }

        $counterRows = PengajuanSurat::where('status', 'selesai')
            ->where('tanggal_selesai', '>=', $periodStart)
            ->selectRaw('jenis_surat, MONTH(tanggal_selesai) as bulan, YEAR(tanggal_selesai) as tahun_col, count(*) as total')
            ->groupBy('jenis_surat', 'bulan', 'tahun_col')
            ->get();

        $counterTotal = $counterRows->sum('total');

        return view('admin.surat.pengaturan.index', [
            'desa'             => $desa,
            'nom'              => $nom,
            'kopTemplate'      => $kopTemplate,
            'kopRendered'      => self::renderTemplate($kopTemplate, $desa),
            'kopAlign'         => $kopAlign,
            'logoPos'          => $logoPos,
            'kopFont'          => $kopFont,
            'kopSizes'         => $kopSizes,
            'fonts'            => self::FONTS,
            'logoUrl'          => $logoPath ? Storage::url($logoPath) : null,
            'jenisList'        => $jenisList,
            'resetPeriode'     => $resetPeriode,
            'resetBulanMulai'  => $resetBulanMulai,
            'periodLabel'      => $periodLabel,
            'nextResetLabel'   => $nextResetLabel,
            'periodMonths'     => $periodMonths,
            'counterRows'      => $counterRows,
            'counterTotal'     => $counterTotal,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'kop_template'         => 'nullable|string|max:2000',
            'kop_align'            => 'nullable|in:left,center',
            'logo_position'        => 'nullable|in:above,left',
            'kop_font'             => 'nullable|string|max:100',
            'kop_size_l1'          => 'nullable|integer|min:8|max:32',
            'kop_size_l2'          => 'nullable|integer|min:8|max:48',
            'kop_size_l3'          => 'nullable|integer|min:8|max:32',
            'kop_size_l4'          => 'nullable|integer|min:8|max:32',
            'kop_size_l5'          => 'nullable|integer|min:8|max:32',
            'nomor_kode1'          => 'nullable|string|max:30',
            'nomor_kode2'          => 'nullable|string|max:30',
            'nomor_format'         => 'nullable|string|max:200',
            'nomor_panjang'        => 'nullable|integer|min:1|max:6',
            'nomor_reset_periode'     => 'nullable|in:tahun,bulan',
            'nomor_reset_bulan_mulai' => 'nullable|integer|min:1|max:12',
        ]);

        foreach ([
            'kop_template', 'kop_align', 'logo_position', 'kop_font',
            'kop_size_l1', 'kop_size_l2', 'kop_size_l3', 'kop_size_l4', 'kop_size_l5',
            'nomor_kode1', 'nomor_kode2', 'nomor_format', 'nomor_panjang',
            'nomor_reset_periode', 'nomor_reset_bulan_mulai',
        ] as $key) {
            Setting::set("surat.{$key}", $request->input($key));
        }

        return back()->with('success', 'Pengaturan surat berhasil disimpan.');
    }
}
