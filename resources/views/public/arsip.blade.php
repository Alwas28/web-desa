@extends('layouts.public')

@section('title', ($aktifKat ? $aktifKat->nama . ' — ' : '') . 'Arsip Dokumen — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', ($aktifKat && $aktifKat->deskripsi ? $aktifKat->deskripsi : 'Kumpulan dokumen, peraturan, dan arsip resmi ' . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa') . '.'))

@push('head')
<style>
.page-hero{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.page-hero::before{content:"";position:absolute;inset:0;background-image:radial-gradient(circle at 80% 50%,rgba(255,255,255,.07) 0%,transparent 60%)}
.page-hero-inner{position:relative}
.page-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.page-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.page-hero h1{font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.page-hero p{font-size:.95rem;opacity:.82;max-width:520px}

.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

.filter-bar{background:var(--surface);border-bottom:1px solid var(--line);padding:14px 0;position:sticky;top:69px;z-index:40}
.filter-inner{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
.search-box{display:flex;align-items:center;gap:8px;border:1.5px solid var(--line);border-radius:12px;background:var(--surface-2);padding:9px 14px;flex:1;min-width:180px;max-width:320px;transition:.2s}
.search-box:focus-within{border-color:var(--green)}
.search-box input{background:none;border:none;outline:none;font:inherit;font-size:.88rem;color:var(--ink);width:100%}
.search-box input::placeholder{color:var(--muted)}
.filter-chips{display:flex;gap:8px;flex-wrap:wrap}
.chip{padding:7px 14px;border-radius:99px;font-size:.8rem;font-weight:700;border:1.5px solid var(--line);background:var(--surface-2);color:var(--muted);cursor:pointer;transition:.2s;text-decoration:none;display:inline-block}
.chip:hover{border-color:var(--green);color:var(--green)}
.chip.on{border-color:var(--green);background:var(--green-soft);color:var(--green)}

.archive-section{padding:40px 0 72px}

/* ── Tabel arsip ── */
.arc-table-wrap{background:var(--surface);border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow)}
.arc-table{width:100%;border-collapse:collapse;font-size:.88rem}
.arc-table thead th{background:var(--surface-2);padding:12px 16px;text-align:left;font-size:.75rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap}
.arc-table tbody tr{border-bottom:1px solid var(--line);transition:.15s}
.arc-table tbody tr:last-child{border-bottom:none}
.arc-table tbody tr:hover{background:var(--surface-2)}
.arc-table td{padding:13px 16px;vertical-align:middle}

.ext-badge{display:inline-flex;align-items:center;justify-content:center;width:42px;height:30px;border-radius:7px;font-size:.68rem;font-weight:900;letter-spacing:.04em}
.td-judul{display:flex;align-items:center;gap:12px}
.td-judul-text{font-weight:700;color:var(--ink);line-height:1.35}
.td-judul-desc{font-size:.78rem;color:var(--muted);margin-top:2px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
.cat-pill{display:inline-block;font-size:.7rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;padding:3px 10px;border-radius:99px;white-space:nowrap}
.td-meta{font-size:.78rem;color:var(--muted);white-space:nowrap}
.arc-dl{display:inline-flex;align-items:center;gap:5px;background:var(--green);color:#fff;font-size:.78rem;font-weight:700;padding:6px 12px;border-radius:8px;transition:.18s;white-space:nowrap;text-decoration:none}
.arc-dl:hover{background:var(--green-deep)}

@media(max-width:760px){
  .arc-table thead th.col-kat,
  .arc-table td.col-kat,
  .arc-table thead th.col-ukuran,
  .arc-table td.col-ukuran{display:none}
}
@media(max-width:560px){
  .arc-table thead th.col-tgl,
  .arc-table td.col-tgl{display:none}
}

.empty{text-align:center;padding:72px 20px;color:var(--muted)}
.empty svg{margin:0 auto 18px;opacity:.35}
.empty h3{font-size:1.1rem;font-weight:800;color:var(--ink);margin-bottom:8px}
.empty p{font-size:.88rem}

.pagination-wrap{display:flex;justify-content:center;margin-top:28px;gap:8px;flex-wrap:wrap}
.pagination-wrap a,.pagination-wrap span{padding:8px 15px;border-radius:10px;border:1.5px solid var(--line);background:var(--surface);font-size:.84rem;font-weight:700;color:var(--muted);transition:.2s;display:inline-flex;align-items:center}
.pagination-wrap a:hover{border-color:var(--green);color:var(--green);background:var(--green-soft)}
.pagination-wrap span[aria-current="page"]{background:var(--green);border-color:var(--green);color:#fff}
</style>
@endpush

@section('content')

<div class="page-hero">
  <div class="container page-hero-inner">
    <div class="eyebrow">Dokumen &amp; Arsip</div>
    <h1>{{ $aktifKat ? $aktifKat->nama : 'Arsip Dokumen' }}</h1>
    @if($aktifKat && $aktifKat->deskripsi)
      <p>{{ $aktifKat->deskripsi }}</p>
    @else
      <p>Kumpulan dokumen, peraturan, dan arsip resmi {{ $desaInfo['desa.nama'] ?? 'Pemerintah Desa' }}.</p>
    @endif
  </div>
</div>

<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    @if($aktifKat)
      <a href="{{ route('arsip') }}">Arsip</a>
      <span>›</span>
      <b>{{ $aktifKat->nama }}</b>
    @else
      <b>Arsip</b>
    @endif
  </div>
</div>

<div class="filter-bar">
  <div class="container filter-inner">
    <form method="GET" action="{{ route('arsip') }}" style="display:contents">
      @if($aktifKat)
        <input type="hidden" name="kategori" value="{{ $aktifKat->id }}">
      @endif
      <div class="search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari dokumen…" autocomplete="off">
      </div>
    </form>
    <div class="filter-chips">
      <a href="{{ route('arsip') }}" class="chip {{ !$aktifKat ? 'on' : '' }}">Semua</a>
      @foreach($kategoris as $kat)
        <a href="{{ route('arsip', ['kategori' => $kat->id]) }}" class="chip {{ $aktifKat?->id === $kat->id ? 'on' : '' }}">{{ $kat->nama }}</a>
      @endforeach
    </div>
    <span style="font-size:.82rem;color:var(--muted);margin-left:auto">{{ $arsips->total() }} dokumen</span>
  </div>
</div>

<section class="archive-section">
  <div class="container">

    @if($arsips->isEmpty())
      <div class="empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <h3>Belum Ada Dokumen</h3>
        <p>Belum ada arsip yang dipublikasikan{{ $aktifKat ? ' pada kategori ini' : '' }}.</p>
        @if($aktifKat)
          <a href="{{ route('arsip') }}" style="display:inline-block;margin-top:14px;color:var(--green);font-weight:700">← Lihat semua arsip</a>
        @endif
      </div>
    @else

      <div class="arc-table-wrap">
        <table class="arc-table">
          <thead>
            <tr>
              <th style="width:48px">#</th>
              <th>Nama Dokumen</th>
              <th class="col-kat">Kategori</th>
              <th class="col-tgl">Tanggal</th>
              <th class="col-ukuran">Ukuran</th>
              <th style="width:100px"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($arsips as $item)
              @php
                $ext = $item->extension;
                $extStyles = [
                  'pdf'  => ['bg'=>'#FEE2E2','color'=>'#DC2626'],
                  'doc'  => ['bg'=>'#DBEAFE','color'=>'#1D4ED8'],
                  'docx' => ['bg'=>'#DBEAFE','color'=>'#1D4ED8'],
                  'xls'  => ['bg'=>'#D1FAE5','color'=>'#065F46'],
                  'xlsx' => ['bg'=>'#D1FAE5','color'=>'#065F46'],
                  'ppt'  => ['bg'=>'#FFEDD5','color'=>'#C2410C'],
                  'pptx' => ['bg'=>'#FFEDD5','color'=>'#C2410C'],
                  'zip'  => ['bg'=>'#EDE9FE','color'=>'#5B21B6'],
                  'rar'  => ['bg'=>'#EDE9FE','color'=>'#5B21B6'],
                  'txt'  => ['bg'=>'#F3F4F6','color'=>'#374151'],
                ];
                $es = $extStyles[$ext] ?? ['bg'=>'#F3F4F6','color'=>'#6B7280'];
                $catColor = $item->kategoriArsip?->warna ?: '#0C7C46';
              @endphp
              <tr>
                <td style="text-align:center;color:var(--muted);font-size:.78rem;font-weight:700">
                  {{ ($arsips->currentPage() - 1) * $arsips->perPage() + $loop->iteration }}
                </td>
                <td>
                  <div class="td-judul">
                    <span class="ext-badge" style="background:{{ $es['bg'] }};color:{{ $es['color'] }}">
                      {{ strtoupper($ext) ?: 'FILE' }}
                    </span>
                    <div>
                      <div class="td-judul-text">{{ $item->judul }}</div>
                      @if($item->deskripsi)
                        <div class="td-judul-desc">{{ $item->deskripsi }}</div>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="col-kat">
                  @if($item->kategoriArsip)
                    <span class="cat-pill" style="background:{{ $catColor }}18;color:{{ $catColor }}">{{ $item->kategoriArsip->nama }}</span>
                  @else
                    <span style="color:var(--muted);font-size:.8rem">—</span>
                  @endif
                </td>
                <td class="col-tgl td-meta">
                  {{ $item->published_at?->locale('id')->translatedFormat('d M Y') ?? '—' }}
                </td>
                <td class="col-ukuran td-meta">{{ $item->ukuran_format }}</td>
                <td>
                  <a href="{{ $item->file_url }}" target="_blank" rel="noopener" class="arc-dl" download>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($arsips->hasPages())
        <div class="pagination-wrap">
          @if($arsips->onFirstPage())
            <span style="opacity:.4">← Sebelumnya</span>
          @else
            <a href="{{ $arsips->previousPageUrl() }}">← Sebelumnya</a>
          @endif
          @foreach($arsips->getUrlRange(max(1,$arsips->currentPage()-2), min($arsips->lastPage(),$arsips->currentPage()+2)) as $pg => $url)
            @if($pg == $arsips->currentPage())
              <span aria-current="page">{{ $pg }}</span>
            @else
              <a href="{{ $url }}">{{ $pg }}</a>
            @endif
          @endforeach
          @if($arsips->hasMorePages())
            <a href="{{ $arsips->nextPageUrl() }}">Selanjutnya →</a>
          @else
            <span style="opacity:.4">Selanjutnya →</span>
          @endif
        </div>
      @endif

    @endif
  </div>
</section>

@endsection
