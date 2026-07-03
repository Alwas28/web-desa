@extends('layouts.public')

@section('title', ($desaInfo['desa.nama'] ?? 'Desa') . ' — Website Resmi Pemerintah Desa')
@section('description', 'Website resmi ' . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa') . '. Layanan publik, informasi, dan potensi desa.')

@push('styles')
<style>
/* ── HERO BIASA ── */
.hero{padding:0;overflow:hidden;background:linear-gradient(180deg,var(--hero-sky),var(--hero-sky2))}
.hero-inner{display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;padding:clamp(48px,7vw,90px) 0 0}
.hero h1{font-size:clamp(2rem,4.6vw,3.4rem);font-weight:800;letter-spacing:-.03em;line-height:1.12}
.hero h1 em{font-family:'Source Serif 4',serif;font-style:italic;font-weight:500;background:linear-gradient(90deg,var(--green),var(--blue));-webkit-background-clip:text;background-clip:text;color:transparent}
.hero p.lead{color:var(--muted);margin:18px 0 26px;max-width:520px;font-size:1.02rem}
.hero-cta{display:flex;gap:12px;flex-wrap:wrap}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:var(--glass);border:1px solid var(--glass-line);backdrop-filter:blur(8px);padding:7px 14px;border-radius:99px;font-size:.78rem;font-weight:700;color:var(--green);box-shadow:var(--shadow);margin-bottom:18px}
.hero-badge .dot{width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px var(--green-soft);animation:pulse 2s infinite}
@keyframes pulse{50%{box-shadow:0 0 0 7px transparent}}
.hero-art{position:relative}
.hero-strip{position:relative;margin-top:clamp(30px,5vw,56px)}
@media(max-width:880px){.hero-inner{grid-template-columns:1fr}.hero-art{order:-1;max-width:520px;margin-inline:auto}}

/* ── HERO SLIDE ── */
.hero-slide{position:relative;height:100vh;height:100svh;overflow:hidden}
.hero-slide .slide{position:absolute;inset:0;opacity:0;transition:opacity .8s ease;background-size:cover;background-position:center}
.hero-slide .slide.active{opacity:1}
.hero-slide .slide-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(5,20,12,.8) 0%,rgba(5,20,12,.45) 50%,rgba(5,20,12,.15) 100%)}
.hero-slide .slide-content{position:absolute;inset:0;display:flex;align-items:center;padding:clamp(32px,5vw,60px)}
.hero-slide .slide-content .container{position:relative;z-index:2}
.hero-slide h1{font-size:clamp(1.8rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.15;text-shadow:0 2px 16px rgba(0,0,0,.4)}
.hero-slide p.lead{color:rgba(255,255,255,.85);font-size:clamp(.95rem,1.8vw,1.1rem);margin-top:12px;max-width:600px;text-shadow:0 1px 8px rgba(0,0,0,.3)}
.slide-nav{position:absolute;bottom:24px;right:24px;display:flex;gap:8px;align-items:center;z-index:10}
.slide-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4);border:none;cursor:pointer;transition:.3s;padding:0}
.slide-dot.active{width:24px;border-radius:4px;background:#fff}
.slide-arrow{width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);backdrop-filter:blur(8px);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.2s;padding:0}
.slide-arrow:hover{background:rgba(255,255,255,.32)}
.slide-strip{position:relative;height:14px;background:var(--bg)}
.scroll-hint{position:absolute;bottom:30px;left:24px;z-index:10;display:flex;flex-direction:column;align-items:center;gap:5px;color:rgba(255,255,255,.65);font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;animation:bounceY 2.2s ease-in-out infinite}
.scroll-hint svg{opacity:.75}
@keyframes bounceY{0%,100%{transform:translateY(0)}50%{transform:translateY(7px)}}

/* ── HERO VIDEO ── */
.hero-video{position:relative;height:100vh;height:100svh;overflow:hidden;background:#050D08}
.hero-video .video-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.hero-video iframe.video-bg{width:177.78vh;min-width:100%;height:56.25vw;min-height:100%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none}
.hero-video .video-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(5,20,12,.78) 0%,rgba(5,20,12,.25) 55%,rgba(5,20,12,.15) 100%)}
.hero-video .video-content{position:absolute;inset:0;display:flex;align-items:center}
.hero-video h1{font-size:clamp(1.8rem,4.5vw,3.2rem);font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.15;text-shadow:0 2px 20px rgba(0,0,0,.5)}
.hero-video p.lead{color:rgba(255,255,255,.85);font-size:clamp(.95rem,1.8vw,1.1rem);margin-top:14px;max-width:600px}
.video-strip{position:relative;height:14px;background:var(--bg)}

/* ── STATS ── */
.stats{margin-top:-58px;position:relative;z-index:5;padding:0 0 8px}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.stat-card{background:var(--glass);border:1px solid var(--glass-line);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-radius:var(--radius);padding:20px 18px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:8px;transition:.25s}
.stat-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.stat-card .ic{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;color:#fff}
.stat-card .num{font-size:1.65rem;font-weight:800;letter-spacing:-.02em;line-height:1}
.stat-card .num span{font-size:.95rem;color:var(--muted);font-weight:700}
.stat-card .lbl{font-size:.78rem;color:var(--muted);font-weight:600}
@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr)}.stats{margin-top:-30px}}

