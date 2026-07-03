@extends('layouts.public')

@section('title', ($berita->meta_title ?: $berita->judul) . ' — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', $berita->meta_deskripsi ?: $berita->ringkasan)
@section('og_type', 'article')
@if($berita->gambar_url)@section('og_image', $berita->gambar_url)@endif

@push('head')
{{-- JSON-LD NewsArticle --}}
@php
$_article = [
    '@context'         => 'https://schema.org',
    '@type'            => 'NewsArticle',
    'headline'         => $berita->meta_title ?: $berita->judul,
    'description'      => $berita->meta_deskripsi ?: $berita->ringkasan,
    'url'              => route('berita.show', $berita->slug),
    'datePublished'    => $berita->published_at?->toIso8601String(),
    'dateModified'     => $berita->updated_at?->toIso8601String(),
    'author'           => ['@type' => 'Organization', 'name' => 'Pemerintah Desa ' . ($desaInfo['desa.nama'] ?? '')],
    'publisher'        => [
        '@type' => 'Organization',
        'name'  => 'Pemerintah Desa ' . ($desaInfo['desa.nama'] ?? ''),
        'logo'  => ['@type' => 'ImageObject', 'url' => $desaLogoUrl ?? ''],
    ],
    'articleSection'   => $berita->kategori?->nama ?? 'Berita',
];
if ($berita->gambar_url) {
    $_article['image'] = ['@type' => 'ImageObject', 'url' => $berita->gambar_url];
}
if ($berita->meta_keywords) {
    $_article['keywords'] = $berita->meta_keywords;
}
@endphp
<script type="application/ld+json">{!! json_encode($_article, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>

<style>
.page-hero-article{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:52px 0 56px;color:#fff;position:relative;overflow:hidden}
.page-hero-article::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 75% 50%,rgba(255,255,255,.07),transparent 60%)}
.page-hero-article .hero-txt{position:relative}
.page-hero-article .cat-pill{display:inline-block;background:rgba(255,255,255,.2);color:#fff;font-size:.72rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:5px 13px;border-radius:99px;margin-bottom:14px;border:1px solid rgba(255,255,255,.3);text-decoration:none;transition:.2s}
.page-hero-article .cat-pill:hover{background:rgba(255,255,255,.3)}
.page-hero-article h1{font-size:clamp(1.6rem,3.8vw,2.5rem);font-weight:800;letter-spacing:-.025em;line-height:1.2;margin-bottom:18px}
.article-meta{display:flex;flex-wrap:wrap;align-items:center;gap:18px;font-size:.82rem;opacity:.88}
.article-meta span{display:flex;align-items:center;gap:6px}

.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

.article-wrap{padding:52px 0 80px;overflow-x:hidden}
.article-layout{display:grid;grid-template-columns:1fr 300px;gap:48px;align-items:start;min-width:0}
.article-layout>*{min-width:0;overflow-x:hidden}
@media(max-width:900px){.article-layout{grid-template-columns:1fr}}

.article-cover{width:100%;max-height:440px;object-fit:cover;border-radius:18px;margin-bottom:36px;box-shadow:var(--shadow-lg)}

.prose{font-size:1.02rem;line-height:1.85;color:var(--ink);overflow-x:hidden;word-break:break-word;overflow-wrap:break-word}
.prose *{max-width:100% !important;box-sizing:border-box}
.prose p,.prose span,.prose div,.prose li,.prose td{background-color:transparent !important;color:inherit}
.prose h1,.prose h2,.prose h3,.prose h4{font-weight:800;letter-spacing:-.02em;line-height:1.25;margin:1.6em 0 .6em;color:var(--ink)}
.prose h2{font-size:1.45rem}.prose h3{font-size:1.18rem}
.prose p{margin-bottom:1.2em}
.prose a{color:var(--green);text-decoration:underline;text-underline-offset:3px}
.prose ul,.prose ol{padding-left:1.4em;margin-bottom:1.2em}
.prose li{margin-bottom:.4em}
.prose blockquote{border-left:4px solid var(--green);padding:12px 20px;margin:1.4em 0;color:var(--muted);font-style:italic;background:var(--surface-2) !important;border-radius:0 12px 12px 0}
.prose img{border-radius:12px;box-shadow:var(--shadow);max-width:100% !important;width:auto;height:auto;margin:1.2em 0;display:block}
.prose table{width:100%;border-collapse:collapse;margin-bottom:1.4em;font-size:.9rem;display:block;overflow-x:auto}
.prose th,.prose td{padding:10px 14px;border:1px solid var(--line);text-align:left}
.prose th{background:var(--surface-2) !important;font-weight:700}
.prose hr{border:none;border-top:1px solid var(--line);margin:2em 0}
.prose iframe,.prose video{max-width:100% !important;height:auto}

/* Share bar */
.share-bar{display:flex;align-items:center;gap:10px;margin-top:36px;padding-top:28px;border-top:1px solid var(--line);flex-wrap:wrap}
.share-bar span{font-size:.82rem;font-weight:700;color:var(--muted)}
.share-btn{display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:700;padding:7px 14px;border-radius:10px;transition:.2s;text-decoration:none}
.share-btn.wa{background:#25D366;color:#fff}
.share-btn.wa:hover{background:#1ebe5a}
.share-btn.fb{background:#1877F2;color:#fff}
.share-btn.fb:hover{background:#0c66d9}
.share-btn.cp{background:var(--surface-2);color:var(--ink);border:1px solid var(--line)}
.share-btn.cp:hover{border-color:var(--green);color:var(--green)}

/* Sidebar */
.sidebar-card{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:20px;box-shadow:var(--shadow)}
.sidebar-label{font-size:.72rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--green);margin-bottom:14px}
.sidebar-meta-item{display:flex;align-items:flex-start;gap:10px;font-size:.84rem;color:var(--muted);padding:9px 0;border-bottom:1px solid var(--line)}
.sidebar-meta-item:last-child{border-bottom:none;padding-bottom:0}
.sidebar-meta-item svg{flex-shrink:0;margin-top:2px;color:var(--green)}
.sidebar-meta-item strong{display:block;color:var(--ink);font-weight:700;margin-top:2px}

/* Related */
.related-card{display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);text-decoration:none;transition:.15s}
.related-card:last-child{border-bottom:none;padding-bottom:0}
.related-card:hover .related-title{color:var(--green)}
.related-thumb{width:68px;height:56px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--surface-2)}
.related-thumb-placeholder{width:68px;height:56px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,var(--green-soft),var(--blue-soft));display:flex;align-items:center;justify-content:center}
.related-date{font-size:.72rem;color:var(--muted);margin-bottom:3px}
.related-title{font-size:.83rem;font-weight:700;color:var(--ink);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:.15s}
</style>
@endpush

@section('content')

{{-- Hero artikel --}}
<div class="page-hero-article">
  <div class="container">
    <div class="hero-txt">
    @if($berita->kategori)
      <a href="{{ route('berita.index', ['kategori' => $berita->kategori_id]) }}" class="cat-pill">{{ $berita->kategori->nama }}</a>
    @endif
    <h1>{{ $berita->judul }}</h1>
    <div class="article-meta">
      <span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        {{ $berita->published_at?->locale('id')->translatedFormat('d F Y') ?? '' }}
      </span>
      <span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
        {{ number_format($berita->dilihat) }} dibaca
      </span>
      @if($berita->user)
      <span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        {{ $berita->user->name }}
      </span>
      @endif
    </div>
    </div>{{-- /hero-txt --}}
  </div>{{-- /container --}}
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <a href="{{ route('berita.index') }}">Berita</a>
    @if($berita->kategori)
      <span>›</span>
      <a href="{{ route('berita.index', ['kategori' => $berita->kategori_id]) }}">{{ $berita->kategori->nama }}</a>
    @endif
    <span>›</span>
    <b>{{ Str::limit($berita->judul, 40) }}</b>
  </div>
</div>

{{-- Konten --}}
<section class="article-wrap">
  <div class="container">

    @if($berita->gambar_url)
      <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="article-cover">
    @endif

    <div class="article-layout">

      {{-- Artikel utama --}}
      <div>
        <article class="prose">
          {!! $berita->konten !!}
        </article>

        {{-- Share --}}
        <div class="share-bar">
          <span>Bagikan:</span>
          <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . route('berita.show', $berita->slug)) }}"
             target="_blank" rel="noopener" class="share-btn wa">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.4-.7-2.9-1.2-4.7-4.1-4.9-4.3-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.8 1.7.3.2.6.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.2.5.3.6.4.1.2.1.7-.3 1.2z"/></svg>
            WhatsApp
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('berita.show', $berita->slug)) }}"
             target="_blank" rel="noopener" class="share-btn fb">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3l-.5 3H14v9h-3.5v-9H8V9h2.5V7.2C10.5 4.8 12 3 14.6 3H17v3h-1.8c-.8 0-1.2.4-1.2 1.2z"/></svg>
            Facebook
          </a>
          <button onclick="navigator.clipboard.writeText('{{ route('berita.show', $berita->slug) }}').then(()=>this.textContent='✓ Disalin!')" class="share-btn cp">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            Salin Link
          </button>
        </div>
      </div>

      {{-- Sidebar --}}
      <aside>
        <div class="sidebar-card">
          <div class="sidebar-label">Informasi Artikel</div>
          @if($berita->published_at)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <div>Diterbitkan<strong>{{ $berita->published_at->locale('id')->translatedFormat('d F Y') }}</strong></div>
          </div>
          @endif
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
            <div>Dibaca<strong>{{ number_format($berita->dilihat) }} kali</strong></div>
          </div>
          @if($berita->kategori)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 7h16M4 12h10M4 17h6"/></svg>
            <div>Kategori<strong><a href="{{ route('berita.index', ['kategori' => $berita->kategori_id]) }}" style="color:var(--green)">{{ $berita->kategori->nama }}</a></strong></div>
          </div>
          @endif
          @if($berita->user)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <div>Penulis<strong>{{ $berita->user->name }}</strong></div>
          </div>
          @endif
        </div>

        @if($related->isNotEmpty())
        <div class="sidebar-card" style="margin-top:16px">
          <div class="sidebar-label">Berita Terkait</div>
          @foreach($related as $r)
            <a href="{{ route('berita.show', $r->slug) }}" class="related-card">
              @if($r->gambar_url)
                <img src="{{ $r->gambar_url }}" alt="{{ $r->judul }}" class="related-thumb" loading="lazy">
              @else
                <div class="related-thumb-placeholder">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.8" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2z"/></svg>
                </div>
              @endif
              <div>
                <div class="related-date">{{ $r->published_at?->locale('id')->translatedFormat('d M Y') ?? '' }}</div>
                <div class="related-title">{{ $r->judul }}</div>
              </div>
            </a>
          @endforeach
        </div>
        @endif

        <div class="sidebar-card" style="margin-top:16px">
          <a href="{{ route('berita.index') }}" style="display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:700;color:var(--green)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Berita
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>

@endsection
