<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#15803d">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>{{ $desa['desa.nama'] ?? 'Portal Desa' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;background:#f0f0f0;font-family:'Inter',system-ui,sans-serif;-webkit-font-smoothing:antialiased}

@keyframes spin{to{transform:rotate(360deg)}}

/* ── Phone frame on desktop ── */
.phone-wrap{
  width:100%;max-width:430px;min-height:100vh;
  margin:0 auto;background:#f5f6f8;
  position:relative;display:flex;flex-direction:column;
  box-shadow:0 0 40px rgba(0,0,0,.18);
  overflow:hidden;
}
@media (min-width:480px){
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
.appbar-btn{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);
  border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.15s}
.appbar-btn:hover,.appbar-btn:active{background:rgba(255,255,255,.25)}

/* ── Main scrollable content ── */
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
  flex:1;padding:10px 4px;border:none;background:none;cursor:pointer;
  display:flex;flex-direction:column;align-items:center;gap:3px;
  color:#9ca3af;font-size:10px;font-weight:500;transition:.15s;
  position:relative;
}
.nav-item.active{color:#15803d}
.nav-item.active::before{
  content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
  width:32px;height:3px;background:#15803d;border-radius:0 0 4px 4px;
}
.nav-item svg{transition:.15s}
.nav-item.active svg{filter:drop-shadow(0 0 4px rgba(21,128,61,.3))}

/* ── Tab panes ── */
.tab-pane{display:none}
.tab-pane.active{display:block}

/* ── Hero card ── */
.hero-card{
  margin:16px 16px 0;border-radius:20px;overflow:hidden;
  background:linear-gradient(135deg,#15803d,#1d4ed8);
  padding:20px;color:#fff;position:relative;
}
.hero-card::before{
  content:'';position:absolute;right:-20px;top:-20px;
  width:120px;height:120px;border-radius:50%;
  background:rgba(255,255,255,.08);
}
.hero-card::after{
  content:'';position:absolute;right:20px;bottom:-30px;
  width:80px;height:80px;border-radius:50%;
  background:rgba(255,255,255,.06);
}
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
@keyframes pulse{50%{box-shadow:0 0 0 6px rgba(74,222,128,0)}}

/* ── Stat row ── */
.stats-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:14px 16px 4px}
.stat-card{background:#fff;border-radius:16px;padding:14px;display:flex;align-items:center;gap:11px;
  box-shadow:0 1px 6px rgba(0,0,0,.07)}
.stat-ic{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-num{font-size:20px;font-weight:800;line-height:1;letter-spacing:-.02em}
.stat-lbl{font-size:11px;color:#6b7280;margin-top:2px;font-weight:500}

/* ── Section header ── */
.section-hd{display:flex;justify-content:space-between;align-items:center;padding:18px 16px 10px}
.section-hd h3{font-size:15px;font-weight:700;color:#111827}
.section-hd a{font-size:12px;font-weight:600;color:#15803d}

/* ── Quick actions ── */
.quick-actions{padding:0 16px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.qa-item{display:flex;flex-direction:column;align-items:center;gap:7px;cursor:pointer}
.qa-ic{width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;
  box-shadow:0 2px 8px rgba(0,0,0,.1)}
.qa-lbl{font-size:11px;font-weight:600;color:#374151;text-align:center;line-height:1.3}

/* ── Berita horizontal scroll ── */
.news-scroll{padding:0 16px;display:flex;gap:12px;overflow-x:auto;-webkit-overflow-scrolling:touch;
  scrollbar-width:none}
.news-scroll::-webkit-scrollbar{display:none}
.news-card{flex-shrink:0;width:220px;background:#fff;border-radius:16px;overflow:hidden;
  box-shadow:0 2px 8px rgba(0,0,0,.07)}
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
.layanan-info p{font-size:11px;color:#6b7280;margin-top:2px;display:-webkit-box;-webkit-line-clamp:1;
  -webkit-box-orient:vertical;overflow:hidden}
.layanan-arrow{flex-shrink:0;color:#d1d5db}
.empty-state{padding:48px 16px;text-align:center;color:#9ca3af}
.empty-state svg{margin:0 auto 12px;opacity:.4}
.empty-state p{font-size:14px;font-weight:500}
.empty-state small{font-size:12px;display:block;margin-top:4px}

/* ── Berita full list ── */
.berita-list{padding:0 16px;display:flex;flex-direction:column;gap:12px;padding-bottom:8px}
.berita-row{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.07);
  display:flex;gap:0}
.berita-row-thumb{width:90px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.berita-row-body{padding:12px;flex:1;min-width:0}
.berita-row-cat{font-size:10px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;
  color:#15803d;margin-bottom:4px}
.berita-row-title{font-size:13px;font-weight:700;color:#111827;line-height:1.4;
  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.berita-row-date{font-size:10px;color:#9ca3af;margin-top:5px;font-weight:500}

/* ── Profil info ── */
.profil-header{margin:16px;background:linear-gradient(135deg,#15803d,#166534);
  border-radius:20px;padding:20px;color:#fff;text-align:center}
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

/* ── FAB ── */
.fab{
  position:fixed;bottom:84px;right:20px;
  width:54px;height:54px;border-radius:18px;
  background:linear-gradient(135deg,#15803d,#166534);
  color:#fff;border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 4px 16px rgba(21,128,61,.45);
  z-index:90;transition:.2s;
}
.fab:active{transform:scale(.93)}
@media (min-width:480px){.fab{right:calc(50% - 200px)}}

/* ── Pill badge ── */
.pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:99px;
  font-size:10px;font-weight:700;letter-spacing:.04em}
.pill-green{background:#dcfce7;color:#15803d}
.pill-blue{background:#dbeafe;color:#1d4ed8}
.pill-orange{background:#fef3c7;color:#d97706}

/* ── Toast ── */
.toast-wrap{position:fixed;top:20px;left:50%;transform:translateX(-50%);
  width:90%;max-width:360px;z-index:200;display:flex;flex-direction:column;gap:8px;pointer-events:none}
.toast{background:#111827;color:#fff;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:500;
  box-shadow:0 4px 16px rgba(0,0,0,.2);animation:slideDown .25s ease}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}

/* ── Loading skeleton ── */
.skeleton{background:linear-gradient(90deg,#e5e7eb 25%,#f3f4f6 50%,#e5e7eb 75%);
  background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ── Scroll pull indicator ── */
.scroll-top-btn{
  position:fixed;bottom:90px;left:50%;transform:translateX(-50%);
  background:#fff;border:1.5px solid #e5e7eb;color:#374151;
  padding:7px 16px;border-radius:99px;font-size:12px;font-weight:600;
  box-shadow:0 2px 10px rgba(0,0,0,.1);cursor:pointer;
  display:none;align-items:center;gap:6px;z-index:89;
}
.scroll-top-btn.show{display:flex}
</style>
</head>
<body>
<div class="phone-wrap" id="app">

  {{-- ── Status Bar ── --}}
  <div class="statusbar" id="statusbar">
    <span id="statusTime">--:--</span>
    <div class="statusbar-icons">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 8.5C5.4 4.6 10.4 2.5 12 2.5s6.6 2.1 10.5 6L12 22 1.5 8.5z" fill-opacity=".3"/><path d="M12 22L5 13.5C7.3 11.2 9.6 10 12 10s4.7 1.2 7 3.5L12 22z" fill-opacity=".6"/><path d="M12 22l-3-4.5c.8-.8 1.9-1.3 3-1.3s2.2.5 3 1.3L12 22z"/></svg>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="2" y="7" width="16" height="10" rx="2"/><path d="M18 10h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2"/><line x1="6" y1="11" x2="6" y2="13"/><line x1="10" y1="11" x2="10" y2="13"/><line x1="14" y1="11" x2="14" y2="13"/></svg>
    </div>
  </div>

  {{-- ── App Bar ── --}}
  <div class="appbar">
    <div class="appbar-logo">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
    </div>
    <div class="appbar-title">
      <h1>{{ $desa['desa.nama'] ?? 'Portal Desa' }}</h1>
      <p>Halo, {{ $penduduk->nama_lengkap ?? $user->name }} 👋</p>
    </div>
    <div class="appbar-actions">
      <button class="appbar-btn" onclick="showSearch()" aria-label="Cari">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      </button>
      <button class="appbar-btn" onclick="switchTab('akun')" aria-label="Akun saya">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      </button>
    </div>
  </div>

  {{-- ── Content ── --}}
  <div class="app-content" id="appContent">

    {{-- ══════════════ TAB: BERANDA ══════════════ --}}
    <div class="tab-pane active" id="pane-home">

      {{-- Hero --}}
      <div class="hero-card">
        <div class="hero-badge">
          <span class="dot"></span>
          Portal Resmi Pemerintah Desa
        </div>
        <h2>Selamat Datang,<br>{{ $penduduk->nama_lengkap ?? $user->name }} 🌿</h2>
        <p style="margin-top:6px">{{ $desa['desa.kecamatan'] ? 'Kec. ' . $desa['desa.kecamatan'] . (isset($desa['desa.kabupaten']) ? ', ' . $desa['desa.kabupaten'] : '') : 'Layanan administrasi desa dalam genggaman Anda' }}</p>
      </div>

      {{-- Statistik --}}
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-ic" style="background:#dcfce7">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div>
            <div class="stat-num">{{ number_format($jumlahPenduduk) }}</div>
            <div class="stat-lbl">Penduduk</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-ic" style="background:#dbeafe">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
          </div>
          <div>
            <div class="stat-num">{{ number_format($jumlahKK) }}</div>
            <div class="stat-lbl">Kartu Keluarga</div>
          </div>
        </div>
      </div>

      {{-- Quick Actions --}}
      <div class="section-hd" style="padding-bottom:12px">
        <h3>Aksi Cepat</h3>
      </div>
      <div class="quick-actions">
        <div class="qa-item" onclick="switchTab('layanan')">
          <div class="qa-ic" style="background:linear-gradient(135deg,#15803d,#166534)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
          </div>
          <span class="qa-lbl">Buat Surat</span>
        </div>
        <div class="qa-item" onclick="switchTab('berita')">
          <div class="qa-ic" style="background:linear-gradient(135deg,#1d4ed8,#1e40af)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
          </div>
          <span class="qa-lbl">Berita Desa</span>
        </div>
        <div class="qa-item" onclick="switchTab('akun')">
          <div class="qa-ic" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          </div>
          <span class="qa-lbl">Akun Saya</span>
        </div>
        <div class="qa-item" onclick="cekSurat()">
          <div class="qa-ic" style="background:linear-gradient(135deg,#d97706,#b45309)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          </div>
          <span class="qa-lbl">Cek Status</span>
        </div>
      </div>

      {{-- Layanan Tersedia --}}
      @if($layanan->isNotEmpty())
      <div class="section-hd">
        <h3>Layanan Surat</h3>
        <a href="#" onclick="switchTab('layanan');return false">Lihat semua</a>
      </div>
      <div style="padding:0 16px;display:flex;flex-direction:column;gap:9px;padding-bottom:4px">
        @foreach($layanan->take(3) as $j)
        <div class="layanan-card" onclick="switchTab('layanan')">
          <div class="layanan-ic">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
          </div>
          <div class="layanan-info">
            <h4>{{ $j->nama }}</h4>
            @if($j->kode)
            <p>Kode: {{ $j->kode }}</p>
            @endif
          </div>
          <div class="layanan-arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- Berita Terbaru --}}
      @if($berita->isNotEmpty())
      <div class="section-hd">
        <h3>Berita Terbaru</h3>
        <a href="#" onclick="switchTab('berita');return false">Lihat semua</a>
      </div>
      <div class="news-scroll">
        @foreach($berita->take(5) as $b)
        @php
          $colors = ['linear-gradient(135deg,#15803d,#166534)','linear-gradient(135deg,#1d4ed8,#1e40af)','linear-gradient(135deg,#7c3aed,#6d28d9)','linear-gradient(135deg,#d97706,#b45309)','linear-gradient(135deg,#dc2626,#b91c1c)'];
          $idx = $loop->index % count($colors);
        @endphp
        <div class="news-card">
          <div class="news-thumb" style="background:{{ $colors[$idx] }}">
            @if($b->gambar_url)
            <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
            @else
            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
            @endif
            @if($b->kategori)
            <span class="news-cat">{{ $b->kategori->nama }}</span>
            @endif
          </div>
          <div class="news-body">
            <div class="news-date">{{ $b->published_at ? $b->published_at->locale('id')->diffForHumans() : '' }}</div>
            <div class="news-title">{{ $b->judul }}</div>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      <div style="height:24px"></div>
    </div>{{-- end pane-home --}}

    {{-- ══════════════ TAB: LAYANAN ══════════════ --}}
    <div class="tab-pane" id="pane-layanan">
      <div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 48px;color:#fff">
        <p style="font-size:11px;opacity:.7;margin-bottom:4px">PORTAL LAYANAN</p>
        <h2 style="font-size:20px;font-weight:800">Layanan Surat</h2>
        <p style="font-size:12px;opacity:.8;margin-top:4px">Pantau riwayat dan ajukan surat desa</p>
      </div>

      {{-- Sub-tab switcher --}}
      <div style="margin:-26px 16px 0;margin-bottom:14px">
        <div style="background:#fff;border-radius:16px;padding:4px;box-shadow:0 2px 12px rgba(0,0,0,.1);display:flex;gap:4px">
          <button id="ltab-riwayat" onclick="switchLayananTab('riwayat')"
            style="flex:1;padding:10px 6px;border:none;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;
                   background:linear-gradient(135deg,#15803d,#166534);color:#fff;transition:all .2s">
            Riwayat
          </button>
          <button id="ltab-buat" onclick="switchLayananTab('buat')"
            style="flex:1;padding:10px 6px;border:none;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;
                   background:transparent;color:#6b7280;transition:all .2s">
            Buat Surat
          </button>
        </div>
      </div>

      {{-- ── Panel: RIWAYAT ── --}}
      <div id="lpane-riwayat">
        @if($riwayat->isEmpty())
        <div class="empty-state" style="padding:40px 24px">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
          <p>Belum ada pengajuan surat</p>
          <small>Tekan "Buat Surat" untuk mengajukan</small>
          <button onclick="switchLayananTab('buat')"
            style="margin-top:14px;padding:10px 24px;background:linear-gradient(135deg,#15803d,#166534);
                   color:#fff;border:none;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer">
            + Buat Pengajuan
          </button>
        </div>
        @else
        <div style="padding:0 16px 8px">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <p style="font-size:12px;font-weight:600;color:#6b7280">{{ $riwayat->count() }} pengajuan</p>
            <button onclick="switchLayananTab('buat')"
              style="padding:6px 14px;background:linear-gradient(135deg,#15803d,#166534);color:#fff;
                     border:none;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer">
              + Baru
            </button>
          </div>
          @php
            $statusColorMap = [
              'diajukan'     => ['bg'=>'#fef3c7','text'=>'#b45309','border'=>'#fde68a'],
              'menunggu_ttd' => ['bg'=>'#f5f3ff','text'=>'#6d28d9','border'=>'#ddd6fe'],
              'disetujui'    => ['bg'=>'#eff6ff','text'=>'#1e40af','border'=>'#bfdbfe'],
              'selesai'      => ['bg'=>'#f0fdf4','text'=>'#15803d','border'=>'#bbf7d0'],
              'ditolak'      => ['bg'=>'#fef2f2','text'=>'#dc2626','border'=>'#fecaca'],
            ];
          @endphp
          @foreach($riwayat as $r)
          @php
            $sc = $statusColorMap[$r->status] ?? ['bg'=>'#f3f4f6','text'=>'#374151','border'=>'#e5e7eb'];
          @endphp
          <div style="background:#fff;border-radius:16px;padding:14px;margin-bottom:10px;
                      box-shadow:0 1px 6px rgba(0,0,0,.06);border:1px solid #f3f4f6">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px">
              <div style="flex:1;min-width:0">
                <h4 style="font-size:13px;font-weight:700;color:#111827;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $r->jenis_surat }}</h4>
                <p style="font-size:11px;color:#9ca3af">{{ $r->created_at->locale('id')->translatedFormat('d M Y') }}</p>
              </div>
              <span style="flex-shrink:0;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;
                           background:{{ $sc['bg'] }};color:{{ $sc['text'] }};border:1.5px solid {{ $sc['border'] }}">
                {{ $r->status_label }}
              </span>
            </div>
            <p style="font-size:12px;color:#6b7280;margin-bottom:2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $r->keperluan }}</p>
            @if($r->nomor_surat)
            <p style="font-size:11px;color:#9ca3af;margin-top:4px">No: {{ $r->nomor_surat }}</p>
            @endif
            @if($r->status === 'ditolak' && $r->catatan)
            <div style="margin-top:8px;padding:8px 10px;background:#fef2f2;border-radius:10px;border:1px solid #fecaca">
              <p style="font-size:11px;color:#dc2626;font-weight:600">Alasan: {{ $r->catatan }}</p>
            </div>
            @endif
            @if($r->status === 'selesai')
            <a href="{{ route('portal.surat.pdf', $r->id) }}"
               style="display:flex;align-items:center;justify-content:center;gap:7px;margin-top:10px;
                      padding:10px;background:linear-gradient(135deg,#15803d,#166534);
                      color:#fff;border-radius:12px;font-size:12px;font-weight:700;text-decoration:none">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Unduh PDF Surat
            </a>
            @endif
          </div>
          @endforeach
        </div>
        @endif
        <div style="height:16px"></div>
      </div>

      {{-- ── Panel: BUAT SURAT ── --}}
      <div id="lpane-buat" style="display:none">
        <div style="padding:0 16px 8px">
          <div style="background:#fff;border-radius:14px;padding:11px 14px;box-shadow:0 1px 6px rgba(0,0,0,.07);
                      display:flex;align-items:center;gap:10px;margin-bottom:12px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input id="searchLayanan" type="text" placeholder="Cari jenis surat..." oninput="filterLayanan(this.value)"
              style="border:none;outline:none;font-size:13px;font-weight:500;width:100%;background:transparent;color:#111827">
          </div>
        </div>

        <div class="layanan-list" id="layananList">
          @forelse($layanan as $j)
          @php
            $lColors = ['#15803d','#1d4ed8','#7c3aed','#d97706','#dc2626','#0891b2','#0f766e'];
            $lIdx = $loop->index % count($lColors);
          @endphp
          <div class="layanan-card" data-nama="{{ strtolower($j->nama) }}"
               onclick="buatSurat({{ $j->id }}, '{{ addslashes($j->nama) }}')">
            <div class="layanan-ic" style="background:{{ $lColors[$lIdx] }}">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
            </div>
            <div class="layanan-info">
              <h4>{{ $j->nama }}</h4>
              <p>Klik untuk mengajukan</p>
            </div>
            <div class="layanan-arrow">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
            </div>
          </div>
          @empty
          <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6"/></svg>
            <p>Belum ada layanan surat</p>
            <small>Hubungi kantor desa untuk informasi lebih lanjut</small>
          </div>
          @endforelse
        </div>
        <div style="height:16px"></div>
      </div>

    </div>{{-- end pane-layanan --}}

    {{-- ══════════════ TAB: BERITA ══════════════ --}}
    <div class="tab-pane" id="pane-berita">
      <div style="background:linear-gradient(135deg,#1d4ed8,#1e40af);padding:20px 16px 24px;color:#fff">
        <p style="font-size:11px;opacity:.7;margin-bottom:4px">INFORMASI TERKINI</p>
        <h2 style="font-size:20px;font-weight:800">Berita & Pengumuman</h2>
        <p style="font-size:12px;opacity:.8;margin-top:4px">Kabar terbaru dari {{ $desa['desa.nama'] ?? 'desa kami' }}</p>
      </div>

      <div class="berita-list" style="padding-top:14px">
        @forelse($berita as $b)
        @php
          $bColors = ['#15803d','#1d4ed8','#7c3aed','#d97706','#dc2626','#0891b2','#0f766e'];
          $bIdx = $loop->index % count($bColors);
        @endphp
        <div class="berita-row">
          <div class="berita-row-thumb" style="background:{{ $bColors[$bIdx] }};position:relative;overflow:hidden">
            @if($b->gambar_url)
            <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" style="width:90px;height:100%;object-fit:cover;display:block">
            @else
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
            @endif
          </div>
          <div class="berita-row-body">
            @if($b->kategori)
            <div class="berita-row-cat">{{ $b->kategori->nama }}</div>
            @endif
            <div class="berita-row-title">{{ $b->judul }}</div>
            <div class="berita-row-date">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:middle;margin-right:3px"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $b->published_at ? $b->published_at->locale('id')->translatedFormat('d M Y') : 'Baru saja' }}
            </div>
          </div>
        </div>
        @empty
        <div class="empty-state">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
          <p>Belum ada berita</p>
          <small>Informasi desa akan tampil di sini</small>
        </div>
        @endforelse
      </div>
      <div style="height:16px"></div>
    </div>{{-- end pane-berita --}}

    {{-- ══════════════ TAB: AKUN ══════════════ --}}
    <div class="tab-pane" id="pane-akun">

      {{-- Profile header --}}
      @php
        $namaUser = $penduduk->nama_lengkap ?? $user->name;
        $inisial  = collect(explode(' ', $namaUser))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->implode('');
      @endphp
      <div class="profil-header">
        <div class="profil-logo" style="font-size:22px;font-weight:800;color:#fff">{{ $inisial }}</div>
        <h2>{{ $namaUser }}</h2>
        <p>{{ $user->email }}</p>
        @if($penduduk)
        <div style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;background:rgba(255,255,255,.15);
                    border:1px solid rgba(255,255,255,.25);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
          Warga Terverifikasi
        </div>
        @endif
      </div>

      {{-- Data Diri --}}
      @if($penduduk)
      <div class="info-card" style="margin-top:16px">
        <div class="info-card-header">
          <div class="info-ic" style="background:#dcfce7">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2.5"/><path d="M5.5 16c.7-2 1.8-3 3.5-3s2.8 1 3.5 3M15 9h4M15 13h4"/></svg>
          </div>
          <h3>Data Diri</h3>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">Nama Lengkap</span>
          <span class="info-row-val">{{ $penduduk->nama_lengkap }}</span>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">NIK</span>
          <span class="info-row-val" style="font-family:monospace;letter-spacing:.05em">{{ substr($penduduk->nik,0,4) . ' •••• •••• ' . substr($penduduk->nik,-4) }}</span>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">Tempat Lahir</span>
          <span class="info-row-val">{{ $penduduk->tempat_lahir }}</span>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">Tanggal Lahir</span>
          <span class="info-row-val">{{ $penduduk->tanggal_lahir->locale('id')->translatedFormat('d F Y') }}</span>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">Jenis Kelamin</span>
          <span class="info-row-val">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">Status</span>
          <span class="info-row-val">{{ $penduduk->status_perkawinan_label }}</span>
        </div>
        @if($penduduk->pekerjaan)
        <div class="info-row">
          <span class="info-row-lbl">Pekerjaan</span>
          <span class="info-row-val">{{ $penduduk->pekerjaan }}</span>
        </div>
        @endif
      </div>

      {{-- Data KK --}}
      @if($penduduk->kartuKeluarga)
      <div class="info-card">
        <div class="info-card-header">
          <div class="info-ic" style="background:#dbeafe">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
          </div>
          <h3>Kartu Keluarga</h3>
        </div>
        <div class="info-row">
          <span class="info-row-lbl">No. KK</span>
          <span class="info-row-val" style="font-family:monospace;letter-spacing:.05em">{{ substr($penduduk->kartuKeluarga->nomor_kk,0,4) . ' •••• •••• ' . substr($penduduk->kartuKeluarga->nomor_kk,-4) }}</span>
        </div>
        @if($penduduk->kartuKeluarga->alamat)
        <div class="info-row">
          <span class="info-row-lbl">Alamat</span>
          <span class="info-row-val">{{ $penduduk->kartuKeluarga->alamat }}{{ $penduduk->kartuKeluarga->rt ? ', RT ' . $penduduk->kartuKeluarga->rt . '/RW ' . $penduduk->kartuKeluarga->rw : '' }}</span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-row-lbl">Status dalam KK</span>
          <span class="info-row-val">{{ $penduduk->hubungan_label }}</span>
        </div>
      </div>
      @endif
      @endif

      {{-- Pengaturan Akun --}}
      <div class="info-card" style="margin-bottom:12px">
        <div class="info-card-header">
          <div class="info-ic" style="background:#f3f4f6">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          </div>
          <h3>Pengaturan Akun</h3>
        </div>
        <div class="info-row" style="cursor:pointer" onclick="document.getElementById('formLogout').submit()">
          <span class="info-row-lbl" style="width:auto;color:#dc2626;display:flex;align-items:center;gap:8px">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            Keluar dari Akun
          </span>
        </div>
      </div>

      {{-- Pejabat Desa --}}
      @if($pejabat->isNotEmpty())
      <div class="info-card">
        <div class="info-card-header">
          <div class="info-ic" style="background:#ede9fe">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3>Pejabat Desa</h3>
        </div>
        <div class="pejabat-list">
          @foreach($pejabat as $pj)
          @php
            $initials = collect(explode(' ', $pj->nama))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->implode('');
            $pjColors = ['linear-gradient(135deg,#15803d,#166534)','linear-gradient(135deg,#1d4ed8,#1e40af)','linear-gradient(135deg,#7c3aed,#6d28d9)','linear-gradient(135deg,#d97706,#b45309)'];
            $pjIdx = $loop->index % count($pjColors);
          @endphp
          <div class="pejabat-item">
            <div class="pejabat-avatar" style="background:{{ $pjColors[$pjIdx] }}">
              @if($pj->foto_url)
              <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}" style="width:42px;height:42px;object-fit:cover;border-radius:12px">
              @else
              {{ $initials }}
              @endif
            </div>
            <div>
              <div class="pejabat-name">{{ $pj->nama }}</div>
              <div class="pejabat-jabatan">{{ $pj->jabatan->nama ?? '—' }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <div style="height:24px"></div>
    </div>{{-- end pane-akun --}}

  </div>{{-- end app-content --}}

  {{-- ── Bottom Navigation ── --}}
  <nav class="bottomnav">
    <button class="nav-item active" id="nav-home" onclick="switchTab('home')">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
      Beranda
    </button>
    <button class="nav-item" id="nav-layanan" onclick="switchTab('layanan')">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
      Layanan
    </button>
    <button class="nav-item" id="nav-berita" onclick="switchTab('berita')">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
      Berita
    </button>
    <button class="nav-item" id="nav-akun" onclick="switchTab('akun')">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      Akun
    </button>
  </nav>

</div>{{-- end phone-wrap --}}

{{-- ── Logout form (hidden) ── --}}
<form id="formLogout" method="POST" action="{{ route('logout') }}" style="display:none">
  @csrf
</form>

{{-- ── Toast container ── --}}
<div class="toast-wrap" id="toastWrap"></div>

{{-- ── Modal Buat Surat (form) ── --}}
<div id="modalSurat" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
  <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:430px;
              background:#fff;border-radius:24px 24px 0 0;padding:20px;
              padding-bottom:calc(20px + env(safe-area-inset-bottom,0));max-height:85vh;overflow-y:auto">
    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:0 auto 16px"></div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
      <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);
                  display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
      </div>
      <div style="flex:1;min-width:0">
        <h3 style="font-size:15px;font-weight:800;color:#111827" id="modalSuratNama"></h3>
        <p style="font-size:12px;color:#6b7280;margin-top:2px">Isi form pengajuan di bawah ini</p>
      </div>
      <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;padding:4px;color:#9ca3af">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <form id="formPengajuan" method="POST" action="{{ route('portal.surat.store') }}">
      @csrf
      <input type="hidden" id="inputJenisSuratId" name="jenis_surat_id">

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Keperluan <span style="color:#dc2626">*</span>
        </label>
        <textarea name="keperluan" id="inputKeperluan" rows="3" maxlength="500"
          placeholder="Jelaskan keperluan pengajuan surat ini..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:13px;font-family:inherit;color:#111827;resize:vertical;outline:none;
                 transition:border-color .2s"
          onfocus="this.style.borderColor='#15803d'"
          onblur="this.style.borderColor='#e5e7eb'"></textarea>
      </div>

      <div style="margin-bottom:20px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Keterangan Tambahan <span style="font-weight:400;color:#9ca3af">(opsional)</span>
        </label>
        <textarea name="keterangan" id="inputKeterangan" rows="2" maxlength="1000"
          placeholder="Informasi tambahan jika diperlukan..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:13px;font-family:inherit;color:#111827;resize:vertical;outline:none;
                 transition:border-color .2s"
          onfocus="this.style.borderColor='#15803d'"
          onblur="this.style.borderColor='#e5e7eb'"></textarea>
      </div>

      <div style="display:flex;gap:10px">
        <button type="button" onclick="closeModal()"
          style="flex:1;padding:13px;border-radius:14px;background:#f3f4f6;
                 border:none;color:#374151;font-size:13px;font-weight:700;cursor:pointer">
          Batal
        </button>
        <button type="submit" id="btnKirimSurat"
          style="flex:2;padding:13px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);
                 border:none;color:#fff;font-size:14px;font-weight:700;cursor:pointer;
                 display:flex;align-items:center;justify-content:center;gap:8px">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
          Kirim Pengajuan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// ── Session flash toast ──
@if(session('success'))
window.addEventListener('load', () => showToast('{{ addslashes(session('success')) }}', 'success'));
@endif
@if(session('error'))
window.addEventListener('load', () => showToast('{{ addslashes(session('error')) }}', 'error'));
@endif

// ── Status bar clock ──
function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  const el = document.getElementById('statusTime');
  if (el) el.textContent = h + ':' + m;
}
updateClock();
setInterval(updateClock, 10000);

// ── Tab switching ──
let activeTab = 'home';
function switchTab(tab) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('pane-' + tab).classList.add('active');
  document.getElementById('nav-' + tab).classList.add('active');
  document.getElementById('appContent').scrollTo({ top: 0, behavior: 'smooth' });
  activeTab = tab;
}

// ── Layanan sub-tabs ──
function switchLayananTab(tab) {
  const isRiwayat = tab === 'riwayat';
  document.getElementById('lpane-riwayat').style.display = isRiwayat ? '' : 'none';
  document.getElementById('lpane-buat').style.display    = isRiwayat ? 'none' : '';
  const rBtn = document.getElementById('ltab-riwayat');
  const bBtn = document.getElementById('ltab-buat');
  if (isRiwayat) {
    rBtn.style.cssText += ';background:linear-gradient(135deg,#15803d,#166534);color:#fff';
    bBtn.style.cssText += ';background:transparent;color:#6b7280';
  } else {
    bBtn.style.cssText += ';background:linear-gradient(135deg,#15803d,#166534);color:#fff';
    rBtn.style.cssText += ';background:transparent;color:#6b7280';
  }
}

// ── Filter layanan ──
function filterLayanan(q) {
  const term = q.toLowerCase().trim();
  document.querySelectorAll('#layananList .layanan-card').forEach(card => {
    card.style.display = (card.dataset.nama || '').includes(term) ? '' : 'none';
  });
}

// ── Modal surat ──
function buatSurat(id, nama) {
  document.getElementById('inputJenisSuratId').value = id;
  document.getElementById('modalSuratNama').textContent = nama;
  document.getElementById('inputKeperluan').value = '';
  document.getElementById('inputKeterangan').value = '';
  document.getElementById('modalSurat').style.display = 'block';
  setTimeout(() => document.getElementById('inputKeperluan').focus(), 150);
}
function closeModal() {
  document.getElementById('modalSurat').style.display = 'none';
}
document.getElementById('modalSurat').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
document.getElementById('formPengajuan').addEventListener('submit', function() {
  const btn = document.getElementById('btnKirimSurat');
  btn.disabled = true;
  btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Mengirim...';
});

// ── Cek status surat ──
function cekSurat() {
  const token = prompt('Masukkan nomor/token surat Anda:');
  if (token && token.trim()) {
    window.location.href = '/verifikasi/' + encodeURIComponent(token.trim());
  }
}

// ── Search (placeholder) ──
function showSearch() {
  switchTab('berita');
  setTimeout(() => {
    const s = document.querySelector('#pane-berita input');
    if (s) s.focus();
  }, 100);
}

// ── Toast ──
function showToast(msg, type) {
  const wrap = document.getElementById('toastWrap');
  const t = document.createElement('div');
  t.className = 'toast';
  t.style.background = type === 'success' ? '#15803d' : type === 'error' ? '#dc2626' : '#111827';
  t.textContent = msg;
  wrap.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
}

// ── Swipe to switch tabs ──
(function(){
  const content = document.getElementById('appContent');
  let startX = 0, startY = 0;
  const tabs = ['home','layanan','berita','akun'];
  content.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
  }, { passive: true });
  content.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - startX;
    const dy = e.changedTouches[0].clientY - startY;
    if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
      const idx = tabs.indexOf(activeTab);
      if (dx < 0 && idx < tabs.length - 1) switchTab(tabs[idx + 1]);
      else if (dx > 0 && idx > 0) switchTab(tabs[idx - 1]);
    }
  }, { passive: true });
})();
</script>
</body>
</html>