/* ── PROFIL ── */
.profil-grid{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:34px}
.kades{padding:30px;display:flex;flex-direction:column;gap:16px}
.kades .quote{font-family:'Source Serif 4',serif;font-style:italic;font-size:1.12rem;line-height:1.7;color:var(--ink)}
.kades .who{display:flex;align-items:center;gap:14px}
.avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--green),var(--blue));display:grid;place-items:center;color:#fff;font-weight:800;font-size:1.1rem;flex-shrink:0;overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.tab-btn{padding:9px 16px;border-radius:99px;font-size:.82rem;font-weight:700;border:1px solid var(--line);background:var(--surface);color:var(--muted);transition:.2s}
.tab-btn.active{background:var(--green);border-color:var(--green);color:#fff}
.tab-pane{display:none;animation:fadein .35s}
.tab-pane.show{display:block}
@keyframes fadein{from{opacity:0;transform:translateY(8px)}to{opacity:1}}
.visi-list{list-style:none;display:grid;gap:10px;margin-top:10px}
.visi-list li{display:flex;gap:10px;align-items:flex-start;color:var(--muted);font-size:.92rem}
.visi-list li::before{content:"✓";color:var(--green);font-weight:800;background:var(--green-soft);width:22px;height:22px;border-radius:7px;display:grid;place-items:center;font-size:.75rem;flex-shrink:0;margin-top:2px}
.org{display:grid;gap:10px;margin-top:10px}
.org-row{display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid var(--line);border-radius:13px;background:var(--surface-2)}
.org-row b{font-size:.9rem}
.org-row small{color:var(--muted);display:block;font-size:.76rem}
@media(max-width:880px){.profil-grid{grid-template-columns:1fr}}

/* ── LAYANAN ── */
#layanan{background:var(--surface-2)}
.svc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:36px}
.svc{padding:26px;display:flex;flex-direction:column;gap:12px;position:relative;overflow:hidden}
.svc:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:var(--green)}
.svc .ic{width:52px;height:52px;border-radius:15px;display:grid;place-items:center;color:#fff;box-shadow:var(--shadow)}
.svc h3{font-size:1.05rem;font-weight:800}
.svc p{color:var(--muted);font-size:.88rem;flex:1}
.svc .go{font-weight:700;font-size:.85rem;color:var(--green);display:inline-flex;gap:6px;align-items:center}
.svc .go svg{transition:.2s}
.svc:hover .go svg{transform:translateX(4px)}
@media(max-width:880px){.svc-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.svc-grid{grid-template-columns:1fr}}

/* ── BERITA ── */
.news-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:36px}
.news{overflow:hidden;display:flex;flex-direction:column}
.news:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg)}
.news .thumb{height:180px;position:relative;display:grid;place-items:center;color:#fff}
.news .cat{position:absolute;top:12px;left:12px;background:rgba(255,255,255,.92);color:var(--green-deep);font-size:.7rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:5px 11px;border-radius:99px}
.news .body{padding:20px;display:flex;flex-direction:column;gap:10px;flex:1}
.news .date{font-size:.74rem;color:var(--muted);font-weight:600;display:flex;gap:6px;align-items:center}
.news h3{font-size:1rem;font-weight:800;line-height:1.4}
.news p{font-size:.86rem;color:var(--muted);flex:1;overflow-wrap:break-word;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.news .more{color:var(--blue);font-weight:700;font-size:.84rem}
@media(max-width:880px){.news-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.news-grid{grid-template-columns:1fr}}

/* ── PENGUMUMAN ── */
.ann-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:36px}
@media(max-width:700px){.ann-grid{grid-template-columns:1fr}}
.ann-card{display:flex;gap:16px;align-items:flex-start;padding:18px 20px;background:var(--surface);border:1.5px solid var(--line);border-radius:16px;text-decoration:none;color:inherit;transition:.2s}
.ann-card:hover{border-color:var(--green);box-shadow:var(--shadow-lg);transform:translateY(-3px)}
.ann-icon{flex-shrink:0;width:44px;height:44px;border-radius:12px;background:var(--green-soft);display:flex;align-items:center;justify-content:center;color:var(--green)}
.ann-body{min-width:0;flex:1}
.ann-date{font-size:.72rem;color:var(--muted);font-weight:600;margin-bottom:5px;display:flex;align-items:center;gap:5px}
.ann-title{font-size:.95rem;font-weight:800;line-height:1.4;margin-bottom:5px;color:var(--ink)}
.ann-desc{font-size:.82rem;color:var(--muted);line-height:1.55;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

/* ── PEJABAT ── */
.pejabat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:36px}
.pejabat-card{padding:24px 18px;display:flex;flex-direction:column;align-items:center;text-align:center;gap:12px}
.pejabat-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg)}
.pejabat-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--green),var(--blue));display:grid;place-items:center;color:#fff;font-weight:800;font-size:1.3rem;overflow:hidden;flex-shrink:0}
.pejabat-avatar img{width:100%;height:100%;object-fit:cover}
@media(max-width:880px){.pejabat-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.pejabat-grid{grid-template-columns:1fr 1fr}}

