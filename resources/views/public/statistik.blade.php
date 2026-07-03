@extends('layouts.public')

@section('title', 'Statistik & Data Desa — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', 'Data statistik dan informasi terbuka Pemerintah Desa ' . ($desaInfo['desa.nama'] ?? '') . ': demografi penduduk, APBDes, layanan surat, dan publikasi.')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<style>
/* ── Hero ── */
.stat-hero{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.stat-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(255,255,255,.07),transparent 60%)}
.stat-hero-inner{position:relative}
.stat-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.stat-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.stat-hero h1{font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.stat-hero p{font-size:.95rem;opacity:.82;max-width:520px}

/* ── Breadcrumb ── */
.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

/* ── Tab nav ── */
.tab-wrap{background:var(--surface);border-bottom:1px solid var(--line);position:sticky;top:69px;z-index:40}
.tab-nav{display:flex;gap:0;overflow-x:auto;scrollbar-width:none}
.tab-nav::-webkit-scrollbar{display:none}
.tab-btn{display:flex;align-items:center;gap:7px;padding:14px 20px;font-size:.84rem;font-weight:700;color:var(--muted);border-bottom:2.5px solid transparent;white-space:nowrap;cursor:pointer;background:none;border-top:none;border-left:none;border-right:none;transition:.2s;flex-shrink:0}
.tab-btn:hover{color:var(--green)}
.tab-btn.active{color:var(--green);border-bottom-color:var(--green)}

/* ── Summary cards ── */
.summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:40px 0 0}
@media(max-width:860px){.summary-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.summary-grid{grid-template-columns:1fr 1fr}}
.sum-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:8px}
.sum-card .ic{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.sum-card .val{font-size:1.9rem;font-weight:800;letter-spacing:-.03em;line-height:1}
.sum-card .lbl{font-size:.78rem;font-weight:600;color:var(--muted)}

/* ── Section ── */
.stat-section{padding:48px 0 32px}
.stat-section+.stat-section{padding-top:32px}
.section-head{margin-bottom:28px}
.section-head h2{font-size:1.35rem;font-weight:800;letter-spacing:-.02em;margin-bottom:4px}
.section-head p{font-size:.86rem;color:var(--muted)}

/* ── Chart cards ── */
.chart-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.chart-grid.cols-3{grid-template-columns:repeat(3,1fr)}
.chart-grid.cols-1{grid-template-columns:1fr}
@media(max-width:860px){.chart-grid,.chart-grid.cols-3{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.chart-grid,.chart-grid.cols-3{grid-template-columns:1fr}}
.chart-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;padding:22px}
.chart-card.span-2{grid-column:span 2}
@media(max-width:860px){.chart-card.span-2{grid-column:span 1}}
.chart-title{font-size:.9rem;font-weight:800;margin-bottom:4px;color:var(--ink)}
.chart-sub{font-size:.75rem;color:var(--muted);margin-bottom:18px}
.chart-box{position:relative}
.chart-box canvas{max-height:280px}
.chart-box.tall canvas{max-height:340px}

/* ── Empty ── */
.empty-chart{text-align:center;padding:40px 20px;color:var(--muted)}
.empty-chart svg{margin:0 auto 10px;opacity:.3;display:block}
.empty-chart p{font-size:.84rem}

.page-foot{padding:40px 0 80px}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="stat-hero">
  <div class="container stat-hero-inner">
    <div class="eyebrow">Transparansi Data</div>
    <h1>Statistik &amp; Data Desa</h1>
    <p>Data dan informasi terbuka dari Pemerintah Desa {{ $desaInfo['desa.nama'] ?? '' }}.</p>
  </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>Statistik Desa</b>
  </div>
</div>

@php
  $tabOrder = ['demografi', 'apbdes', 'surat', 'konten'];
  $firstTab = collect($tabOrder)->first(fn($t) => $grafikAktif[$t] ?? false) ?? 'demografi';
@endphp

{{-- Tab nav --}}
<div class="tab-wrap">
  <div class="container">
    <nav class="tab-nav">
      @if($grafikAktif['demografi'])
      <button class="tab-btn {{ $firstTab === 'demografi' ? 'active' : '' }}" data-tab="demografi">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Demografi Penduduk
      </button>
      @endif
      @if($grafikAktif['apbdes'])
      <button class="tab-btn {{ $firstTab === 'apbdes' ? 'active' : '' }}" data-tab="apbdes">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        APBDes &amp; Keuangan
      </button>
      @endif
      @if($grafikAktif['surat'])
      <button class="tab-btn {{ $firstTab === 'surat' ? 'active' : '' }}" data-tab="surat">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Layanan Surat
      </button>
      @endif
      @if($grafikAktif['konten'])
      <button class="tab-btn {{ $firstTab === 'konten' ? 'active' : '' }}" data-tab="konten">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2z"/><path d="M4 22a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
        Publikasi &amp; Konten
      </button>
      @endif
    </nav>
  </div>
</div>

{{-- ═══ TAB: DEMOGRAFI ═══ --}}
@if($grafikAktif['demografi'])
<div id="tab-demografi" class="tab-content" {{ $firstTab !== 'demografi' ? 'style=display:none' : '' }}>
  <div class="container">

    <div class="summary-grid">
      <div class="sum-card">
        <div class="ic" style="background:#dcfce7;color:#0C7C46">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="val">{{ number_format($totalPenduduk) }}</div>
        <div class="lbl">Total Penduduk</div>
      </div>
      <div class="sum-card">
        <div class="ic" style="background:#dbeafe;color:#1d4ed8">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
        </div>
        <div class="val">{{ number_format($totalKK) }}</div>
        <div class="lbl">Kartu Keluarga</div>
      </div>
      <div class="sum-card">
        <div class="ic" style="background:#fef9c3;color:#a16207">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
        </div>
        <div class="val">{{ number_format($jenisKelamin['L'] ?? $jenisKelamin['laki-laki'] ?? $jenisKelamin->filter(fn($v,$k) => str_contains(strtolower($k),'laki'))->sum() ?? 0) }}</div>
        <div class="lbl">Laki-laki</div>
      </div>
      <div class="sum-card">
        <div class="ic" style="background:#fce7f3;color:#be185d">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
        </div>
        <div class="val">{{ number_format($jenisKelamin['P'] ?? $jenisKelamin['perempuan'] ?? $jenisKelamin->filter(fn($v,$k) => str_contains(strtolower($k),'perempuan'))->sum() ?? 0) }}</div>
        <div class="lbl">Perempuan</div>
      </div>
    </div>

    <div class="stat-section">
      <div class="chart-grid">

        {{-- Jenis Kelamin --}}
        <div class="chart-card">
          <div class="chart-title">Jenis Kelamin</div>
          <div class="chart-sub">Komposisi penduduk berdasarkan jenis kelamin</div>
          <div class="chart-box">
            <canvas id="chartJenisKelamin"></canvas>
          </div>
        </div>

        {{-- Kelompok Umur --}}
        <div class="chart-card">
          <div class="chart-title">Kelompok Umur</div>
          <div class="chart-sub">Distribusi usia penduduk</div>
          <div class="chart-box">
            <canvas id="chartKelompokUmur"></canvas>
          </div>
        </div>

        {{-- Pendidikan --}}
        <div class="chart-card">
          <div class="chart-title">Tingkat Pendidikan</div>
          <div class="chart-sub">Pendidikan terakhir warga desa</div>
          <div class="chart-box">
            <canvas id="chartPendidikan"></canvas>
          </div>
        </div>

        {{-- Pekerjaan --}}
        <div class="chart-card">
          <div class="chart-title">Mata Pencaharian</div>
          <div class="chart-sub">8 pekerjaan terbanyak warga desa</div>
          @if($pekerjaanRaw->isEmpty())
            <div class="empty-chart">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              <p>Data pekerjaan belum tersedia</p>
            </div>
          @else
            <div class="chart-box tall">
              <canvas id="chartPekerjaan"></canvas>
            </div>
          @endif
        </div>

        {{-- Status Perkawinan --}}
        <div class="chart-card">
          <div class="chart-title">Status Perkawinan</div>
          <div class="chart-sub">Komposisi status perkawinan penduduk</div>
          <div class="chart-box">
            <canvas id="chartStatusPerkawinan"></canvas>
          </div>
        </div>

        {{-- Agama --}}
        <div class="chart-card">
          <div class="chart-title">Agama</div>
          <div class="chart-sub">Komposisi agama yang dianut penduduk</div>
          @if($agamaRaw->isEmpty())
            <div class="empty-chart">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v20M2 12h20"/></svg>
              <p>Data agama belum tersedia</p>
            </div>
          @else
            <div class="chart-box">
              <canvas id="chartAgama"></canvas>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
@endif

{{-- ═══ TAB: APBDES ═══ --}}
@if($grafikAktif['apbdes'])
<div id="tab-apbdes" class="tab-content" {{ $firstTab !== 'apbdes' ? 'style=display:none' : '' }}>
  <div class="container">
    <div class="stat-section">
      @if($apbdesData->isEmpty())
        <div class="empty-chart" style="padding:80px 20px">
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" style="margin:0 auto 14px;opacity:.3;display:block"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          <h3 style="font-size:1rem;font-weight:800;margin-bottom:6px">Data APBDes Belum Tersedia</h3>
          <p style="font-size:.86rem;color:var(--muted)">Data anggaran desa belum diinput oleh administrator.</p>
        </div>
      @else
        <div class="section-head">
          <h2>Anggaran Pendapatan &amp; Belanja Desa</h2>
          <p>Perbandingan anggaran dan realisasi per tahun anggaran</p>
        </div>
        <div class="chart-grid cols-1">
          <div class="chart-card">
            <div class="chart-title">Anggaran vs Realisasi per Tahun</div>
            <div class="chart-sub">Satuan: Rupiah</div>
            <div class="chart-box tall">
              <canvas id="chartApbdes"></canvas>
            </div>
          </div>
        </div>

        @if($apbdesTerbaru && $belanjaKelompok->isNotEmpty())
        <div class="chart-grid" style="margin-top:20px">
          <div class="chart-card span-2">
            <div class="chart-title">Komposisi Belanja Tahun {{ $apbdesTerbaru->tahun }}</div>
            <div class="chart-sub">Anggaran belanja per bidang</div>
            <div class="chart-box">
              <canvas id="chartBelanja"></canvas>
            </div>
          </div>
        </div>
        @endif

        <div class="chart-grid" style="margin-top:20px">
          @foreach($apbdesData as $a)
          <div class="chart-card">
            <div class="chart-title">APBDes Tahun {{ $a['tahun'] }}</div>
            <div class="chart-sub">Anggaran vs Realisasi</div>
            <div style="display:flex;flex-direction:column;gap:14px;margin-top:4px">
              @foreach(['pendapatan' => ['Pendapatan','#0C7C46'], 'belanja' => ['Belanja','#0E63A8']] as $jenis => [$label, $color])
              @php
                $anggaran  = $a['anggaran_'.$jenis];
                $realisasi = $a['realisasi_'.$jenis];
                $pct = $anggaran > 0 ? min(100, round($realisasi / $anggaran * 100)) : 0;
              @endphp
              <div>
                <div style="display:flex;justify-content:space-between;font-size:.78rem;font-weight:700;margin-bottom:5px">
                  <span style="color:{{ $color }}">{{ $label }}</span>
                  <span style="color:var(--muted)">{{ $pct }}%</span>
                </div>
                <div style="background:var(--line);border-radius:99px;height:8px;overflow:hidden">
                  <div style="width:{{ $pct }}%;background:{{ $color }};height:100%;border-radius:99px;transition:.6s"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--muted);margin-top:4px">
                  <span>Rp {{ number_format($realisasi, 0, ',', '.') }}</span>
                  <span>dari Rp {{ number_format($anggaran, 0, ',', '.') }}</span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
