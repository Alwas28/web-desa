<?php

namespace App\Services;

use App\Http\Controllers\Admin\SuratPengaturanController;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;

class SuratPdfService
{
    public static function download(PengajuanSurat $pengajuan)
    {
        $pengajuan->loadMissing('penduduk.kartuKeluarga');
        $penduduk = $pengajuan->penduduk;
        $desa     = Setting::forGroup('desa.');
        $jenis    = JenisSurat::where('nama', $pengajuan->jenis_surat)->first();

        // ── KOP ──
        $kopTemplate = Setting::get('surat.kop_template', SuratPengaturanController::DEFAULT_TEMPLATE);
        $kopRendered = SuratPengaturanController::renderTemplate($kopTemplate, $desa);
        $kopLines    = explode("\n", $kopRendered);
        $kopAlign    = Setting::get('surat.kop_align', 'center');
        $kopFont     = Setting::get('surat.kop_font', 'Inter');
        $kopSizes    = [
            'l1' => (int) Setting::get('surat.kop_size_l1', 11),
            'l2' => (int) Setting::get('surat.kop_size_l2', 18),
            'l3' => (int) Setting::get('surat.kop_size_l3', 11),
            'l4' => (int) Setting::get('surat.kop_size_l4', 11),
            'l5' => (int) Setting::get('surat.kop_size_l5', 11),
        ];

        // ── Logo ──
        $logoData = null;
        if (! empty($desa['desa.logo'])) {
            $path = storage_path('app/public/' . ltrim($desa['desa.logo'], '/'));
            if (file_exists($path)) {
                $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime     = $ext === 'png' ? 'image/png' : 'image/jpeg';
                $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }
        $logoPos = Setting::get('surat.logo_position', 'left');

        // ── Tanggal & pejabat ──
        $tanggal = $pengajuan->tanggal_selesai
            ? $pengajuan->tanggal_selesai->locale('id')->translatedFormat('d F Y')
            : now()->locale('id')->translatedFormat('d F Y');
        $namaKades    = $pengajuan->nama_kades    ?: ($desa['desa.kepala'] ?? '');
        $jabatanKades = $pengajuan->jabatan_kades ?: 'Kepala Desa';

        // ── Alamat dari KK ──
        $alamat = '';
        if ($penduduk?->kartuKeluarga) {
            $kk     = $penduduk->kartuKeluarga;
            $alamat = trim(
                ($kk->alamat ?? '') .
                ($kk->rt     ? ', RT ' . $kk->rt . '/RW ' . $kk->rw : '') .
                ($kk->dusun  ? ', Dusun ' . $kk->dusun : '')
            );
        }

        // ── Replace placeholder ──
        $desaPlaceholders = array_keys(SuratPengaturanController::PLACEHOLDER_MAP);
        $desaValues       = array_map(
            fn ($k) => $desa[$k] ?? '',
            SuratPengaturanController::PLACEHOLDER_MAP
        );

        $pendudukPlaceholders = [
            '{nama}', '{nik}', '{jenis_kelamin}', '{tempat_lahir}', '{tanggal_lahir}',
            '{alamat}', '{keperluan}', '{keterangan}',
            '{nomor_surat}', '{tanggal}', '{kepala_desa}', '{jabatan_kades}',
        ];
        $pendudukValues = [
            $penduduk?->nama_lengkap ?? '',
            $penduduk?->nik ?? '',
            $penduduk?->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $penduduk?->tempat_lahir ?? '',
            $penduduk?->tanggal_lahir?->locale('id')->translatedFormat('d F Y') ?? '',
            $alamat,
            $pengajuan->keperluan,
            $pengajuan->keterangan ?? '',
            $pengajuan->nomor_surat ?? '',
            $tanggal,
            $namaKades,
            $jabatanKades,
        ];

        $rawIsi = str_replace(
            array_merge($desaPlaceholders, $pendudukPlaceholders),
            array_merge($desaValues, $pendudukValues),
            $jenis?->template_isi ?? ''
        );

        // Jika template berisi HTML (dari CKEditor), pakai langsung.
        // Jika plain text (template lama), konversi \n → <p>.
        if ($rawIsi !== strip_tags($rawIsi)) {
            // HTML dari CKEditor — hanya atur struktur tabel, TIDAK paksa border
            // border dikontrol sepenuhnya oleh atribut/style di template (border="0" dihormati)
            $isiSurat = '<style>
              .surat-body table { border-collapse:collapse; width:100%; margin-bottom:6pt; }
              .surat-body td, .surat-body th { padding:3pt 6pt; font-size:10pt; }
            </style>' . $rawIsi;
        } else {
            // Plain text lama — konversi newline ke paragraf
            $isiLines = array_map(
                fn ($line) => '<p style="margin-bottom:4pt">' . e(trim($line)) . '</p>',
                explode("\n", $rawIsi)
            );
            $isiSurat = implode('', $isiLines);
        }

        // ── QR Code ──
        $verifikasiUrl = url('/verifikasi/' . $pengajuan->token);
        $qrOptions     = new QROptions;
        $qrOptions->outputInterface = QRGdImagePNG::class;
        $qrOptions->outputBase64    = true;
        $qrOptions->scale           = 6;
        $qrData = (new QRCode($qrOptions))->render($verifikasiUrl);

        $pdf = Pdf::loadView('warga.surat.pdf', compact(
            'pengajuan', 'penduduk', 'desa',
            'kopLines', 'kopAlign', 'kopFont', 'kopSizes',
            'logoData', 'logoPos',
            'isiSurat', 'namaKades', 'jabatanKades', 'tanggal',
            'qrData', 'verifikasiUrl'
        ))->setPaper('a4', 'portrait');

        $nama = 'Surat-' . ($pengajuan->nomor_surat
            ? str_replace(['/', '\\', ' '], '-', $pengajuan->nomor_surat)
            : $pengajuan->id) . '.pdf';

        return $pdf->download($nama);
    }
}