/* ── KONTAK ── */
.map-grid{display:grid;grid-template-columns:1fr .8fr;gap:20px;margin-top:36px}
.map-frame{border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--line);min-height:360px}
.map-frame iframe{width:100%;height:100%;min-height:360px;border:0}
.contact-card{padding:26px;display:grid;gap:14px;align-content:start}
.c-row{display:flex;gap:13px;align-items:flex-start}
.c-row .ic{width:42px;height:42px;border-radius:12px;background:var(--green-soft);color:var(--green);display:grid;place-items:center;flex-shrink:0}
.c-row b{font-size:.88rem;display:block}
.c-row span{font-size:.85rem;color:var(--muted)}
@media(max-width:880px){.map-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
@php
  $hTipe     = $headerSettings['header.tipe']    ?? 'biasa';
  $hJudul    = $headerSettings['header.judul']   ?? '';
  $hSubjudul = $headerSettings['header.subjudul']?? '';
  $hVideoUrl   = $headerSettings['header.video_url']   ?? '';
  $hBgVideo    = $headerSettings['header.bg_video']    ?? null;
  $hBgFoto     = $headerSettings['header.bg_foto']     ?? null;
  $hBgFotoUrl  = $hBgFoto ? Storage::url($hBgFoto) : null;
  $hBadge         = $headerSettings['header.badge']           ?? '';
  $hBtnLayanan    = $headerSettings['header.btn_layanan']     ?? '';
  $hBtnLayananUrl = $headerSettings['header.btn_layanan_url'] ?? '';
  $hBtnProfil     = $headerSettings['header.btn_profil']      ?? '';
  $hBtnProfilUrl  = $headerSettings['header.btn_profil_url']  ?? '';
  // Kosong (null atau '') = sembunyikan, ada isi = tampilkan
  $showBadge      = !empty($hBadge);
  $showBtnLayanan = !empty($hBtnLayanan);
  $showBtnProfil  = !empty($hBtnProfil);
  $urlBtnLayanan  = !empty($hBtnLayananUrl)
    ? (str_starts_with($hBtnLayananUrl, 'http') ? $hBtnLayananUrl : '#'.$hBtnLayananUrl)
    : '#layanan';
  $urlBtnProfil   = !empty($hBtnProfilUrl)
    ? (str_starts_with($hBtnProfilUrl, 'http') ? $hBtnProfilUrl : '#'.$hBtnProfilUrl)
    : '#profil';
  $defaultJudul    = 'Selamat Datang di Website Resmi ' . ($desaInfo['desa.nama'] ?? 'Desa Kami');
  $defaultSubjudul = 'Pusat informasi, pelayanan publik digital, dan transparansi pemerintahan desa. Melayani warga dengan cepat, terbuka, dan sepenuh hati.';
@endphp

{{-- ══════ HERO: SLIDE FOTO ══════ --}}
@if($hTipe === 'slide' && count($headerSlides))
<section class="hero-slide" id="beranda">
  @foreach($headerSlides as $si => $slide)
  <div class="slide{{ $si === 0 ? ' active' : '' }}"
       style="background-image:url('{{ Storage::url($slide['path']) }}')">
    <div class="slide-overlay"></div>
  </div>
  @endforeach
  <div class="slide-content">
    <div class="container">
      @if($showBadge)
        <span class="hero-badge" style="margin-bottom:16px"><span class="dot"></span> {{ $hBadge }}</span>
      @endif
      <h1 id="slide-title">{{ $headerSlides[0]['judul'] ?: ($hJudul ?: $defaultJudul) }}</h1>
      @php $sub0 = $headerSlides[0]['subjudul'] ?: ($hSubjudul ?: $defaultSubjudul); @endphp
      @if($sub0)<p class="lead" id="slide-sub">{{ $sub0 }}</p>@endif
      <div class="hero-cta" style="margin-top:22px">
        @if($showBtnLayanan)
          <a class="btn btn-primary" href="{{ $urlBtnLayanan }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 12h11m0 0-4-4m4 4-4 4M4 5v14"/></svg>
            {{ $hBtnLayanan }}
          </a>
        @endif
        @if($showBtnProfil)
          <a class="btn" style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.35);color:#fff;backdrop-filter:blur(6px)" href="{{ $urlBtnProfil }}">
            {{ $hBtnProfil }}
          </a>
        @endif
      </div>
    </div>
  </div>
  <a href="#statistik" class="scroll-hint" aria-label="Scroll ke bawah">
    <span>Scroll</span>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
  </a>
  @if(count($headerSlides) > 1)
  <div class="slide-nav">
    <button class="slide-arrow" onclick="slidePrev()" aria-label="Sebelumnya">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    @foreach($headerSlides as $si => $slide)
    <button class="slide-dot{{ $si === 0 ? ' active' : '' }}" onclick="slideTo({{ $si }})" aria-label="Slide {{ $si+1 }}"></button>
    @endforeach
    <button class="slide-arrow" onclick="slideNext()" aria-label="Berikutnya">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m9 18 6-6-6-6"/></svg>
    </button>
  </div>
  @endif
