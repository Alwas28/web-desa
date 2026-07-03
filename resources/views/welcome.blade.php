<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Desa Tirta Lestari — Website Resmi Pemerintah Desa</title>
<meta name="description" content="Website resmi Pemerintah Desa Tirta Lestari. Layanan publik, transparansi anggaran, berita desa, dan potensi unggulan desa.">
<meta property="og:title" content="Desa Tirta Lestari — Website Resmi">
<meta property="og:description" content="Layanan publik, transparansi anggaran, dan potensi Desa Tirta Lestari.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@1,8..60,500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
/* ============ DESIGN TOKENS ============ */
:root{
  --bg:#F4FAF7; --surface:#FFFFFF; --surface-2:#EDF6F1;
  --ink:#0F241B; --muted:#54685E; --line:rgba(15,36,27,.09);
  --green:#0C7C46; --green-deep:#075F36; --green-soft:#DDF1E6;
  --blue:#0E63A8; --blue-deep:#0A4E86; --blue-soft:#E1EFFA;
  --gold:#C99A2C;
  --glass:rgba(255,255,255,.72); --glass-line:rgba(255,255,255,.55);
  --shadow:0 10px 30px -12px rgba(12,60,40,.18);
  --shadow-lg:0 24px 60px -20px rgba(12,60,40,.25);
  --radius:18px;
  --hero-sky:#CFE8F7; --hero-sky2:#EAF6EE;
}
[data-theme="dark"]{
  --bg:#0A1410; --surface:#101D17; --surface-2:#15251D;
  --ink:#E9F4EE; --muted:#9DB3A8; --line:rgba(233,244,238,.1);
  --green:#3BCD85; --green-deep:#2AA468; --green-soft:#143323;
  --blue:#5FAEE6; --blue-deep:#3F8FC9; --blue-soft:#10283B;
  --gold:#E0B854;
  --glass:rgba(16,29,23,.72); --glass-line:rgba(233,244,238,.12);
  --shadow:0 10px 30px -12px rgba(0,0,0,.5);
  --shadow-lg:0 24px 60px -20px rgba(0,0,0,.6);
  --hero-sky:#0E2233; --hero-sky2:#0E1F18;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  *,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}
}
body{
  font-family:'Plus Jakarta Sans',system-ui,sans-serif;
  background:var(--bg); color:var(--ink);
  line-height:1.65; font-size:16px;
  transition:background .35s,color .35s;
  -webkit-font-smoothing:antialiased;
}
img,svg{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
button{font:inherit;cursor:pointer;border:none;background:none;color:inherit}
:focus-visible{outline:3px solid var(--blue);outline-offset:3px;border-radius:6px}
.container{width:min(1180px,92%);margin-inline:auto}
.eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--green);}
.eyebrow::before{content:"";width:26px;height:2px;background:linear-gradient(90deg,var(--green),var(--blue));border-radius:2px}
.sec-title{font-size:clamp(1.6rem,3.4vw,2.3rem);font-weight:800;letter-spacing:-.02em;margin:.4rem 0 .6rem}
.sec-desc{color:var(--muted);max-width:620px}
section{padding:clamp(56px,8vw,96px) 0;position:relative}
.tenun{height:14px;width:100%;background-image:repeating-linear-gradient(135deg,var(--green) 0 6px,transparent 6px 14px),repeating-linear-gradient(45deg,var(--blue) 0 6px,transparent 6px 14px);opacity:.18;border-radius:99px;margin-top:1.2rem;max-width:220px}

/* ============ HEADER ============ */
.topbar{background:linear-gradient(90deg,var(--green-deep),var(--blue-deep));color:#fff;font-size:.78rem;padding:6px 0}
.topbar .container{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
.topbar a{opacity:.9}
header.site{
  position:sticky;top:0;z-index:60;
  background:var(--glass);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
  border-bottom:1px solid var(--line);
  transition:box-shadow .3s,background .35s;
}
header.site.scrolled{box-shadow:var(--shadow)}
.nav-wrap{display:flex;align-items:center;gap:14px;padding:12px 0}
.brand{display:flex;align-items:center;gap:11px;margin-right:auto}
.brand .logo{width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,var(--green),var(--blue));display:grid;place-items:center;color:#fff;box-shadow:var(--shadow);flex-shrink:0}
.brand b{font-size:1.02rem;letter-spacing:-.01em;display:block;line-height:1.2}
.brand small{color:var(--muted);font-size:.72rem;font-weight:600;letter-spacing:.06em}
nav.primary{display:flex;gap:2px;align-items:center}
nav.primary a{padding:8px 11px;border-radius:10px;font-size:.83rem;font-weight:600;color:var(--muted);transition:.2s;white-space:nowrap}
nav.primary a:hover{color:var(--ink);background:var(--surface-2)}
nav.primary a.active{color:var(--green);background:var(--green-soft)}
.icon-btn{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;border:1px solid var(--line);background:var(--surface);transition:.2s;flex-shrink:0}
.icon-btn:hover{border-color:var(--green);color:var(--green);transform:translateY(-1px)}
.btn{display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:.88rem;padding:11px 20px;border-radius:13px;transition:.25s;border:1px solid transparent}
.btn-primary{background:linear-gradient(135deg,var(--green),var(--green-deep));color:#fff;box-shadow:0 8px 20px -8px rgba(12,124,70,.55)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 14px 26px -10px rgba(12,124,70,.6)}
.btn-outline{border-color:var(--line);background:var(--surface);color:var(--ink)}
.btn-outline:hover{border-color:var(--blue);color:var(--blue);transform:translateY(-2px)}
.btn-blue{background:linear-gradient(135deg,var(--blue),var(--blue-deep));color:#fff;box-shadow:0 8px 20px -8px rgba(14,99,168,.55)}
.btn-blue:hover{transform:translateY(-2px)}
#hamburger{display:none}
.mobile-nav{display:none}
@media (max-width:1080px){
  nav.primary{display:none}
  #hamburger{display:grid}
  .mobile-nav{display:block;position:fixed;inset:0;z-index:100;background:var(--glass);backdrop-filter:blur(18px);opacity:0;pointer-events:none;transition:.3s}
  .mobile-nav.open{opacity:1;pointer-events:auto}
  .mobile-nav .panel{background:var(--surface);width:min(340px,86%);height:100%;margin-left:auto;padding:24px;display:flex;flex-direction:column;gap:4px;transform:translateX(40px);transition:.3s;overflow-y:auto}
  .mobile-nav.open .panel{transform:none}
  .mobile-nav a{padding:13px 14px;border-radius:12px;font-weight:600;border:1px solid transparent}
  .mobile-nav a:hover,.mobile-nav a.active{background:var(--green-soft);color:var(--green)}
}
.breadcrumb{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;padding:8px 0;color:var(--muted)}
.breadcrumb .container{display:flex;align-items:center;gap:8px}
.breadcrumb b{color:var(--green);font-weight:700}

/* ============ HERO ============ */
.hero{padding:0;overflow:hidden;background:linear-gradient(180deg,var(--hero-sky),var(--hero-sky2));}
.hero-inner{display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;padding:clamp(48px,7vw,90px) 0 0}
.hero h1{font-size:clamp(2rem,4.6vw,3.4rem);font-weight:800;letter-spacing:-.03em;line-height:1.12}
.hero h1 em{font-family:'Source Serif 4',serif;font-style:italic;font-weight:500;background:linear-gradient(90deg,var(--green),var(--blue));-webkit-background-clip:text;background-clip:text;color:transparent}
.hero p.lead{color:var(--muted);margin:18px 0 26px;max-width:520px;font-size:1.02rem}
.hero-cta{display:flex;gap:12px;flex-wrap:wrap}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:var(--glass);border:1px solid var(--glass-line);backdrop-filter:blur(8px);padding:7px 14px;border-radius:99px;font-size:.78rem;font-weight:700;color:var(--green);box-shadow:var(--shadow);margin-bottom:18px}
.hero-badge .dot{width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px var(--green-soft);animation:pulse 2s infinite}
@keyframes pulse{50%{box-shadow:0 0 0 7px transparent}}
.hero-art{position:relative}
.hero-art svg{width:100%;height:auto}
.hero-strip{position:relative;margin-top:clamp(30px,5vw,56px)}
@media (max-width:880px){.hero-inner{grid-template-columns:1fr}.hero-art{order:-1;max-width:520px;margin-inline:auto}}

/* ============ STATISTIK ============ */
.stats{margin-top:-58px;position:relative;z-index:5;padding:0 0 8px}
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px}
.stat-card{background:var(--glass);border:1px solid var(--glass-line);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-radius:var(--radius);padding:20px 18px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:8px;transition:.25s}
.stat-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.stat-card .ic{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;color:#fff}
.stat-card .num{font-size:1.65rem;font-weight:800;letter-spacing:-.02em;line-height:1}
.stat-card .num span{font-size:.95rem;color:var(--muted);font-weight:700}
.stat-card .lbl{font-size:.78rem;color:var(--muted);font-weight:600}
@media (max-width:980px){.stats-grid{grid-template-columns:repeat(3,1fr)}}
@media (max-width:620px){.stats-grid{grid-template-columns:repeat(2,1fr)}.stats{margin-top:-30px}}

/* ============ PROFIL ============ */
.profil-grid{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:34px}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);transition:.25s}
.kades{padding:30px;display:flex;flex-direction:column;gap:16px}
.kades .quote{font-family:'Source Serif 4',serif;font-style:italic;font-size:1.12rem;line-height:1.7;color:var(--ink)}
.kades .who{display:flex;align-items:center;gap:14px}
.avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--green),var(--blue));display:grid;place-items:center;color:#fff;font-weight:800;font-size:1.1rem;flex-shrink:0}
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
@media (max-width:880px){.profil-grid{grid-template-columns:1fr}}