@endif

{{-- ═══ TAB: SURAT ═══ --}}
@if($grafikAktif['surat'])
<div id="tab-surat" class="tab-content" {{ $firstTab !== 'surat' ? 'style=display:none' : '' }}>
  <div class="container">
    <div class="stat-section">
      <div class="section-head">
        <h2>Layanan Administrasi Surat</h2>
        <p>Data pengajuan surat masyarakat 12 bulan terakhir</p>
      </div>
      <div class="chart-grid">
        <div class="chart-card span-2">
          <div class="chart-title">Tren Pengajuan Surat (12 Bulan Terakhir)</div>
          <div class="chart-sub">Jumlah pengajuan surat per bulan</div>
          <div class="chart-box">
            <canvas id="chartSuratBulan"></canvas>
          </div>
        </div>
        <div class="chart-card span-2">
          <div class="chart-title">Pengajuan per Jenis Surat</div>
          <div class="chart-sub">Total keseluruhan pengajuan berdasarkan jenis</div>
          @if($suratPerJenis->isEmpty())
            <div class="empty-chart"><p>Belum ada data pengajuan surat</p></div>
          @else
            <div class="chart-box">
              <canvas id="chartSuratJenis"></canvas>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- ═══ TAB: KONTEN ═══ --}}
@if($grafikAktif['konten'])
<div id="tab-konten" class="tab-content" {{ $firstTab !== 'konten' ? 'style=display:none' : '' }}>
  <div class="container">

    <div class="summary-grid" style="grid-template-columns:repeat(3,1fr);margin-top:40px">
      <div class="sum-card">
        <div class="ic" style="background:#dcfce7;color:#0C7C46">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2z"/></svg>
        </div>
        <div class="val">{{ number_format($totalBerita) }}</div>
        <div class="lbl">Berita Diterbitkan</div>
      </div>
      <div class="sum-card">
        <div class="ic" style="background:#dbeafe;color:#1d4ed8">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        </div>
        <div class="val">{{ number_format($totalArsip) }}</div>
        <div class="lbl">Dokumen Arsip</div>
      </div>
      <div class="sum-card">
        <div class="ic" style="background:#fef9c3;color:#a16207">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
        </div>
        <div class="val">{{ number_format($totalPengumuman) }}</div>
        <div class="lbl">Pengumuman</div>
      </div>
    </div>

    <div class="stat-section">
      <div class="chart-grid">
        <div class="chart-card span-2">
          <div class="chart-title">Publikasi Berita per Bulan (12 Bulan Terakhir)</div>
          <div class="chart-sub">Jumlah berita yang diterbitkan tiap bulan</div>
          <div class="chart-box">
            <canvas id="chartBeritaBulan"></canvas>
          </div>
        </div>
        @if($beritaPerKategori->isNotEmpty())
        <div class="chart-card span-2">
          <div class="chart-title">Berita per Kategori</div>
          <div class="chart-sub">Distribusi berita berdasarkan kategori</div>
          <div class="chart-box">
            <canvas id="chartBeritaKategori"></canvas>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