</section>
<div class="slide-strip"></div>

{{-- ══════ HERO: BACKGROUND VIDEO ══════ --}}
@elseif($hTipe === 'video' && ($hBgVideo || $hVideoUrl))
<section class="hero-video" id="beranda">
  @if($hBgVideo)
    {{-- Uploaded local video --}}
    <video class="video-bg" autoplay muted loop playsinline>
      <source src="{{ Storage::url($hBgVideo) }}" type="video/mp4">
    </video>
  @else
    @php
      preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $hVideoUrl, $ytm);
      $ytId = $ytm[1] ?? null;
    @endphp
    @if($ytId)
      <iframe class="video-bg"
        src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1"
        frameborder="0" allow="autoplay; encrypted-media" title="Hero Video"></iframe>
    @else
      <video class="video-bg" autoplay muted loop playsinline>
        <source src="{{ $hVideoUrl }}" type="video/mp4">
      </video>
    @endif
  @endif
  <div class="video-overlay"></div>
  <div class="video-content">
    <div class="container">
      @if($showBadge)
        <span class="hero-badge" style="margin-bottom:16px"><span class="dot"></span> {{ $hBadge }}</span>
      @endif
      <h1>{{ $hJudul ?: $defaultJudul }}</h1>
      @if($hSubjudul ?: $defaultSubjudul)
        <p class="lead">{{ $hSubjudul ?: $defaultSubjudul }}</p>
      @endif
      <div class="hero-cta" style="margin-top:22px">
        @if($showBtnLayanan)
          <a class="btn btn-primary" href="{{ $urlBtnLayanan }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 12h11m0 0-4-4m4 4-4 4M4 5v14"/></svg>
            {{ $hBtnLayanan }}
          </a>
        @endif
        @if($showBtnProfil)
          <a class="btn" style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.35);color:#fff;backdrop-filter:blur(6px)" href="{{ $urlBtnProfil }}">
            {{ $hBtnProfil }}
          </a>
        @endif
      </div>
    </div>
  </div>
  <a href="#statistik" class="scroll-hint" aria-label="Scroll ke bawah">
    <span>Scroll</span>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
  </a>
</section>
<div class="video-strip"></div>

{{-- ══════ HERO: BIASA DENGAN FOTO BACKGROUND ══════ --}}
@elseif($hTipe === 'biasa' && $hBgFotoUrl)
<section class="hero-slide" id="beranda">
  <div class="slide active" style="background-image:url('{{ $hBgFotoUrl }}')">
    <div class="slide-overlay"></div>
  </div>
  <div class="slide-content">
    <div class="container">
      @if($showBadge)
        <span class="hero-badge" style="margin-bottom:16px"><span class="dot"></span> {{ $hBadge }}</span>
      @endif
      <h1>{{ $hJudul ?: $defaultJudul }}</h1>
      @php $sub = $hSubjudul ?: $defaultSubjudul; @endphp
      @if($sub)<p class="lead">{{ $sub }}</p>@endif
      <div class="hero-cta" style="margin-top:22px">
        @if($showBtnLayanan)
          <a class="btn btn-primary" href="{{ $urlBtnLayanan }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 12h11m0 0-4-4m4 4-4 4M4 5v14"/></svg>
            {{ $hBtnLayanan }}
          </a>
        @endif
        @if($showBtnProfil)
          <a class="btn btn-outline" href="{{ $urlBtnProfil }}" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.4);color:#fff">
            {{ $hBtnProfil }}
          </a>
        @endif
      </div>
    </div>
  </div>
  <a href="#statistik" class="scroll-hint" aria-label="Scroll ke bawah">
    <span>Scroll</span>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
  </a>
</section>
<div class="slide-strip"></div>

