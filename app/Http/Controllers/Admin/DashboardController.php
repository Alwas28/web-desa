<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Berita;
use App\Models\IbuHamil;
use App\Models\KartuKeluarga;
use App\Models\LaporanWarga;
use App\Models\Penduduk;
use App\Models\PengajuanPenjual;
use App\Models\PengajuanSurat;
use App\Models\Pengumuman;
use App\Models\Permission;
use App\Models\Produk;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Chart: surat 6 bulan terakhir ──────────────────────────────
        $bulanId = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        $suratDb = PengajuanSurat::select(
                DB::raw('MONTH(created_at) as bln'),
                DB::raw('YEAR(created_at)  as thn'),
                DB::raw('COUNT(*)           as total')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('thn', 'bln')
            ->get()
            ->keyBy(fn($r) => "$r->thn-$r->bln");

        $chartSuratLabels = [];
        $chartSuratData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $chartSuratLabels[] = $bulanId[$d->month - 1] . ' ' . $d->year;
            $chartSuratData[]   = (int) ($suratDb->get("{$d->year}-{$d->month}")?->total ?? 0);
        }

        // ── Chart: distribusi status surat ─────────────────────────────
        $suratStatus = PengajuanSurat::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusOrder  = ['diajukan', 'menunggu_ttd', 'disetujui', 'selesai', 'ditolak'];
        $statusLabel  = ['Diajukan', 'Menunggu TTD', 'Disetujui', 'Selesai', 'Ditolak'];
        $statusColors = ['#C99A2C', '#7C3AED', '#0E63A8', '#0C7C46', '#E05A5A'];

        $chartStatusLabels = [];
        $chartStatusData   = [];
        $chartStatusColors = [];
        foreach ($statusOrder as $idx => $s) {
            $val = (int) ($suratStatus[$s] ?? 0);
            if ($val > 0) {
                $chartStatusLabels[] = $statusLabel[$idx];
                $chartStatusData[]   = $val;
                $chartStatusColors[] = $statusColors[$idx];
            }
        }

        // ── Chart: laporan per bulan ────────────────────────────────────
        $laporanDb = LaporanWarga::select(
                DB::raw('MONTH(created_at) as bln'),
                DB::raw('YEAR(created_at)  as thn'),
                DB::raw('COUNT(*)           as total')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('thn', 'bln')
            ->get()
            ->keyBy(fn($r) => "$r->thn-$r->bln");

        $chartLaporanLabels = [];
        $chartLaporanData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $chartLaporanLabels[] = $bulanId[$d->month - 1];
            $chartLaporanData[]   = (int) ($laporanDb->get("{$d->year}-{$d->month}")?->total ?? 0);
        }

        // ── Chart: jenis kelamin penduduk ──────────────────────────────
        $jkRaw = Penduduk::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        return view('dashboard', [
            // Kependudukan
            'statPenduduk'  => Penduduk::count(),
            'statKK'        => KartuKeluarga::count(),

            // Layanan Surat
            'statSuratTotal'    => PengajuanSurat::count(),
            'statSuratPending'  => PengajuanSurat::where('status', 'diajukan')->count(),
            'statSuratTtd'      => PengajuanSurat::where('status', 'menunggu_ttd')->count(),
            'statSuratBulanIni' => PengajuanSurat::whereMonth('created_at', now()->month)
                                                  ->whereYear('created_at', now()->year)->count(),
            'statSuratSelesai'  => PengajuanSurat::where('status', 'selesai')->count(),

            // Laporan Warga
            'statLaporanTotal'   => LaporanWarga::count(),
            'statLaporanPending' => LaporanWarga::where('status', 'menunggu')->count(),

            // Konten
            'statBerita'     => Berita::where('status', 'diterbitkan')->count(),
            'statPengumuman' => Pengumuman::where('status', 'terbit')->count(),

            // Kesehatan
            'statBalita'   => Balita::count(),
            'statIbuHamil' => IbuHamil::count(),

            // Pasar Desa
            'statProdukAktif'    => Produk::where('status', 'aktif')->count(),
            'statPenjualPending' => PengajuanPenjual::where('status', 'menunggu')->count(),

            // Sistem
            'statUsers'       => User::count(),
            'statRoles'       => Role::count(),
            'statPermissions' => Permission::count(),

            // Chart data
            'chartSuratLabels'  => $chartSuratLabels,
            'chartSuratData'    => $chartSuratData,
            'chartStatusLabels' => $chartStatusLabels,
            'chartStatusData'   => $chartStatusData,
            'chartStatusColors' => $chartStatusColors,
            'chartLaporanLabels'=> $chartLaporanLabels,
            'chartLaporanData'  => $chartLaporanData,
            'chartJkLaki'       => (int) ($jkRaw['L'] ?? 0),
            'chartJkPerempuan'  => (int) ($jkRaw['P'] ?? 0),

            // Aktivitas terbaru
            'recentSurat'   => PengajuanSurat::with('penduduk')->latest()->take(6)->get(),
            'recentLaporan' => LaporanWarga::with('user')->latest()->take(6)->get(),
        ]);
    }
}
