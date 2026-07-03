@extends('layouts.public')

@section('title', $p->judul . ' — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', $p->ringkasan)
@section('og_type', 'article')
@if($p->gambar_url)@section('og_image', $p->gambar_url)@endif

@push('head')
<style>
.page-hero-ann{background:linear-gradient(135deg,#1a5c38 0%,#0e3d5c 100%);padding:48px 0 52px;color:#fff;position:relative;overflow:hidden}
.page-hero-ann::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 75% 50%,rgba(255,255,255,.06),transparent 60%)}
.hero-ann-inner{position:relative}
.ann-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.28);color:#fff;font-size:.72rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:5px 13px;border-radius:99px;margin-bottom:14px}
.page-hero-ann h1{font-size:clamp(1.5rem,3.5vw,2.3rem);font-weight:800;letter-spacing:-.025em;line-height:1.25;margin-bottom:16px}
.ann-meta{display:flex;flex-wrap:wrap;gap:16px;font-size:.82rem;opacity:.88}
.ann-meta span{display:flex;align-items:center;gap:6px}

.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

.ann-wrap{padding:48px 0 80px}
.ann-layout{display:grid;grid-template-columns:1fr 280px;gap:44px;align-items:start}
@media(max-width:860px){.ann-layout{grid-template-columns:1fr}}

.ann-cover{width:100%;max-height:400px;object-fit:cover;border-radius:16px;margin-bottom:32px;box-shadow:var(--shadow-lg)}

.prose{font-size:1.01rem;line-height:1.85;color:var(--ink);overflow-x:hidden;word-break:break-word;overflow-wrap:break-word}
.prose *{max-width:100% !important;box-sizing:border-box}
.prose p,.prose span,.prose div,.prose li,.prose td{background-color:transparent !important;color:inherit}
.prose h2,.prose h3,.prose h4{font-weight:800;letter-spacing:-.02em;margin:1.5em 0 .6em;color:var(--ink)}
.prose h2{font-size:1.35rem}.prose h3{font-size:1.1rem}
.prose p{margin-bottom:1.2em}
.prose a{color:var(--green);text-decoration:underline;text-underline-offset:3px}
.prose ul,.prose ol{padding-left:1.4em;margin-bottom:1.2em}
.prose li{margin-bottom:.4em}
.prose blockquote{border-left:4px solid var(--green);padding:12px 20px;margin:1.4em 0;color:var(--muted);font-style:italic;background:var(--surface-2) !important;border-radius:0 12px 12px 0}
.prose img{border-radius:12px;max-width:100% !important;height:auto;margin:1.2em 0;display:block}
.prose table{width:100%;border-collapse:collapse;margin-bottom:1.4em;font-size:.9rem;display:block;overflow-x:auto}
.prose th,.prose td{padding:10px 14px;border:1px solid var(--line)}
.prose th{background:var(--surface-2) !important;font-weight:700}
.prose hr{border:none;border-top:1px solid var(--line);margin:2em 0}

.sidebar-card{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:20px;box-shadow:var(--shadow)}
.sidebar-label{font-size:.72rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--green);margin-bottom:14px}
.sidebar-meta-item{display:flex;align-items:flex-start;gap:10px;font-size:.84rem;color:var(--muted);padding:9px 0;border-bottom:1px solid var(--line)}
.sidebar-meta-item:last-child{border-bottom:none;padding-bottom:0}
.sidebar-meta-item svg{flex-shrink:0;margin-top:2px;color:var(--green)}
.sidebar-meta-item strong{display:block;color:var(--ink);font-weight:700;margin-top:2px}

.rel-item{display:flex;gap:10px;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--line);text-decoration:none;transition:.15s}
.rel-item:last-child{border-bottom:none;padding-bottom:0}
.rel-item:hover .rel-title{color:var(--green)}
.rel-icon{width:38px;height:38px;border-radius:10px;background:var(--green-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--green)}
.rel-date{font-size:.71rem;color:var(--muted);margin-bottom:3px}
.rel-title{font-size:.82rem;font-weight:700;color:var(--ink);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:.15s}

.share-bar{display:flex;align-items:center;gap:10px;margin-top:32px;padding-top:24px;border-top:1px solid var(--line);flex-wrap:wrap}
.share-bar span{font-size:.82rem;font-weight:700;color:var(--muted)}
.share-btn{display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:700;padding:7px 14px;border-radius:10px;transition:.2s;text-decoration:none;cursor:pointer;border:none}
.share-btn.wa{background:#25D366;color:#fff}.share-btn.wa:hover{background:#1ebe5a}
.share-btn.cp{background:var(--surface-2);color:var(--ink);border:1px solid var(--line)}.share-btn.cp:hover{border-color:var(--green);color:var(--green)}
</style>
@endpush

@section('content')

<div class="page-hero-ann">
  <div class="container hero-ann-inner">
    <div class="ann-badge">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Pengumuman
    </div>
    <h1>{{ $p->judul }}</h1>
    <div class="ann-meta">
      <span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        {{ $p->published_at?->locale('id')->translatedFormat('d F Y') ?? '' }}
      </span>
      @if($p->user)
      <span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        {{ $p->user->name }}
      </span>
      @endif
    </div>
  </div>
</div>

<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <a href="{{ route('pengumuman') }}">Pengumuman</a>
    <span>›</span>
    <b>{{ Str::limit($p->judul, 40) }}</b>
  </div>
</div>

<section class="ann-wrap">
  <div class="container">

    @if($p->gambar_url)
      <img src="{{ $p->gambar_url }}" alt="{{ $p->judul }}" class="ann-cover">
    @endif

    <div class="ann-layout">

      <div>
        <article class="prose">
          {!! $p->konten !!}
        </article>

        <div class="share-bar">
          <span>Bagikan:</span>
          <a href="https://wa.me/?text={{ urlencode($p->judul . ' ' . route('pengumuman.show', $p->id)) }}"
             target="_blank" rel="noopener" class="share-btn wa">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.4-.7-2.9-1.2-4.7-4.1-4.9-4.3-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.8 1.7.3.2.6.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.2.5.3.6.4.1.2.1.7-.3 1.2z"/></svg>
            WhatsApp
          </a>
          <button onclick="navigator.clipboard.writeText('{{ route('pengumuman.show', $p->id) }}').then(()=>this.textContent='✓ Disalin!')" class="share-btn cp">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            Salin Link
          </button>
        </div>
      </div>

      <aside>
        <div class="sidebar-card">
          <div class="sidebar-label">Informasi</div>
          @if($p->published_at)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <div>Diterbitkan<strong>{{ $p->published_at->locale('id')->translatedFormat('d F Y') }}</strong></div>
          </div>
          @endif
          @if($p->user)
          <div class="sidebar-meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <div>Diterbitkan oleh<strong>{{ $p->user->name }}</strong></div>
          </div>
          @endif
        </div>

        @if($related->isNotEmpty())
        <div class="sidebar-card" style="margin-top:14px">
          <div class="sidebar-label">Pengumuman Lainnya</div>
          @foreach($related as $r)
            <a href="{{ route('pengumuman.show', $r->id) }}" class="rel-item">
              <div class="rel-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              </div>
              <div>
                <div class="rel-date">{{ $r->published_at?->locale('id')->translatedFormat('d M Y') ?? '' }}</div>
                <div class="rel-title">{{ $r->judul }}</div>
              </div>
            </a>
          @endforeach
        </div>
        @endif

        <div class="sidebar-card" style="margin-top:14px">
          <a href="{{ route('pengumuman') }}" style="display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:700;color:var(--green)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
            Semua Pengumuman
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>

@endsection