{{-- ══════ HERO: BIASA (DEFAULT - ILUSTRASI) ══════ --}}
@else
<section class="hero" id="beranda">
  <div class="container hero-inner">
    <div>
      @if($showBadge)
        <span class="hero-badge reveal in"><span class="dot"></span> {{ $hBadge }}</span>
      @endif
      <h1 class="reveal in">
        @if($hJudul)
          {{ $hJudul }}
        @else
          Selamat Datang di Website Resmi<br>
          <em>{{ $desaInfo['desa.nama'] ?? 'Desa Kami' }}</em>
        @endif
      </h1>
      <p class="lead reveal in" data-d="1">
        {{ $hSubjudul ?: $defaultSubjudul }}
        @if(!$hSubjudul && (($desaInfo['desa.kecamatan'] ?? null) || ($desaInfo['desa.kabupaten'] ?? null)))
          <br><span style="font-size:.92rem;opacity:.75">
            {{ implode(', ', array_filter(['Kec. '.($desaInfo['desa.kecamatan'] ?? null), $desaInfo['desa.kabupaten'] ?? null, $desaInfo['desa.provinsi'] ?? null])) }}
          </span>
        @endif
      </p>
      <div class="hero-cta reveal in" data-d="2">
        @if($showBtnLayanan)
          <a class="btn btn-primary" href="{{ $urlBtnLayanan }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 12h11m0 0-4-4m4 4-4 4M4 5v14"/></svg>
            {{ $hBtnLayanan }}
          </a>
        @endif
        @if($showBtnProfil)
          <a class="btn btn-outline" href="{{ $urlBtnProfil }}">{{ $hBtnProfil }}</a>
        @endif
      </div>
    </div>
    <div class="hero-art reveal in" data-d="1" aria-hidden="true">
      <svg viewBox="0 0 560 420" role="img" aria-label="Ilustrasi panorama desa">
        <defs>
          <linearGradient id="sun" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FFD66B"/><stop offset="1" stop-color="#F5A93B"/></linearGradient>
          <linearGradient id="hill1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#2FA56B"/><stop offset="1" stop-color="#0C7C46"/></linearGradient>
          <linearGradient id="hill2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#56BE8B"/><stop offset="1" stop-color="#1E9059"/></linearGradient>
          <linearGradient id="water" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#4FA3DE"/><stop offset="1" stop-color="#0E63A8"/></linearGradient>
        </defs>
        <circle cx="430" cy="84" r="46" fill="url(#sun)" opacity=".95"/>
        <circle cx="430" cy="84" r="64" fill="#FFD66B" opacity=".18"/>
        <path d="M0 230 Q140 130 290 215 T560 200 V420 H0 Z" fill="url(#hill2)" opacity=".85"/>
        <path d="M0 280 Q170 180 340 265 T560 250 V420 H0 Z" fill="url(#hill1)"/>
        <path d="M30 330 Q160 300 300 322" stroke="#fff" stroke-width="3" fill="none" opacity=".35" stroke-linecap="round"/>
        <path d="M50 360 Q190 330 330 352" stroke="#fff" stroke-width="3" fill="none" opacity=".3" stroke-linecap="round"/>
        <path d="M380 420 C400 360 360 330 420 290 C460 264 470 240 466 226 L500 226 C512 252 488 282 458 306 C420 336 452 372 440 420 Z" fill="url(#water)" opacity=".9"/>
        <g transform="translate(120,196)">
          <rect x="14" y="46" width="92" height="58" rx="6" fill="#FFFFFF"/>
          <path d="M2 50 L60 8 L118 50 Z" fill="#C2452F"/>
          <path d="M14 50 L60 18 L106 50 Z" fill="#E0563C"/>
          <rect x="48" y="68" width="24" height="36" rx="3" fill="#0E63A8"/>
          <rect x="24" y="60" width="16" height="14" rx="2" fill="#BEE3F7"/>
          <rect x="80" y="60" width="16" height="14" rx="2" fill="#BEE3F7"/>
        </g>
        <g transform="translate(470,250)" stroke-linecap="round">
          <path d="M10 90 C6 60 8 36 14 16" stroke="#7A4A2B" stroke-width="7" fill="none"/>
          <path d="M14 16 C-6 8 -16 14 -22 24 M14 16 C8 0 -2 -6 -12 -6 M14 16 C20 0 32 -6 42 -2 M14 16 C34 8 44 16 48 26" stroke="#1E9059" stroke-width="7" fill="none"/>
        </g>
        <path d="M250 80 q8 -8 16 0 q8 -8 16 0 M310 60 q7 -7 14 0 q7 -7 14 0" stroke="#5B7D96" stroke-width="2.4" fill="none" stroke-linecap="round" opacity=".7"/>
      </svg>
    </div>
  </div>
  <div class="hero-strip"></div>
</section>
@endif

{{-- ── STATISTIK ── --}}
<div id="statistik" class="stats" aria-label="Statistik desa">
  <div class="container stats-grid">
    <div class="stat-card reveal">
      <span class="ic" style="background:linear-gradient(135deg,#0C7C46,#1E9059)">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </span>
      <span class="num">{{ number_format($jumlahPenduduk) }} <span>jiwa</span></span>
      <span class="lbl">Jumlah Penduduk</span>
    </div>
    <div class="stat-card reveal" data-d="1">
      <span class="ic" style="background:linear-gradient(135deg,#0E63A8,#3F8FC9)">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10M9 20v-5h6v5"/></svg>
      </span>
      <span class="num">{{ number_format($jumlahKK) }} <span>KK</span></span>
      <span class="lbl">Kepala Keluarga</span>
    </div>
    <div class="stat-card reveal" data-d="2">
      <span class="ic" style="background:linear-gradient(135deg,#1E9059,#56BE8B)">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
      </span>
      <span class="num">{{ $layanan->count() }} <span>jenis</span></span>
      <span class="lbl">Layanan Tersedia</span>
    </div>
    <div class="stat-card reveal" data-d="3">
      <span class="ic" style="background:linear-gradient(135deg,#C99A2C,#E0B854)">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 9h16l-1.5 11h-13zM4 9l2-5h12l2 5M9 13v3M15 13v3"/></svg>
      </span>
      <span class="num">{{ $berita->count() }} <span>berita</span></span>
      <span class="lbl">Berita Diterbitkan</span>
    </div>
  </div>
