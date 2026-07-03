<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#15803d">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>@yield('title', $desa['desa.nama'] ?? 'Portal Desa')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;background:#f0f0f0;font-family:'Inter',system-ui,sans-serif;-webkit-font-smoothing:antialiased}

@keyframes spin{to{transform:rotate(360deg)}}
@keyframes pulse{50%{box-shadow:0 0 0 6px rgba(74,222,128,0)}}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ── Phone frame ── */
.phone-wrap{
  width:100%;max-width:430px;min-height:100vh;
  margin:0 auto;background:#f5f6f8;
  position:relative;display:flex;flex-direction:column;
  box-shadow:0 0 40px rgba(0,0,0,.18);
  overflow:hidden;
}
@media(min-width:480px){
  body{display:flex;align-items:flex-start;justify-content:center;min-height:100vh;padding:24px 0}
  .phone-wrap{min-height:calc(100vh - 48px);border-radius:36px;overflow:hidden}
}

/* ── Status bar ── */
.statusbar{
  background:#15803d;color:#fff;
  font-size:11px;font-weight:600;letter-spacing:.01em;
  padding:env(safe-area-inset-top,0) 20px 6px;
  padding-top:calc(env(safe-area-inset-top,0) + 6px);
  display:flex;justify-content:space-between;align-items:center;
  position:sticky;top:0;z-index:100;
}
.statusbar-icons{display:flex;gap:5px;align-items:center}