/* ============ LAYANAN ============ */
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
@media (max-width:880px){.svc-grid{grid-template-columns:repeat(2,1fr)}}
@media (max-width:560px){.svc-grid{grid-template-columns:1fr}}

/* ============ BERITA ============ */
.news-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:36px}
.news{overflow:hidden;display:flex;flex-direction:column}
.news:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg)}
.news .thumb{height:180px;position:relative;display:grid;place-items:center;color:#fff}
.news .cat{position:absolute;top:12px;left:12px;background:rgba(255,255,255,.92);color:var(--green-deep);font-size:.7rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:5px 11px;border-radius:99px}
.news .body{padding:20px;display:flex;flex-direction:column;gap:10px;flex:1}
.news .date{font-size:.74rem;color:var(--muted);font-weight:600;display:flex;gap:6px;align-items:center}
.news h3{font-size:1rem;font-weight:800;line-height:1.4}
.news p{font-size:.86rem;color:var(--muted);flex:1}
.news .more{color:var(--blue);font-weight:700;font-size:.84rem}
@media (max-width:880px){.news-grid{grid-template-columns:1fr 1fr}}
@media (max-width:560px){.news-grid{grid-template-columns:1fr}}

/* ============ POTENSI ============ */
#potensi{background:linear-gradient(180deg,var(--surface-2),var(--bg))}
.pot-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:36px}
.pot{position:relative;border-radius:var(--radius);overflow:hidden;min-height:210px;display:flex;align-items:flex-end;padding:22px;color:#fff;box-shadow:var(--shadow);transition:.3s;cursor:pointer}
.pot::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 30%,rgba(5,30,18,.85));transition:.3s}
.pot:hover{transform:translateY(-5px) scale(1.01);box-shadow:var(--shadow-lg)}
.pot .ct{position:relative;z-index:2}
.pot h3{font-size:1.15rem;font-weight:800}
.pot p{font-size:.82rem;opacity:.9;max-height:0;overflow:hidden;transition:.35s}
.pot:hover p{max-height:90px;margin-top:6px}
.pot .big-ic{position:absolute;top:18px;right:18px;z-index:2;width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.18);backdrop-filter:blur(6px);display:grid;place-items:center;border:1px solid rgba(255,255,255,.3)}
@media (max-width:880px){.pot-grid{grid-template-columns:1fr 1fr}}
@media (max-width:560px){.pot-grid{grid-template-columns:1fr}}

/* ============ TRANSPARANSI ============ */
.dash{display:grid;grid-template-columns:1.25fr .75fr;gap:20px;margin-top:36px}
.panel{padding:24px}
.panel h3{font-size:1rem;font-weight:800;margin-bottom:4px}
.panel small{color:var(--muted);font-size:.78rem}
.chart-box{position:relative;height:300px;margin-top:16px}
.budget-rows{display:grid;gap:14px;margin-top:18px}
.budget-row .top{display:flex;justify-content:space-between;font-size:.82rem;font-weight:700;margin-bottom:6px}
.budget-row .top span:last-child{color:var(--muted);font-weight:600}
.bar{height:9px;border-radius:99px;background:var(--surface-2);overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px;width:0;transition:width 1.4s cubic-bezier(.2,.8,.2,1)}
.dl-list{display:grid;gap:10px;margin-top:16px}
.dl{display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid var(--line);border-radius:13px;background:var(--surface-2);font-size:.85rem;font-weight:600;transition:.2s}
.dl:hover{border-color:var(--blue);color:var(--blue);transform:translateX(3px)}
.dl .pdf{width:38px;height:38px;border-radius:10px;background:#E5484D;color:#fff;display:grid;place-items:center;font-size:.62rem;font-weight:800;flex-shrink:0}
@media (max-width:880px){.dash{grid-template-columns:1fr}}

/* ============ GALERI ============ */
#galeri{background:var(--surface-2)}
.masonry{columns:3;column-gap:16px;margin-top:36px}
.m-item{break-inside:avoid;margin-bottom:16px;border-radius:var(--radius);overflow:hidden;position:relative;box-shadow:var(--shadow);transition:.3s;display:grid;place-items:center;color:#fff;text-align:center;padding:18px}
.m-item:hover{transform:scale(1.02);box-shadow:var(--shadow-lg)}
.m-item .lbl{position:absolute;left:14px;bottom:12px;font-size:.78rem;font-weight:700;text-shadow:0 1px 8px rgba(0,0,0,.5)}
.m-item .play{width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.25);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.45);display:grid;place-items:center}
@media (max-width:880px){.masonry{columns:2}}
@media (max-width:560px){.masonry{columns:1}}

/* ============ PETA & KONTAK ============ */
.map-grid{display:grid;grid-template-columns:1fr .8fr;gap:20px;margin-top:36px}
.map-frame{border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--line);min-height:360px}
.map-frame iframe{width:100%;height:100%;min-height:360px;border:0;filter:saturate(.95)}
[data-theme="dark"] .map-frame iframe{filter:invert(.88) hue-rotate(180deg) saturate(.8)}
.contact-card{padding:26px;display:grid;gap:14px;align-content:start}
.c-row{display:flex;gap:13px;align-items:flex-start}
.c-row .ic{width:42px;height:42px;border-radius:12px;background:var(--green-soft);color:var(--green);display:grid;place-items:center;flex-shrink:0}
.c-row b{font-size:.88rem;display:block}
.c-row span{font-size:.85rem;color:var(--muted)}
@media (max-width:880px){.map-grid{grid-template-columns:1fr}}