</div>

{{-- ── PROFIL DESA ── --}}
<section id="profil">
  <div class="container">
    <span class="eyebrow reveal">Profil &amp; Pemerintahan</span>
    <h2 class="sec-title reveal">Mengenal {{ $desaInfo['desa.nama'] ?? 'Desa Kami' }}</h2>
    <p class="sec-desc reveal" data-d="1">Sejarah, visi-misi, dan struktur pemerintahan yang melayani warga secara terbuka dan akuntabel.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="profil-grid">
      {{-- Sambutan Kepala Desa --}}
      @php
        $kades = $pejabat->first(fn($p) => $p->jabatan && str_contains(strtolower($p->jabatan->nama), 'kepala'));
        $kadesNama = $kades?->nama ?? ($desaInfo['desa.kepala'] ?? null);
        $kadesInisial = $kadesNama ? collect(explode(' ', $kadesNama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') : 'KD';
      @endphp
      <div class="card kades reveal">
        <span class="eyebrow" style="font-size:.72rem">Sambutan Kepala Desa</span>
        @php
          $sambutanFull = $desaInfo['desa.sambutan'] ?? '';
          $sambutanRingkasan = $sambutanFull
            ? \Illuminate\Support\Str::limit($sambutanFull, 200)
            : 'Kami berkomitmen untuk mewujudkan pemerintahan yang terbuka dan melayani. Setiap layanan, informasi, dan potensi desa kami sajikan agar warga dapat mengakses, mengawasi, dan ikut membangun bersama.';
        @endphp
        <p class="quote">"{{ $sambutanRingkasan }}"</p>
        <div class="who">
          <span class="avatar">
            @if($kades?->foto_url)
              <img src="{{ $kades->foto_url }}" alt="{{ $kadesNama }}">
            @else
              {{ $kadesInisial }}
            @endif
          </span>
          <div>
            <b style="font-size:.95rem">{{ $kadesNama ?? 'Kepala Desa' }}</b>
            <small style="color:var(--muted);display:block">
              Kepala Desa {{ $desaInfo['desa.nama'] ?? '' }}
            </small>
          </div>
        </div>
        <a href="{{ route('profil-desa') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:700;color:var(--green);margin-top:4px;text-decoration:none">
          Selengkapnya
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      {{-- Tabs: Visi Misi & Struktur --}}
      <div class="card reveal" data-d="1" style="padding:26px">
        <div class="tabs" role="tablist">
          <button class="tab-btn active" data-tab="visi" role="tab">Visi &amp; Misi</button>
          <button class="tab-btn" data-tab="struktur" role="tab">Struktur Organisasi</button>
        </div>

        <div class="tab-pane show" id="tab-visi" role="tabpanel">
          @if($desaInfo['desa.visi'] ?? null)
            <p style="font-size:.92rem;font-weight:700;line-height:1.6">"{{ $desaInfo['desa.visi'] }}"</p>
            @if($desaInfo['desa.misi'] ?? null)
              <ul class="visi-list" style="margin-top:14px">
                @foreach(explode("\n", $desaInfo['desa.misi']) as $misi)
                  @if(trim($misi))
                    <li>{{ trim($misi) }}</li>
                  @endif
                @endforeach
              </ul>
            @endif
          @else
            <p style="font-size:.92rem;color:var(--muted)">
              Mewujudkan {{ $desaInfo['desa.nama'] ?? 'desa' }} yang maju, mandiri, dan sejahtera melalui pelayanan publik yang prima, tata kelola yang transparan, dan pemberdayaan masyarakat yang berkelanjutan.
            </p>
            <ul class="visi-list" style="margin-top:14px">
              <li>Mewujudkan tata kelola pemerintahan yang transparan dan akuntabel</li>
              <li>Meningkatkan kualitas pelayanan publik berbasis digital</li>
              <li>Mengembangkan potensi ekonomi dan sumber daya masyarakat desa</li>
            </ul>
          @endif
        </div>

        <div class="tab-pane" id="tab-struktur" role="tabpanel">
          <div class="org">
            @forelse($pejabat->take(5) as $pj)
              @php $inisial = collect(explode(' ', $pj->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode(''); @endphp
              <div class="org-row">
                <span class="avatar" style="width:40px;height:40px;font-size:.8rem">
                  @if($pj->foto_url)
                    <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}">
                  @else
                    {{ $inisial }}
                  @endif
                </span>
                <div>
                  <b>{{ $pj->nama }}</b>
                  <small>{{ $pj->jabatan?->nama ?? 'Perangkat Desa' }}</small>
                </div>
              </div>
            @empty
              <p style="font-size:.9rem;color:var(--muted)">Data struktur organisasi belum tersedia.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── LAYANAN ── --}}
@if($layanan->isNotEmpty())
<section id="layanan">
  <div class="container">
    <span class="eyebrow reveal">Layanan Publik</span>
    <h2 class="sec-title reveal">Urus Administrasi Lebih Mudah</h2>
    <p class="sec-desc reveal" data-d="1">Ajukan permohonan surat secara online — unggah dokumen, pantau status, dan ambil hasilnya di kantor desa.</p>
    <div class="tenun reveal" data-d="1"></div>

    @php
      $svcColors = [
        ['#0C7C46','#1E9059'],['#0E63A8','#3F8FC9'],['#C99A2C','#E0B854'],
        ['#1E9059','#56BE8B'],['#B7472A','#E0563C'],['#0A4E86','#0E63A8'],
      ];
    @endphp
    <div class="svc-grid">
      @foreach($layanan->take(6) as $j)
        @php $c = $svcColors[$loop->index % 6]; @endphp
        <article class="card svc reveal" data-d="{{ $loop->index % 3 }}">
          <span class="ic" style="background:linear-gradient(135deg,{{ $c[0] }},{{ $c[1] }})">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
          </span>
          <h3>{{ $j->nama }}</h3>
          @if($j->keterangan ?? null)
            <p>{{ \Illuminate\Support\Str::limit($j->keterangan, 90) }}</p>
          @else
            <p>Layanan {{ $j->nama }} untuk keperluan administrasi warga desa.</p>
          @endif
          <a class="go" href="{{ route('login') }}">
            Ajukan sekarang
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg>
          </a>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── BERITA ── --}}
