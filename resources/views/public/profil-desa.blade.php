@extends('layouts.public')

@section('title', 'Profil Desa — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', 'Profil resmi Desa ' . ($desaInfo['desa.nama'] ?? '') . ': sambutan kepala desa, visi dan misi, serta struktur organisasi pemerintahan desa.')

@section('content')
<style>
/* ── Hero ── */
.profil-hero{background:linear-gradient(135deg,var(--green-deep) 0%,var(--blue-deep) 100%);padding:56px 0 52px;color:#fff;position:relative;overflow:hidden}
.profil-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(255,255,255,.07),transparent 60%)}
.profil-hero-inner{position:relative}
.profil-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.profil-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.profil-hero h1{font-size:clamp(1.9rem,4vw,2.7rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.profil-hero p{font-size:.95rem;opacity:.82;max-width:540px}

/* ── Breadcrumb ── */
.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

/* ── Section ── */
.profil-section{padding:56px 0 0}
.profil-section:last-of-type{padding-bottom:72px}
.sec-label{font-size:.72rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--green);display:flex;align-items:center;gap:8px;margin-bottom:10px}
.sec-label::before{content:"";width:18px;height:2px;background:var(--green);border-radius:2px}
.sec-title{font-size:clamp(1.4rem,3vw,2rem);font-weight:800;letter-spacing:-.025em;color:var(--ink);margin-bottom:6px}
.sec-divider{width:48px;height:3px;background:var(--green);border-radius:3px;margin:16px 0 28px}

/* ── Sambutan ── */
.sambutan-grid{display:grid;grid-template-columns:220px 1fr;gap:48px;align-items:start}
@media(max-width:760px){.sambutan-grid{grid-template-columns:1fr}}
.kades-card{background:var(--surface);border:1.5px solid var(--line);border-radius:20px;overflow:hidden;text-align:center}
.kades-foto{width:100%;aspect-ratio:3/4;object-fit:cover;object-position:top;display:block;background:var(--surface-2)}
.kades-foto-placeholder{width:100%;aspect-ratio:3/4;background:linear-gradient(135deg,var(--green),var(--blue-deep));display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:900;color:#fff;letter-spacing:-.04em}
.kades-info{padding:16px}
.kades-info strong{display:block;font-size:.92rem;font-weight:800;color:var(--ink);margin-bottom:2px}
.kades-info span{font-size:.75rem;color:var(--muted)}
.sambutan-body{font-family:'Source Serif 4',Georgia,serif}
.sambutan-body .opening-quote{font-size:3rem;line-height:.8;color:var(--green);opacity:.4;font-weight:900;display:block;margin-bottom:-8px}
.sambutan-body p{font-size:1.05rem;line-height:1.85;color:var(--ink);margin-bottom:18px}
.sambutan-body p:last-child{margin-bottom:0}

/* ── Visi Misi ── */
.vimisi-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px}
@media(max-width:760px){.vimisi-grid{grid-template-columns:1fr}}
.visi-card{background:linear-gradient(135deg,var(--green-deep),#1d6b3e);color:#fff;border-radius:20px;padding:32px}
.visi-card .label{font-size:.72rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;opacity:.75;margin-bottom:12px}
.visi-card blockquote{font-family:'Source Serif 4',Georgia,serif;font-size:1.1rem;line-height:1.7;font-style:italic;margin:0}
.misi-card{background:var(--surface);border:1.5px solid var(--line);border-radius:20px;padding:32px}
.misi-card .label{font-size:.72rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:16px}
.misi-list{list-style:none;display:flex;flex-direction:column;gap:12px;margin:0;padding:0}
.misi-list li{display:flex;gap:12px;align-items:flex-start;font-size:.92rem;line-height:1.6;color:var(--ink)}
.misi-list li .num{width:26px;height:26px;border-radius:8px;background:var(--green);color:#fff;display:grid;place-items:center;font-size:.72rem;font-weight:800;flex-shrink:0;margin-top:1px}

/* ── Struktur Organisasi ── */
.org-section{background:var(--surface-2);border-radius:24px;padding:36px}
.org-head-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;display:flex;align-items:center;gap:20px;padding:20px 24px;margin-bottom:24px;max-width:480px;margin-left:auto;margin-right:auto}
.org-avatar{width:72px;height:72px;border-radius:16px;object-fit:cover;flex-shrink:0;background:linear-gradient(135deg,var(--green),var(--blue-deep))}
.org-avatar-placeholder{width:72px;height:72px;border-radius:16px;background:linear-gradient(135deg,var(--green),var(--blue-deep));display:grid;place-items:center;font-size:1.4rem;font-weight:900;color:#fff;flex-shrink:0}
.org-head-info strong{font-size:1rem;font-weight:800;color:var(--ink);display:block;margin-bottom:3px}
.org-head-info span{font-size:.8rem;color:var(--green);font-weight:700}
.org-head-info small{font-size:.75rem;color:var(--muted);display:block;margin-top:2px}
.org-connector{width:2px;height:24px;background:var(--line);margin:0 auto;border-radius:2px}
.org-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
@media(max-width:860px){.org-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:500px){.org-grid{grid-template-columns:1fr}}
.org-card{background:var(--surface);border:1.5px solid var(--line);border-radius:16px;padding:16px;display:flex;align-items:center;gap:14px}
.org-card-avatar{width:48px;height:48px;border-radius:12px;object-fit:cover;flex-shrink:0;background:linear-gradient(135deg,var(--green-soft),#c3dac8)}
.org-card-avatar-placeholder{width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--green-soft),#c3dac8);display:grid;place-items:center;font-size:.9rem;font-weight:800;color:var(--green-deep);flex-shrink:0}
.org-card-info strong{font-size:.85rem;font-weight:800;color:var(--ink);display:block;line-height:1.3}
.org-card-info span{font-size:.73rem;color:var(--muted);font-weight:600}

.page-foot{padding:0 0 60px}
</style>

{{-- Hero --}}
<div class="profil-hero">
  <div class="container profil-hero-inner">
    <div class="eyebrow">Tentang Kami</div>
    <h1>Profil Desa {{ $desaInfo['desa.nama'] ?? '' }}</h1>
    <p>{{ $desaInfo['desa.kecamatan'] ?? '' }}{{ isset($desaInfo['desa.kecamatan'], $desaInfo['desa.kabupaten']) ? ', ' : '' }}{{ $desaInfo['desa.kabupaten'] ?? '' }}{{ isset($desaInfo['desa.provinsi']) ? ', ' . $desaInfo['desa.provinsi'] : '' }}</p>
  </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>Profil Desa</b>
  </div>
</div>

{{-- ── SAMBUTAN ── --}}
@php
  $sambutan = $desaInfo['desa.sambutan'] ?? '';
  $kades    = $pejabat->first(fn($p) => $p->jabatan && str_contains(strtolower($p->jabatan->nama), 'kepala'));
  $kadesNama = $kades?->nama ?? ($desaInfo['desa.kepala'] ?? 'Kepala Desa');
  $kadesInisial = collect(explode(' ', $kadesNama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
  $sambutan = $sambutan ?: 'Assalamu\'alaikum Warahmatullahi Wabarakatuh. Dengan mengucapkan puji dan syukur ke hadirat Tuhan Yang Maha Esa, atas segala rahmat dan karunia-Nya, kami menyambut kehadiran website resmi Desa ' . ($desaInfo['desa.nama'] ?? 'kami') . ' ini dengan penuh rasa syukur. Website ini kami hadirkan sebagai wujud komitmen pemerintah desa dalam mewujudkan tata kelola pemerintahan yang transparan, akuntabel, dan partisipatif. Semoga website ini dapat menjadi media informasi yang bermanfaat bagi seluruh warga dan memberikan kemudahan dalam mengakses berbagai layanan publik desa.';
@endphp

<div class="profil-section">
  <div class="container">
    <div class="sec-label">Sambutan</div>
    <h2 class="sec-title">Sambutan Kepala Desa</h2>
    <div class="sec-divider"></div>

    <div class="sambutan-grid">
      {{-- Foto & identitas kepala desa --}}
      <div class="kades-card">
        @if($kades?->foto_url)
          <img src="{{ $kades->foto_url }}" alt="{{ $kadesNama }}" class="kades-foto">
        @else
          <div class="kades-foto-placeholder">{{ $kadesInisial }}</div>
        @endif
        <div class="kades-info">
          <strong>{{ $kadesNama }}</strong>
          <span>Kepala Desa {{ $desaInfo['desa.nama'] ?? '' }}</span>
        </div>
      </div>

      {{-- Isi sambutan --}}
      <div class="sambutan-body">
        <span class="opening-quote">"</span>
        @foreach(array_filter(array_map('trim', explode("\n\n", $sambutan))) as $para)
          <p>{{ $para }}</p>
        @endforeach
        @if(!str_contains($sambutan, "\n\n"))
          <p>{{ $sambutan }}</p>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- ── VISI & MISI ── --}}
<div class="profil-section">
  <div class="container">
    <div class="sec-label">Arah Pembangunan</div>
    <h2 class="sec-title">Visi &amp; Misi</h2>
    <div class="sec-divider"></div>

    <div class="vimisi-grid">
      {{-- Visi --}}
      <div class="visi-card">
        <div class="label">Visi</div>
        <blockquote>
          {{ $desaInfo['desa.visi'] ?? 'Mewujudkan ' . ($desaInfo['desa.nama'] ?? 'Desa') . ' yang maju, mandiri, dan sejahtera melalui pelayanan publik yang prima, tata kelola yang transparan, dan pemberdayaan masyarakat yang berkelanjutan.' }}
        </blockquote>
      </div>

      {{-- Misi --}}
      <div class="misi-card">
        <div class="label">Misi</div>
        @php
          $misiRaw = $desaInfo['desa.misi'] ?? '';
          $misiItems = $misiRaw
            ? array_values(array_filter(array_map('trim', explode("\n", $misiRaw))))
            : [
                'Mewujudkan tata kelola pemerintahan yang transparan dan akuntabel',
                'Meningkatkan kualitas pelayanan publik berbasis digital',
                'Mengembangkan potensi ekonomi dan sumber daya masyarakat desa',
                'Meningkatkan kualitas infrastruktur dan sarana prasarana desa',
                'Memberdayakan masyarakat dalam pembangunan desa yang berkelanjutan',
              ];
        @endphp
        <ol class="misi-list">
          @foreach($misiItems as $i => $item)
          <li>
            <span class="num">{{ $i + 1 }}</span>
            <span>{{ $item }}</span>
          </li>
          @endforeach
        </ol>
      </div>
    </div>
  </div>
</div>

{{-- ── STRUKTUR ORGANISASI ── --}}
<div class="profil-section">
  <div class="container">
    <div class="sec-label">Pemerintahan</div>
    <h2 class="sec-title">Struktur Organisasi</h2>
    <div class="sec-divider"></div>

    @if($pejabat->isEmpty())
      <div style="text-align:center;padding:48px 20px;color:var(--muted)">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" style="margin:0 auto 12px;opacity:.3;display:block"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <p style="font-size:.88rem">Data struktur organisasi belum tersedia.</p>
      </div>
    @else
      @php
        $kadesStrukt = $pejabat->first(fn($p) => $p->jabatan && str_contains(strtolower($p->jabatan->nama), 'kepala'));
        $anggotaStrukt = $pejabat->reject(fn($p) => $p->id === $kadesStrukt?->id);
      @endphp

      <div class="org-section">

        {{-- Kepala Desa (top) --}}
        @if($kadesStrukt)
        @php
          $inisialKd = collect(explode(' ', $kadesStrukt->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
        @endphp
        <div class="org-head-card">
          @if($kadesStrukt->foto_url)
            <img src="{{ $kadesStrukt->foto_url }}" alt="{{ $kadesStrukt->nama }}" class="org-avatar">
          @else
            <div class="org-avatar-placeholder">{{ $inisialKd }}</div>
          @endif
          <div class="org-head-info">
            <strong>{{ $kadesStrukt->nama }}</strong>
            <span>{{ $kadesStrukt->jabatan?->nama ?? 'Kepala Desa' }}</span>
            @if($kadesStrukt->periode)
              <small>Periode {{ $kadesStrukt->periode->tahun_mulai ?? '' }}–{{ $kadesStrukt->periode->tahun_selesai ?? 'sekarang' }}</small>
            @endif
          </div>
        </div>
        @if($anggotaStrukt->isNotEmpty())
          <div class="org-connector"></div>
        @endif
        @endif

        {{-- Perangkat lainnya --}}
        @if($anggotaStrukt->isNotEmpty())
        <div class="org-grid">
          @foreach($anggotaStrukt as $pj)
          @php $inisial = collect(explode(' ', $pj->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode(''); @endphp
          <div class="org-card">
            @if($pj->foto_url)
              <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}" class="org-card-avatar">
            @else
              <div class="org-card-avatar-placeholder">{{ $inisial }}</div>
            @endif
            <div class="org-card-info">
              <strong>{{ $pj->nama }}</strong>
              <span>{{ $pj->jabatan?->nama ?? 'Perangkat Desa' }}</span>
            </div>
          </div>
          @endforeach
        </div>
        @endif

      </div>
    @endif
  </div>
</div>

<div class="page-foot"></div>
@endsection
