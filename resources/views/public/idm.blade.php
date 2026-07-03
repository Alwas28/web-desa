@extends('layouts.public')

@section('title', 'IDM — Indeks Desa Membangun — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', 'Data Indeks Desa Membangun (IDM) Desa ' . ($desaInfo['desa.nama'] ?? '') . ': skor IKS, IKE, IKL, status, dan detail 50 indikator per tahun.')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<style>
/* ── Hero ── */
.idm-hero{background:linear-gradient(135deg,#1a3a5c 0%,#0C7C46 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.idm-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 75% 50%,rgba(255,255,255,.07),transparent 60%)}
.idm-hero-inner{position:relative}
.idm-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.idm-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.idm-hero h1{font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.idm-hero p{font-size:.95rem;opacity:.82;max-width:520px}

/* ── Breadcrumb ── */
.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

/* ── Status badge ── */
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:999px;font-size:.8rem;font-weight:800;letter-spacing:.02em}
.status-mandiri   {background:rgba(16,185,129,.2);color:#10b981;border:1.5px solid rgba(16,185,129,.35)}
.status-maju      {background:rgba(59,130,246,.2);color:#3b82f6;border:1.5px solid rgba(59,130,246,.35)}
.status-berkembang{background:rgba(245,158,11,.2);color:#f59e0b;border:1.5px solid rgba(245,158,11,.35)}
.status-tertinggal{background:rgba(239,68,68,.2);color:#ef4444;border:1.5px solid rgba(239,68,68,.35)}
.status-sangat-tertinggal{background:rgba(127,29,29,.25);color:#fca5a5;border:1.5px solid rgba(239,68,68,.4)}

/* ── Skor ring ── */
.skor-ring{width:130px;height:130px;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(255,255,255,.1);border:3px solid rgba(255,255,255,.25);backdrop-filter:blur(8px)}
.skor-ring .val{font-size:2rem;font-weight:900;letter-spacing:-.04em;line-height:1}
.skor-ring .sub{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.75;margin-top:3px}

/* ── Summary cards ── */
.idm-summary{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:40px 0}
@media(max-width:860px){.idm-summary{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.idm-summary{grid-template-columns:1fr 1fr}}
.sum-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:6px}
.sum-card .ic{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:4px;flex-shrink:0}
.sum-card .val{font-size:1.75rem;font-weight:800;letter-spacing:-.03em;line-height:1}
.sum-card .lbl{font-size:.78rem;font-weight:600;color:var(--muted)}
.sum-card .sub-lbl{font-size:.72rem;color:var(--muted);margin-top:2px}

/* ── Progress bar ── */
.prog-bar{height:8px;background:var(--line);border-radius:99px;overflow:hidden;margin-top:8px}
.prog-bar-inner{height:100%;border-radius:99px;transition:.6s ease}

/* ── Section ── */
.idm-section{padding:48px 0 32px}
.section-head{margin-bottom:28px}
.section-head h2{font-size:1.25rem;font-weight:800;letter-spacing:-.02em;margin-bottom:4px}
.section-head p{font-size:.86rem;color:var(--muted)}

/* ── Chart card ── */
.chart-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;padding:22px}
.chart-title{font-size:.9rem;font-weight:800;margin-bottom:4px;color:var(--ink)}
.chart-sub{font-size:.75rem;color:var(--muted);margin-bottom:18px}
.chart-box canvas{max-height:300px}

/* ── Tahun selector ── */
.tahun-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.tahun-tab{padding:6px 16px;border-radius:999px;font-size:.82rem;font-weight:700;border:1.5px solid var(--line);color:var(--muted);cursor:pointer;background:var(--surface);transition:.2s}
.tahun-tab:hover{border-color:var(--green);color:var(--green)}
.tahun-tab.active{background:var(--green);border-color:var(--green);color:#fff}

/* ── Indikator table ── */
.indikator-section{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;overflow:hidden;margin-bottom:16px}
.indikator-section-head{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--line);background:var(--surface-2)}
.indikator-section-head .ic2{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0}
.indikator-section-head strong{font-size:.9rem;font-weight:800}
.indikator-section-head span{font-size:.78rem;color:var(--muted)}
.indikator-row{display:flex;align-items:center;gap:12px;padding:11px 20px;border-bottom:1px solid var(--line)}
.indikator-row:last-child{border-bottom:none}
.indikator-row .num{font-size:.72rem;font-weight:700;color:var(--muted);width:24px;flex-shrink:0;text-align:right}
.indikator-row .name{flex:1;font-size:.83rem;color:var(--ink)}
.indikator-row .score{font-size:.82rem;font-weight:800;width:48px;text-align:right;flex-shrink:0}
.indikator-row .bar-wrap{width:80px;flex-shrink:0}
.score-high{color:#10b981}
.score-mid {color:#f59e0b}
.score-low {color:#ef4444}

/* ── Empty state ── */
.empty-state{text-align:center;padding:80px 20px}
.empty-state svg{margin:0 auto 16px;opacity:.25;display:block}
.empty-state h3{font-size:1rem;font-weight:800;margin-bottom:6px}
.empty-state p{font-size:.86rem;color:var(--muted)}

.page-foot{padding:40px 0 80px}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="idm-hero">
  <div class="container idm-hero-inner">
    @if($latest)
    <div style="display:flex;align-items:center;gap:40px;flex-wrap:wrap">
      <div style="flex:1;min-width:260px">
        <div class="eyebrow">Transparansi Data</div>
        <h1>Indeks Desa Membangun</h1>
        <p style="margin-bottom:16px">Data IDM resmi Desa {{ $desaInfo['desa.nama'] ?? '' }} berdasarkan penilaian Kementerian Desa PDTT.</p>
        @php
          $slug = strtolower(str_replace(' ', '-', $latest->status_idm ?? ''));
        @endphp
        <span class="status-badge status-{{ $slug }}">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
          {{ $latest->status_idm }}
        </span>
      </div>
      <div style="display:flex;gap:20px;flex-wrap:wrap;justify-content:center">
        <div class="skor-ring">
          <div class="val">{{ number_format($latest->skor_idm, 4) }}</div>
          <div class="sub">IDM {{ $latest->tahun }}</div>
        </div>
        <div style="display:flex;flex-direction:column;justify-content:center;gap:14px">
          @foreach(['IKS'=>['skor_iks','#10b981'],'IKE'=>['skor_ike','#3b82f6'],'IKL'=>['skor_ikl','#f59e0b']] as $lbl=>[$field,$color])
          <div style="min-width:140px">
            <div style="display:flex;justify-content:space-between;font-size:.73rem;font-weight:700;opacity:.85;margin-bottom:5px">
              <span>{{ $lbl }}</span>
              <span>{{ number_format($latest->$field, 4) }}</span>
            </div>
            <div style="background:rgba(255,255,255,.15);border-radius:99px;height:6px;overflow:hidden">
              <div style="width:{{ min(100, round($latest->$field * 100)) }}%;background:{{ $color }};height:100%;border-radius:99px"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @else
    <div class="eyebrow">Transparansi Data</div>
    <h1>Indeks Desa Membangun</h1>
    <p>Data IDM Desa {{ $desaInfo['desa.nama'] ?? '' }} belum tersedia.</p>
    @endif
  </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>IDM</b>
  </div>
</div>

@if($rows->isEmpty())
<div class="container">
  <div class="empty-state" style="padding:80px 20px">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
      <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M12 8v8"/>
    </svg>
    <h3>Data IDM Belum Tersedia</h3>
    <p>Data Indeks Desa Membangun belum diinput oleh administrator.</p>
  </div>
</div>
@else

{{-- Summary Cards --}}
<div class="container">
  <div class="idm-summary">

    {{-- IDM --}}
    <div class="sum-card">
      <div class="ic" style="background:#dcfce7;color:#0C7C46">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
      </div>
      <div class="val">{{ number_format($latest->skor_idm, 4) }}</div>
      <div class="lbl">Skor IDM {{ $latest->tahun }}</div>
      <div class="prog-bar"><div class="prog-bar-inner" style="width:{{ min(100,round($latest->skor_idm*100)) }}%;background:#0C7C46"></div></div>
    </div>

    {{-- IKS --}}
    <div class="sum-card">
      <div class="ic" style="background:#d1fae5;color:#10b981">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div class="val" style="color:#10b981">{{ number_format($latest->skor_iks, 4) }}</div>
      <div class="lbl">IKS — Sosial</div>
      <div class="sub-lbl">Indeks Ketahanan Sosial</div>
      <div class="prog-bar"><div class="prog-bar-inner" style="width:{{ min(100,round($latest->skor_iks*100)) }}%;background:#10b981"></div></div>
    </div>

    {{-- IKE --}}
    <div class="sum-card">
      <div class="ic" style="background:#dbeafe;color:#3b82f6">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      </div>
      <div class="val" style="color:#3b82f6">{{ number_format($latest->skor_ike, 4) }}</div>
      <div class="lbl">IKE — Ekonomi</div>
      <div class="sub-lbl">Indeks Ketahanan Ekonomi</div>
      <div class="prog-bar"><div class="prog-bar-inner" style="width:{{ min(100,round($latest->skor_ike*100)) }}%;background:#3b82f6"></div></div>
    </div>

    {{-- IKL --}}
    <div class="sum-card">
      <div class="ic" style="background:#fef9c3;color:#ca8a04">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 4-4 8-7 12-3-4-7-8-7-12a7 7 0 0 1 7-7z"/></svg>
      </div>
      <div class="val" style="color:#ca8a04">{{ number_format($latest->skor_ikl, 4) }}</div>
      <div class="lbl">IKL — Lingkungan</div>
      <div class="sub-lbl">Indeks Ketahanan Lingkungan</div>
      <div class="prog-bar"><div class="prog-bar-inner" style="width:{{ min(100,round($latest->skor_ikl*100)) }}%;background:#ca8a04"></div></div>
    </div>

  </div>
</div>

{{-- Tren & Radar --}}
@if($rows->count() > 0)
<div class="container">
  <div class="idm-section" style="padding-top:0">
    <div class="section-head">
      <h2>Tren Perkembangan IDM</h2>
      <p>Pergerakan skor IDM, IKS, IKE, dan IKL dari tahun ke tahun</p>
    </div>
    <div style="display:grid;grid-template-columns:1fr{{ $rows->count() > 1 ? '' : '' }};gap:20px">
      <div class="chart-card">
        <div class="chart-title">Skor per Tahun</div>
        <div class="chart-sub">IDM · IKS · IKE · IKL</div>
        <div class="chart-box"><canvas id="chartTren"></canvas></div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- Riwayat tabel --}}
@if($rows->count() > 1)
<div class="container">
  <div class="section-head">
    <h2>Riwayat Data IDM</h2>
    <p>Data IDM seluruh tahun yang telah diinput</p>
  </div>
  <div style="background:var(--surface);border:1.5px solid var(--line);border-radius:18px;overflow:hidden;margin-bottom:40px">
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:.85rem">
        <thead>
          <tr style="background:var(--surface-2);border-bottom:1.5px solid var(--line)">
            <th style="padding:12px 20px;text-align:left;font-weight:700;color:var(--muted);font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">Tahun</th>
            <th style="padding:12px 16px;text-align:center;font-weight:700;color:var(--muted);font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">Skor IDM</th>
            <th style="padding:12px 16px;text-align:center;font-weight:700;color:#10b981;font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">IKS</th>
            <th style="padding:12px 16px;text-align:center;font-weight:700;color:#3b82f6;font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">IKE</th>
            <th style="padding:12px 16px;text-align:center;font-weight:700;color:#ca8a04;font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">IKL</th>
            <th style="padding:12px 16px;text-align:left;font-weight:700;color:var(--muted);font-size:.75rem;letter-spacing:.06em;text-transform:uppercase">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows->sortByDesc('tahun') as $row)
          @php $rSlug = strtolower(str_replace(' ', '-', $row->status_idm ?? '')); @endphp
          <tr style="border-bottom:1px solid var(--line)" class="hover:bg-[var(--surface-2)] transition-colors">
            <td style="padding:13px 20px;font-weight:800">{{ $row->tahun }}</td>
            <td style="padding:13px 16px;text-align:center;font-weight:800">{{ number_format($row->skor_idm, 4) }}</td>
            <td style="padding:13px 16px;text-align:center;font-weight:700;color:#10b981">{{ number_format($row->skor_iks, 4) }}</td>
            <td style="padding:13px 16px;text-align:center;font-weight:700;color:#3b82f6">{{ number_format($row->skor_ike, 4) }}</td>
            <td style="padding:13px 16px;text-align:center;font-weight:700;color:#ca8a04">{{ number_format($row->skor_ikl, 4) }}</td>
            <td style="padding:13px 20px">
              <span class="status-badge status-{{ $rSlug }}" style="font-size:.72rem;padding:3px 10px">{{ $row->status_idm }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

{{-- Detail Indikator per Tahun --}}
<div class="container">
  <div class="section-head">
    <h2>Detail Indikator IDM</h2>
    <p>Skor 50 indikator IDM per dimensi berdasarkan tahun yang dipilih</p>
  </div>

  {{-- Tahun selector --}}
  @if($rows->count() > 1)
  <div class="tahun-tabs" id="tahunTabs">
    @foreach($rows->sortByDesc('tahun') as $row)
    <button class="tahun-tab {{ $loop->first ? 'active' : '' }}"
            data-tahun="{{ $row->tahun }}"
            onclick="switchTahun({{ $row->tahun }}, this)">
      {{ $row->tahun }}
    </button>
    @endforeach
  </div>
  @endif

  @foreach($rows->sortByDesc('tahun') as $row)
  <div id="detail-{{ $row->tahun }}" class="tahun-detail" style="{{ !$loop->first ? 'display:none' : '' }}">

    @foreach(['iks'=>['IKS','Indeks Ketahanan Sosial','#10b981','rgba(16,185,129,.1)','#dcfce7'],'ike'=>['IKE','Indeks Ketahanan Ekonomi','#3b82f6','rgba(59,130,246,.1)','#dbeafe'],'ikl'=>['IKL','Indeks Ketahanan Lingkungan','#ca8a04','rgba(202,138,4,.1)','#fef9c3']] as $dim=>[$title,$subtitle,$color,$bgColor,$iconBg])
    @php
      $defs      = $indikatorDefs[$dim] ?? [];
      $nilais    = $row->indikators[$dim] ?? [];
      $hasData   = !empty($nilais);
    @endphp

    <div class="indikator-section" style="margin-bottom:16px">
      <div class="indikator-section-head">
        <div class="ic2" style="background:{{ $iconBg }};color:{{ $color }}">
          @if($dim==='iks')
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          @elseif($dim==='ike')
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          @else
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 4-4 8-7 12-3-4-7-8-7-12a7 7 0 0 1 7-7z"/></svg>
          @endif
        </div>
        <div style="flex:1">
          <strong>{{ $title }} — {{ $subtitle }}</strong><br>
          <span>{{ count($defs) }} indikator · Skor: {{ number_format($row->{'skor_'.$dim}, 4) }}</span>
        </div>
        <div style="width:80px;text-align:right">
          <div style="font-size:1.3rem;font-weight:900;color:{{ $color }}">{{ number_format($row->{'skor_'.$dim}, 4) }}</div>
          <div class="prog-bar" style="margin-top:4px"><div class="prog-bar-inner" style="width:{{ min(100,round($row->{'skor_'.$dim}*100)) }}%;background:{{ $color }}"></div></div>
        </div>
      </div>

      @if($hasData)
        @foreach($defs as $i => $def)
        @php
          $val       = (float)($nilais[$def['key']] ?? 0);
          $scoreClass = $val >= 0.75 ? 'score-high' : ($val >= 0.5 ? 'score-mid' : 'score-low');
          $barPct    = min(100, round($val * 100));
        @endphp
        <div class="indikator-row">
          <div class="num">{{ $i + 1 }}</div>
          <div class="name">{{ $def['label'] }}</div>
          <div class="bar-wrap">
            <div class="prog-bar" style="height:5px">
              <div class="prog-bar-inner" style="width:{{ $barPct }}%;background:{{ $color }}80"></div>
            </div>
          </div>
          <div class="score {{ $scoreClass }}">{{ number_format($val, 2) }}</div>
        </div>
        @endforeach
      @else
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:.83rem">
          Detail indikator {{ strtoupper($dim) }} belum tersedia untuk tahun {{ $row->tahun }}.
        </div>
      @endif
    </div>
    @endforeach

    @if($row->catatan)
    <div style="background:var(--surface);border:1.5px solid var(--line);border-radius:16px;padding:20px;margin-bottom:24px">
      <div style="font-size:.78rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px">Catatan</div>
      <p style="font-size:.88rem;line-height:1.65;color:var(--ink)">{{ $row->catatan }}</p>
    </div>
    @endif

  </div>
  @endforeach

</div>

@endif {{-- end if rows not empty --}}

<div class="page-foot"></div>

@endsection

@push('scripts')
<script>
@if($rows->isNotEmpty())
Chart.defaults.font.family = "'Inter','Helvetica Neue',sans-serif";
Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim() || '#6b7280';

const trenLabels = {!! json_encode($rows->sortBy('tahun')->pluck('tahun')->map(fn($y) => (string)$y)->values()) !!};
const trenIDM    = {!! json_encode($rows->sortBy('tahun')->pluck('skor_idm')->values()) !!};
const trenIKS    = {!! json_encode($rows->sortBy('tahun')->pluck('skor_iks')->values()) !!};
const trenIKE    = {!! json_encode($rows->sortBy('tahun')->pluck('skor_ike')->values()) !!};
const trenIKL    = {!! json_encode($rows->sortBy('tahun')->pluck('skor_ikl')->values()) !!};

new Chart(document.getElementById('chartTren'), {
  type: 'line',
  data: {
    labels: trenLabels,
    datasets: [
      { label:'IDM',  data: trenIDM, borderColor:'#0C7C46', backgroundColor:'#0C7C4622', fill:true, tension:.35, pointBackgroundColor:'#0C7C46', pointRadius:5, borderWidth:2.5 },
      { label:'IKS',  data: trenIKS, borderColor:'#10b981', backgroundColor:'transparent', tension:.35, pointBackgroundColor:'#10b981', pointRadius:4, borderDash:[4,3] },
      { label:'IKE',  data: trenIKE, borderColor:'#3b82f6', backgroundColor:'transparent', tension:.35, pointBackgroundColor:'#3b82f6', pointRadius:4, borderDash:[4,3] },
      { label:'IKL',  data: trenIKL, borderColor:'#ca8a04', backgroundColor:'transparent', tension:.35, pointBackgroundColor:'#ca8a04', pointRadius:4, borderDash:[4,3] },
    ]
  },
  options: {
    plugins: {
      legend: { position:'bottom', labels:{ boxWidth:12, padding:16 } },
      tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + parseFloat(ctx.raw).toFixed(4) } }
    },
    scales: {
      y: { min:0, max:1, ticks:{ callback: v => v.toFixed(2) } }
    }
  }
});

function switchTahun(tahun, btn) {
  document.querySelectorAll('.tahun-tab').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tahun-detail').forEach(d => d.style.display = 'none');
  btn.classList.add('active');
  const el = document.getElementById('detail-' + tahun);
  if (el) el.style.display = 'block';
}
@endif
</script>
@endpush
