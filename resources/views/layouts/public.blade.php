<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0C7C46">

{{-- Favicon --}}
@if($desaLogoUrl)
<link rel="icon" href="{{ $desaLogoUrl }}">
<link rel="apple-touch-icon" href="{{ $desaLogoUrl }}">
@else
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%230C7C46'/%3E%3Cpath d='M5 29V16L16 7l11 9v13' stroke='white' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3Crect x='11' y='20' width='10' height='9' rx='1' fill='white'/%3E%3C/svg%3E">
@endif

{{-- SEO Utama --}}
<title>@yield('title', ($desaInfo['desa.nama'] ?? 'Desa') . ' — Portal Resmi')</title>
<meta name="description" content="@yield('description', 'Website resmi ' . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa') . '. Informasi layanan publik, berita, dan potensi desa.')">
<meta name="robots" content="index, follow">
<meta name="author" content="{{ $desaInfo['desa.nama'] ?? 'Pemerintah Desa' }}">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph --}}
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', ($desaInfo['desa.nama'] ?? 'Desa') . ' — Portal Resmi')">
<meta property="og:description" content="@yield('description', 'Website resmi ' . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa'))">
<meta property="og:image" content="@yield('og_image', $desaLogoUrl ?? '')">
<meta property="og:site_name" content="Pemerintah Desa {{ $desaInfo['desa.nama'] ?? '' }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', ($desaInfo['desa.nama'] ?? 'Desa') . ' — Portal Resmi')">
<meta name="twitter:description" content="@yield('description', 'Website resmi ' . ($desaInfo['desa.nama'] ?? 'Pemerintah Desa'))">
<meta name="twitter:image" content="@yield('og_image', $desaLogoUrl ?? '')">

{{-- JSON-LD Structured Data --}}
@php
$_org = ['@type' => 'GovernmentOrganization', 'name' => 'Pemerintah Desa ' . ($desaInfo['desa.nama'] ?? ''), 'url' => url('/')];
if ($desaLogoUrl) $_org['logo'] = $desaLogoUrl;
if ($desaInfo['desa.alamat'] ?? null) {
    $_addr = ['@type' => 'PostalAddress', 'streetAddress' => $desaInfo['desa.alamat'], 'addressCountry' => 'ID'];
    if ($desaInfo['desa.kecamatan'] ?? null) $_addr['addressLocality'] = $desaInfo['desa.kecamatan'];
    if ($desaInfo['desa.kabupaten'] ?? null) $_addr['addressRegion']   = $desaInfo['desa.kabupaten'];
    $_org['address'] = $_addr;
}
if ($desaInfo['desa.whatsapp'] ?? null) {
    $_org['contactPoint'] = ['@type' => 'ContactPoint', 'telephone' => $desaInfo['desa.whatsapp'], 'contactType' => 'customer service'];
}
$_jsonLd = ['@context' => 'https://schema.org', '@graph' => [
    ['@type' => 'WebSite', 'name' => ($desaInfo['desa.nama'] ?? 'Portal Desa'), 'url' => url('/')],
    $_org,
]];
@endphp
<script type="application/ld+json">{!! json_encode($_jsonLd, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@1,8..60,500&display=swap" rel="stylesheet">
@stack('head')
<style>
/* ── TOKENS ── */
:root{
  --bg:#F4FAF7;--surface:#FFFFFF;--surface-2:#EDF6F1;
  --ink:#0F241B;--muted:#54685E;--line:rgba(15,36,27,.09);
  --green:#0C7C46;--green-deep:#075F36;--green-soft:#DDF1E6;
  --blue:#0E63A8;--blue-deep:#0A4E86;--blue-soft:#E1EFFA;
  --gold:#C99A2C;
  --glass:rgba(255,255,255,.72);--glass-line:rgba(255,255,255,.55);
  --shadow:0 10px 30px -12px rgba(12,60,40,.18);
  --shadow-lg:0 24px 60px -20px rgba(12,60,40,.25);
  --radius:18px;
  --hero-sky:#CFE8F7;--hero-sky2:#EAF6EE;
}
[data-theme="dark"]{
  --bg:#0A1410;--surface:#101D17;--surface-2:#15251D;
  --ink:#E9F4EE;--muted:#9DB3A8;--line:rgba(233,244,238,.1);
  --green:#3BCD85;--green-deep:#2AA468;--green-soft:#143323;
  --blue:#5FAEE6;--blue-deep:#3F8FC9;--blue-soft:#10283B;
  --gold:#E0B854;
  --glass:rgba(16,29,23,.72);--glass-line:rgba(233,244,238,.12);
  --shadow:0 10px 30px -12px rgba(0,0,0,.5);
  --shadow-lg:0 24px 60px -20px rgba(0,0,0,.6);
  --hero-sky:#0E2233;--hero-sky2:#0E1F18;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg);color:var(--ink);line-height:1.65;font-size:16px;transition:background .35s,color .35s;-webkit-font-smoothing:antialiased}
img,svg{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
button{font:inherit;cursor:pointer;border:none;background:none;color:inherit}
:focus-visible{outline:3px solid var(--blue);outline-offset:3px;border-radius:6px}
.container{width:min(1180px,92%);margin-inline:auto}
.eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--green)}
.eyebrow::before{content:"";width:26px;height:2px;background:linear-gradient(90deg,var(--green),var(--blue));border-radius:2px}
.sec-title{font-size:clamp(1.6rem,3.4vw,2.3rem);font-weight:800;letter-spacing:-.02em;margin:.4rem 0 .6rem}
.sec-desc{color:var(--muted);max-width:620px}
section{padding:clamp(56px,8vw,96px) 0;position:relative}
.tenun{height:14px;width:100%;background-image:repeating-linear-gradient(135deg,var(--green) 0 6px,transparent 6px 14px),repeating-linear-gradient(45deg,var(--blue) 0 6px,transparent 6px 14px);opacity:.18;border-radius:99px;margin-top:1.2rem;max-width:220px}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);transition:.25s}

