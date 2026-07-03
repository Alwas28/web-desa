@extends('layouts.public')

@section('title', 'Pengumuman — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', 'Daftar pengumuman resmi dari Pemerintah Desa ' . ($desaInfo['desa.nama'] ?? '') . '. Informasi penting untuk masyarakat desa.')

@push('head')
<style>
.page-hero{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.page-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(255,255,255,.07),transparent 60%)}
.page-hero-inner{position:relative}
.page-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.page-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.page-hero h1{font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.page-hero p{font-size:.95rem;opacity:.82;max-width:520px}

.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

.ann-section{padding:48px 0 80px}
.ann-list{display:flex;flex-direction:column;gap:14px}
.ann-item{display:flex;gap:18px;align-items:flex-start;padding:22px 24px;background:var(--surface);border:1.5px solid var(--line);border-radius:18px;text-decoration:none;color:inherit;transition:.2s}
.ann-item:hover{border-color:var(--green);box-shadow:var(--shadow-lg);transform:translateX(4px)}
.ann-icon{flex-shrink:0;width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:var(--green-soft);color:var(--green)}
.ann-body{flex:1;min-width:0}
.ann-date{font-size:.74rem;color:var(--muted);font-weight:600;display:flex;align-items:center;gap:6px;margin-bottom:6px}
.ann-title{font-size:1rem;font-weight:800;line-height:1.4;color:var(--ink);margin-bottom:6px}
.ann-desc{font-size:.86rem;color:var(--muted);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.ann-thumb{width:100px;height:80px;border-radius:12px;object-fit:cover;flex-shrink:0}
.ann-arrow{flex-shrink:0;align-self:center;color:var(--muted);transition:.2s}
.ann-item:hover .ann-arrow{color:var(--green);transform:translateX(4px)}
@media(max-width:600px){.ann-thumb{display:none}.ann-item{padding:16px 18px}}

.empty{text-align:center;padding:72px 20px;color:var(--muted)}
.empty svg{margin:0 auto 18px;opacity:.35;display:block}
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
    <div class="eyebrow">Informasi Publik</div>
    <h1>Pengumuman Desa</h1>
    <p>Informasi dan pengumuman resmi dari Pemerintah Desa {{ $desaInfo['desa.nama'] ?? '' }}.</p>
  </div>
</div>

<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>Pengumuman</b>
  </div>
</div>

<section class="ann-section">
  <div class="container">

    @if($pengumumans->isEmpty())
      <div class="empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <h3>Belum Ada Pengumuman</h3>
        <p>Belum ada pengumuman yang dipublikasikan saat ini.</p>
      </div>
    @else

      <div style="font-size:.84rem;color:var(--muted);margin-bottom:20px">
        Menampilkan {{ $pengumumans->firstItem() }}–{{ $pengumumans->lastItem() }} dari {{ $pengumumans->total() }} pengumuman
      </div>

      <div class="ann-list">
        @foreach($pengumumans as $ann)
          <a href="{{ route('pengumuman.show', $ann->id) }}" class="ann-item">
            @if($ann->gambar_url)
              <img src="{{ $ann->gambar_url }}" alt="{{ $ann->judul }}" class="ann-thumb" loading="lazy">
            @else
              <div class="ann-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              </div>
            @endif
            <div class="ann-body">
              <div class="ann-date">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $ann->published_at?->locale('id')->translatedFormat('d F Y') ?? '' }}
              </div>
              <div class="ann-title">{{ $ann->judul }}</div>
              <div class="ann-desc">{{ $ann->ringkasan }}</div>
            </div>
            <svg class="ann-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
          </a>
        @endforeach
      </div>

      @if($pengumumans->hasPages())
        <div class="pagination-wrap">
          @if($pengumumans->onFirstPage())
            <span style="opacity:.4">← Sebelumnya</span>
          @else
            <a href="{{ $pengumumans->previousPageUrl() }}">← Sebelumnya</a>
          @endif
          @foreach($pengumumans->getUrlRange(max(1,$pengumumans->currentPage()-2), min($pengumumans->lastPage(),$pengumumans->currentPage()+2)) as $pg => $url)
            @if($pg == $pengumumans->currentPage())
              <span aria-current="page">{{ $pg }}</span>
            @else
              <a href="{{ $url }}">{{ $pg }}</a>
            @endif
          @endforeach
          @if($pengumumans->hasMorePages())
            <a href="{{ $pengumumans->nextPageUrl() }}">Selanjutnya →</a>
          @else
            <span style="opacity:.4">Selanjutnya →</span>
          @endif
        </div>
      @endif

    @endif
  </div>
</section>

@endsection