<div class="page-foot"></div>

@endsection

@push('scripts')
<script>
Chart.defaults.font.family = "'Inter','Helvetica Neue',sans-serif";
Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim() || '#6b7280';

const PALETTE = ['#0C7C46','#0E63A8','#C99A2C','#7C3AED','#E05A5A','#2CB5C9','#59A656','#9B59B6','#E67E22','#1ABC9C'];
const GREEN = '#0C7C46', BLUE = '#0E63A8', AMBER = '#C99A2C';

function fmt(n) {
  if (n >= 1e9) return 'Rp '+(n/1e9).toFixed(1)+'M';
  if (n >= 1e6) return 'Rp '+(n/1e6).toFixed(1)+'jt';
  if (n >= 1e3) return 'Rp '+(n/1e3).toFixed(0)+'rb';
  return 'Rp '+n;
}

// ── DEMOGRAFI ─────────────────────────────────────────────────────────────────
@if($grafikAktif['demografi'])

// ── Jenis Kelamin ─────────────────────────────────────────────────────────────
@php
  $jkLabels = $jenisKelamin->keys()->map(fn($k) => match(strtolower($k)) {
      'l','laki-laki','laki_laki' => 'Laki-laki',
      'p','perempuan' => 'Perempuan',
      default => $k
  })->values()->toArray();
  $jkData = $jenisKelamin->values()->toArray();