@if($berita->isNotEmpty())
<section id="berita">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:18px;flex-wrap:wrap">
      <div>
        <span class="eyebrow reveal">Berita &amp; Informasi</span>
        <h2 class="sec-title reveal">Kabar Terbaru dari Desa</h2>
        <div class="tenun reveal"></div>
      </div>
      <a class="btn btn-outline reveal" href="{{ route('berita.index') }}">Semua Berita →</a>
    </div>

    @php
      $bgColors = [
        'linear-gradient(135deg,#0C7C46,#1E9059)',
        'linear-gradient(135deg,#0E63A8,#3F8FC9)',
        'linear-gradient(135deg,#C99A2C,#E0B854)',
      ];
    @endphp
    <div class="news-grid">
      @foreach($berita as $b)
        <a href="{{ route('berita.show', $b->slug) }}" class="card news reveal" data-d="{{ $loop->index }}" style="text-decoration:none;color:inherit;display:block">
          <div class="thumb" style="background:{{ $bgColors[$loop->index % 3] }}">
            @if($b->gambar_url)
              <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
            @else
              <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
            @endif
            @if($b->kategori)
              <span class="cat">{{ $b->kategori->nama }}</span>
            @endif
          </div>
          <div class="body">
            <span class="date">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              {{ $b->published_at?->locale('id')->isoFormat('D MMM YYYY') ?? '' }}
            </span>
            <h3>{{ $b->judul }}</h3>
            <p>{{ Str::limit(strip_tags(html_entity_decode($b->ringkasan ?? '')), 120) }}</p>
            <span class="more">Baca selengkapnya →</span>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── PENGUMUMAN ── --}}
<section id="pengumuman" style="background:var(--surface-2)">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:18px;flex-wrap:wrap">
      <div>
        <span class="eyebrow reveal">Informasi Publik</span>
        <h2 class="sec-title reveal">Pengumuman Desa</h2>
        <div class="tenun reveal"></div>
      </div>
      <a class="btn btn-outline reveal" href="{{ route('pengumuman') }}">Semua Pengumuman →</a>
    </div>
    @if(isset($pengumuman) && $pengumuman->isNotEmpty())
    <div class="ann-grid">
      @foreach($pengumuman as $ann)
        <a href="{{ route('pengumuman.show', $ann->id) }}" class="ann-card reveal" data-d="{{ $loop->index }}">
          <div class="ann-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </div>
          <div class="ann-body">
            <div class="ann-date">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              {{ $ann->published_at?->locale('id')->translatedFormat('d F Y') ?? '' }}
            </div>
            <div class="ann-title">{{ $ann->judul }}</div>
            <div class="ann-desc">{{ $ann->ringkasan }}</div>
          </div>
        </a>
      @endforeach
    </div>
    @else
    <div style="text-align:center;padding:40px 20px;color:var(--muted)">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" style="margin:0 auto 12px;opacity:.35;display:block"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <p style="font-size:.88rem">Belum ada pengumuman terbaru.</p>
    </div>
    @endif
  </div>
</section>

