@extends('layouts.public')

@section('title', 'Pasar Desa — ' . ($desaInfo['desa.nama'] ?? 'Portal Desa'))
@section('description', 'Produk-produk unggulan yang dijual oleh warga Desa ' . ($desaInfo['desa.nama'] ?? '') . '. Belanja langsung, dukung ekonomi lokal.')

@section('content')

<style>
/* ── Hero ── */
.pasar-hero{background:linear-gradient(135deg,#065f46 0%,#0C7C46 60%,#0e7490 100%);padding:52px 0 48px;color:#fff;position:relative;overflow:hidden}
.pasar-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 80% 50%,rgba(255,255,255,.07),transparent 65%)}
.pasar-hero-inner{position:relative}
.pasar-hero .eyebrow{color:rgba(255,255,255,.75);font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.pasar-hero .eyebrow::before{content:"";width:22px;height:2px;background:rgba(255,255,255,.5);border-radius:2px}
.pasar-hero h1{font-size:clamp(1.8rem,4vw,2.5rem);font-weight:800;letter-spacing:-.025em;line-height:1.15;margin-bottom:8px}
.pasar-hero p{font-size:.95rem;opacity:.82;max-width:500px;margin-bottom:0}

/* ── Breadcrumb ── */
.breadcrumb-bar{background:var(--surface);border-bottom:1px solid var(--line);font-size:.78rem;color:var(--muted);padding:9px 0}
.breadcrumb-bar .container{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.breadcrumb-bar b{color:var(--green);font-weight:700}

/* ── Toolbar ── */
.pasar-toolbar{padding:24px 0 0}
.search-box{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.search-input{flex:1;min-width:220px;padding:0 14px;height:42px;border:1.5px solid var(--line);border-radius:10px;background:var(--surface);color:var(--ink);font-size:.88rem;outline:none;transition:.2s}
.search-input:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(12,124,70,.12)}
.search-btn{height:42px;padding:0 18px;background:var(--green);color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;flex-shrink:0;transition:.15s}
.search-btn:hover{filter:brightness(1.08)}
.result-count{font-size:.82rem;color:var(--muted);padding:10px 0 20px}

/* ── Product grid ── */
.product-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;padding-bottom:48px}
@media(max-width:1024px){.product-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:720px) {.product-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:420px) {.product-grid{grid-template-columns:1fr}}

/* ── Product card ── */
.product-card{background:var(--surface);border:1.5px solid var(--line);border-radius:18px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .2s,transform .2s}
.product-card:hover{box-shadow:0 8px 32px rgba(0,0,0,.08);transform:translateY(-3px)}
.product-thumb{aspect-ratio:1/1;overflow:hidden;background:var(--surface-2);position:relative}
.product-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .35s}
.product-card:hover .product-thumb img{transform:scale(1.05)}
.product-thumb .no-photo{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--muted)}
.product-thumb .no-photo svg{opacity:.3}
.product-thumb .no-photo span{font-size:.72rem;opacity:.5}
.product-body{padding:14px;display:flex;flex-direction:column;gap:6px;flex:1}
.product-name{font-size:.92rem;font-weight:800;color:var(--ink);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.product-price{font-size:1.05rem;font-weight:900;color:var(--green);letter-spacing:-.02em}
.product-satuan{font-size:.72rem;color:var(--muted);font-weight:600;margin-top:-4px}
.product-seller{display:flex;align-items:center;gap:6px;margin-top:4px;font-size:.75rem;color:var(--muted)}
.product-seller svg{flex-shrink:0;opacity:.6}
.product-desc{font-size:.78rem;color:var(--muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.product-footer{padding:0 14px 14px;margin-top:auto}
.wa-btn{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;height:38px;border-radius:10px;background:#25d366;color:#fff;font-size:.82rem;font-weight:700;text-decoration:none;transition:.15s}
.wa-btn:hover{background:#1db954}
.wa-btn svg{flex-shrink:0}

/* ── Empty ── */
.empty-state{text-align:center;padding:80px 20px}
.empty-state svg{margin:0 auto 16px;opacity:.25;display:block}
.empty-state h3{font-size:1rem;font-weight:800;margin-bottom:6px}
.empty-state p{font-size:.86rem;color:var(--muted)}

/* ── Pagination ── */
.pasar-pagination{display:flex;justify-content:center;padding-bottom:60px}
</style>

{{-- Hero --}}
<div class="pasar-hero">
  <div class="container pasar-hero-inner">
    <div class="eyebrow">Ekonomi Lokal</div>
    <h1>Pasar Desa</h1>
    <p>Temukan produk-produk unggulan dari warga Desa {{ $desaInfo['desa.nama'] ?? '' }}. Beli langsung, dukung ekonomi lokal.</p>
  </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
  <div class="container">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M3 11l9-8 9 8M5 10v10h14V10"/></svg>
    <a href="{{ route('home') }}">Beranda</a>
    <span>›</span>
    <b>Pasar Desa</b>
  </div>
</div>

<div class="container">

  {{-- Search --}}
  <div class="pasar-toolbar">
    <form method="GET" action="{{ route('pasar-desa') }}" class="search-box">
      <input type="text" name="q" value="{{ $q }}" class="search-input"
             placeholder="Cari produk...">
      <button type="submit" class="search-btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        Cari
      </button>
      @if($q)
      <a href="{{ route('pasar-desa') }}" style="font-size:.82rem;color:var(--muted);display:flex;align-items:center;gap:4px">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        Hapus filter
      </a>
      @endif
    </form>
  </div>

  <div class="result-count">
    @if($q)
      Menampilkan <strong>{{ $produk->total() }}</strong> produk untuk "<em>{{ $q }}</em>"
    @else
      <strong>{{ $produk->total() }}</strong> produk tersedia
    @endif
  </div>

  {{-- Grid --}}
  @if($produk->isEmpty())
    <div class="empty-state">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      <h3>{{ $q ? 'Produk tidak ditemukan' : 'Belum ada produk tersedia' }}</h3>
      <p>{{ $q ? 'Coba kata kunci lain.' : 'Warga desa belum menambahkan produk.' }}</p>
    </div>
  @else
    <div class="product-grid">
      @foreach($produk as $item)
      <div class="product-card">

        {{-- Foto --}}
        <div class="product-thumb">
          @if($item->foto_url)
            <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" loading="lazy">
          @else
            <div class="no-photo">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
              </svg>
              <span>Tanpa foto</span>
            </div>
          @endif
        </div>

        {{-- Body --}}
        <div class="product-body">
          <div class="product-name">{{ $item->nama }}</div>
          <div class="product-price">{{ $item->harga_format }}</div>
          <div class="product-satuan">per {{ $item->satuan }}</div>
          @if($item->deskripsi)
          <div class="product-desc">{{ $item->deskripsi }}</div>
          @endif
          <div class="product-seller">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ $item->penduduk?->nama_lengkap ?? $item->user?->name ?? 'Warga Desa' }}
          </div>
        </div>

        {{-- WA Button --}}
        <div class="product-footer">
          @if($item->nomor_wa)
          <a href="{{ $item->nomor_wa_link }}?text={{ urlencode('Halo, saya tertarik dengan produk *' . $item->nama . '* yang dijual di Pasar Desa ' . ($desaInfo['desa.nama'] ?? '') . '. Apakah masih tersedia?') }}"
             target="_blank" rel="noopener" class="wa-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.122.554 4.118 1.52 5.855L0 24l6.324-1.495A11.941 11.941 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.804 9.804 0 0 1-5.003-1.368l-.36-.213-3.754.886.928-3.658-.235-.376A9.808 9.808 0 0 1 2.182 12C2.182 6.57 6.57 2.182 12 2.182c5.43 0 9.818 4.388 9.818 9.818 0 5.43-4.388 9.818-9.818 9.818z"/></svg>
            Hubungi Penjual
          </a>
          @else
          <div style="text-align:center;font-size:.75rem;color:var(--muted);padding:8px 0">Tidak ada kontak</div>
          @endif
        </div>

      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($produk->hasPages())
    <div class="pasar-pagination">
      {{ $produk->links() }}
    </div>
    @endif
  @endif

</div>

@endsection