/* ── App bar ── */
.appbar{
  background:linear-gradient(135deg,#15803d,#166534);
  color:#fff;padding:12px 20px 14px;
  display:flex;align-items:center;gap:12px;
  position:sticky;top:0;z-index:99;
  box-shadow:0 2px 8px rgba(0,0,0,.15);
}
.appbar-logo{
  width:40px;height:40px;border-radius:12px;
  background:rgba(255,255,255,.2);backdrop-filter:blur(4px);
  border:1.5px solid rgba(255,255,255,.3);
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.appbar-title{flex:1;min-width:0}
.appbar-title h1{font-size:15px;font-weight:700;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.appbar-title p{font-size:11px;opacity:.8;margin-top:1px}
.appbar-actions{display:flex;gap:6px}
.appbar-btn{
  width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);
  border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;
  transition:.15s;text-decoration:none;
}
.appbar-btn:hover,.appbar-btn:active{background:rgba(255,255,255,.25)}

/* ── Main content ── */
.app-content{flex:1;overflow-y:auto;-webkit-overflow-scrolling:touch;padding-bottom:72px}

/* ── Bottom navigation ── */
.bottomnav{
  position:fixed;bottom:0;left:50%;transform:translateX(-50%);
  width:100%;max-width:430px;
  background:#fff;border-top:1px solid #e5e7eb;
  display:flex;z-index:100;
  padding-bottom:env(safe-area-inset-bottom,0);
  box-shadow:0 -2px 12px rgba(0,0,0,.08);
}
.nav-item{
  flex:1;padding:8px 2px 10px;
  display:flex;flex-direction:column;align-items:center;gap:2px;
  color:#9ca3af;font-size:10px;font-weight:500;transition:.15s;
  position:relative;text-decoration:none;border:none;background:none;cursor:pointer;
}
.nav-item.active{color:#15803d}
.nav-item.active::before{
  content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
  width:28px;height:3px;background:#15803d;border-radius:0 0 4px 4px;
}
.nav-item svg{transition:.15s;flex-shrink:0}
.nav-item.active svg{filter:drop-shadow(0 0 4px rgba(21,128,61,.3))}

/* ── Hero card ── */
.hero-card{
  margin:16px 16px 0;border-radius:20px;overflow:hidden;
  background:linear-gradient(135deg,#15803d,#1d4ed8);
  padding:20px;color:#fff;position:relative;
}
.hero-card::before{content:'';position:absolute;right:-20px;top:-20px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.08)}
.hero-card::after{content:'';position:absolute;right:20px;bottom:-30px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.06)}
.hero-card h2{font-size:16px;font-weight:700;line-height:1.3;margin-bottom:4px;position:relative;z-index:1}
.hero-card p{font-size:12px;opacity:.85;position:relative;z-index:1}
.hero-badge{
  display:inline-flex;align-items:center;gap:5px;
  background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);
  padding:4px 10px;border-radius:99px;font-size:11px;font-weight:600;
  margin-bottom:10px;backdrop-filter:blur(4px);
}
.hero-badge .dot{width:6px;height:6px;border-radius:50%;background:#4ade80;
  box-shadow:0 0 0 3px rgba(74,222,128,.25);animation:pulse 2s infinite}

/* ── Stats ── */
.stats-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:14px 16px 4px}
.stat-card{background:#fff;border-radius:16px;padding:14px;display:flex;align-items:center;gap:11px;box-shadow:0 1px 6px rgba(0,0,0,.07)}
.stat-ic{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-num{font-size:20px;font-weight:800;line-height:1;letter-spacing:-.02em}
.stat-lbl{font-size:11px;color:#6b7280;margin-top:2px;font-weight:500}

/* ── Section header ── */
.section-hd{display:flex;justify-content:space-between;align-items:center;padding:18px 16px 10px}
.section-hd h3{font-size:15px;font-weight:700;color:#111827}
.section-hd a{font-size:12px;font-weight:600;color:#15803d;text-decoration:none}

/* ── Quick actions ── */
.quick-actions{padding:0 16px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.qa-item{display:flex;flex-direction:column;align-items:center;gap:7px;cursor:pointer;text-decoration:none}
.qa-ic{width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.1)}
.qa-lbl{font-size:11px;font-weight:600;color:#374151;text-align:center;line-height:1.3}

/* ── Berita horizontal scroll ── */
.news-scroll{padding:0 16px;display:flex;gap:12px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none}
.news-scroll::-webkit-scrollbar{display:none}
.news-card{flex-shrink:0;width:220px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.news-thumb{height:110px;display:flex;align-items:center;justify-content:center;position:relative}
.news-cat{position:absolute;top:8px;left:8px;background:rgba(0,0,0,.55);color:#fff;
  font-size:9px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
  padding:3px 8px;border-radius:99px;backdrop-filter:blur(4px)}
.news-body{padding:12px}
.news-date{font-size:10px;color:#9ca3af;font-weight:500;margin-bottom:4px}
.news-title{font-size:13px;font-weight:700;color:#111827;line-height:1.4;
  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

/* ── Layanan list ── */
.layanan-list{padding:0 16px;display:flex;flex-direction:column;gap:10px;padding-bottom:8px}
.layanan-card{background:#fff;border-radius:16px;padding:14px;display:flex;align-items:center;gap:13px;
  box-shadow:0 1px 6px rgba(0,0,0,.07);cursor:pointer;transition:.15s;border:1.5px solid transparent}
.layanan-card:active{background:#f0fdf4;border-color:#bbf7d0}
.layanan-ic{width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;
  flex-shrink:0;background:linear-gradient(135deg,#15803d,#166534)}
.layanan-info{flex:1;min-width:0}
.layanan-info h4{font-size:14px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.layanan-info p{font-size:11px;color:#6b7280;margin-top:2px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
.layanan-arrow{flex-shrink:0;color:#d1d5db}

/* ── Empty state ── */
.empty-state{padding:48px 16px;text-align:center;color:#9ca3af}
.empty-state svg{margin:0 auto 12px;opacity:.4}
.empty-state p{font-size:14px;font-weight:500}
.empty-state small{font-size:12px;display:block;margin-top:4px}

/* ── Berita full list ── */
.berita-list{padding:0 16px;display:flex;flex-direction:column;gap:12px;padding-bottom:8px}
.berita-row{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.07);display:flex}
.berita-row-thumb{width:90px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.berita-row-body{padding:12px;flex:1;min-width:0}
.berita-row-cat{font-size:10px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#15803d;margin-bottom:4px}
.berita-row-title{font-size:13px;font-weight:700;color:#111827;line-height:1.4;
  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.berita-row-date{font-size:10px;color:#9ca3af;margin-top:5px;font-weight:500}

/* ── Akun / profil ── */
.profil-header{margin:16px;background:linear-gradient(135deg,#15803d,#166534);border-radius:20px;padding:20px;color:#fff;text-align:center}
.profil-header h2{font-size:18px;font-weight:800;margin-top:8px}
.profil-header p{font-size:12px;opacity:.8;margin-top:4px}
.profil-logo{width:64px;height:64px;border-radius:18px;background:rgba(255,255,255,.2);
  border:2px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;margin:0 auto}
.info-card{background:#fff;border-radius:16px;margin:0 16px 12px;box-shadow:0 1px 6px rgba(0,0,0,.07);overflow:hidden}
.info-card-header{padding:14px 16px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px}
.info-card-header h3{font-size:14px;font-weight:700;color:#111827}
.info-ic{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.info-row{padding:12px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #f9fafb}
.info-row:last-child{border-bottom:none}
.info-row-lbl{font-size:11px;color:#9ca3af;font-weight:500;width:90px;flex-shrink:0}
.info-row-val{font-size:13px;font-weight:600;color:#111827;flex:1}
.pejabat-list{padding:0 0 4px}
.pejabat-item{padding:12px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #f9fafb}
.pejabat-item:last-child{border-bottom:none}
.pejabat-avatar{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#15803d,#1d4ed8);
  display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;flex-shrink:0}
.pejabat-name{font-size:13px;font-weight:700;color:#111827}
.pejabat-jabatan{font-size:11px;color:#6b7280;margin-top:2px}

/* ── Pill badge ── */
.pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:99px;font-size:10px;font-weight:700;letter-spacing:.04em}
.pill-green{background:#dcfce7;color:#15803d}
.pill-blue{background:#dbeafe;color:#1d4ed8}
.pill-orange{background:#fef3c7;color:#d97706}

/* ── Toast ── */
.toast-wrap{position:fixed;top:20px;left:50%;transform:translateX(-50%);width:90%;max-width:360px;z-index:200;display:flex;flex-direction:column;gap:8px;pointer-events:none}
.toast{background:#111827;color:#fff;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:500;
  box-shadow:0 4px 16px rgba(0,0,0,.2);animation:slideDown .25s ease}

/* ── Skeleton ── */
.skeleton{background:linear-gradient(90deg,#e5e7eb 25%,#f3f4f6 50%,#e5e7eb 75%);
  background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px}
</style>
@stack('styles')
</head>
<body>
<div class="phone-wrap" id="app">

  {{-- App Bar --}}
  <div class="appbar">
    <div class="appbar-logo">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
    </div>
    <div class="appbar-title">
      <h1>{{ $desa['desa.nama'] ?? 'Portal Desa' }}</h1>
      <p>Halo, {{ $penduduk->nama_lengkap ?? $user->name }} 👋</p>
    </div>
    <div class="appbar-actions">
      <a href="{{ route('portal.notifikasi') }}" class="appbar-btn" aria-label="Notifikasi" style="position:relative">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span id="mobileNotifBadge" style="display:none;position:absolute;top:4px;right:4px;min-width:14px;height:14px;
              border-radius:99px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;
              line-height:14px;text-align:center;padding:0 3px"></span>
      </a>
      <a href="{{ route('portal.akun') }}" class="appbar-btn" aria-label="Akun saya">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      </a>
    </div>
  </div>

  {{-- Content --}}
  <div class="app-content" id="appContent">
    @yield('content')
  </div>

  {{-- Bottom Navigation --}}
  @php $rn = Route::currentRouteName(); @endphp
  <nav class="bottomnav">
    <a href="{{ route('portal') }}" class="nav-item {{ $rn === 'portal' ? 'active' : '' }}">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
      Beranda
    </a>
    <a href="{{ route('portal.layanan') }}" class="nav-item {{ $rn === 'portal.layanan' ? 'active' : '' }}">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
      Persurata
    </a>
    <a href="{{ route('portal.produk') }}" class="nav-item {{ $rn === 'portal.produk' ? 'active' : '' }}">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Produk
    </a>
    <a href="{{ route('portal.prediksi-stunting') }}" class="nav-item {{ $rn === 'portal.prediksi-stunting' ? 'active' : '' }}">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/><path d="M12 8v4l3 3"/><path d="M9.5 2.5C7 4 5 6.5 5 9.5"/></svg>
      Stunting
    </a>
    <a href="{{ route('portal.akun') }}" class="nav-item {{ $rn === 'portal.akun' ? 'active' : '' }}">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      Akun
    </a>
  </nav>

</div>{{-- end phone-wrap --}}

{{-- Logout form --}}
<form id="formLogout" method="POST" action="{{ route('logout') }}" style="display:none">
  @csrf
</form>

{{-- Toast container --}}
<div class="toast-wrap" id="toastWrap"></div>

<script>
@if(session('success'))
window.addEventListener('load', () => showToast('{{ addslashes(session('success')) }}', 'success'));
@endif
@if(session('error'))
window.addEventListener('load', () => showToast('{{ addslashes(session('error')) }}', 'error'));
@endif

function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  const el = document.getElementById('statusTime');
  if (el) el.textContent = h + ':' + m;
}
updateClock();
setInterval(updateClock, 10000);

// ── Notifikasi badge ──
async function loadMobileNotifCount() {
  try {
    const res  = await fetch('{{ route("notifications.unread-count") }}', { headers: { Accept: 'application/json' } });
    const data = await res.json();
    const badge = document.getElementById('mobileNotifBadge');
    if (badge) {
      if (data.count > 0) {
        badge.textContent = data.count > 99 ? '99+' : data.count;
        badge.style.display = 'block';
      } else {
        badge.style.display = 'none';
      }
    }
  } catch {}
}
loadMobileNotifCount();
setInterval(loadMobileNotifCount, 45000);

function showToast(msg, type) {
  const wrap = document.getElementById('toastWrap');
  const t = document.createElement('div');
  t.className = 'toast';
  t.style.background = type === 'success' ? '#15803d' : type === 'error' ? '#dc2626' : '#111827';
  t.textContent = msg;
  wrap.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
}

// Swipe antar halaman
(function(){
  const content = document.getElementById('appContent');
  let startX = 0, startY = 0;
  const PAGES = [
    '{{ route("portal") }}',
    '{{ route("portal.layanan") }}',
    '{{ route("portal.produk") }}',
    '{{ route("portal.prediksi-stunting") }}',
    '{{ route("portal.akun") }}',
  ];
  const RN = '{{ Route::currentRouteName() }}';
  const NAMES = ['portal','portal.layanan','portal.produk','portal.prediksi-stunting','portal.akun'];
  const idx = NAMES.indexOf(RN);
  content.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
  }, { passive: true });
  content.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - startX;
    const dy = e.changedTouches[0].clientY - startY;
    if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
      if (dx < 0 && idx < PAGES.length - 1) window.location.href = PAGES[idx + 1];
      else if (dx > 0 && idx > 0) window.location.href = PAGES[idx - 1];
    }
  }, { passive: true });
})();
</script>
@stack('scripts')
</body>
</html>
