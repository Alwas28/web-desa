@extends('layouts.public')

@section('title', ($aktifKat ? $aktifKat->nama . ' — ' : '') . 'Berita — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', ($aktifKat ? 'Berita kategori '.$aktifKat->nama.' dari ' : 'Berita dan informasi terbaru dari ') . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa') . '.')

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
.chip{padding:7px 14px;border-radius:99px;font-size:.8rem;font-weight:700;border:1.5px solid var(--line);background:var(--surface-2);color:var(--muted);transition:.2s;text-decoration:none;display:inline-block}
.chip:hover{border-color:var(--green);color:var(--green)}
.chip.on{border-color:var(--green);background:var(--green-soft);color:var(--green)}

.berita-section{padding:48px 0 80px}
.berita-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
@media(max-width:900px){.berita-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.berita-grid{grid-template-columns:1fr}}

.news-card{background:var(--surface);border:1px solid var(--line);border-radius:18px;overflow:hidden;display:flex;flex-direction:column;transition:.25s;text-decoration:none;color:inherit}
.news-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:var(--green)}
.news-thumb{height:200px;position:relative;background:var(--surface-2);overflow:hidden;flex-shrink:0}
.news-thumb img{width:100%;height:100%;object-fit:cover;transition:.35s}
.news-card:hover .news-thumb img{transform:scale(1.04)}
.news-thumb .placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center}
.cat-badge{position:absolute;top:12px;left:12px;font-size:.7rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:4px 11px;border-radius:99px;background:rgba(255,255,255,.92);color:var(--green-deep);border:1px solid rgba(255,255,255,.8)}
.news-body{padding:20px;flex:1;display:flex;flex-direction:column;gap:10px}
.news-date{font-size:.74rem;color:var(--muted);font-weight:600;display:flex;align-items:center;gap:5px}
.news-title{font-size:1rem;font-weight:800;line-height:1.4;color:var(--ink)}
.news-desc{font-size:.86rem;color:var(--muted);line-height:1.6;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.news-more{font-size:.82rem;font-weight:700;color:var(--green);margin-top:auto;display:flex;align-items:center;gap:5px}

.empty{text-align:center;padding:72px 20px;color:var(--muted)}
.empty svg{margin:0 auto 18px;opacity:.35}
.empty h3{font-size:1.1rem;font-weight:800;color:var(--ink);margin-bottom:8px}

.pagination-wrap{display:flex;justify-content:center;margin-top:40px;gap:8px;flex-wrap:wrap}
.pagination-wrap a,.pagination-wrap span{padding:9px 16px;border-radius:10px;border:1.5px solid var(--line);background:var(--surface);font-size:.84rem;font-weight:700;color:var(--muted);transition:.2s;display:inline-flex;align-items:center}
.pagination-wrap a:hover{border-color:var(--green);color:var(--green);background:var(--green-soft)}
.pagination-wrap span[aria-current="page"]{background:var(--green);border-color:var(--green);color:#fff}
</style>
@endpush

@section('content')

<div class="page-hero">
  <div class="container page-hero-inner">
    <div class="eyebrow">Berita &amp; Informasi</div>
    <h1>{{ $aktifKat ? $aktifKat->nama : 'Kabar Terbaru Desa' }}</h1>
    <p>Berita, informasi, dan pengumuman terbaru dari {{ $desaInfo['desa.nama'] ?? 'Pemerintah Desa' }}.</p>
  </div>
</div>

<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    @if($aktifKat)
      <a href="{{ route('berita.index') }}">Berita</a>
      <span>›</span>
      <b>{{ $aktifKat->nama }}</b>
    @else
      <b>Berita</b>
    @endif
  </div>
</div>

<div class="filter-bar">
  <div class="container filter-inner">
    <form method="GET" action="{{ route('berita.index') }}" style="display:contents">
      @if($aktifKat)
        <input type="hidden" name="kategori" value="{{ $aktifKat->id }}">
      @endif
      <div class="search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita…" autocomplete="off">
      </div>
    </form>
    <div class="filter-chips">
      <a href="{{ route('berita.index') }}" class="chip {{ !$aktifKat ? 'on' : '' }}">Semua</a>
      @foreach($kategoris as $kat)
        @php $warna = $kat->warna ?: '#0C7C46'; @endphp
        <a href="{{ route('berita.index', ['kategori' => $kat->id]) }}"
           class="chip {{ $aktifKat?->id === $kat->id ? 'on' : '' }}"
           @if($aktifKat?->id === $kat->id) style="border-color:{{ $warna }};background:{{ $warna }}18;color:{{ $warna }}" @endif>
          {{ $kat->nama }}
        </a>
      @endforeach
    </div>
    <span style="font-size:.82rem;color:var(--muted);margin-left:auto">{{ $beritas->total() }} berita</span>
  </div>
</div>

<section class="berita-section">
  <div class="container">

    @if($beritas->isEmpty())
      <div class="empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
          <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
        </svg>
        <h3>Belum Ada Berita</h3>
        <p>Belum ada berita yang dipublikasikan{{ $aktifKat ? ' pada kategori ini' : '' }}.</p>
        @if($aktifKat)
          <a href="{{ route('berita.index') }}" style="display:inline-block;margin-top:14px;color:var(--green);font-weight:700">← Semua berita</a>
        @endif
      </div>
    @else

      <div class="berita-grid">
        @foreach($beritas as $b)
          @php
            $bgPalette = ['linear-gradient(135deg,#0C7C46,#1E9059)','linear-gradient(135deg,#0E63A8,#3F8FC9)','linear-gradient(135deg,#C99A2C,#E0B854)','linear-gradient(135deg,#7C3AED,#9F67FA)'];
            $bg = $bgPalette[$loop->index % count($bgPalette)];
          @endphp
          <a href="{{ route('berita.show', $b->slug) }}" class="news-card">
            <div class="news-thumb" style="{{ !$b->gambar_url ? 'background:'.$bg : '' }}">
              @if($b->gambar_url)
                <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" loading="lazy">
              @else
                <div class="placeholder">
                  <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.8)" stroke-width="1.5" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
                </div>
              @endif
              @if($b->kategori)
                <span class="cat-badge">{{ $b->kategori->nama }}</span>
              @endif
            </div>
            <div class="news-body">
              <div class="news-date">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $b->published_at?->locale('id')->translatedFormat('d F Y') ?? '' }}
              </div>
              <div class="news-title">{{ $b->judul }}</div>
              <div class="news-desc">{{ Str::limit(strip_tags(html_entity_decode($b->ringkasan ?? '')), 120) }}</div>
              <span class="news-more">
                Baca selengkapnya
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
              </span>
            </div>
          </a>
        @endforeach
      </div>

      @if($beritas->hasPages())
        <div class="pagination-wrap">
          @if($beritas->onFirstPage())
            <span style="opacity:.4">← Sebelumnya</span>
          @else
            <a href="{{ $beritas->previousPageUrl() }}">← Sebelumnya</a>
          @endif
          @foreach($beritas->getUrlRange(max(1,$beritas->currentPage()-2), min($beritas->lastPage(),$beritas->currentPage()+2)) as $pg => $url)
            @if($pg == $beritas->currentPage())
              <span aria-current="page">{{ $pg }}</span>
            @else
              <a href="{{ $url }}">{{ $pg }}</a>
            @endif
          @endforeach
          @if($beritas->hasMorePages())
            <a href="{{ $beritas->nextPageUrl() }}">Selanjutnya →</a>
          @else
            <span style="opacity:.4">Selanjutnya →</span>
          @endif
        </div>
      @endif

    @endif
  </div>
</section>

@endsection