/* ── TOPBAR ── */
.topbar{background:linear-gradient(90deg,var(--green-deep),var(--blue-deep));color:#fff;font-size:.78rem;padding:6px 0}
.topbar .container{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
.topbar a{opacity:.9;color:#fff}
.topbar a:hover{opacity:1}

/* ── HEADER ── */
header.site{position:sticky;top:0;z-index:60;background:var(--glass);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);transition:box-shadow .3s,background .35s}
header.site.scrolled{box-shadow:var(--shadow)}
.nav-wrap{display:flex;align-items:center;gap:14px;padding:12px 0}
.brand{display:flex;align-items:center;gap:11px;margin-right:auto;text-decoration:none}
.brand .logo{width:46px;height:46px;border-radius:13px;background:transparent;display:grid;place-items:center;color:var(--green);flex-shrink:0;overflow:hidden}
.brand .logo img{width:100%;height:100%;object-fit:contain}
.brand b{font-size:1.02rem;letter-spacing:-.01em;line-height:1.2}
.brand small{color:var(--muted);font-size:.72rem;font-weight:600;letter-spacing:.06em}
.brand>span:last-child{display:flex;flex-direction:column;gap:1px;line-height:1.2}

/* ── NAV ── */
nav.primary{display:flex;gap:2px;align-items:center}
nav.primary>a,nav.primary>.nav-has-dd>a{padding:8px 11px;border-radius:10px;font-size:.83rem;font-weight:600;color:var(--muted);transition:.2s;white-space:nowrap;display:flex;align-items:center;gap:4px}
nav.primary>a:hover,nav.primary>.nav-has-dd>a:hover{color:var(--ink);background:var(--surface-2)}
nav.primary>a.active,nav.primary>.nav-has-dd>a.active{color:var(--green);background:var(--green-soft)}

/* Dropdown */
.nav-has-dd{position:relative}
.nav-has-dd::after{content:'';position:absolute;left:-10px;right:-10px;top:100%;height:12px}
.nav-dd{display:block;visibility:hidden;opacity:0;pointer-events:none;position:absolute;top:calc(100% + 10px);left:50%;transform:translateX(-50%) translateY(-8px);min-width:190px;background:var(--surface);border:1px solid var(--line);border-radius:14px;box-shadow:var(--shadow-lg);overflow:hidden;z-index:200;padding:6px;transition:opacity .2s ease,transform .2s ease,visibility 0s .2s}
.nav-has-dd:hover .nav-dd{visibility:visible;opacity:1;pointer-events:auto;transform:translateX(-50%) translateY(0);transition:opacity .2s ease,transform .2s ease,visibility 0s}
.nav-dd a{display:block;padding:9px 14px;border-radius:10px;font-size:.83rem;font-weight:600;color:var(--muted);transition:.2s;white-space:nowrap}
.nav-dd a:hover{color:var(--ink);background:var(--surface-2)}

/* ── ICON BTN & CTA ── */
.icon-btn{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;border:1px solid var(--line);background:var(--surface);transition:.2s;flex-shrink:0}
.icon-btn:hover{border-color:var(--green);color:var(--green);transform:translateY(-1px)}
.btn{display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:.88rem;padding:11px 20px;border-radius:13px;transition:.25s;border:1px solid transparent}
.btn-primary{background:linear-gradient(135deg,var(--green),var(--green-deep));color:#fff;box-shadow:0 8px 20px -8px rgba(12,124,70,.55)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 14px 26px -10px rgba(12,124,70,.6)}
.btn-outline{border-color:var(--line);background:var(--surface);color:var(--ink)}
.btn-outline:hover{border-color:var(--blue);color:var(--blue);transform:translateY(-2px)}
.btn-blue{background:linear-gradient(135deg,var(--blue),var(--blue-deep));color:#fff;box-shadow:0 8px 20px -8px rgba(14,99,168,.55)}
.btn-blue:hover{transform:translateY(-2px)}

/* ── MOBILE NAV ── */
#hamburger{display:none}
.mobile-nav{display:none}
@media(max-width:1080px){
  nav.primary{display:none}
  #hamburger{display:grid}
  #themeBtn{display:none}
  .mobile-nav{display:block;position:fixed;inset:0;z-index:100;background:rgba(5,20,12,.55);backdrop-filter:blur(8px);opacity:0;pointer-events:none;transition:.3s}
  .mobile-nav.open{opacity:1;pointer-events:auto}
  .mobile-nav .panel{background:var(--surface);width:min(300px,82%);height:100%;margin-left:auto;padding:20px;display:flex;flex-direction:column;gap:3px;transform:translateX(40px);transition:.3s;overflow-y:auto}
  .mobile-nav.open .panel{transform:none}
  .mobile-nav a,.mobile-nav button.mnav-parent{padding:12px 14px;border-radius:12px;font-weight:600;border:1px solid transparent;display:block;text-align:left;width:100%;font-size:.9rem;color:var(--ink);background:none;font-family:inherit}
  .mobile-nav a:hover,.mobile-nav a.active,.mobile-nav button.mnav-parent:hover{background:var(--green-soft);color:var(--green)}
  .mobile-nav .mnav-children{padding-left:14px;display:none;flex-direction:column;gap:2px}
  .mobile-nav .mnav-children.open{display:flex}
  .mobile-nav .mnav-children a{font-size:.85rem;padding:9px 14px;color:var(--muted)}
  .mobile-nav .mnav-divider{height:1px;background:var(--line);margin:6px 0}
  .mnav-actions{display:flex;gap:8px;padding:6px 0}
  .mnav-action-btn{flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border-radius:12px;border:1px solid var(--line);background:var(--surface-2);cursor:pointer;color:var(--ink);font-size:.75rem;font-weight:600;font-family:inherit;transition:.2s}
  .mnav-action-btn:hover{border-color:var(--green);color:var(--green);background:var(--green-soft)}
}

/* ── FOOTER ── */
footer{background:linear-gradient(160deg,#06301C,#0A3B5C);color:#DFEDE6;padding:60px 0 0;position:relative;overflow:hidden}
footer::before{content:"";position:absolute;inset:0;background-image:repeating-linear-gradient(135deg,rgba(255,255,255,.03) 0 8px,transparent 8px 22px)}
.f-grid{display:grid;grid-template-columns:1.3fr 1fr 1fr 1fr;gap:30px;position:relative}
.f-grid h4{font-size:.85rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#9FD9BB;margin-bottom:14px}
.f-grid a,.f-grid p{font-size:.86rem;color:#BBD3C7;display:block;padding:4px 0;transition:.2s}
.f-grid a:hover{color:#fff;transform:translateX(3px)}
.socials{display:flex;gap:10px;margin-top:14px}
.socials a{width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.1);display:grid;place-items:center;border:1px solid rgba(255,255,255,.15);padding:0;transition:.2s}
.socials a:hover{background:var(--green);transform:translateY(-3px)}
.copy{border-top:1px solid rgba(255,255,255,.12);margin-top:44px;padding:18px 0;font-size:.78rem;color:#9DBBAC;text-align:center;position:relative}
@media(max-width:640px){#headerLogin .login-text{display:none}#headerLogin{padding:9px 12px!important;gap:0}}
@media(max-width:880px){.f-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.f-grid{grid-template-columns:1fr}}

/* ── FLOATING ── */
.fab{position:fixed;right:20px;z-index:70;width:54px;height:54px;border-radius:50%;display:grid;place-items:center;box-shadow:var(--shadow-lg);transition:.3s}
.fab-wa{bottom:20px;background:#25D366;color:#fff}
.fab-wa:hover{transform:scale(1.08)}
.fab-top{bottom:86px;background:var(--surface);border:1px solid var(--line);color:var(--green);opacity:0;pointer-events:none;transform:translateY(10px)}
.fab-top.show{opacity:1;pointer-events:auto;transform:none}
.fab-top:hover{background:var(--green);color:#fff}

/* ── MODAL / SEARCH ── */
.modal{position:fixed;inset:0;z-index:120;background:rgba(5,20,12,.55);backdrop-filter:blur(8px);display:grid;place-items:center;padding:20px;opacity:0;pointer-events:none;transition:.25s}
.modal.open{opacity:1;pointer-events:auto}
.modal .box{background:var(--surface);border:1px solid var(--line);border-radius:22px;box-shadow:var(--shadow-lg);width:min(500px,100%);padding:28px;transform:translateY(16px);transition:.3s}
.modal.open .box{transform:none}
.field{display:grid;gap:6px;margin-bottom:14px}
.field label{font-size:.8rem;font-weight:700}
.field input{padding:12px 14px;border-radius:12px;border:1px solid var(--line);background:var(--surface-2);color:var(--ink);font:inherit;font-size:.9rem}
.field input:focus{outline:2px solid var(--green);border-color:transparent}

/* ── REVEAL ANIMATION ── */
.reveal{opacity:0;transform:translateY(26px);transition:opacity .7s cubic-bezier(.2,.7,.2,1),transform .7s cubic-bezier(.2,.7,.2,1)}
.reveal.in{opacity:1;transform:none}
.reveal[data-d="1"]{transition-delay:.08s}.reveal[data-d="2"]{transition-delay:.16s}
.reveal[data-d="3"]{transition-delay:.24s}.reveal[data-d="4"]{transition-delay:.32s}
.reveal[data-d="5"]{transition-delay:.4s}
.skip{position:absolute;left:-9999px;top:0;background:var(--green);color:#fff;padding:10px 18px;border-radius:0 0 12px 0;z-index:200;font-weight:700}
.skip:focus{left:0}

@stack('styles')
</style>
</head>
<body>

<a class="skip" href="#main">Lewati ke konten utama</a>

{{-- ── TOPBAR ── --}}
@if(($desaInfo['desa.whatsapp'] ?? null) || ($desaInfo['desa.email'] ?? null))
<div class="topbar">
  <div class="container">
    <span>
      @if($desaInfo['desa.whatsapp'] ?? null)
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $desaInfo['desa.whatsapp']) }}">
          📞 {{ $desaInfo['desa.whatsapp'] }}
        </a>
      @endif
      @if(($desaInfo['desa.whatsapp'] ?? null) && ($desaInfo['desa.email'] ?? null))
        &nbsp;·&nbsp;
      @endif
      @if($desaInfo['desa.email'] ?? null)
        <a href="mailto:{{ $desaInfo['desa.email'] }}">✉️ {{ $desaInfo['desa.email'] }}</a>
      @endif
    </span>
    <span>Senin–Jumat · 08.00–16.00</span>
  </div>
</div>
@endif

{{-- ── HEADER ── --}}
<header class="site" id="siteHeader">
  <div class="container nav-wrap">

    {{-- Logo & Nama Desa --}}
    <a class="brand" href="{{ route('home') }}" aria-label="Beranda {{ $desaInfo['desa.nama'] ?? 'Desa' }}">
      <span class="logo">
        @if($desaLogoUrl)
          <img src="{{ $desaLogoUrl }}" alt="Logo {{ $desaInfo['desa.nama'] ?? 'Desa' }}">
        @else
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"/></svg>
        @endif
      </span>
      <span>
        <b>Desa {{ $desaInfo['desa.nama'] ?? 'Portal Desa' }}</b>
        <small>{{ implode(' · ', array_filter([($desaInfo['desa.kecamatan'] ?? '') ? 'Kec. '.($desaInfo['desa.kecamatan']) : '', ($desaInfo['desa.kabupaten'] ?? '') ? 'Kab. '.($desaInfo['desa.kabupaten']) : ''])) ?: 'PEMERINTAH DESA' }}</small>
      </span>
    </a>

    {{-- Nav Desktop --}}
    <nav class="primary" aria-label="Navigasi utama">
      @foreach($pubNavItems as $item)
        @php
          $lnk = $item->link;
          $isExt = str_starts_with($lnk, 'http');
          $isActive = !$isExt && $lnk !== '#' && $lnk !== '' && Request::is(ltrim($lnk, '/'));
          $hasChildren = $item->children->isNotEmpty();
        @endphp
        @if($hasChildren)
          <div class="nav-has-dd">
            <a href="{{ $lnk }}" target="{{ $item->target }}" class="{{ $isActive ? 'active' : '' }}">
              {{ $item->label }}
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
            </a>
            <div class="nav-dd">
              @foreach($item->children as $child)
                <a href="{{ $child->link }}" target="{{ $child->target }}">{{ $child->label }}</a>
              @endforeach
            </div>
          </div>
        @else
          <a href="{{ $lnk }}" target="{{ $item->target }}" class="{{ $isActive ? 'active' : '' }}">{{ $item->label }}</a>
        @endif
      @endforeach
    </nav>

    {{-- Actions --}}
    <button class="icon-btn" id="themeBtn" aria-label="Mode gelap / terang">
      <svg id="iconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
      <svg id="iconSun" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
    </button>
    @auth
      @php $dashUrl = Auth::user()->roles()->exists() ? route('admin.dashboard') : route('portal'); @endphp
      <a href="{{ $dashUrl }}" id="headerLogin" class="btn btn-primary" style="padding:10px 16px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span class="login-text">Dashboard</span>
      </a>
    @else
      <a href="{{ route('login') }}" id="headerLogin" class="btn btn-blue" style="padding:10px 16px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
        <span class="login-text">Login</span>
      </a>
    @endauth
    <button class="icon-btn" id="hamburger" aria-label="Buka menu" aria-expanded="false">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
  </div>
</header>

{{-- ── MOBILE NAV ── --}}
<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
      <b style="font-size:1rem">Menu</b>
      <button class="icon-btn" id="closeNav" aria-label="Tutup menu" style="border:none">✕</button>
    </div>
    <div class="mnav-divider"></div>
    @foreach($pubNavItems as $item)
      @php $hasChildren = $item->children->isNotEmpty(); @endphp
      @if($hasChildren)
        <button class="mnav-parent" onclick="toggleMnavChildren(this)">
          {{ $item->label }}
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;margin-left:6px"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="mnav-children">
          @foreach($item->children as $child)
            <a href="{{ $child->link }}" target="{{ $child->target }}">{{ $child->label }}</a>
          @endforeach
        </div>
      @else
        <a href="{{ $item->link }}" target="{{ $item->target }}">{{ $item->label }}</a>
      @endif
    @endforeach
    <div class="mnav-divider"></div>
    <div class="mnav-actions">
      <button class="mnav-action-btn" id="mnavThemeBtn" aria-label="Mode gelap / terang">
        <svg id="mnavIconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
        <svg id="mnavIconSun" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
        <span id="mnavThemeLabel">Gelap</span>
      </button>
    </div>
  </div>
</div>

{{-- ── KONTEN UTAMA ── --}}
<main id="main">
  @yield('content')
</main>

{{-- ── FOOTER ── --}}
<footer>
  <div class="container">
    <div class="f-grid">
      {{-- Kolom 1: Identitas --}}
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
          <div style="width:40px;height:40px;border-radius:11px;background:rgba(255,255,255,.12);display:grid;place-items:center;flex-shrink:0;overflow:hidden">
            @if($desaLogoUrl)
              <img src="{{ $desaLogoUrl }}" alt="" style="width:100%;height:100%;object-fit:contain">
            @else
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9FD9BB" stroke-width="2" stroke-linecap="round"><path d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"/></svg>
            @endif
          </div>
          <div>
            <div style="font-weight:800;font-size:.95rem;color:#E9F4EE">{{ $desaInfo['desa.nama'] ?? 'Portal Desa' }}</div>
            @if($desaInfo['desa.kecamatan'] ?? null)
              <div style="font-size:.75rem;color:#9FD9BB">Kec. {{ $desaInfo['desa.kecamatan'] }}{{ isset($desaInfo['desa.kabupaten']) ? ', ' . $desaInfo['desa.kabupaten'] : '' }}</div>
            @endif
          </div>
        </div>
        <p style="font-size:.85rem;color:#BBD3C7;line-height:1.65;max-width:260px">
          {{ $desaInfo['desa.alamat'] ?? 'Melayani warga dengan cepat, terbuka, dan sepenuh hati.' }}
        </p>
        <div class="socials">
          @if($desaInfo['desa.whatsapp'] ?? null)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $desaInfo['desa.whatsapp']) }}" aria-label="WhatsApp">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            </a>
          @endif
          @if($desaInfo['desa.email'] ?? null)
            <a href="mailto:{{ $desaInfo['desa.email'] }}" aria-label="Email">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg>
            </a>
          @endif
        </div>
      </div>

      {{-- Kolom 2: Navigasi --}}
      <div>
        <h4>Navigasi</h4>
        @forelse($pubNavItems->take(8) as $item)
          <a href="{{ $item->link }}" target="{{ $item->target }}">{{ $item->label }}</a>
        @empty
          <a href="{{ route('home') }}">Beranda</a>
        @endforelse
      </div>

      {{-- Kolom 3: Layanan --}}
      <div>
        <h4>Layanan</h4>
        <a href="{{ route('login') }}">Portal Warga</a>
        <a href="{{ route('pengumuman') }}">Pengumuman</a>
        <a href="{{ route('login') }}">Ajukan Surat</a>
        <a href="{{ route('login') }}">Lapor ke Desa</a>
      </div>

      {{-- Kolom 4: Kontak --}}
      <div>
        <h4>Kontak</h4>
        @if($desaInfo['desa.alamat'] ?? null)
          <p>{{ $desaInfo['desa.alamat'] }}</p>
        @endif
        @if($desaInfo['desa.whatsapp'] ?? null)
          <a href="https://wa.me/{{ preg_replace('/\D/', '', $desaInfo['desa.whatsapp']) }}">{{ $desaInfo['desa.whatsapp'] }}</a>
        @endif
        @if($desaInfo['desa.email'] ?? null)
          <a href="mailto:{{ $desaInfo['desa.email'] }}">{{ $desaInfo['desa.email'] }}</a>
        @endif
        @if($desaInfo['desa.kepala'] ?? null)
          <p style="margin-top:10px;font-size:.8rem;opacity:.7">Kepala Desa:<br><strong style="color:#E9F4EE">{{ $desaInfo['desa.kepala'] }}</strong></p>
        @endif
      </div>
    </div>
  </div>
  <div class="copy">
    <div class="container">
      © {{ date('Y') }} {{ $desaInfo['desa.nama'] ?? 'Portal Desa' }}
      @if(($desaInfo['desa.kecamatan'] ?? null) || ($desaInfo['desa.kabupaten'] ?? null))
        · {{ implode(', ', array_filter([$desaInfo['desa.kecamatan'] ?? null, $desaInfo['desa.kabupaten'] ?? null])) }}
      @endif
      · Powered by <a href="https://viteks.id" target="_blank" style="color:#9FD9BB">Viteks.id</a>
    </div>
  </div>
</footer>

{{-- ── FAB ── --}}
<a href="{{ ($desaInfo['desa.whatsapp'] ?? null) ? 'https://wa.me/'.preg_replace('/\D/', '', $desaInfo['desa.whatsapp']) : '#' }}"
   class="fab fab-wa" aria-label="Chat WhatsApp" target="_blank">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
</a>
<button class="fab fab-top" id="fabTop" aria-label="Ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m18 15-6-6-6 6"/></svg>
</button>

<script>
// ── Dark Mode ──
(function(){
  const saved = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = saved || (prefersDark ? 'dark' : 'light');
  document.documentElement.setAttribute('data-theme', theme);
  ['iconMoon','mnavIconMoon'].forEach(id => { const el=document.getElementById(id); if(el) el.style.display=theme==='dark'?'none':'block'; });
  ['iconSun','mnavIconSun'].forEach(id => { const el=document.getElementById(id); if(el) el.style.display=theme==='dark'?'block':'none'; });
  const lbl=document.getElementById('mnavThemeLabel'); if(lbl) lbl.textContent=theme==='dark'?'Terang':'Gelap';
})();
function applyTheme(next) {
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  ['iconMoon','mnavIconMoon'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = next === 'dark' ? 'none' : 'block';
  });
  ['iconSun','mnavIconSun'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = next === 'dark' ? 'block' : 'none';
  });
  const lbl = document.getElementById('mnavThemeLabel');
  if (lbl) lbl.textContent = next === 'dark' ? 'Terang' : 'Gelap';
}
document.getElementById('themeBtn')?.addEventListener('click', function() {
  applyTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
});
document.getElementById('mnavThemeBtn')?.addEventListener('click', function() {
  applyTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
});

// ── Scroll effects ──
window.addEventListener('scroll', function() {
  const header = document.getElementById('siteHeader');
  if (header) header.classList.toggle('scrolled', window.scrollY > 30);
  const fabTop = document.getElementById('fabTop');
  if (fabTop) fabTop.classList.toggle('show', window.scrollY > 400);
}, { passive: true });

// ── Mobile nav ──
function openNav() {
  const nav = document.getElementById('mobileNav');
  nav.classList.add('open');
  nav.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
}
function closeNav() {
  const nav = document.getElementById('mobileNav');
  nav.classList.remove('open');
  nav.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}
document.getElementById('hamburger')?.addEventListener('click', openNav);
document.getElementById('closeNav')?.addEventListener('click', closeNav);
document.getElementById('mobileNav')?.addEventListener('click', function(e) {
  if (e.target === this) closeNav();
});

function toggleMnavChildren(btn) {
  const children = btn.nextElementSibling;
  if (children) children.classList.toggle('open');
}

// ── Reveal on scroll ──
(function() {
  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
  }, { threshold: 0.12 });
  els.forEach(el => obs.observe(el));
})();
</script>
@stack('scripts')
</body>
</html>
