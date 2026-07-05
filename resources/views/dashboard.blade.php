@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub')Ringkasan data {{ $desaNama }}@endsection

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endsection

@section('content')

{{-- ── Stat cards utama ──────────────────────────────────────────── --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

  <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Penduduk</span>
      <span class="grid place-items-center w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
        <i class="ti ti-users text-lg"></i>
      </span>
    </div>
    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($statPenduduk) }}</div>
    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ number_format($statKK) }} kartu keluarga</div>
    <a href="{{ route('admin.penduduk.index') }}" class="mt-2 text-xs text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1">
      Lihat data <i class="ti ti-arrow-right text-[11px]"></i>
    </a>
  </div>

  <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Surat Bulan Ini</span>
      <span class="grid place-items-center w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
        <i class="ti ti-mail text-lg"></i>
      </span>
    </div>
    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($statSuratBulanIni) }}</div>
    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ number_format($statSuratTotal) }} total keseluruhan</div>
    <a href="{{ route('admin.layanan-surat.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
      Lihat pengajuan <i class="ti ti-arrow-right text-[11px]"></i>
    </a>
  </div>

  <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Laporan Warga</span>
      <span class="grid place-items-center w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
        <i class="ti ti-message-report text-lg"></i>
      </span>
    </div>
    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($statLaporanTotal) }}</div>
    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
      @if($statLaporanPending > 0)
        <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $statLaporanPending }} menunggu tindak lanjut</span>
      @else
        Semua laporan sudah ditangani
      @endif
    </div>
    <a href="{{ route('admin.laporan.index') }}" class="mt-2 text-xs text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-1">
      Lihat laporan <i class="ti ti-arrow-right text-[11px]"></i>
    </a>
  </div>

  <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Konten Publik</span>
      <span class="grid place-items-center w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400">
        <i class="ti ti-news text-lg"></i>
      </span>
    </div>
    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($statBerita + $statPengumuman) }}</div>
    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
      {{ $statBerita }} berita · {{ $statPengumuman }} pengumuman
    </div>
    <a href="{{ route('admin.berita.index') }}" class="mt-2 text-xs text-violet-600 dark:text-violet-400 hover:underline inline-flex items-center gap-1">
      Kelola konten <i class="ti ti-arrow-right text-[11px]"></i>
    </a>
  </div>

</div>

{{-- ── Banner aksi mendesak ──────────────────────────────────────── --}}
@php $totalPending = $statSuratPending + $statSuratTtd + $statLaporanPending + $statPenjualPending; @endphp
@if($totalPending > 0)
<div class="flex flex-wrap gap-2 p-4 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30">
  <div class="flex items-center gap-2 flex-1 min-w-0">
    <i class="ti ti-alert-circle text-amber-600 dark:text-amber-400 text-lg flex-shrink-0"></i>
    <span class="text-sm font-medium text-amber-800 dark:text-amber-300">Ada item yang perlu perhatian:</span>
  </div>
  <div class="flex flex-wrap gap-2">
    @if($statSuratPending > 0)
      <a href="{{ route('admin.layanan-surat.index', ['status' => 'diajukan']) }}"
         class="inline-flex items-center gap-1.5 px-3 h-7 rounded-full bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-500/40 text-xs font-semibold text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors">
        <i class="ti ti-mail"></i> {{ $statSuratPending }} surat diajukan
      </a>
    @endif
    @if($statSuratTtd > 0)
      <a href="{{ route('admin.layanan-surat.index', ['status' => 'menunggu_ttd']) }}"
         class="inline-flex items-center gap-1.5 px-3 h-7 rounded-full bg-white dark:bg-slate-900 border border-violet-300 dark:border-violet-500/40 text-xs font-semibold text-violet-700 dark:text-violet-400 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-colors">
        <i class="ti ti-pen"></i> {{ $statSuratTtd }} menunggu TTD
      </a>
    @endif
    @if($statLaporanPending > 0)
      <a href="{{ route('admin.laporan.index', ['status' => 'menunggu']) }}"
         class="inline-flex items-center gap-1.5 px-3 h-7 rounded-full bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-500/40 text-xs font-semibold text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors">
        <i class="ti ti-message-report"></i> {{ $statLaporanPending }} laporan baru
      </a>
    @endif
    @if($statPenjualPending > 0)
      <a href="{{ route('admin.penjual.index', ['status' => 'menunggu']) }}"
         class="inline-flex items-center gap-1.5 px-3 h-7 rounded-full bg-white dark:bg-slate-900 border border-blue-300 dark:border-blue-500/40 text-xs font-semibold text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
        <i class="ti ti-users-group"></i> {{ $statPenjualPending }} penjual baru
      </a>
    @endif
  </div>