@endphp
new Chart(document.getElementById('chartJenisKelamin'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($jkLabels) !!},
    datasets: [{ data: {!! json_encode($jkData) !!}, backgroundColor: [BLUE, '#ec4899'], borderWidth: 0, hoverOffset: 8 }]
  },
  options: { plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
});

// ── Kelompok Umur ─────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartKelompokUmur'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($kelompokOrder) !!},
    datasets: [{ label: 'Jumlah', data: {!! json_encode($kelompokData) !!},
      backgroundColor: PALETTE.slice(0,5), borderRadius: 8, borderSkipped: false }]
  },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

// ── Pendidikan ────────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartPendidikan'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($pendidikanLabel) !!},
    datasets: [{ label: 'Jumlah', data: {!! json_encode($pendidikanData) !!},
      backgroundColor: GREEN+'cc', borderRadius: 8, borderSkipped: false }]
  },
  options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
});

// ── Pekerjaan ─────────────────────────────────────────────────────────────────
@if($pekerjaanRaw->isNotEmpty())
new Chart(document.getElementById('chartPekerjaan'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($pekerjaanRaw->keys()->toArray()) !!},
    datasets: [{ label: 'Jumlah', data: {!! json_encode($pekerjaanRaw->values()->toArray()) !!},
      backgroundColor: PALETTE.map(c => c+'cc'), borderRadius: 6, borderSkipped: false }]
  },
  options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
});
@endif