/* ============ FOOTER ============ */
footer{background:linear-gradient(160deg,#06301C,#0A3B5C);color:#DFEDE6;padding:60px 0 0;position:relative;overflow:hidden}
footer::before{content:"";position:absolute;inset:0;background-image:repeating-linear-gradient(135deg,rgba(255,255,255,.03) 0 8px,transparent 8px 22px)}
.f-grid{display:grid;grid-template-columns:1.3fr 1fr 1fr 1fr;gap:30px;position:relative}
.f-grid h4{font-size:.85rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#9FD9BB;margin-bottom:14px}
.f-grid a,.f-grid p{font-size:.86rem;color:#BBD3C7;display:block;padding:4px 0;transition:.2s}
.f-grid a:hover{color:#fff;transform:translateX(3px)}
.socials{display:flex;gap:10px;margin-top:14px}
.socials a{width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.1);display:grid;place-items:center;border:1px solid rgba(255,255,255,.15);padding:0}
.socials a:hover{background:var(--green);transform:translateY(-3px)}
.copy{border-top:1px solid rgba(255,255,255,.12);margin-top:44px;padding:18px 0;font-size:.78rem;color:#9DBBAC;text-align:center;position:relative}
@media (max-width:880px){.f-grid{grid-template-columns:1fr 1fr}}
@media (max-width:560px){.f-grid{grid-template-columns:1fr}}

/* ============ FLOATING ============ */
.fab{position:fixed;right:20px;z-index:70;width:54px;height:54px;border-radius:50%;display:grid;place-items:center;box-shadow:var(--shadow-lg);transition:.3s}
.fab-wa{bottom:20px;background:#25D366;color:#fff}
.fab-wa:hover{transform:scale(1.08)}
.fab-top{bottom:86px;background:var(--surface);border:1px solid var(--line);color:var(--green);opacity:0;pointer-events:none;transform:translateY(10px)}
.fab-top.show{opacity:1;pointer-events:auto;transform:none}
.fab-top:hover{background:var(--green);color:#fff}

/* ============ MODALS ============ */
.modal{position:fixed;inset:0;z-index:120;background:rgba(5,20,12,.55);backdrop-filter:blur(8px);display:grid;place-items:center;padding:20px;opacity:0;pointer-events:none;transition:.25s}
.modal.open{opacity:1;pointer-events:auto}
.modal .box{background:var(--surface);border:1px solid var(--line);border-radius:22px;box-shadow:var(--shadow-lg);width:min(460px,100%);padding:28px;transform:translateY(16px);transition:.3s}
.modal.open .box{transform:none}
.modal h3{font-weight:800;margin-bottom:4px}
.modal .sub{color:var(--muted);font-size:.86rem;margin-bottom:18px}
.field{display:grid;gap:6px;margin-bottom:14px}
.field label{font-size:.8rem;font-weight:700}
.field input{padding:12px 14px;border-radius:12px;border:1px solid var(--line);background:var(--surface-2);color:var(--ink);font:inherit;font-size:.9rem}
.field input:focus{outline:2px solid var(--green);border-color:transparent}
.search-results{display:grid;gap:8px;max-height:280px;overflow-y:auto;margin-top:14px}
.search-results a{padding:11px 14px;border-radius:12px;border:1px solid var(--line);font-size:.86rem;font-weight:600;transition:.2s}
.search-results a:hover{background:var(--green-soft);border-color:var(--green);color:var(--green)}
.search-results small{display:block;color:var(--muted);font-weight:500;font-size:.74rem}
.no-result{color:var(--muted);font-size:.85rem;text-align:center;padding:18px}

/* ============ REVEAL ============ */
.reveal{opacity:0;transform:translateY(26px);transition:opacity .7s cubic-bezier(.2,.7,.2,1),transform .7s cubic-bezier(.2,.7,.2,1)}
.reveal.in{opacity:1;transform:none}
.reveal[data-d="1"]{transition-delay:.08s}.reveal[data-d="2"]{transition-delay:.16s}
.reveal[data-d="3"]{transition-delay:.24s}.reveal[data-d="4"]{transition-delay:.32s}
.reveal[data-d="5"]{transition-delay:.4s}
.skip{position:absolute;left:-9999px;top:0;background:var(--green);color:#fff;padding:10px 18px;border-radius:0 0 12px 0;z-index:200;font-weight:700}
.skip:focus{left:0}
</style>
</head>
<body>

<a class="skip" href="#beranda">Lewati ke konten utama</a>

<!-- ===== TOP BAR ===== -->
<div class="topbar">
  <div class="container">
    <span>📞 (0911) 123-456 &nbsp;·&nbsp; ✉️ info@tirtalestari.desa.id</span>
    <span>Senin–Jumat · 08.00–16.00 WIT</span>
  </div>
</div>

<!-- ===== HEADER ===== -->
<header class="site" id="siteHeader">
  <div class="container nav-wrap">
    <a class="brand" href="#beranda" aria-label="Beranda Desa Tirta Lestari">
      <span class="logo" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"/></svg>
      </span>
      <span>
        <b>Desa Tirta Lestari</b>
        <small>MAJU · MANDIRI · SEJAHTERA</small>
      </span>
    </a>
    <nav class="primary" aria-label="Navigasi utama">
      <a href="#beranda" class="active">Beranda</a>
      <a href="#profil">Profil Desa</a>
      <a href="#profil">Pemerintahan</a>
      <a href="#layanan">Layanan</a>
      <a href="#berita">Berita</a>
      <a href="{{ route('pengumuman') }}">Pengumuman</a>
      <a href="#potensi">Potensi</a>
      <a href="#galeri">Galeri</a>
      <a href="#transparansi">Anggaran</a>
      <a href="#kontak">Kontak</a>
    </nav>
    <button class="icon-btn" id="searchBtn" aria-label="Cari di website">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    </button>
    <button class="icon-btn" id="themeBtn" aria-label="Ganti mode gelap / terang">
      <svg id="iconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
      <svg id="iconSun" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
    </button>
    <button class="btn btn-blue" id="loginBtn" style="padding:10px 16px">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
      <span class="login-label">Login Admin</span>
    </button>
    <button class="icon-btn" id="hamburger" aria-label="Buka menu navigasi" aria-expanded="false">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
  </div>
</header>

<!-- mobile nav -->
<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <b>Menu</b>
      <button class="icon-btn" id="closeNav" aria-label="Tutup menu">✕</button>
    </div>
    <a href="#beranda">Beranda</a><a href="#profil">Profil Desa</a><a href="#profil">Pemerintahan</a>
    <a href="#layanan">Layanan Publik</a><a href="#berita">Berita</a>
    <a href="{{ route('pengumuman') }}">Pengumuman</a>
    <a href="#potensi">Potensi Desa</a>
    <a href="#galeri">Galeri</a><a href="#transparansi">Transparansi Anggaran</a><a href="#kontak">Kontak</a>
  </div>
</div>

<!-- breadcrumb -->
<div class="breadcrumb" aria-label="Breadcrumb">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    Beranda <span aria-hidden="true">›</span> <b id="crumbNow">Beranda</b>
  </div>
</div>

<!-- ===== HERO ===== -->
<section class="hero" id="beranda">
  <div class="container hero-inner">
    <div>
      <span class="hero-badge reveal in"><span class="dot"></span> Portal Resmi Pemerintah Desa</span>
      <h1 class="reveal in">Selamat Datang di Website Resmi <em>Desa Tirta Lestari</em></h1>
      <p class="lead reveal in" data-d="1">Pusat informasi, pelayanan publik digital, dan transparansi pemerintahan desa. Melayani warga dengan cepat, terbuka, dan sepenuh hati — dari kantor desa hingga genggaman Anda.</p>
      <div class="hero-cta reveal in" data-d="2">
        <a class="btn btn-primary" href="#layanan">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 12h11m0 0-4-4m4 4-4 4M4 5v14"/></svg>
          Lihat Layanan
        </a>
        <a class="btn btn-outline" href="#potensi">Jelajahi Potensi Desa</a>
      </div>
    </div>
    <div class="hero-art reveal in" data-d="1" aria-hidden="true">
      <!-- Ilustrasi SVG: bukit, sawah terasering, balai desa, matahari -->
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
        <!-- terasering -->
        <path d="M30 330 Q160 300 300 322" stroke="#fff" stroke-width="3" fill="none" opacity=".35" stroke-linecap="round"/>
        <path d="M50 360 Q190 330 330 352" stroke="#fff" stroke-width="3" fill="none" opacity=".3" stroke-linecap="round"/>
        <path d="M70 390 Q210 360 350 382" stroke="#fff" stroke-width="3" fill="none" opacity=".25" stroke-linecap="round"/>
        <!-- sungai -->
        <path d="M380 420 C400 360 360 330 420 290 C460 264 470 240 466 226 L500 226 C512 252 488 282 458 306 C420 336 452 372 440 420 Z" fill="url(#water)" opacity=".9"/>
        <!-- balai desa -->
        <g transform="translate(120,196)">
          <rect x="14" y="46" width="92" height="58" rx="6" fill="#FFFFFF"/>
          <path d="M2 50 L60 8 L118 50 Z" fill="#C2452F"/>
          <path d="M14 50 L60 18 L106 50 Z" fill="#E0563C"/>
          <rect x="48" y="68" width="24" height="36" rx="3" fill="#0E63A8"/>
          <rect x="24" y="60" width="16" height="14" rx="2" fill="#BEE3F7"/>
          <rect x="80" y="60" width="16" height="14" rx="2" fill="#BEE3F7"/>
          <rect x="57" y="-14" width="4" height="26" fill="#7A4A2B"/>
          <rect x="61" y="-12" width="18" height="11" fill="#E0563C"/>
          <rect x="61" y="-1" width="18" height="11" fill="#fff"/>
        </g>
        <!-- pohon kelapa -->
        <g transform="translate(470,250)" stroke-linecap="round">
          <path d="M10 90 C6 60 8 36 14 16" stroke="#7A4A2B" stroke-width="7" fill="none"/>
          <path d="M14 16 C-6 8 -16 14 -22 24 M14 16 C8 0 -2 -6 -12 -6 M14 16 C20 0 32 -6 42 -2 M14 16 C34 8 44 16 48 26" stroke="#1E9059" stroke-width="7" fill="none"/>
        </g>
        <g transform="translate(60,236) scale(.7)" stroke-linecap="round">
          <path d="M10 90 C6 60 8 36 14 16" stroke="#7A4A2B" stroke-width="7" fill="none"/>
          <path d="M14 16 C-6 8 -16 14 -22 24 M14 16 C8 0 -2 -6 -12 -6 M14 16 C20 0 32 -6 42 -2 M14 16 C34 8 44 16 48 26" stroke="#1E9059" stroke-width="7" fill="none"/>
        </g>
        <!-- burung -->
        <path d="M250 80 q8 -8 16 0 q8 -8 16 0 M310 60 q7 -7 14 0 q7 -7 14 0" stroke="#5B7D96" stroke-width="2.4" fill="none" stroke-linecap="round" opacity=".7"/>
      </svg>
    </div>
  </div>
  <div class="hero-strip"></div>
</section>

<!-- ===== STATISTIK ===== -->
<div class="stats" aria-label="Statistik desa">
  <div class="container stats-grid">
    <div class="stat-card reveal">
      <span class="ic" style="background:linear-gradient(135deg,#0C7C46,#1E9059)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
      <span class="num"><span class="count" data-target="4823">0</span> <span>jiwa</span></span>
      <span class="lbl">Jumlah Penduduk</span>
    </div>
    <div class="stat-card reveal" data-d="1">
      <span class="ic" style="background:linear-gradient(135deg,#0E63A8,#3F8FC9)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10M9 20v-5h6v5"/></svg></span>
      <span class="num"><span class="count" data-target="1287">0</span> <span>KK</span></span>
      <span class="lbl">Kepala Keluarga</span>
    </div>
    <div class="stat-card reveal" data-d="2">
      <span class="ic" style="background:linear-gradient(135deg,#1E9059,#56BE8B)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 6l6-2 6 2 6-2v14l-6 2-6-2-6 2zM9 4v14M15 6v14"/></svg></span>
      <span class="num"><span class="count" data-target="865" data-decimal="1">0</span> <span>ha</span></span>
      <span class="lbl">Luas Wilayah</span>
    </div>
    <div class="stat-card reveal" data-d="3">
      <span class="ic" style="background:linear-gradient(135deg,#0A4E86,#0E63A8)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span>
      <span class="num"><span class="count" data-target="24">0</span> <span>RT · 6 RW</span></span>
      <span class="lbl">Jumlah RT/RW</span>
    </div>
    <div class="stat-card reveal" data-d="4">
      <span class="ic" style="background:linear-gradient(135deg,#C99A2C,#E0B854)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 9h16l-1.5 11h-13zM4 9l2-5h12l2 5M9 13v3M15 13v3"/></svg></span>
      <span class="num"><span class="count" data-target="142">0</span> <span>unit</span></span>
      <span class="lbl">UMKM Aktif</span>
    </div>
  </div>
</div>

<!-- ===== PROFIL ===== -->
<section id="profil" data-crumb="Profil Desa">
  <div class="container">
    <span class="eyebrow reveal">Profil &amp; Pemerintahan</span>
    <h2 class="sec-title reveal">Mengenal Desa Tirta Lestari</h2>
    <p class="sec-desc reveal" data-d="1">Sejarah, visi-misi, dan struktur pemerintahan yang melayani warga secara terbuka dan akuntabel.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="profil-grid">
      <!-- Sambutan -->
      <div class="card kades reveal">
        <span class="eyebrow" style="font-size:.72rem">Sambutan Kepala Desa</span>
        <p class="quote">“Website ini adalah wujud komitmen kami untuk pemerintahan yang terbuka. Setiap rupiah anggaran, setiap layanan, dan setiap potensi desa kami sajikan agar warga dan investor dapat melihat, mengawasi, dan ikut membangun bersama.”</p>
        <div class="who">
          <span class="avatar" aria-hidden="true">HS</span>
          <div>
            <b style="font-size:.95rem">H. Suparman Latuconsina, S.IP.</b>
            <small style="color:var(--muted);display:block">Kepala Desa Tirta Lestari · Periode 2024–2030</small>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="card panel reveal" data-d="1">
        <div class="tabs" role="tablist" aria-label="Informasi profil desa">
          <button class="tab-btn active" data-tab="sejarah" role="tab" aria-selected="true">Sejarah</button>
          <button class="tab-btn" data-tab="visi" role="tab" aria-selected="false">Visi &amp; Misi</button>
          <button class="tab-btn" data-tab="struktur" role="tab" aria-selected="false">Struktur Organisasi</button>
        </div>
        <div class="tab-pane show" id="tab-sejarah" role="tabpanel">
          <p style="font-size:.92rem;color:var(--muted)">Berdiri sejak tahun 1923, Desa Tirta Lestari bermula dari permukiman petani di tepi mata air Wai Tirta — sumber air yang tak pernah kering dan menjadi asal nama desa. Kini desa berkembang menjadi sentra pertanian, perikanan, dan ekowisata dengan tetap menjaga kearifan lokal serta gotong royong sebagai napas kehidupan warganya.</p>
        </div>
        <div class="tab-pane" id="tab-visi" role="tabpanel">
          <p style="font-size:.92rem;font-weight:700">“Terwujudnya Desa Tirta Lestari yang Maju, Mandiri, dan Sejahtera berbasis Potensi Lokal dan Pemerintahan Digital pada 2030.”</p>
          <ul class="visi-list">
            <li>Mewujudkan tata kelola pemerintahan yang transparan dan akuntabel</li>
            <li>Meningkatkan kualitas pelayanan publik berbasis digital</li>
            <li>Mengembangkan ekonomi desa melalui UMKM, pertanian, dan wisata</li>
            <li>Membangun infrastruktur yang merata dan ramah lingkungan</li>
          </ul>
        </div>
        <div class="tab-pane" id="tab-struktur" role="tabpanel">
          <div class="org">
            <div class="org-row"><span class="avatar" style="width:40px;height:40px;font-size:.8rem">HS</span><div><b>H. Suparman Latuconsina, S.IP.</b><small>Kepala Desa</small></div></div>
            <div class="org-row"><span class="avatar" style="width:40px;height:40px;font-size:.8rem;background:linear-gradient(135deg,var(--blue),var(--blue-deep))">RN</span><div><b>Rahma Nurlette, S.E.</b><small>Sekretaris Desa</small></div></div>
            <div class="org-row"><span class="avatar" style="width:40px;height:40px;font-size:.8rem">DM</span><div><b>Daud Manuputty</b><small>Kaur Keuangan</small></div></div>
            <div class="org-row"><span class="avatar" style="width:40px;height:40px;font-size:.8rem;background:linear-gradient(135deg,var(--blue),var(--blue-deep))">SP</span><div><b>Sarah Pattiasina, S.Sos.</b><small>Kasi Pelayanan &amp; Kesejahteraan</small></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== LAYANAN ===== -->
<section id="layanan" data-crumb="Layanan Publik">
  <div class="container">
    <span class="eyebrow reveal">Layanan Publik</span>
    <h2 class="sec-title reveal">Urus Surat Tanpa Antre</h2>
    <p class="sec-desc reveal" data-d="1">Ajukan permohonan secara online — cukup unggah dokumen, pantau status, dan ambil hasilnya di kantor desa atau via email.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="svc-grid">
      <article class="card svc reveal">
        <span class="ic" style="background:linear-gradient(135deg,#0C7C46,#1E9059)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6M9 13h6M9 17h6"/></svg></span>
        <h3>Surat Keterangan</h3>
        <p>SKTM, surat keterangan belum menikah, kelahiran, kematian, dan keterangan umum lainnya.</p>
        <a class="go" href="#kontak">Ajukan sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
      <article class="card svc reveal" data-d="1">
        <span class="ic" style="background:linear-gradient(135deg,#0E63A8,#3F8FC9)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg></span>
        <h3>Surat Domisili</h3>
        <p>Keterangan tempat tinggal untuk keperluan administrasi bank, sekolah, dan pekerjaan.</p>
        <a class="go" href="#kontak">Ajukan sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
      <article class="card svc reveal" data-d="2">
        <span class="ic" style="background:linear-gradient(135deg,#C99A2C,#E0B854)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 9h16l-1.5 11h-13zM4 9l2-5h12l2 5"/></svg></span>
        <h3>Surat Usaha (SKU)</h3>
        <p>Legalitas usaha mikro untuk pengajuan KUR, NIB, dan kemitraan UMKM desa.</p>
        <a class="go" href="#kontak">Ajukan sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
      <article class="card svc reveal" data-d="3">
        <span class="ic" style="background:linear-gradient(135deg,#1E9059,#56BE8B)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4-4.8-2.5-4.8 2.5.9-5.4L4.2 7.7l5.4-.8z"/></svg></span>
        <h3>Pengajuan Bantuan</h3>
        <p>Pendaftaran BLT, PKH, bantuan bibit/pupuk, dan program kesejahteraan sosial desa.</p>
        <a class="go" href="#kontak">Ajukan sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
      <article class="card svc reveal" data-d="4">
        <span class="ic" style="background:linear-gradient(135deg,#B7472A,#E0563C)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.6 8.6 0 0 1-3.8-.9L3 21l2-5.2a8.4 8.4 0 1 1 16-4.3z"/><path d="M12 8v4M12 15h.01"/></svg></span>
        <h3>Pengaduan Masyarakat</h3>
        <p>Laporkan infrastruktur rusak, layanan, atau aspirasi. Identitas pelapor dirahasiakan.</p>
        <a class="go" href="#kontak">Buat laporan <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
      <article class="card svc reveal" data-d="5">
        <span class="ic" style="background:linear-gradient(135deg,#0A4E86,#0E63A8)"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2.5"/><circle cx="9" cy="11" r="2.4"/><path d="M5.5 18c.7-2 1.9-3 3.5-3s2.8 1 3.5 3M15 9h4M15 13h4"/></svg></span>
        <h3>Informasi Kependudukan</h3>
        <p>Cek status KTP, KK, akta, dan jadwal layanan keliling Dukcapil di balai desa.</p>
        <a class="go" href="#kontak">Cek informasi <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14m0 0-5-5m5 5-5 5"/></svg></a>
      </article>
    </div>
  </div>
</section>

<!-- ===== BERITA ===== -->
<section id="berita" data-crumb="Berita & Pengumuman">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:18px;flex-wrap:wrap">
      <div>
        <span class="eyebrow reveal">Berita &amp; Pengumuman</span>
        <h2 class="sec-title reveal">Kabar Terbaru dari Desa</h2>
        <div class="tenun reveal"></div>
      </div>
      <a class="btn btn-outline reveal" href="#berita">Semua Berita →</a>
    </div>

    <div class="news-grid">
      <article class="card news reveal">
        <div class="thumb" style="background:linear-gradient(135deg,#0C7C46,#1E9059)">
          <span class="cat">Pembangunan</span>
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M2 20h20M4 20V8l8-5 8 5v12M9 20v-6h6v6"/></svg>
        </div>
        <div class="body">
          <span class="date">📅 8 Juni 2026</span>
          <h3>Jalan Usaha Tani Dusun Wailoba Selesai 100%, Akses Hasil Panen Kini Lancar</h3>
          <p>Pembangunan jalan sepanjang 1,2 km yang didanai Dana Desa 2026 resmi diserahterimakan kepada warga…</p>
          <a class="more" href="#berita">Baca selengkapnya →</a>
        </div>
      </article>
      <article class="card news reveal" data-d="1">
        <div class="thumb" style="background:linear-gradient(135deg,#0E63A8,#3F8FC9)">
          <span class="cat">Pengumuman</span>
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1zM15 9a4 4 0 0 1 0 6M18 6a8 8 0 0 1 0 12"/></svg>
        </div>
        <div class="body">
          <span class="date">5 Juni 2026 · 📅</span>
          <h3>Musrenbangdes 2027: Warga Diundang Usulkan Program Prioritas</h3>
          <p>Musyawarah perencanaan pembangunan desa akan digelar 20 Juni 2026 di Balai Desa, terbuka untuk seluruh RT/RW…</p>
          <a class="more" href="#berita">Baca selengkapnya →</a>
        </div>
      </article>
      <article class="card news reveal" data-d="2">
        <div class="thumb" style="background:linear-gradient(135deg,#C99A2C,#E0B854)">
          <span class="cat">UMKM</span>
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.6" stroke-linecap="round"><path d="M4 9h16l-1.5 11h-13zM4 9l2-5h12l2 5M9 13v3M15 13v3"/></svg>
        </div>
        <div class="body">
          <span class="date">📅 1 Juni 2026</span>
          <h3>Minyak Kayu Putih “Tirta Wangi” Tembus Pasar Ekspor Perdana</h3>
          <p>Produk unggulan BUMDes berhasil mengirim 2 ton minyak kayu putih ke Singapura, membuka lapangan kerja baru…</p>
          <a class="more" href="#berita">Baca selengkapnya →</a>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ===== POTENSI ===== -->
<section id="potensi" data-crumb="Potensi Desa">
  <div class="container">
    <span class="eyebrow reveal">Potensi &amp; Investasi</span>
    <h2 class="sec-title reveal">Kekayaan Desa, Peluang Bersama</h2>
    <p class="sec-desc reveal" data-d="1">Enam sektor unggulan yang terbuka untuk kemitraan masyarakat, BUMDes, dan investor.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="pot-grid">
      <div class="pot reveal" style="background:linear-gradient(135deg,#2FA56B,#0C7C46)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M12 22V8M12 8C12 4 9 2 5 2c0 4 3 6 7 6zM12 12c0-4 3-6 7-6 0 4-3 6-7 6z"/></svg></span>
        <div class="ct"><h3>Pertanian</h3><p>320 ha sawah &amp; hortikultura dengan irigasi teknis. Produksi padi 1.800 ton/tahun, plus cabai dan sayuran dataran rendah.</p></div>
      </div>
      <div class="pot reveal" data-d="1" style="background:linear-gradient(135deg,#1E9059,#075F36)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M12 2C8 6 5 9 5 13a7 7 0 0 0 14 0c0-4-3-7-7-11z"/><path d="M12 18a4.5 4.5 0 0 1-4-4"/></svg></span>
        <div class="ct"><h3>Perkebunan</h3><p>Kayu putih, cengkih, dan pala seluas 210 ha — komoditas khas Maluku dengan nilai ekspor tinggi.</p></div>
      </div>
      <div class="pot reveal" data-d="2" style="background:linear-gradient(135deg,#3F8FC9,#0A4E86)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6S2 12 2 12z"/><circle cx="14" cy="12" r="1.6" fill="#fff"/><path d="M2 12l4-3M2 12l4 3"/></svg></span>
        <div class="ct"><h3>Perikanan</h3><p>Budidaya ikan air tawar 42 kolam aktif dan tangkapan laut pesisir; pasokan rutin ke pasar kota.</p></div>
      </div>
      <div class="pot reveal" data-d="3" style="background:linear-gradient(135deg,#0E63A8,#063A63)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M3.5 15h17M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18z"/></svg></span>
        <div class="ct"><h3>Pariwisata</h3><p>Mata air Wai Tirta, trekking bukit terasering, dan wisata budaya tahunan “Pesta Panen Raya”.</p></div>
      </div>
      <div class="pot reveal" data-d="4" style="background:linear-gradient(135deg,#C99A2C,#8F6A14)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M4 9h16l-1.5 11h-13zM4 9l2-5h12l2 5"/></svg></span>
        <div class="ct"><h3>UMKM</h3><p>142 unit usaha: kuliner sagu, kerajinan anyaman, dan jasa — didampingi BUMDes &amp; pelatihan digital.</p></div>
      </div>
      <div class="pot reveal" data-d="5" style="background:linear-gradient(135deg,#56BE8B,#1E9059)" tabindex="0">
        <span class="big-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M20 12v8H4v-8M2 7h20v5H2zM12 7v13M12 7c-2 0-4-1-4-3 0-1.5 1-2 2-2 2 0 2 3 2 5zm0 0c2 0 4-1 4-3 0-1.5-1-2-2-2-2 0-2 3-2 5z"/></svg></span>
        <div class="ct"><h3>Produk Unggulan</h3><p>Minyak kayu putih “Tirta Wangi”, beras organik, dan keripik sagu — tersedia untuk kemitraan distribusi.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== TRANSPARANSI ===== -->
<section id="transparansi" data-crumb="Transparansi Anggaran">
  <div class="container">
    <span class="eyebrow reveal">Transparansi Anggaran</span>
    <h2 class="sec-title reveal">APBDes 2026: Terbuka untuk Semua</h2>
    <p class="sec-desc reveal" data-d="1">Setiap rupiah dipublikasikan. Pantau pendapatan, belanja, dan realisasi anggaran desa secara real-time.</p>
    <div class="tenun reveal" data-d="1"></div>

    <div class="dash">
      <div class="card panel reveal">
        <h3>Belanja Desa per Bidang</h3>
        <small>APBDes Tahun Anggaran 2026 · Total Rp 2,84 Miliar</small>
        <div class="chart-box"><canvas id="chartBelanja" role="img" aria-label="Grafik batang belanja desa per bidang"></canvas></div>
      </div>
      <div style="display:grid;gap:20px">
        <div class="card panel reveal" data-d="1">
          <h3>Komposisi Pendapatan</h3>
          <small>Dana Desa, ADD, PADes, dan lainnya</small>
          <div class="chart-box" style="height:210px"><canvas id="chartPendapatan" role="img" aria-label="Grafik donat komposisi pendapatan desa"></canvas></div>
        </div>
        <div class="card panel reveal" data-d="2">
          <h3>Realisasi s.d. Juni 2026</h3>
          <div class="budget-rows">
            <div class="budget-row"><div class="top"><span>Penyelenggaraan Pemerintahan</span><span>78%</span></div><div class="bar"><i data-w="78" style="background:linear-gradient(90deg,#0C7C46,#1E9059)"></i></div></div>
            <div class="budget-row"><div class="top"><span>Pembangunan Desa</span><span>64%</span></div><div class="bar"><i data-w="64" style="background:linear-gradient(90deg,#0E63A8,#3F8FC9)"></i></div></div>
            <div class="budget-row"><div class="top"><span>Pemberdayaan Masyarakat</span><span>52%</span></div><div class="bar"><i data-w="52" style="background:linear-gradient(90deg,#C99A2C,#E0B854)"></i></div></div>
            <div class="budget-row"><div class="top"><span>Penanggulangan Bencana</span><span>31%</span></div><div class="bar"><i data-w="31" style="background:linear-gradient(90deg,#1E9059,#56BE8B)"></i></div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card panel reveal" style="margin-top:20px">
      <h3>Unduh Laporan Resmi (PDF)</h3>
      <div class="dl-list" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));display:grid">
        <a class="dl" href="#" onclick="return false"><span class="pdf">PDF</span><span>APBDes 2026<small style="display:block;color:var(--muted);font-weight:500">1,2 MB · Ditetapkan Jan 2026</small></span></a>
        <a class="dl" href="#" onclick="return false"><span class="pdf">PDF</span><span>Realisasi Semester I<small style="display:block;color:var(--muted);font-weight:500">860 KB · Juni 2026</small></span></a>
        <a class="dl" href="#" onclick="return false"><span class="pdf">PDF</span><span>LPJ Dana Desa 2025<small style="display:block;color:var(--muted);font-weight:500">2,4 MB · Audit Inspektorat</small></span></a>
      </div>
    </div>
  </div>
</section>

<!-- ===== GALERI ===== -->
<section id="galeri" data-crumb="Galeri">
  <div class="container">
    <span class="eyebrow reveal">Galeri Desa</span>
    <h2 class="sec-title reveal">Dokumentasi Kegiatan &amp; Keindahan Desa</h2>
    <div class="tenun reveal"></div>

    <div class="masonry">
      <div class="m-item reveal" style="height:240px;background:linear-gradient(135deg,#2FA56B,#075F36)"><svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.8)" stroke-width="1.5" stroke-linecap="round"><path d="M3 18l5-8 4 5 3-4 6 7zM3 18h18"/><circle cx="17" cy="6" r="2.5"/></svg><span class="lbl">Panorama Sawah Terasering</span></div>
      <div class="m-item reveal" data-d="1" style="height:170px;background:linear-gradient(135deg,#0E63A8,#063A63)"><span class="play"><svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span><span class="lbl">Video Profil Desa 2026</span></div>
      <div class="m-item reveal" data-d="2" style="height:200px;background:linear-gradient(135deg,#C99A2C,#8F6A14)"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg><span class="lbl">Gotong Royong Bersih Desa</span></div>
      <div class="m-item reveal" style="height:180px;background:linear-gradient(135deg,#56BE8B,#1E9059)"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M12 22V8M12 8C12 4 9 2 5 2c0 4 3 6 7 6zM12 12c0-4 3-6 7-6 0 4-3 6-7 6z"/></svg><span class="lbl">Panen Raya Padi Organik</span></div>
      <div class="m-item reveal" data-d="1" style="height:230px;background:linear-gradient(135deg,#3F8FC9,#0E63A8)"><span class="play"><svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span><span class="lbl">Dokumentasi Pesta Panen Raya</span></div>
      <div class="m-item reveal" data-d="2" style="height:160px;background:linear-gradient(135deg,#1E9059,#0C7C46)"><svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="1.6" stroke-linecap="round"><path d="M2 20h20M4 20V8l8-5 8 5v12"/></svg><span class="lbl">Peresmian Balai Desa Baru</span></div>
    </div>
  </div>
</section>

<!-- ===== PETA & KONTAK ===== -->
<section id="kontak" data-crumb="Kontak">
  <div class="container">
    <span class="eyebrow reveal">Lokasi &amp; Kontak</span>
    <h2 class="sec-title reveal">Kunjungi Kantor Desa Kami</h2>
    <div class="tenun reveal"></div>

    <div class="map-grid">
      <div class="map-frame reveal">
        <iframe title="Peta lokasi Kantor Desa Tirta Lestari" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
          src="https://www.google.com/maps?q=Ambon,Maluku,Indonesia&z=12&output=embed"></iframe>
      </div>
      <div class="card contact-card reveal" data-d="1">
        <div class="c-row">
          <span class="ic"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg></span>
          <div><b>Alamat Kantor Desa</b><span>Jl. Wai Tirta No. 1, Desa Tirta Lestari, Kec. Leihitu, Kab. Maluku Tengah, Maluku 97581</span></div>
        </div>
        <div class="c-row">
          <span class="ic" style="background:var(--blue-soft);color:var(--blue)"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3 7 9 6 9-6"/></svg></span>
          <div><b>Email Resmi</b><span>info@tirtalestari.desa.id</span></div>
        </div>
        <div class="c-row">
          <span class="ic" style="background:#DCF5E5;color:#1B9E55"><svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.4-.7-2.9-1.2-4.7-4.1-4.9-4.3-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.8 1.7.3.2.6.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.2.5.3.6.4.1.2.1.7-.3 1.2z"/></svg></span>
          <div><b>WhatsApp Layanan</b><span>+62 812-3456-7890 (chat &amp; telepon)</span></div>
        </div>
        <div class="c-row">
          <span class="ic" style="background:var(--green-soft)"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></span>
          <div><b>Jam Pelayanan</b><span>Senin–Jumat · 08.00–16.00 WIT<br>Layanan online 24 jam via website</span></div>
        </div>
        <a class="btn btn-primary" href="#layanan" style="justify-content:center;margin-top:6px">Mulai Layanan Online</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="container f-grid">
    <div>
      <div class="brand" style="margin-bottom:14px">
        <span class="logo"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"/></svg></span>
        <span><b style="color:#fff">Desa Tirta Lestari</b><small style="color:#9FD9BB">PEMERINTAH DESA</small></span>
      </div>
      <p>Portal resmi pelayanan publik dan informasi Pemerintah Desa Tirta Lestari, Kecamatan Leihitu, Kabupaten Maluku Tengah.</p>
      <div class="socials">
        <a href="#" aria-label="Facebook"><svg width="17" height="17" viewBox="0 0 24 24" fill="#fff"><path d="M14 9h3l-.5 3H14v9h-3.5v-9H8V9h2.5V7.2C10.5 4.8 12 3 14.6 3H17v3h-1.8c-.8 0-1.2.4-1.2 1.2z"/></svg></a>
        <a href="#" aria-label="Instagram"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.2" fill="#fff" stroke="none"/></svg></a>
        <a href="#" aria-label="YouTube"><svg width="17" height="17" viewBox="0 0 24 24" fill="#fff"><path d="M22 12s0-3.4-.4-5a2.8 2.8 0 0 0-2-2C18 4.6 12 4.6 12 4.6s-6 0-7.6.4a2.8 2.8 0 0 0-2 2C2 8.6 2 12 2 12s0 3.4.4 5a2.8 2.8 0 0 0 2 2c1.6.4 7.6.4 7.6.4s6 0 7.6-.4a2.8 2.8 0 0 0 2-2c.4-1.6.4-5 .4-5zM10 15.5v-7l6 3.5z"/></svg></a>
        <a href="#" aria-label="TikTok"><svg width="17" height="17" viewBox="0 0 24 24" fill="#fff"><path d="M16.6 3c.4 2.3 1.9 3.7 4.4 3.9v3c-1.7 0-3.2-.5-4.4-1.4v6.6a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.1a3 3 0 1 0 2 2.8V3z"/></svg></a>
      </div>
    </div>
    <div>
      <h4>Navigasi</h4>
      <a href="#profil">Profil Desa</a><a href="#layanan">Layanan Publik</a><a href="#berita">Berita</a><a href="{{ route('pengumuman') }}">Pengumuman</a><a href="#potensi">Potensi Desa</a><a href="#transparansi">Transparansi Anggaran</a>
    </div>
    <div>
      <h4>Layanan Populer</h4>
      <a href="#layanan">Surat Keterangan</a><a href="#layanan">Surat Domisili</a><a href="#layanan">Surat Usaha</a><a href="#layanan">Pengaduan Masyarakat</a>
    </div>
    <div>
      <h4>Kontak</h4>
      <p>Jl. Wai Tirta No. 1<br>Kec. Leihitu, Maluku Tengah</p>
      <p>✉️ info@tirtalestari.desa.id</p>
      <p>📱 +62 812-3456-7890</p>
    </div>
  </div>
  <div class="copy">© <span id="year">2026</span> Pemerintah Desa Tirta Lestari · Dikelola Tim Sistem Informasi Desa · Hak Cipta Dilindungi</div>
</footer>

<!-- ===== FLOATING BUTTONS ===== -->
<a class="fab fab-wa" href="https://wa.me/6281234567890" target="_blank" rel="noopener" aria-label="Chat WhatsApp layanan desa">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.4-.7-2.9-1.2-4.7-4.1-4.9-4.3-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.8 1.7.3.2.6.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.2.5.3.6.4.1.2.1.7-.3 1.2z"/></svg>
</a>
<button class="fab fab-top" id="backTop" aria-label="Kembali ke atas">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 19V5m0 0-6 6m6-6 6 6"/></svg>
</button>

<!-- ===== SEARCH MODAL ===== -->
<div class="modal" id="searchModal" role="dialog" aria-modal="true" aria-label="Pencarian">
  <div class="box">
    <h3>Cari di Website Desa</h3>
    <p class="sub">Ketik kata kunci layanan, berita, atau halaman.</p>
    <div class="field"><input type="search" id="searchInput" placeholder="Contoh: surat domisili, anggaran, panen…" autocomplete="off"></div>
    <div class="search-results" id="searchResults"></div>
    <button class="btn btn-outline" id="closeSearch" style="width:100%;justify-content:center;margin-top:14px">Tutup</button>
  </div>
</div>

<!-- ===== LOGIN MODAL ===== -->
<div class="modal" id="loginModal" role="dialog" aria-modal="true" aria-label="Login admin">
  <div class="box">
    <h3>Login Admin Desa</h3>
    <p class="sub">Khusus perangkat desa. Terhubung ke panel admin Laravel (REST API).</p>
    <div class="field"><label for="lu">Nama Pengguna</label><input id="lu" type="text" placeholder="admin.desa"></div>
    <div class="field"><label for="lp">Kata Sandi</label><input id="lp" type="password" placeholder="••••••••"></div>
    <button class="btn btn-blue" style="width:100%;justify-content:center" onclick="alert('Demo: pada implementasi nyata, ini memanggil POST /api/auth/login (Laravel Sanctum).')">Masuk ke Dasbor</button>
    <button class="btn btn-outline" id="closeLogin" style="width:100%;justify-content:center;margin-top:10px">Batal</button>
  </div>
</div>

<script>
/* ====== THEME (in-memory; di produksi simpan ke preferensi user) ====== */
const root=document.documentElement, themeBtn=document.getElementById('themeBtn');
const moon=document.getElementById('iconMoon'), sun=document.getElementById('iconSun');
let dark=false;
function applyTheme(){
  root.dataset.theme=dark?'dark':'light';
  moon.style.display=dark?'none':'block'; sun.style.display=dark?'block':'none';
  buildCharts();
}
themeBtn.addEventListener('click',()=>{dark=!dark;applyTheme();});

/* ====== HEADER SHADOW + BACK TO TOP ====== */
const header=document.getElementById('siteHeader'), backTop=document.getElementById('backTop');
window.addEventListener('scroll',()=>{
  header.classList.toggle('scrolled',scrollY>10);
  backTop.classList.toggle('show',scrollY>500);
},{passive:true});
backTop.addEventListener('click',()=>scrollTo({top:0,behavior:'smooth'}));

/* ====== MOBILE NAV ====== */
const mNav=document.getElementById('mobileNav'),burger=document.getElementById('hamburger');
function setNav(open){mNav.classList.toggle('open',open);burger.setAttribute('aria-expanded',open);mNav.setAttribute('aria-hidden',!open);}
burger.addEventListener('click',()=>setNav(true));
document.getElementById('closeNav').addEventListener('click',()=>setNav(false));
mNav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>setNav(false)));

/* ====== SCROLL REVEAL (gaya AOS, native IntersectionObserver) ====== */
const io=new IntersectionObserver(es=>es.forEach(e=>{
  if(e.isIntersecting){e.target.classList.add('in');
    e.target.querySelectorAll?.('.bar i').forEach(b=>b.style.width=b.dataset.w+'%');
    io.unobserve(e.target);}
}),{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
document.querySelectorAll('.budget-rows').forEach(el=>io.observe(el));

/* ====== COUNTER ANIMASI STATISTIK ====== */
const cio=new IntersectionObserver(es=>es.forEach(e=>{
  if(!e.isIntersecting)return;
  const el=e.target,t=+el.dataset.target,dur=1400,t0=performance.now();
  (function tick(now){const p=Math.min((now-t0)/dur,1),v=Math.floor(t*(1-Math.pow(1-p,3)));
    el.textContent=v.toLocaleString('id-ID');
    if(p<1)requestAnimationFrame(tick);else el.textContent=t.toLocaleString('id-ID');})(t0);
  cio.unobserve(el);
}),{threshold:.5});
document.querySelectorAll('.count').forEach(el=>cio.observe(el));

/* ====== SCROLLSPY: NAV ACTIVE + BREADCRUMB ====== */
const navLinks=[...document.querySelectorAll('nav.primary a')];
const crumb=document.getElementById('crumbNow');
const spy=new IntersectionObserver(es=>es.forEach(e=>{
  if(!e.isIntersecting)return;
  const id=e.target.id, name=e.target.dataset.crumb||'Beranda';
  crumb.textContent=name;
  navLinks.forEach(a=>a.classList.toggle('active',a.getAttribute('href')==='#'+id));
}),{rootMargin:'-40% 0px -55% 0px'});
document.querySelectorAll('section[id]').forEach(s=>spy.observe(s));

/* ====== TABS PROFIL ====== */
document.querySelectorAll('.tab-btn').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.tab-btn').forEach(x=>{x.classList.remove('active');x.setAttribute('aria-selected','false');});
  document.querySelectorAll('.tab-pane').forEach(p=>p.classList.remove('show'));
  b.classList.add('active');b.setAttribute('aria-selected','true');
  document.getElementById('tab-'+b.dataset.tab).classList.add('show');
}));

/* ====== MODALS ====== */
function bindModal(id,openBtn,closeBtn){
  const m=document.getElementById(id);
  document.getElementById(openBtn).addEventListener('click',()=>m.classList.add('open'));
  document.getElementById(closeBtn).addEventListener('click',()=>m.classList.remove('open'));
  m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');});
  return m;
}
const sModal=bindModal('searchModal','searchBtn','closeSearch');
bindModal('loginModal','loginBtn','closeLogin');
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal.open').forEach(m=>m.classList.remove('open'));});

/* ====== PENCARIAN GLOBAL ====== */
const INDEX=[
  {t:'Surat Keterangan (SKTM, kelahiran, dll.)',s:'Layanan Publik',h:'#layanan'},
  {t:'Surat Domisili',s:'Layanan Publik',h:'#layanan'},
  {t:'Surat Usaha (SKU)',s:'Layanan Publik',h:'#layanan'},
  {t:'Pengajuan Bantuan Sosial / BLT',s:'Layanan Publik',h:'#layanan'},
  {t:'Pengaduan Masyarakat',s:'Layanan Publik',h:'#layanan'},
  {t:'Informasi Kependudukan (KTP, KK)',s:'Layanan Publik',h:'#layanan'},
  {t:'Jalan Usaha Tani Dusun Wailoba Selesai',s:'Berita · Pembangunan',h:'#berita'},
  {t:'Musrenbangdes 2027',s:'Pengumuman',h:'#berita'},
  {t:'Minyak Kayu Putih Tirta Wangi Ekspor',s:'Berita · UMKM',h:'#berita'},
  {t:'APBDes 2026 & Realisasi Anggaran',s:'Transparansi',h:'#transparansi'},
  {t:'Download Laporan PDF Anggaran',s:'Transparansi',h:'#transparansi'},
  {t:'Potensi Pertanian, Perkebunan, Perikanan',s:'Potensi Desa',h:'#potensi'},
  {t:'Wisata Mata Air Wai Tirta',s:'Potensi · Pariwisata',h:'#potensi'},
  {t:'Visi Misi & Struktur Organisasi',s:'Profil Desa',h:'#profil'},
  {t:'Sambutan Kepala Desa',s:'Profil Desa',h:'#profil'},
  {t:'Galeri Foto & Video Kegiatan',s:'Galeri',h:'#galeri'},
  {t:'Alamat Kantor & Kontak WhatsApp',s:'Kontak',h:'#kontak'},
];
const sIn=document.getElementById('searchInput'),sRes=document.getElementById('searchResults');
function renderSearch(q){
  q=q.trim().toLowerCase();
  const hits=q?INDEX.filter(i=>(i.t+' '+i.s).toLowerCase().includes(q)):INDEX.slice(0,6);
  sRes.innerHTML=hits.length
    ?hits.map(i=>`<a href="${i.h}" onclick="document.getElementById('searchModal').classList.remove('open')">${i.t}<small>${i.s}</small></a>`).join('')
    :'<div class="no-result">Tidak ditemukan. Coba kata kunci lain, atau hubungi kami via WhatsApp.</div>';
}
sIn.addEventListener('input',()=>renderSearch(sIn.value));
document.getElementById('searchBtn').addEventListener('click',()=>{renderSearch('');setTimeout(()=>sIn.focus(),80);});

/* ====== CHARTS (Chart.js, mengikuti tema) ====== */
let cBelanja,cPendapatan;
function buildCharts(){
  if(typeof Chart==='undefined')return;
  const css=getComputedStyle(root);
  const ink=css.getPropertyValue('--ink').trim(), muted=css.getPropertyValue('--muted').trim(), line=css.getPropertyValue('--line').trim();
  Chart.defaults.font.family="'Plus Jakarta Sans',sans-serif";
  cBelanja?.destroy(); cPendapatan?.destroy();
  cBelanja=new Chart(document.getElementById('chartBelanja'),{
    type:'bar',
    data:{labels:['Pemerintahan','Pembangunan','Pembinaan','Pemberdayaan','Tak Terduga'],
      datasets:[
        {label:'Anggaran (Rp Juta)',data:[680,1240,310,460,150],backgroundColor:'rgba(14,99,168,.78)',borderRadius:9,maxBarThickness:42},
        {label:'Realisasi (Rp Juta)',data:[530,794,182,239,46],backgroundColor:'rgba(12,124,70,.85)',borderRadius:9,maxBarThickness:42}
      ]},
    options:{responsive:true,maintainAspectRatio:false,
      plugins:{legend:{labels:{color:muted,boxWidth:12,boxHeight:12,borderRadius:3,useBorderRadius:true}}},
      scales:{x:{ticks:{color:muted},grid:{display:false}},
              y:{ticks:{color:muted},grid:{color:line}}}}});
  cPendapatan=new Chart(document.getElementById('chartPendapatan'),{
    type:'doughnut',
    data:{labels:['Dana Desa','Alokasi Dana Desa','PADes','Bagi Hasil Pajak','Lain-lain'],
      datasets:[{data:[58,24,9,6,3],
        backgroundColor:['#0C7C46','#0E63A8','#C99A2C','#56BE8B','#3F8FC9'],
        borderWidth:3,borderColor:css.getPropertyValue('--surface').trim()}]},
    options:{responsive:true,maintainAspectRatio:false,cutout:'62%',
      plugins:{legend:{position:'right',labels:{color:muted,boxWidth:11,boxHeight:11,borderRadius:3,useBorderRadius:true,font:{size:11}}}}}});
}
window.addEventListener('load',buildCharts);

/* ====== TAHUN COPYRIGHT ====== */
document.getElementById('year').textContent=new Date().getFullYear();
</script>
</body>
</html>