</div>
@endif

{{-- ── Grafik ────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

  {{-- Surat per bulan (bar) --}}
  <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="font-semibold text-sm text-slate-800 dark:text-white">Pengajuan Surat</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">6 bulan terakhir</p>
      </div>
      <span class="grid place-items-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
        <i class="ti ti-chart-bar text-base"></i>
      </span>
    </div>
    <div class="h-52">
      <canvas id="chartSurat"></canvas>
    </div>
  </div>

  {{-- Status surat + jenis kelamin (doughnut) --}}
  <div class="flex flex-col gap-4">

    {{-- Status surat --}}
    <div class="flex-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
      <h2 class="font-semibold text-sm text-slate-800 dark:text-white mb-1">Status Surat</h2>
      <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Distribusi keseluruhan</p>
      @if(array_sum($chartStatusData) > 0)
        <div class="h-36 flex items-center justify-center">
          <canvas id="chartStatus"></canvas>
        </div>
      @else
        <div class="h-36 flex items-center justify-center text-slate-400 dark:text-slate-600 text-sm text-center">
          <div><i class="ti ti-mail-off text-3xl block mb-1"></i>Belum ada data</div>
        </div>
      @endif
    </div>

    {{-- Jenis kelamin penduduk --}}
    <div class="flex-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
      <h2 class="font-semibold text-sm text-slate-800 dark:text-white mb-1">Jenis Kelamin</h2>
      <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Distribusi penduduk</p>
      @if($chartJkLaki + $chartJkPerempuan > 0)
        <div class="h-36 flex items-center justify-center">
          <canvas id="chartJk"></canvas>
        </div>
      @else
        <div class="h-36 flex items-center justify-center text-slate-400 dark:text-slate-600 text-sm text-center">
          <div><i class="ti ti-users-group text-3xl block mb-1"></i>Belum ada data</div>
        </div>
      @endif
    </div>
  </div>

</div>

{{-- Laporan warga per bulan --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="font-semibold text-sm text-slate-800 dark:text-white">Laporan Warga</h2>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">6 bulan terakhir</p>
    </div>
    <span class="grid place-items-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
      <i class="ti ti-chart-line text-base"></i>
    </span>
  </div>
  <div class="h-40">
    <canvas id="chartLaporan"></canvas>
  </div>
</div>

{{-- ── Aktivitas terbaru ─────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-sm text-slate-800 dark:text-white">Surat Terbaru</h2>
      <a href="{{ route('admin.layanan-surat.index') }}"
         class="text-xs text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
        Lihat semua <i class="ti ti-arrow-right text-[11px]"></i>
      </a>
    </div>
    @if($recentSurat->isEmpty())
      <div class="py-10 text-center text-slate-400 dark:text-slate-500 text-sm">
        <i class="ti ti-mail-off text-3xl block mb-2"></i>Belum ada pengajuan surat
      </div>
    @else
      <div class="divide-y divide-slate-50 dark:divide-slate-800">
        @foreach($recentSurat as $surat)
          @php
            $ss = match($surat->status) {
              'diajukan'     => ['Diajukan',    'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'],
              'menunggu_ttd' => ['Menunggu TTD','bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400'],
              'disetujui'    => ['Disetujui',   'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
              'selesai'      => ['Selesai',     'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'],
              'ditolak'      => ['Ditolak',     'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400'],
              default        => [ucfirst($surat->status), 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'],
            };
          @endphp
          <div class="flex items-center gap-3 px-5 py-3">
            <div class="grid place-items-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex-shrink-0">
              <i class="ti ti-file-text text-slate-500 dark:text-slate-400 text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-medium text-slate-800 dark:text-white truncate">
                {{ $surat->penduduk?->nama_lengkap ?? '—' }}
              </div>
              <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $surat->jenis_surat }}</div>
            </div>
            <div class="flex-shrink-0 flex flex-col items-end gap-1">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $ss[1] }}">{{ $ss[0] }}</span>
              <span class="text-[10px] text-slate-400">{{ $surat->created_at->diffForHumans() }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-sm text-slate-800 dark:text-white">Laporan Warga Terbaru</h2>
      <a href="{{ route('admin.laporan.index') }}"
         class="text-xs text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-1">
        Lihat semua <i class="ti ti-arrow-right text-[11px]"></i>
      </a>
    </div>
    @if($recentLaporan->isEmpty())
      <div class="py-10 text-center text-slate-400 dark:text-slate-500 text-sm">
        <i class="ti ti-message-off text-3xl block mb-2"></i>Belum ada laporan masuk
      </div>
    @else
      <div class="divide-y divide-slate-50 dark:divide-slate-800">
        @foreach($recentLaporan as $laporan)
          @php
            $sl = match($laporan->status) {
              'menunggu' => ['Menunggu', 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'],
              'diproses' => ['Diproses', 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
              'selesai'  => ['Selesai',  'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'],
              default    => [ucfirst($laporan->status), 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'],
            };
          @endphp
          <div class="flex items-center gap-3 px-5 py-3">
            <div class="grid place-items-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex-shrink-0">
              <i class="ti ti-message-report text-slate-500 dark:text-slate-400 text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ $laporan->judul }}</div>
              <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                {{ $laporan->user?->name ?? 'Anonim' }} · {{ $laporan->kategori }}
              </div>
            </div>
            <div class="flex-shrink-0 flex flex-col items-end gap-1">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $sl[1] }}">{{ $sl[0] }}</span>
              <span class="text-[10px] text-slate-400">{{ $laporan->created_at->diffForHumans() }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>

{{-- ── Statistik tambahan ────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
  @foreach([
    ['Kartu Keluarga',  $statKK,         'ti-home',           'text-sky-600 dark:text-sky-400',     'bg-sky-50 dark:bg-sky-500/10',     route('admin.kk.index')],
    ['Data Balita',     $statBalita,     'ti-baby-carriage',  'text-pink-600 dark:text-pink-400',   'bg-pink-50 dark:bg-pink-500/10',   route('admin.kesehatan.balita.index')],
    ['Ibu Hamil',       $statIbuHamil,   'ti-heart-plus',     'text-rose-600 dark:text-rose-400',   'bg-rose-50 dark:bg-rose-500/10',   route('admin.kesehatan.ibuHamil.index')],
    ['Produk Aktif',    $statProdukAktif,'ti-shopping-bag',   'text-teal-600 dark:text-teal-400',   'bg-teal-50 dark:bg-teal-500/10',   route('admin.produk.index')],
    ['Pengguna Sistem', $statUsers,      'ti-user-circle',    'text-indigo-600 dark:text-indigo-400','bg-indigo-50 dark:bg-indigo-500/10',route('admin.users.index')],
    ['Role & Akses',    $statRoles,      'ti-shield-lock',    'text-slate-600 dark:text-slate-400', 'bg-slate-100 dark:bg-slate-800',    route('admin.roles.index')],
  ] as [$label, $val, $icon, $iconColor, $iconBg, $link])
  <a href="{{ $link }}"
     class="flex flex-col gap-2 p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-brand-300 dark:hover:border-brand-600 transition-colors group">
    <span class="grid place-items-center w-8 h-8 rounded-lg {{ $iconBg }} flex-shrink-0">
      <i class="ti {{ $icon }} text-base {{ $iconColor }}"></i>
    </span>
    <div>
      <div class="text-xl font-bold text-slate-800 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
        {{ number_format($val) }}
      </div>
      <div class="text-xs text-slate-500 dark:text-slate-400 leading-tight">{{ $label }}</div>
    </div>
  </a>
  @endforeach
</div>

@endsection

@section('scripts')
<script>
(function () {
  const isDark = document.documentElement.classList.contains('dark');
  const mutedColor  = isDark ? '#94a3b8' : '#64748b';
  const gridColor   = isDark ? '#1e293b' : '#f1f5f9';
  const WHITE       = isDark ? '#0f172a' : '#ffffff';

  Chart.defaults.font.family = "'Inter','Helvetica Neue',sans-serif";
  Chart.defaults.font.size   = 12;
  Chart.defaults.color       = mutedColor;

  const GREEN = '#0C7C46', BLUE = '#0E63A8', AMBER = '#C99A2C', ROSE = '#E05A5A', VIOLET = '#7C3AED';

  const baseGrid = {
    color: gridColor,
    drawBorder: false,
  };
  const baseTick = { color: mutedColor };

  // ── Bar chart: Surat per bulan ──────────────────────────────────
  new Chart(document.getElementById('chartSurat'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartSuratLabels) !!},
      datasets: [{
        label: 'Pengajuan Surat',
        data: {!! json_encode($chartSuratData) !!},
        backgroundColor: GREEN + 'cc',
        borderRadius: 8,
        borderSkipped: false,
        hoverBackgroundColor: GREEN,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: WHITE,
          titleColor: isDark ? '#f1f5f9' : '#0f172a',
          bodyColor: mutedColor,
          borderColor: gridColor,
          borderWidth: 1,
          padding: 10,
          callbacks: { label: ctx => ' ' + ctx.parsed.y + ' pengajuan' }
        },
      },
      scales: {
        x: { grid: { ...baseGrid, display: false }, ticks: baseTick },
        y: {
          grid: baseGrid,
          ticks: { ...baseTick, stepSize: 1, precision: 0 },
          beginAtZero: true,
        }
      }
    }
  });

  // ── Doughnut: Status surat ──────────────────────────────────────
  @if(array_sum($chartStatusData) > 0)
  new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($chartStatusLabels) !!},
      datasets: [{
        data: {!! json_encode($chartStatusData) !!},
        backgroundColor: {!! json_encode($chartStatusColors) !!},
        borderWidth: 2,
        borderColor: WHITE,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 10, padding: 10, font: { size: 11 } }
        },
        tooltip: {
          backgroundColor: WHITE,
          titleColor: isDark ? '#f1f5f9' : '#0f172a',
          bodyColor: mutedColor,
          borderColor: gridColor,
          borderWidth: 1,
          padding: 8,
        }
      }
    }
  });
  @endif

  // ── Doughnut: Jenis kelamin ─────────────────────────────────────
  @if($chartJkLaki + $chartJkPerempuan > 0)
  new Chart(document.getElementById('chartJk'), {
    type: 'doughnut',
    data: {
      labels: ['Laki-laki', 'Perempuan'],
      datasets: [{
        data: [{{ $chartJkLaki }}, {{ $chartJkPerempuan }}],
        backgroundColor: [BLUE, '#ec4899'],
        borderWidth: 2,
        borderColor: WHITE,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 10, padding: 10, font: { size: 11 } }
        },
        tooltip: {
          backgroundColor: WHITE,
          titleColor: isDark ? '#f1f5f9' : '#0f172a',
          bodyColor: mutedColor,
          borderColor: gridColor,
          borderWidth: 1,
          padding: 8,
        }
      }
    }
  });
  @endif

  // ── Line chart: Laporan per bulan ───────────────────────────────
  new Chart(document.getElementById('chartLaporan'), {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLaporanLabels) !!},
      datasets: [{
        label: 'Laporan Masuk',
        data: {!! json_encode($chartLaporanData) !!},
        borderColor: AMBER,
        backgroundColor: AMBER + '22',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: AMBER,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: WHITE,
          titleColor: isDark ? '#f1f5f9' : '#0f172a',
          bodyColor: mutedColor,
          borderColor: gridColor,
          borderWidth: 1,
          padding: 10,
          callbacks: { label: ctx => ' ' + ctx.parsed.y + ' laporan' }
        },
      },
      scales: {
        x: { grid: { ...baseGrid, display: false }, ticks: baseTick },
        y: {
          grid: baseGrid,
          ticks: { ...baseTick, stepSize: 1, precision: 0 },
          beginAtZero: true,
        }
      }
    }
  });

})();
</script>
@endsection