// ── Status Perkawinan ─────────────────────────────────────────────────────────
new Chart(document.getElementById('chartStatusPerkawinan'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($spkLabel) !!},
    datasets: [{
      data: {!! json_encode($spkData) !!},
      backgroundColor: ['#94a3b8', GREEN, '#f97316', '#ef4444'],
      borderWidth: 0,
      hoverOffset: 8
    }]
  },
  options: {
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } } },
    cutout: '58%'
  }
});

// ── Agama ─────────────────────────────────────────────────────────────────────
@if($agamaRaw->isNotEmpty())
new Chart(document.getElementById('chartAgama'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($agamaRaw->keys()->map(fn($k) => ucfirst(strtolower($k)))->toArray()) !!},
    datasets: [{
      data: {!! json_encode($agamaRaw->values()->toArray()) !!},
      backgroundColor: PALETTE,
      borderWidth: 0,
      hoverOffset: 8
    }]
  },
  options: {
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } } },
    cutout: '58%'
  }
});
@endif

@endif {{-- grafikAktif demografi --}}

// ── APBDes ────────────────────────────────────────────────────────────────────
@if($grafikAktif['apbdes'])
@if($apbdesData->isNotEmpty())
const apbdes = {!! json_encode($apbdesData) !!};
new Chart(document.getElementById('chartApbdes'), {
  type: 'bar',
  data: {
    labels: apbdes.map(a => 'Tahun '+a.tahun),
    datasets: [
      { label:'Anggaran Pendapatan', data: apbdes.map(a=>a.anggaran_pendapatan), backgroundColor: GREEN+'99', borderRadius:6, borderSkipped:false },
      { label:'Realisasi Pendapatan', data: apbdes.map(a=>a.realisasi_pendapatan), backgroundColor: GREEN, borderRadius:6, borderSkipped:false },
      { label:'Anggaran Belanja', data: apbdes.map(a=>a.anggaran_belanja), backgroundColor: BLUE+'99', borderRadius:6, borderSkipped:false },
      { label:'Realisasi Belanja', data: apbdes.map(a=>a.realisasi_belanja), backgroundColor: BLUE, borderRadius:6, borderSkipped:false },
    ]
  },
  options: {
    plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: ctx => ctx.dataset.label+': '+fmt(ctx.raw) } } },
    scales: { y: { beginAtZero: true, ticks: { callback: v => fmt(v) } } }
  }
});
@endif

