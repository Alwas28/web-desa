@extends('mobile.layouts.app')

@section('title', 'Berita & Pengumuman')

@section('content')

<div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 24px;color:#fff">
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

@endsection
