<!DOCTYPE html>
<html lang="id" class="">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') — {{ $desaNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.7.0/dist/tabler-icons.min.css" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
@yield('head')
{{-- Anti-FOUC: restore dark mode & brand theme before render --}}
<script>
(function(){
  try {
    var mode = localStorage.getItem('villageMode');
    if (mode === 'dark' || (!mode && matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
    var theme = localStorage.getItem('villageTheme');
    var themes = {
      hijau:  {50:'236 253 245',100:'209 250 229',500:'16 185 129',600:'5 150 105',700:'4 120 87'},
      biru:   {50:'239 246 255',100:'219 234 254',500:'59 130 246', 600:'37 99 235', 700:'29 78 216'},
      teal:   {50:'240 253 250',100:'204 251 241',500:'20 184 166', 600:'13 148 136',700:'15 118 110'},
      ungu:   {50:'245 243 255',100:'237 233 254',500:'139 92 246', 600:'124 58 237',700:'109 40 217'},
      merah:  {50:'254 242 242',100:'254 226 226',500:'239 68 68',  600:'220 38 38', 700:'185 28 28'},
      amber:  {50:'255 251 235',100:'254 243 199',500:'245 158 11', 600:'217 119 6', 700:'180 83 9'},
    };
    var t = themes[theme] || themes.hijau;
    var r = document.documentElement.style;
    r.setProperty('--brand-50',  t[50]);
    r.setProperty('--brand-100', t[100]);
    r.setProperty('--brand-500', t[500]);
    r.setProperty('--brand-600', t[600]);
    r.setProperty('--brand-700', t[700]);
  } catch(e){}
}());
</script>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 antialiased">

<div class="flex min-h-screen">

  {{-- ── Sidebar ─────────────────────────────────────── --}}
  <aside id="sidebar"
    class="fixed lg:sticky lg:top-0 lg:h-screen inset-y-0 left-0 z-40 w-64 -translate-x-full lg:translate-x-0 transition-transform duration-300
           bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col flex-shrink-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 h-16 border-b border-slate-200 dark:border-slate-800 flex-shrink-0">
      <div class="grid place-items-center w-9 h-9 rounded-lg flex-shrink-0 overflow-hidden
                  {{ $logoUrl ? 'bg-white border border-slate-200 dark:border-slate-700' : 'bg-brand-600 text-white' }}">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="{{ $desaNama }}" class="w-full h-full object-contain p-0.5">
        @else
          <i class="ti ti-building-community"></i>
        @endif
      </div>
      <div class="leading-tight min-w-0">
        <div class="font-semibold text-sm truncate">{{ $desaNama }}</div>
        <div class="text-xs text-slate-400">Panel Admin</div>
      </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm overflow-y-auto">

      {{-- Utama --}}
      <div class="px-3 pt-2 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Utama</div>
      <a href="{{ route('dashboard') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('dashboard') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-layout-dashboard"></i> Dashboard
      </a>

      @php
        $u = Auth::user();
        $isAdmin = $u->hasRole('administrator');
        // Shorthand helper — true jika admin atau punya permission
        $can = fn(string $p) => $isAdmin || $u->hasPermission($p);
      @endphp

      {{-- Kependudukan --}}
      @if($can('lihat.penduduk') || $can('lihat.kartu-keluarga'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Kependudukan</div>
      @if($can('lihat.penduduk'))
      <a href="{{ route('admin.penduduk.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.penduduk.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-users"></i> Data Penduduk
      </a>
      @endif
      @if($can('lihat.kartu-keluarga'))
      <a href="{{ route('admin.kk.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.kk.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-home"></i> Kartu Keluarga
      </a>
      @endif
      @endif

      {{-- Publik --}}
      @if($can('lihat.berita') || $can('lihat.pengumuman') || $can('lihat.page') || $can('lihat.arsip'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Publik</div>
      @if($can('lihat.berita'))
      <a href="{{ route('admin.berita.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.berita.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-news"></i> Berita
      </a>
      @endif
      @if($can('lihat.pengumuman'))
      <a href="{{ route('admin.pengumuman.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.pengumuman.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-speakerphone"></i> Pengumuman
      </a>
      @endif
      @if($can('lihat.page'))
      <a href="{{ route('admin.page.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.page.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-file-text"></i> Page
      </a>
      @endif
      @if($can('lihat.arsip'))
      <a href="{{ route('admin.arsip.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.arsip.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-archive"></i> Arsip
      </a>
      @endif
      @endif

      {{-- Kesehatan --}}
      @if($can('lihat.posyandu') || $can('lihat.balita') || $can('lihat.ibu-hamil'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Kesehatan</div>
      @if($can('lihat.posyandu'))
      <a href="{{ route('admin.kesehatan.posyandu.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.kesehatan.posyandu.index') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-building-hospital"></i> Posyandu
      </a>
      <a href="{{ route('admin.kesehatan.kegiatan.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.kesehatan.kegiatan.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-clipboard-list"></i> Kegiatan Posyandu
      </a>
      @endif
      @if($can('lihat.balita'))
      <a href="{{ route('admin.kesehatan.balita.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.kesehatan.balita.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-baby-carriage"></i> Data Balita
      </a>
      @endif
      @if($can('lihat.ibu-hamil'))
      <a href="{{ route('admin.kesehatan.ibuHamil.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.kesehatan.ibuHamil.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-heart-plus"></i> Ibu Hamil
      </a>
      @endif
      @endif

      {{-- Kecerdasan Buatan --}}
      @if($can('akses.ai'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Kecerdasan Buatan</div>
      <a href="{{ route('admin.ai.sambutan') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.ai.sambutan') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-microphone"></i> Kata Sambutan
      </a>
      <a href="{{ route('admin.ai.perdes') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.ai.perdes') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-file-certificate"></i> Peraturan Desa
      </a>
      <a href="{{ route('admin.ai.surat') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.ai.surat') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-mail"></i> Konten Surat
      </a>
      <a href="{{ route('admin.ai.perkades') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.ai.perkades') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-file-check"></i> Peraturan Kades
      </a>
      <a href="{{ route('admin.ai.prediksi-stunting') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.ai.prediksi-stunting') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-brain"></i> Prediksi Stunting
      </a>
      @endif

      {{-- Statistik Desa --}}
      @if($can('lihat.idm') || $can('lihat.apbdes'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Statistik Desa</div>
      @if($can('lihat.idm'))
      <a href="{{ route('admin.idm.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.idm.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-chart-bar"></i> IDM
      </a>
      @endif
      @if($can('lihat.apbdes'))
      <a href="{{ route('admin.apbdes.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.apbdes.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-cash"></i> APBDes
      </a>
      @endif
      @endif

      {{-- Peta Desa --}}
      @if($can('lihat.peta'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Peta Desa</div>
      <a href="{{ route('admin.peta.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.peta.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-map"></i> Peta Desa (GIS)
      </a>
      @endif

      {{-- Pelaporan Warga --}}
      @php
        try { $pendingLaporan = \App\Models\LaporanWarga::where('status','menunggu')->count(); }
        catch (\Exception $e) { $pendingLaporan = 0; }
      @endphp
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Pelaporan</div>
      <a href="{{ route('admin.laporan.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.laporan.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-message-report"></i>
        <span class="flex-1 min-w-0 truncate">Laporan Warga</span>
        @if($pendingLaporan > 0)
          <span class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500 text-white">{{ $pendingLaporan }}</span>
        @endif
      </a>

      {{-- Bantuan Sosial --}}
      @if($can('lihat.bansos'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Bansos</div>
      <a href="{{ route('admin.bansos.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.bansos.index') || Request::routeIs('admin.bansos.show') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-heart-handshake"></i> Penerima Bansos
      </a>
      <a href="{{ route('admin.bansos.jenis.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.bansos.jenis.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-list"></i> Jenis Bansos
      </a>
      @endif

      {{-- Layanan Persuratan --}}
      @php
        try { $pendingLayanan = \App\Models\PengajuanSurat::where('status','diajukan')->count(); }
        catch (\Exception $e) { $pendingLayanan = 0; }
        try { $menungguKades = \App\Models\PengajuanSurat::where('status','menunggu_ttd')->count(); }
        catch (\Exception $e) { $menungguKades = 0; }
      @endphp
      @if($can('lihat.layanan-surat') || $can('lihat.surat-jenis') || $can('lihat.surat-pengaturan'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Layanan Persuratan</div>
      @if($can('lihat.layanan-surat'))
      <a href="{{ route('admin.layanan-surat.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.layanan-surat.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-mail"></i>
        <span class="flex-1 min-w-0 truncate">Layanan Surat</span>
        @if($pendingLayanan > 0)
          <span class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500 text-white">{{ $pendingLayanan }}</span>
        @elseif($menungguKades > 0 && $can('setujui.layanan-surat'))
          <span class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-violet-500 text-white">{{ $menungguKades }}</span>
        @endif
      </a>
      @endif
      @if($can('lihat.surat-jenis'))
      <a href="{{ route('admin.surat.jenis.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.surat.jenis.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-list-details"></i>
        <span class="flex-1 min-w-0 truncate">Jenis Surat</span>
      </a>
      @endif
      @if($can('lihat.surat-pengaturan'))
      <a href="{{ route('admin.surat.pengaturan.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.surat.pengaturan.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-settings-2"></i>
        <span class="flex-1 min-w-0 truncate">Pengaturan Surat</span>
      </a>
      @endif
      @endif

      {{-- Pasar Desa --}}
      @if($can('lihat.pasar-desa'))
      @php
        try { $pendingPenjual = \App\Models\PengajuanPenjual::where('status','menunggu')->count(); }
        catch (\Exception $e) { $pendingPenjual = 0; }
      @endphp
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Pasar Desa</div>
      <a href="{{ route('admin.penjual.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.penjual.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-users-group"></i>
        <span class="flex-1 min-w-0 truncate">Penjual</span>
        @if($pendingPenjual > 0)
          <span class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500 text-white">{{ $pendingPenjual }}</span>
        @endif
      </a>
      <a href="{{ route('admin.produk.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.produk.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-shopping-bag"></i>
        <span class="flex-1 min-w-0 truncate">Produk</span>
      </a>
      @endif

      {{-- Master Data --}}
      @if($can('lihat.kategori-arsip') || $can('lihat.kategori') || $can('lihat.jabatan') || $can('lihat.periode') || $can('lihat.pejabat'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Master Data</div>
      @if($can('lihat.kategori-arsip'))
      <a href="{{ route('admin.master.kategoriArsip.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.master.kategoriArsip.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-folder"></i> Kategori Arsip
      </a>
      @endif
      @if($can('lihat.kategori'))
      <a href="{{ route('admin.master.kategori.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.master.kategori.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-tag"></i> Kategori Berita
      </a>
      @endif
      @if($can('lihat.jabatan'))
      <a href="{{ route('admin.master.jabatan.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.master.jabatan.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-id-badge"></i> Jabatan
      </a>
      @endif
      @if($can('lihat.periode'))
      <a href="{{ route('admin.master.periode.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.master.periode.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-calendar-time"></i> Periode
      </a>
      @endif
      @if($can('lihat.pejabat'))
      <a href="{{ route('admin.master.pejabat.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.master.pejabat.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-user-star"></i> Pejabat Desa
      </a>
      @endif
      @endif

      {{-- Manajemen Pengguna --}}
      @if($can('lihat.pengguna') || $can('lihat.role'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Manajemen Pengguna</div>
      @if($can('lihat.pengguna'))
      <a href="{{ route('admin.users.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.users.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-user-circle"></i> Pengguna
      </a>
      @endif
      @if($can('lihat.role'))
      <a href="{{ route('admin.roles.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.roles.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-shield-lock"></i> Role &amp; Akses
      </a>
      @endif
      @endif

      {{-- Panduan --}}
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Bantuan</div>
      <a href="{{ route('admin.panduan') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.panduan') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-help-circle"></i> Panduan Penggunaan
      </a>

      {{-- Sistem --}}
      @if($can('lihat.pengaturan'))
      <div class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Sistem</div>
      <a href="{{ route('admin.settings.index') }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ Request::routeIs('admin.settings.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-100 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
        <i class="ti ti-settings"></i> Pengaturan
      </a>
      @endif

    </nav>

    {{-- User footer --}}
    <div class="flex items-center gap-3 px-4 py-4 border-t border-slate-200 dark:border-slate-800 flex-shrink-0">
      <div class="grid place-items-center w-9 h-9 rounded-full bg-brand-600 text-white text-xs font-semibold flex-shrink-0">
        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
      </div>
      <div class="leading-tight flex-1 min-w-0">
        <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
        <div class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</div>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" title="Keluar"
          class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-400 hover:text-rose-600 transition-colors">
          <i class="ti ti-logout text-base"></i>
        </button>
      </form>
    </div>
  </aside>

  {{-- Mobile backdrop --}}
  <div id="backdrop" class="fixed inset-0 z-30 bg-black/40 lg:hidden hidden"></div>

  {{-- ── Main area ─────────────────────────────────── --}}
  <div class="flex-1 min-w-0 flex flex-col">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 h-16 flex items-center gap-3 px-4 sm:px-6
                   bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200 dark:border-slate-800 flex-shrink-0">

      <button id="menuBtn"
        class="lg:hidden grid place-items-center w-10 h-10 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-menu-2"></i>
      </button>

      {{-- Page title --}}
      <div class="flex-1 min-w-0 hidden sm:block">
        <h1 class="text-base font-semibold leading-tight">@yield('page-title', 'Dashboard')</h1>
        @hasSection('page-sub')
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">@yield('page-sub')</p>
        @endif
      </div>

      {{-- Search --}}
      <div class="hidden sm:flex items-center gap-2 flex-1 max-w-xs px-3 h-9 rounded-lg bg-slate-100 dark:bg-slate-800">
        <i class="ti ti-search text-slate-400 text-sm"></i>
        <input type="text" placeholder="Cari…"
               class="bg-transparent outline-none text-sm w-full placeholder:text-slate-400 border-0 p-0 focus:ring-0">
      </div>

      <div class="flex items-center gap-1 ml-auto">

        {{-- Notification --}}
        <div class="relative" id="notifWrap">
          <button id="notifBtn" onclick="toggleNotifDd()"
            class="relative grid place-items-center w-10 h-10 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i class="ti ti-bell text-slate-600 dark:text-slate-300"></i>
            <span id="notifBadge" style="display:none"
              class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-4 text-center"></span>
          </button>
          {{-- Dropdown --}}
          <div id="notifDd"
            class="hidden absolute right-0 mt-2 w-80 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl z-30 overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
              <span class="text-sm font-bold text-slate-800 dark:text-white">Notifikasi</span>
              <a href="{{ route('notifications.read-all') }}"
                 onclick="event.preventDefault();fetch(this.href,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>{closeNotifDd();loadNotifCount()})"
                 class="text-xs text-brand-600 dark:text-brand-400 font-semibold hover:underline">
                Tandai semua dibaca
              </a>
            </div>
            <div id="notifList" class="max-h-72 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-800">
              <div class="py-8 text-center text-slate-400 text-sm" id="notifEmpty">Memuat...</div>
            </div>
            <div class="border-t border-slate-100 dark:border-slate-800 px-4 py-2.5 text-center">
              <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                Lihat semua notifikasi
              </a>
            </div>
          </div>
        </div>

        {{-- User avatar --}}
        <div class="relative">
          <button id="avatarBtn"
            class="grid place-items-center w-9 h-9 rounded-full bg-brand-600 text-white text-xs font-semibold ml-1 hover:opacity-90 transition-opacity">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
          </button>
          <div id="userDd"
            class="hidden absolute right-0 mt-2 w-52 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl z-30 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
              <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-user text-slate-400"></i> Profil Saya
            </a>
            <div class="border-t border-slate-100 dark:border-slate-800">
              <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit"
                  class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                  <i class="ti ti-logout"></i> Keluar
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 p-4 sm:p-6 space-y-6">
      @yield('content')
    </main>
  </div>
</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>

{{-- Modal Konfirmasi Hapus (global) --}}
<div id="modalKonfirmasiHapus" style="display:none"
     class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
  <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="flex items-start gap-4 p-6">
      <div class="flex-shrink-0 w-11 h-11 rounded-full bg-rose-100 dark:bg-rose-500/15 flex items-center justify-center">
        <i class="ti ti-trash text-rose-600 dark:text-rose-400 text-xl"></i>
      </div>
      <div class="flex-1 min-w-0">
        <h3 class="font-semibold text-slate-800 dark:text-white text-base" id="konfirmasiHapusJudul">Hapus Data</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed" id="konfirmasiHapusPesan">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
      </div>
    </div>
    <div class="flex items-center gap-3 px-6 pb-6">
      <button type="button" onclick="batalHapus()"
        class="flex-1 h-10 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        Batal
      </button>
      <button type="button" onclick="doHapus()"
        class="flex-1 h-10 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2">
        <i class="ti ti-trash text-sm"></i> Hapus
      </button>
    </div>
  </div>
</div>

<script>
// ── Sidebar mobile ──
const sidebar  = document.getElementById('sidebar');
const backdrop = document.getElementById('backdrop');
document.getElementById('menuBtn').addEventListener('click', () => {
  sidebar.classList.toggle('-translate-x-full');
  backdrop.classList.toggle('hidden');
});
backdrop.addEventListener('click', () => {
  sidebar.classList.add('-translate-x-full');
  backdrop.classList.add('hidden');
});

document.addEventListener('click', e => {
  if (!e.target.closest('#userDd') && !e.target.closest('#avatarBtn'))
    document.getElementById('userDd').classList.add('hidden');
  if (!e.target.closest('#notifWrap'))
    document.getElementById('notifDd').classList.add('hidden');
});

// ── Notifikasi admin ──
const NOTIF_COUNT_URL = '{{ route("notifications.unread-count") }}';
const NOTIF_READ_URL  = (id) => `/notifications/${id}/read`;
const CSRF_TOKEN      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function toggleNotifDd() {
  const dd = document.getElementById('notifDd');
  const isHidden = dd.classList.contains('hidden');
  dd.classList.toggle('hidden');
  if (isHidden) loadNotifDropdown();
}
function closeNotifDd() {
  document.getElementById('notifDd').classList.add('hidden');
}

async function loadNotifCount() {
  try {
    const res  = await fetch(NOTIF_COUNT_URL, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    const badge = document.getElementById('notifBadge');
    if (data.count > 0) {
      badge.textContent = data.count > 99 ? '99+' : data.count;
      badge.style.display = 'block';
    } else {
      badge.style.display = 'none';
    }
  } catch {}
}

async function loadNotifDropdown() {
  const list = document.getElementById('notifList');
  list.innerHTML = '<div class="py-8 text-center text-slate-400 text-sm">Memuat...</div>';
  try {
    const res  = await fetch('{{ route("notifications.dropdown") }}', { headers: { 'Accept': 'application/json' } });
    const notifs = await res.json();
    if (!notifs.length) {
      list.innerHTML = '<div class="py-8 text-center text-slate-400 text-sm">Belum ada notifikasi</div>';
      return;
    }
    list.innerHTML = notifs.map(n => `
      <a href="${n.url || '#'}" onclick="markRead('${n.id}',event)"
         class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors ${n.read_at ? '' : 'bg-brand-50 dark:bg-brand-900/10'}">
        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm
                    ${n.read_at ? 'bg-slate-100 dark:bg-slate-700' : 'bg-brand-100 dark:bg-brand-800/40'}">
          ${n.icon}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-${n.read_at ? '500' : '700'} text-slate-800 dark:text-white leading-snug">${n.message}</p>
          <p class="text-[10px] text-slate-400 mt-0.5">${n.time}</p>
        </div>
        ${n.read_at ? '' : '<div class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0 mt-1.5"></div>'}
      </a>
    `).join('');
    loadNotifCount();
  } catch (err) {
    list.innerHTML = '<div class="py-8 text-center text-slate-400 text-sm">Gagal memuat</div>';
  }
}

async function markRead(id, event) {
  fetch(NOTIF_READ_URL(id), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
  }).catch(() => {});
}

// Load count on page load and poll every 45 seconds
loadNotifCount();
setInterval(loadNotifCount, 45000);
document.getElementById('avatarBtn').addEventListener('click', e => {
  e.stopPropagation();
  document.getElementById('userDd').classList.toggle('hidden');
});

// ── Konfirmasi Hapus ──
let _pendingHapusForm = null;
function konfirmasiHapus(formEl, pesan, judul) {
  _pendingHapusForm = formEl;
  document.getElementById('konfirmasiHapusPesan').textContent = pesan || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
  document.getElementById('konfirmasiHapusJudul').textContent = judul || 'Hapus Data';
  document.getElementById('modalKonfirmasiHapus').style.display = 'flex';
}
function batalHapus() {
  document.getElementById('modalKonfirmasiHapus').style.display = 'none';
  _pendingHapusForm = null;
}
function doHapus() {
  document.getElementById('modalKonfirmasiHapus').style.display = 'none';
  if (_pendingHapusForm) { _pendingHapusForm.submit(); _pendingHapusForm = null; }
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') batalHapus();
});

// ── Toast ──
function toast(msg, type) {
  const t   = document.getElementById('toast');
  const icons = { success:'ti-circle-check', error:'ti-alert-circle', info:'ti-info-circle' };
  const ic  = icons[type] || 'ti-circle-check';
  t.innerHTML = `<i class="ti ${ic} text-base flex-shrink-0"></i><span>${msg}</span>`;
  t.className = 'toast show' + (type ? ' ' + type : ' success');
  clearTimeout(t._t);
  t._t = setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
@yield('scripts')
<script>
// ── Auto-toast dari session flash ──
@if(session('success'))
toast(@js(session('success')), 'success');
@endif
@if(session('error'))
toast(@js(session('error')), 'error');
@endif
</script>
</body>
</html>