@if($apbdesTerbaru && $belanjaKelompok->isNotEmpty())
new Chart(document.getElementById('chartBelanja'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($belanjaKelompok->keys()->toArray()) !!},
    datasets: [{ data: {!! json_encode($belanjaKelompok->values()->toArray()) !!},
      backgroundColor: PALETTE, borderWidth: 0, hoverOffset: 8 }]
  },
  options: {
    plugins: { legend: { position: 'right' }, tooltip: { callbacks: { label: ctx => ctx.label+': '+fmt(ctx.raw) } } },
    cutout: '52%'
  }
});
@endif

@endif {{-- grafikAktif apbdes --}}

// ── Surat per Bulan ───────────────────────────────────────────────────────────
@if($grafikAktif['surat'])
new Chart(document.getElementById('chartSuratBulan'), {
  type: 'line',
  data: {
    labels: {!! json_encode($suratBulanLabels) !!},
    datasets: [{ label:'Pengajuan Surat', data: {!! json_encode($suratBulanData) !!},
      borderColor: GREEN, backgroundColor: GREEN+'22', fill: true,
      tension: 0.4, pointBackgroundColor: GREEN, pointRadius: 5 }]
  },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

// ── Surat per Jenis ───────────────────────────────────────────────────────────
@if($suratPerJenis->isNotEmpty())
new Chart(document.getElementById('chartSuratJenis'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($suratPerJenis->keys()->toArray()) !!},
    datasets: [{ label:'Jumlah Pengajuan', data: {!! json_encode($suratPerJenis->values()->toArray()) !!},
      backgroundColor: PALETTE.map(c=>c+'cc'), borderRadius: 8, borderSkipped: false }]
  },
  options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
});
@endif

@endif {{-- grafikAktif surat --}}

// ── Berita per Bulan ──────────────────────────────────────────────────────────
@if($grafikAktif['konten'])
new Chart(document.getElementById('chartBeritaBulan'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($suratBulanLabels) !!},
    datasets: [{ label:'Berita Diterbitkan', data: {!! json_encode($beritaBulanData) !!},
      backgroundColor: GREEN+'cc', borderRadius: 8, borderSkipped: false }]
  },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

// ── Berita per Kategori ───────────────────────────────────────────────────────
@if($beritaPerKategori->isNotEmpty())
new Chart(document.getElementById('chartBeritaKategori'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($beritaPerKategori->keys()->toArray()) !!},
    datasets: [{ data: {!! json_encode($beritaPerKategori->values()->toArray()) !!},
      backgroundColor: PALETTE, borderWidth: 0, hoverOffset: 8 }]
  },
  options: { plugins: { legend: { position: 'right' } }, cutout: '55%' }
});
@endif

@endif {{-- grafikAktif konten --}}

// ── Tab switching ─────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    btn.classList.add('active');
    document.getElementById('tab-'+btn.dataset.tab).style.display = 'block';
  });
});
</script>
@endpush