{{-- ── PERANGKAT DESA ── --}}
@if($pejabat->count() > 1)
<section id="perangkat" style="background:var(--surface-2)">
  <div class="container">
    <span class="eyebrow reveal">Pemerintahan</span>
    <h2 class="sec-title reveal">Perangkat Desa</h2>
    <p class="sec-desc reveal" data-d="1">Tim yang berdedikasi melayani warga {{ $desaInfo['desa.nama'] ?? '' }} setiap hari.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="pejabat-grid">
      @foreach($pejabat as $pj)
        @php $inisial = collect(explode(' ', $pj->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode(''); @endphp
        <div class="card pejabat-card reveal" data-d="{{ $loop->index % 4 }}">
          <div class="pejabat-avatar">
            @if($pj->foto_url)
              <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}">
            @else
              {{ $inisial }}
            @endif
          </div>
          <div>
            <div style="font-size:.95rem;font-weight:800;line-height:1.3">{{ $pj->nama }}</div>
            <div style="font-size:.8rem;color:var(--muted);margin-top:4px">{{ $pj->jabatan?->nama ?? 'Perangkat Desa' }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── PETA & KONTAK ── --}}
<section id="kontak">
  <div class="container">
    <span class="eyebrow reveal">Lokasi &amp; Kontak</span>
    <h2 class="sec-title reveal">Kunjungi Kantor Desa</h2>
    <div class="tenun reveal"></div>

    <div class="map-grid">
      <div class="map-frame reveal">
        @php
          $mapQuery = urlencode(($desaInfo['desa.nama'] ?? 'Kantor Desa') . ', ' . ($desaInfo['desa.kecamatan'] ?? '') . ', ' . ($desaInfo['desa.kabupaten'] ?? '') . ', Indonesia');
        @endphp
        <iframe
          title="Peta lokasi {{ $desaInfo['desa.nama'] ?? 'Kantor Desa' }}"
          loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
          src="https://maps.google.com/maps?q={{ $mapQuery }}&z=14&output=embed">
        </iframe>
      </div>
      <div class="card contact-card reveal" data-d="1">
        @if($desaInfo['desa.alamat'] ?? null)
          <div class="c-row">
            <span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 22s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="11" r="2.5"/></svg></span>
            <div><b>Alamat</b><span>{{ $desaInfo['desa.alamat'] }}</span></div>
          </div>
        @endif
        @if($desaInfo['desa.whatsapp'] ?? null)
          <div class="c-row">
            <span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.04z"/></svg></span>
            <div><b>WhatsApp</b><a href="https://wa.me/{{ preg_replace('/\D/', '', $desaInfo['desa.whatsapp']) }}" style="color:var(--green)">{{ $desaInfo['desa.whatsapp'] }}</a></div>
          </div>
        @endif
        @if($desaInfo['desa.email'] ?? null)
          <div class="c-row">
            <span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg></span>
            <div><b>Email</b><a href="mailto:{{ $desaInfo['desa.email'] }}" style="color:var(--blue)">{{ $desaInfo['desa.email'] }}</a></div>
          </div>
        @endif
        <div class="c-row">
          <span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
          <div><b>Jam Operasional</b><span>Senin – Jumat, 08.00 – 16.00</span></div>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary" style="margin-top:8px;text-align:center;justify-content:center">
          Portal Warga — Login
        </a>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
// ── Slideshow hero ──
(function() {
  const slides     = document.querySelectorAll('.hero-slide .slide');
  const dots       = document.querySelectorAll('.slide-dot');
  const titleEl    = document.getElementById('slide-title');
  const subEl      = document.getElementById('slide-sub');
  if (!slides.length) return;

  const slideMeta = @json(array_values($headerSlides));
  const fallbackJudul    = @json($hJudul ?: $defaultJudul);
  const fallbackSubjudul = @json($hSubjudul ?: $defaultSubjudul);

  let current  = 0;
  let timer    = null;

  function slideTo(idx) {
    slides[current].classList.remove('active');
    dots[current]?.classList.remove('active');
    current = (idx + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current]?.classList.add('active');
    if (titleEl) titleEl.textContent = slideMeta[current]?.judul || fallbackJudul;
    if (subEl)   subEl.textContent   = slideMeta[current]?.subjudul || fallbackSubjudul;
    resetTimer();
  }

  function slideNext() { slideTo(current + 1); }
  function slidePrev() { slideTo(current - 1); }

  function resetTimer() {
    clearInterval(timer);
    if (slides.length > 1) timer = setInterval(slideNext, 5000);
  }

  window.slideTo   = slideTo;
  window.slideNext = slideNext;
  window.slidePrev = slidePrev;

  resetTimer();
})();

// Tab profil
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const tab = this.dataset.tab;
    const panel = this.closest('.card') || this.closest('div[class*="profil"]');
    if (!panel) return;
    panel.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    panel.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show'));
    this.classList.add('active');
    const target = panel.querySelector('#tab-' + tab);
    if (target) target.classList.add('show');
  });
});
</script>
@endpush
