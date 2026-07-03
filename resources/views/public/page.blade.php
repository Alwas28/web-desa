@extends('layouts.public')

@section('title', ($page->meta_title ?: $page->judul) . ' — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', $page->meta_deskripsi ?: $page->ringkasan)
@section('og_type', 'article')
@if($page->gambar_url)@section('og_image', $page->gambar_url)@endif

@push('head')
<style>
/* ── Page Hero ── */
.page-hero{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.page-hero::before{content:"";position:absolute;inset:0;background-image:radial-gradient(circle at 80% 50%,rgba(255,255,255,.07) 0%,transparent 60%)}
.page-hero-inner{position:relative}
.page-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.page-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.page-hero h1{font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;letter-spacing:-.025em;line-height:1.15}

/* ── Breadcrumb ── */
.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px}
.breadcrumb-bar b{color:var(--green);font-weight:700}

/* ── Article ── */
.article-wrap{padding:56px 0 80px}
.article-layout{display:grid;grid-template-columns:1fr 300px;gap:48px;align-items:start}
@media(max-width:900px){.article-layout{grid-template-columns:1fr}}

.article-cover{width:100%;max-height:420px;object-fit:cover;border-radius:18px;margin-bottom:36px;box-shadow:var(--shadow-lg)}

.prose{font-size:1rem;line-height:1.8;color:var(--ink)}
.prose h1,.prose h2,.prose h3,.prose h4{font-weight:800;letter-spacing:-.02em;line-height:1.25;margin:1.6em 0 .6em;color:var(--ink)}
.prose h1{font-size:1.8rem}
.prose h2{font-size:1.4rem}
.prose h3{font-size:1.15rem}
.prose p{margin-bottom:1.2em}
.prose a{color:var(--green);text-decoration:underline;text-underline-offset:3px}
.prose a:hover{color:var(--green-deep)}
.prose ul,.prose ol{padding-left:1.4em;margin-bottom:1.2em}
.prose li{margin-bottom:.4em}
.prose blockquote{border-left:4px solid var(--green);padding-left:1.2em;margin:1.4em 0;color:var(--muted);font-style:italic}
.prose img{border-radius:12px;box-shadow:var(--shadow);max-width:100%;margin:1.2em 0}
.prose table{width:100%;border-collapse:collapse;margin-bottom:1.4em;font-size:.9rem}
.prose th,.prose td{padding:10px 14px;border:1px solid var(--line);text-align:left}
.prose th{background:var(--surface-2);font-weight:700}
.prose hr{border:none;border-top:1px solid var(--line);margin:2em 0}

/* ── Sidebar ── */
.sidebar-card{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:20px;box-shadow:var(--shadow)}
.sidebar-card+.sidebar-card{margin-top:16px}
.sidebar-label{font-size:.72rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--green);margin-bottom:12px}
.sidebar-meta-item{display:flex;align-items:flex-start;gap:10px;font-size:.85rem;color:var(--muted);padding:8px 0;border-bottom:1px solid var(--line)}
.sidebar-meta-item:last-child{border-bottom:none}
.sidebar-meta-item svg{flex-shrink:0;margin-top:2px;color:var(--green)}
.sidebar-meta-item span{color:var(--ink);font-weight:600}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="page-hero">
  <div class="container page-hero-inner">
    <div class="eyebrow">Halaman</div>
    <h1>{{ $page->judul }}</h1>
  </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>{{ $page->judul }}</b>
  </div>
</div>

{{-- Content --}}
<section class="article-wrap">
  <div class="container">

    @if($page->gambar_url)
      <img src="{{ $page->gambar_url }}" alt="{{ $page->judul }}" class="article-cover">
    @endif

    <div class="article-layout">

      {{-- Konten utama --}}
      <article class="prose">
        {!! $page->konten !!}
      </article>

      {{-- Sidebar --}}
      <aside>
        <div class="sidebar-card">
          <div class="sidebar-label">Informasi</div>

          @if($page->published_at)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <div>Diterbitkan<br><span>{{ $page->published_at->locale('id')->translatedFormat('d F Y') }}</span></div>
          </div>
          @endif

          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
            <div>Dilihat<br><span>{{ number_format($page->dilihat ?? 0) }} kali</span></div>
          </div>
        </div>

        <div class="sidebar-card" style="margin-top:16px">
          <div class="sidebar-label">Navigasi</div>
          <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:600;color:var(--green);padding:4px 0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Beranda
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>

@endsection
