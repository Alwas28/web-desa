<?php

namespace App\Http\Controllers;

use App\Models\Apbdes;
use App\Models\Arsip;
use App\Models\IdmData;
use App\Models\Produk;
use App\Models\Berita;
use App\Models\KategoriArsip;
use App\Models\Page;
use App\Models\Pengumuman;
use App\Models\JenisSurat;
use App\Models\KartuKeluarga;
use App\Models\Pejabat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function home()
    {
        $desaInfo = Setting::forGroup('desa.');

        $headerSettings = Setting::forGroup('header.');
        $headerSlides   = json_decode($headerSettings['header.slides'] ?? '[]', true) ?: [];

        $berita = Berita::where('status', 'diterbitkan')
                        ->latest('published_at')
                        ->take(3)
                        ->with('kategori')
                        ->get();

        $layanan = JenisSurat::aktif()
                             ->where('dapat_dibuat_masyarakat', true)
                             ->orderBy('urutan')
                             ->orderBy('nama')
                             ->get();

        $pejabat        = Pejabat::with(['jabatan', 'periode'])->orderBy('id')->get();
        $jumlahPenduduk = Penduduk::count();
        $jumlahKK       = KartuKeluarga::count();

        $pengumuman = Pengumuman::where('status', 'terbit')
                                ->latest('published_at')
                                ->take(4)
                                ->get();

        return view('public.beranda', compact(
            'desaInfo', 'headerSettings', 'headerSlides',
            'berita', 'layanan', 'pejabat', 'jumlahPenduduk', 'jumlahKK',
            'pengumuman'
        ));
    }

    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('status', 'diterbitkan')
                    ->firstOrFail();

        $page->increment('dilihat');

        $desaInfo = Setting::forGroup('desa.');

        return view('public.page', compact('page', 'desaInfo'));
    }

    public function beritaIndex(\Illuminate\Http\Request $request)
    {
        $kategoris = \App\Models\Kategori::whereHas('beritas', fn($q) => $q->where('status', 'diterbitkan'))
                                          ->orderBy('nama')->get();

        $aktifKat = $request->filled('kategori')
            ? $kategoris->firstWhere('id', $request->input('kategori'))
            : null;

        $query = Berita::with('kategori')
                       ->where('status', 'diterbitkan')
                       ->latest('published_at');

        if ($aktifKat) {
            $query->where('kategori_id', $aktifKat->id);
        }
        if ($q = $request->input('q')) {
            $query->where('judul', 'like', "%{$q}%");
        }

        $beritas  = $query->paginate(12)->withQueryString();
        $desaInfo = Setting::forGroup('desa.');

        return view('public.berita.index', compact('beritas', 'kategoris', 'aktifKat', 'desaInfo'));
    }

    public function beritaShow(string $slug)
    {
        $berita = Berita::with('kategori', 'user')
                        ->where('slug', $slug)
                        ->where('status', 'diterbitkan')
                        ->firstOrFail();

        $berita->increment('dilihat');

        $related = Berita::where('status', 'diterbitkan')
                         ->where('id', '!=', $berita->id)
                         ->when($berita->kategori_id, fn($q) => $q->where('kategori_id', $berita->kategori_id))
                         ->latest('published_at')
                         ->take(3)
                         ->get();

        $desaInfo = Setting::forGroup('desa.');

        return view('public.berita.show', compact('berita', 'related', 'desaInfo'));
    }

    public function arsip(\Illuminate\Http\Request $request)
    {
        $kategoris  = KategoriArsip::orderBy('nama')->get();
        $aktifKat   = $request->input('kategori') ? $kategoris->find($request->input('kategori')) : null;

        $query = Arsip::with('kategoriArsip')
                      ->where('status', 'publish')
                      ->latest('published_at');

        if ($aktifKat) {
            $query->where('kategori_arsip_id', $aktifKat->id);
        }

        if ($q = $request->input('q')) {
            $query->where('judul', 'like', "%{$q}%");
        }

        $arsips   = $query->paginate(18)->withQueryString();
        $desaInfo = Setting::forGroup('desa.');

        return view('public.arsip', compact('arsips', 'kategoris', 'aktifKat', 'desaInfo'));
    }

    public function statistik()
    {
        $grafikAktif = [
            'demografi' => (Setting::get('publik.grafik.demografi', '1') === '1'),
            'apbdes'    => (Setting::get('publik.grafik.apbdes',    '1') === '1'),
            'surat'     => (Setting::get('publik.grafik.surat',     '1') === '1'),
            'konten'    => (Setting::get('publik.grafik.konten',    '1') === '1'),
        ];

        $desaInfo = Setting::forGroup('desa.');

        // ── Demografis ──────────────────────────────────────────────────────────
        $totalPenduduk = Penduduk::count();
        $totalKK       = KartuKeluarga::count();

        $jenisKelamin = Penduduk::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')->pluck('total', 'jenis_kelamin');

        $pendidikanRaw = Penduduk::select('pendidikan', DB::raw('count(*) as total'))
            ->groupBy('pendidikan')->orderByDesc('total')->pluck('total', 'pendidikan');

        $pendidikanOrder = ['tidak_sekolah','sd','smp','sma','diploma','s1','s2','s3'];
        $pendidikanLabel = ['Tdk Sekolah','SD','SMP','SMA','Diploma','S1','S2','S3'];
        $pendidikanData  = array_map(fn($k) => (int)($pendidikanRaw[$k] ?? 0), $pendidikanOrder);

        $kelompokRaw = Penduduk::select(
            DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 4  THEN 'Balita (0-4)'
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 12 THEN 'Anak (5-12)'
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 17 THEN 'Remaja (13-17)'
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 59 THEN 'Dewasa (18-59)'
                    ELSE 'Lansia (60+)'
                END as kelompok
            "),
            DB::raw('count(*) as total')
        )->groupBy('kelompok')->pluck('total', 'kelompok');

        $kelompokOrder = ['Balita (0-4)', 'Anak (5-12)', 'Remaja (13-17)', 'Dewasa (18-59)', 'Lansia (60+)'];
        $kelompokData  = array_map(fn($k) => (int)($kelompokRaw[$k] ?? 0), $kelompokOrder);

        $pekerjaanRaw = Penduduk::select('pekerjaan', DB::raw('count(*) as total'))
            ->whereNotNull('pekerjaan')->where('pekerjaan', '!=', '')
            ->groupBy('pekerjaan')->orderByDesc('total')->take(8)
            ->pluck('total', 'pekerjaan');

        $statusPerkawinanRaw = Penduduk::select('status_perkawinan', DB::raw('count(*) as total'))
            ->whereNotNull('status_perkawinan')
            ->groupBy('status_perkawinan')->pluck('total', 'status_perkawinan');
        $spkOrder  = ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'];
        $spkLabel  = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
        $spkData   = array_map(fn($k) => (int)($statusPerkawinanRaw[$k] ?? 0), $spkOrder);

        $agamaRaw = Penduduk::select('agama', DB::raw('count(*) as total'))
            ->whereNotNull('agama')->where('agama', '!=', '')
            ->groupBy('agama')->orderByDesc('total')
            ->pluck('total', 'agama');

        // ── APBDes ──────────────────────────────────────────────────────────────
        $apbdesList = Apbdes::with('pos')->orderBy('tahun')->get();
        $apbdesData = $apbdesList->map(fn($a) => [
            'tahun'                => $a->tahun,
            'anggaran_pendapatan'  => $a->totalAnggaran('pendapatan'),
            'realisasi_pendapatan' => $a->totalRealisasi('pendapatan'),
            'anggaran_belanja'     => $a->totalAnggaran('belanja'),
            'realisasi_belanja'    => $a->totalRealisasi('belanja'),
        ])->values();

        // Belanja per kelompok (tahun terbaru)
        $apbdesTerbaru   = $apbdesList->last();
        $belanjaKelompok = $apbdesTerbaru
            ? $apbdesTerbaru->pos->where('jenis', 'belanja')
                ->groupBy('kelompok')
                ->map(fn($g) => round($g->sum('anggaran')))
            : collect();

        // ── Layanan Surat ────────────────────────────────────────────────────────
        $suratBulanRaw = PengajuanSurat::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
            DB::raw('count(*) as total')
        )->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
         ->groupBy('bulan')->orderBy('bulan')
         ->pluck('total', 'bulan');

        $suratBulanLabels = [];
        $suratBulanData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $suratBulanLabels[] = now()->subMonths($i)->locale('id')->translatedFormat('M Y');
            $suratBulanData[]   = (int)($suratBulanRaw[$key] ?? 0);
        }

        $suratPerJenis = PengajuanSurat::select('jenis_surat', DB::raw('count(*) as total'))
            ->groupBy('jenis_surat')->orderByDesc('total')->take(8)
            ->pluck('total', 'jenis_surat');

        // ── Konten ──────────────────────────────────────────────────────────────
        $totalBerita     = Berita::where('status', 'diterbitkan')->count();
        $totalArsip      = Arsip::where('status', 'publish')->count();
        $totalPengumuman = Pengumuman::where('status', 'terbit')->count();

        $beritaBulanRaw = Berita::select(
            DB::raw("DATE_FORMAT(published_at, '%Y-%m') as bulan"),
            DB::raw('count(*) as total')
        )->where('status', 'diterbitkan')
         ->where('published_at', '>=', now()->subMonths(11)->startOfMonth())
         ->groupBy('bulan')->orderBy('bulan')
         ->pluck('total', 'bulan');

        $beritaBulanData = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $beritaBulanData[] = (int)($beritaBulanRaw[$key] ?? 0);
        }

        $beritaPerKategori = Berita::select('kategori_id', DB::raw('count(*) as total'))
            ->where('status', 'diterbitkan')->whereNotNull('kategori_id')
            ->with('kategori')->groupBy('kategori_id')->get()
            ->pluck('total', 'kategori.nama');

        return view('public.statistik', compact(
            'grafikAktif',
            'desaInfo',
            'totalPenduduk', 'totalKK',
            'jenisKelamin',
            'pendidikanLabel', 'pendidikanData',
            'kelompokOrder', 'kelompokData',
            'pekerjaanRaw',
            'spkLabel', 'spkData',
            'agamaRaw',
            'apbdesData', 'belanjaKelompok', 'apbdesTerbaru',
            'suratBulanLabels', 'suratBulanData', 'suratPerJenis',
            'totalBerita', 'totalArsip', 'totalPengumuman',
            'suratBulanLabels', 'beritaBulanData', 'beritaPerKategori'
        ));
    }

    public function profilDesa()
    {
        $desaInfo = Setting::forGroup('desa.');
        $pejabat  = Pejabat::with(['jabatan', 'periode'])->orderBy('id')->get();

        return view('public.profil-desa', compact('desaInfo', 'pejabat'));
    }

    public function pasarDesa(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');

        $produk = Produk::with('penduduk')
            ->where('status', 'aktif')
            ->when($q, fn($query) => $query->where('nama', 'like', "%{$q}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $desaInfo = Setting::forGroup('desa.');

        return view('public.pasar-desa', compact('produk', 'q', 'desaInfo'));
    }

    public function idm()
    {
        $rows          = IdmData::orderBy('tahun')->get();
        $latest        = $rows->last();
        $indikatorDefs = IdmData::indikatorDefs();
        $desaInfo      = Setting::forGroup('desa.');

        return view('public.idm', compact('rows', 'latest', 'indikatorDefs', 'desaInfo'));
    }

    public function pengumuman()
    {
        $desaInfo    = Setting::forGroup('desa.');
        $pengumumans = Pengumuman::where('status', 'terbit')
                                 ->latest('published_at')
                                 ->paginate(12);

        return view('public.pengumuman', compact('desaInfo', 'pengumumans'));
    }

    public function pengumumanShow(int $id)
    {
        $p        = Pengumuman::where('status', 'terbit')->findOrFail($id);
        $related  = Pengumuman::where('status', 'terbit')
                              ->where('id', '!=', $id)
                              ->latest('published_at')
                              ->take(4)
                              ->get();
        $desaInfo = Setting::forGroup('desa.');

        return view('public.pengumuman-show', compact('p', 'related', 'desaInfo'));
    }

    public function portal()
    {
        /** @var User $user */
        $user = Auth::user();

        $desa    = Setting::forGroup('desa.');
        $berita  = Berita::where('status', 'terbit')
                         ->latest('published_at')
                         ->take(10)
                         ->get();
        $layanan = JenisSurat::aktif()
                             ->where('dapat_dibuat_masyarakat', true)
                             ->orderBy('urutan')
                             ->orderBy('nama')
                             ->get();
        $pejabat        = Pejabat::with(['jabatan', 'periode'])->orderBy('id')->get();
        $jumlahPenduduk = Penduduk::count();
        $jumlahKK       = KartuKeluarga::count();

        $penduduk = $user->penduduk_id
            ? Penduduk::with('kartuKeluarga')->find($user->penduduk_id)
            : null;

        $riwayat = $user->penduduk_id
            ? PengajuanSurat::where('penduduk_id', $user->penduduk_id)
                             ->latest()
                             ->get()
            : collect();

        return view('mobile.home', compact(
            'user', 'desa', 'berita', 'layanan', 'pejabat',
            'jumlahPenduduk', 'jumlahKK', 'penduduk', 'riwayat'
        ));
    }
}
