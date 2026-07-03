@extends('mobile.layouts.app')

@section('title', 'Produk Desa')

@section('content')

@php
  $status    = $pengajuanPenjual?->status;
  $disetujui = $status === 'disetujui';
  $menunggu  = $status === 'menunggu';
  $ditolak   = $status === 'ditolak';
@endphp

<div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 48px;color:#fff">
  <p style="font-size:11px;opacity:.7;margin-bottom:4px">PASAR DESA</p>
  <h2 style="font-size:20px;font-weight:800">Produk Saya</h2>
  <p style="font-size:12px;opacity:.8;margin-top:4px">Kelola produk yang Anda jual di {{ $desa['desa.nama'] ?? 'desa kami' }}</p>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div style="margin:12px 16px 0;padding:12px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;display:flex;align-items:center;gap:10px">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  <p style="font-size:13px;color:#15803d;font-weight:600">{{ session('success') }}</p>
</div>
@endif
@if(session('error'))
<div style="margin:12px 16px 0;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:14px;display:flex;align-items:center;gap:10px">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <p style="font-size:13px;color:#dc2626;font-weight:600">{{ session('error') }}</p>
</div>
@endif

{{-- ── PENJUAL TERVERIFIKASI ── --}}
@if($disetujui)

{{-- Header badge + tombol tambah --}}
<div style="margin:-26px 16px 0;padding:14px 16px;background:#fff;border-radius:20px;
            box-shadow:0 2px 16px rgba(0,0,0,.08);display:flex;align-items:center;justify-content:space-between;gap:10px">
  <div style="display:flex;align-items:center;gap:10px">
    <div style="width:38px;height:38px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div>
      <p style="font-size:13px;font-weight:700;color:#111827">Penjual Terverifikasi</p>
      <p style="font-size:11px;color:#6b7280">{{ $produkSaya->where('status','aktif')->count() }} aktif · {{ $produkSaya->where('status','nonaktif')->count() }} nonaktif</p>
    </div>
  </div>
  <button onclick="openTambah()"
    style="padding:9px 16px;background:linear-gradient(135deg,#15803d,#166534);border:none;
           border-radius:12px;color:#fff;font-size:13px;font-weight:700;cursor:pointer;
           display:flex;align-items:center;gap:6px;white-space:nowrap">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Produk
  </button>
</div>

{{-- Daftar produk --}}
<div style="padding:14px 16px 0">
  @if($produkSaya->isEmpty())
  <div style="padding:40px 24px;text-align:center;background:#fff;border-radius:20px;box-shadow:0 1px 8px rgba(0,0,0,.06)">
    <div style="width:64px;height:64px;border-radius:20px;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8" stroke-linecap="round">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
    </div>
    <p style="font-size:14px;font-weight:700;color:#111827;margin-bottom:6px">Belum ada produk</p>
    <p style="font-size:12px;color:#6b7280">Tekan "Tambah Produk" untuk mulai berjualan</p>
  </div>
  @else
  <div style="display:flex;flex-direction:column;gap:10px">
    @foreach($produkSaya as $p)
    @php $isAktif = $p->status === 'aktif'; @endphp
    <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.06);border:1px solid {{ $isAktif ? '#f3f4f6' : '#e5e7eb' }};opacity:{{ $isAktif ? '1' : '.75' }}">
      {{-- Foto --}}
      @if($p->foto_url)
      <div style="position:relative">
        <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}"
          style="width:100%;height:160px;object-fit:cover;display:block">
        {{-- Badge status di atas foto --}}
        <span style="position:absolute;top:10px;left:10px;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;
                     background:{{ $isAktif ? 'rgba(21,128,61,.9)' : 'rgba(107,114,128,.85)' }};color:#fff">
          {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
        </span>
      </div>
      @else
      {{-- Badge status tanpa foto --}}
      <div style="padding:8px 14px 0;display:flex;justify-content:flex-end">
        <span style="padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;
                     background:{{ $isAktif ? '#f0fdf4' : '#f3f4f6' }};
                     color:{{ $isAktif ? '#15803d' : '#6b7280' }};
                     border:1px solid {{ $isAktif ? '#bbf7d0' : '#e5e7eb' }}">
          {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
        </span>
      </div>
      @endif
      <div style="padding:12px 14px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
          <div style="flex:1;min-width:0">
            <h4 style="font-size:14px;font-weight:700;color:#111827;margin-bottom:2px">{{ $p->nama }}</h4>
            <p style="font-size:15px;font-weight:800;color:#15803d;margin-bottom:3px">{{ $p->harga_format }}<span style="font-size:11px;font-weight:500;color:#9ca3af"> / {{ $p->satuan }}</span></p>
            @if($p->deskripsi)
            <p style="font-size:12px;color:#6b7280;margin-bottom:4px">{{ $p->deskripsi }}</p>
            @endif
            <div style="display:flex;align-items:center;gap:5px">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#25D366" stroke-width="2.5" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
              <span style="font-size:11px;color:#6b7280">{{ $p->nomor_wa }}</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0">
            <button onclick="openEdit({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->harga }}, '{{ addslashes($p->satuan) }}', '{{ addslashes($p->nomor_wa) }}', '{{ addslashes($p->deskripsi ?? '') }}', {{ $p->foto_url ? "'".e($p->foto_url)."'" : 'null' }})"
              style="padding:6px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#15803d;font-size:11px;font-weight:700;cursor:pointer">
              Edit
            </button>
            {{-- Toggle aktif/nonaktif --}}
            <form method="POST" action="{{ route('portal.produk.toggle', $p) }}">
              @csrf @method('PATCH')
              <button type="submit"
                style="width:100%;padding:6px 12px;border-radius:10px;font-size:11px;font-weight:700;cursor:pointer;
                       background:{{ $isAktif ? '#fef3c7' : '#f0fdf4' }};
                       border:1px solid {{ $isAktif ? '#fde68a' : '#bbf7d0' }};
                       color:{{ $isAktif ? '#92400e' : '#15803d' }}">
                {{ $isAktif ? 'Nonaktifkan' : 'Aktifkan' }}
              </button>
            </form>
            <form method="POST" action="{{ route('portal.produk.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini?')">
              @csrf @method('DELETE')
              <button type="submit"
                style="width:100%;padding:6px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#dc2626;font-size:11px;font-weight:700;cursor:pointer">
                Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>

{{-- ── STATUS LAINNYA (menunggu / ditolak / belum ajukan) ── --}}
@else

<div style="margin:-26px 16px 0;padding:20px;background:#fff;border-radius:20px;box-shadow:0 2px 16px rgba(0,0,0,.08)">

  @if($menunggu)
  <div style="text-align:center;padding:12px 0 8px">
    <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <h3 style="font-size:16px;font-weight:800;color:#111827;margin-bottom:6px">Pengajuan Sedang Ditinjau</h3>
    <p style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:12px">Permohonan Anda sedang dalam proses review oleh admin desa. Kami akan memberikan notifikasi setelah ditinjau.</p>
    <div style="padding:12px 14px;background:#fef3c7;border:1px solid #fde68a;border-radius:12px;text-align:left">
      <p style="font-size:11px;font-weight:600;color:#92400e;margin-bottom:4px">Alasan yang Anda ajukan:</p>
      <p style="font-size:12px;color:#78350f;font-style:italic">"{{ $pengajuanPenjual->alasan }}"</p>
    </div>
    <p style="font-size:11px;color:#9ca3af;margin-top:10px">Diajukan {{ $pengajuanPenjual->created_at->locale('id')->diffForHumans() }}</p>
  </div>

  @elseif($ditolak)
  <div style="text-align:center;padding:8px 0 4px">
    <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,#fef2f2,#fecaca);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <h3 style="font-size:16px;font-weight:800;color:#111827;margin-bottom:6px">Pengajuan Sebelumnya Ditolak</h3>
    @if($pengajuanPenjual->catatan_admin)
    <div style="padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;text-align:left;margin-bottom:14px">
      <p style="font-size:11px;font-weight:600;color:#991b1b;margin-bottom:4px">Alasan penolakan admin:</p>
      <p style="font-size:12px;color:#7f1d1d">{{ $pengajuanPenjual->catatan_admin }}</p>
    </div>
    @endif
    <p style="font-size:13px;color:#6b7280;margin-bottom:16px">Perbaiki alasan Anda dan ajukan kembali permohonan.</p>
    <button onclick="document.getElementById('modalAjukan').style.display='block'"
      style="width:100%;padding:13px;background:linear-gradient(135deg,#15803d,#166534);border:none;border-radius:14px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
      Ajukan Ulang Permohonan
    </button>
  </div>

  @else
  <div style="text-align:center;padding:12px 0 4px">
    <div style="width:72px;height:72px;border-radius:22px;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8" stroke-linecap="round">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
    </div>
    <h3 style="font-size:17px;font-weight:800;color:#111827;margin-bottom:8px">Jual Produk di Pasar Desa</h3>
    <p style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:20px">Pasarkan produk Anda kepada warga desa. Daftarkan diri sebagai penjual terverifikasi untuk mulai berjualan.</p>
    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;text-align:left">
      @foreach([
        ['Produk Anda dilihat seluruh warga desa'],
        ['Hanya penjual terverifikasi yang dapat berjualan'],
        ['Admin memverifikasi setiap penjual untuk keamanan warga'],
      ] as $f)
      <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fef9f0;border-radius:12px">
        <div style="width:28px;height:28px;border-radius:8px;background:#fde68a;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <p style="font-size:12px;color:#374151;font-weight:500">{{ $f[0] }}</p>
      </div>
      @endforeach
    </div>
    <button onclick="document.getElementById('modalAjukan').style.display='block'"
      style="width:100%;padding:14px;background:linear-gradient(135deg,#15803d,#166534);border:none;border-radius:14px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(217,119,6,.35)">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
      Minta Persetujuan Admin
    </button>
    <p style="font-size:11px;color:#9ca3af;margin-top:10px">Gratis • Proses verifikasi 1–2 hari kerja</p>
  </div>
  @endif

</div>
@endif

<div style="height:24px"></div>

{{-- ── Modal Tambah Produk ── --}}
<div id="modalTambah" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
  <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:430px;
              background:#fff;border-radius:24px 24px 0 0;padding:20px;
              padding-bottom:calc(20px + env(safe-area-inset-bottom,0));max-height:90vh;overflow-y:auto">
    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:0 auto 16px"></div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
      <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      </div>
      <div style="flex:1">
        <h3 style="font-size:15px;font-weight:800;color:#111827">Tambah Produk</h3>
        <p style="font-size:12px;color:#6b7280;margin-top:2px">Isi detail produk yang ingin dijual</p>
      </div>
      <button onclick="closeTambah()" style="background:none;border:none;cursor:pointer;padding:4px;color:#9ca3af">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('portal.produk.store') }}" id="formTambah" enctype="multipart/form-data">
      @csrf
      @include('mobile._form_produk')
      <div style="display:flex;gap:10px;margin-top:4px">
        <button type="button" onclick="closeTambah()"
          style="flex:1;padding:13px;border-radius:14px;background:#f3f4f6;border:none;color:#374151;font-size:13px;font-weight:700;cursor:pointer">Batal</button>
        <button type="submit"
          style="flex:2;padding:13px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);border:none;color:#fff;font-size:14px;font-weight:700;cursor:pointer">
          Simpan Produk
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal Edit Produk ── --}}
<div id="modalEdit" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
  <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:430px;
              background:#fff;border-radius:24px 24px 0 0;padding:20px;
              padding-bottom:calc(20px + env(safe-area-inset-bottom,0));max-height:90vh;overflow-y:auto">
    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:0 auto 16px"></div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
      <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </div>
      <div style="flex:1">
        <h3 style="font-size:15px;font-weight:800;color:#111827">Edit Produk</h3>
        <p style="font-size:12px;color:#6b7280;margin-top:2px">Perbarui detail produk</p>
      </div>
      <button onclick="closeEdit()" style="background:none;border:none;cursor:pointer;padding:4px;color:#9ca3af">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formEdit" method="POST" action="" enctype="multipart/form-data">
      @csrf @method('PUT')
      @include('mobile._form_produk', ['prefix' => 'edit_'])
      <div style="display:flex;gap:10px;margin-top:4px">
        <button type="button" onclick="closeEdit()"
          style="flex:1;padding:13px;border-radius:14px;background:#f3f4f6;border:none;color:#374151;font-size:13px;font-weight:700;cursor:pointer">Batal</button>
        <button type="submit"
          style="flex:2;padding:13px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);border:none;color:#fff;font-size:14px;font-weight:700;cursor:pointer">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Ajukan Persetujuan (hanya untuk non-disetujui) --}}
@if(!$disetujui)
<div id="modalAjukan" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
  <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:430px;
              background:#fff;border-radius:24px 24px 0 0;padding:20px;
              padding-bottom:calc(20px + env(safe-area-inset-bottom,0))">
    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:0 auto 16px"></div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
      <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </div>
      <div style="flex:1">
        <h3 style="font-size:15px;font-weight:800;color:#111827">Permohonan Penjual</h3>
        <p style="font-size:12px;color:#6b7280;margin-top:2px">Admin akan meninjau permohonan Anda</p>
      </div>
      <button onclick="document.getElementById('modalAjukan').style.display='none'"
        style="background:none;border:none;cursor:pointer;padding:4px;color:#9ca3af">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('portal.produk.ajukan') }}" id="formAjukan">
      @csrf
      <div style="margin-bottom:6px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Apa yang ingin Anda jual? <span style="color:#dc2626">*</span>
        </label>
        <textarea name="alasan" rows="5" minlength="20" maxlength="1000" required
          placeholder="Contoh: Saya ingin menjual hasil kebun seperti sayuran dan buah-buahan organik..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:13px;font-family:inherit;color:#111827;resize:none;outline:none;transition:border-color .2s;box-sizing:border-box"
          onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
        <p style="font-size:11px;color:#9ca3af;margin-top:4px">Minimal 20 karakter.</p>
      </div>
      <div style="margin:10px 0 16px;padding:10px 14px;background:#fef3c7;border-radius:12px;display:flex;gap:10px;align-items:flex-start">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p style="font-size:11px;color:#92400e;line-height:1.5">Produk yang melanggar hukum atau merugikan warga tidak akan disetujui.</p>
      </div>
      <div style="display:flex;gap:10px">
        <button type="button" onclick="document.getElementById('modalAjukan').style.display='none'"
          style="flex:1;padding:13px;border-radius:14px;background:#f3f4f6;border:none;color:#374151;font-size:13px;font-weight:700;cursor:pointer">Batal</button>
        <button type="submit"
          style="flex:2;padding:13px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);border:none;color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
          Kirim Permohonan
        </button>
      </div>
    </form>
  </div>
</div>
@endif

@endsection

@push('scripts')
<script>
/* ── Format harga Rupiah ───────────────────────────────── */
function formatHargaInput(el, px) {
  const raw = el.value.replace(/\D/g, '');
  document.getElementById(px + 'harga').value = raw;
  el.value = raw === '' ? '' : parseInt(raw, 10).toLocaleString('id-ID');
}
function setHarga(px, raw) {
  document.getElementById(px + 'harga').value = raw || '';
  const display = document.getElementById(px + 'harga_display');
  display.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
}

/* ── Preview & hapus foto ──────────────────────────────── */
function previewFoto(input, px) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById(px + 'foto_preview').src = e.target.result;
    document.getElementById(px + 'foto_preview_wrap').style.display = 'block';
    document.getElementById(px + 'foto_dropzone').style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}
function hapusFoto(px) {
  document.getElementById(px + 'foto_input').value = '';
  document.getElementById(px + 'foto_preview_wrap').style.display = 'none';
  document.getElementById(px + 'foto_dropzone').style.display = 'flex';
}

/* Highlight drop zone on drag */
['dragover','dragleave','drop'].forEach(ev => {
  document.addEventListener(ev, e => {
    if (!e.target.closest('[id$="foto_dropzone"]')) return;
    if (ev === 'dragover') { e.preventDefault(); e.target.closest('[id$="foto_dropzone"]').style.borderColor = '#f59e0b'; }
    else { e.target.closest('[id$="foto_dropzone"]').style.borderColor = '#e5e7eb'; }
  });
});

/* ── Modal Tambah ──────────────────────────────────────── */
function openTambah() {
  document.getElementById('formTambah').reset();
  setHarga('', '');
  hapusFoto('');
  document.getElementById('modalTambah').style.display = 'block';
}
function closeTambah() { document.getElementById('modalTambah').style.display = 'none'; }

/* ── Modal Edit ────────────────────────────────────────── */
function openEdit(id, nama, harga, satuan, nomor_wa, deskripsi, fotoUrl) {
  const base = '{{ url('portal/produk') }}/';
  document.getElementById('formEdit').action   = base + id;
  document.getElementById('edit_nama').value      = nama;
  setHarga('edit_', harga);
  document.getElementById('edit_satuan').value    = satuan;
  document.getElementById('edit_nomor_wa').value  = nomor_wa;
  document.getElementById('edit_deskripsi').value = deskripsi;

  // Reset file input
  document.getElementById('edit_foto_input').value = '';
  if (fotoUrl) {
    document.getElementById('edit_foto_preview').src = fotoUrl;
    document.getElementById('edit_foto_preview_wrap').style.display = 'block';
    document.getElementById('edit_foto_dropzone').style.display = 'none';
  } else {
    hapusFoto('edit_');
  }

  document.getElementById('modalEdit').style.display = 'block';
}
function closeEdit() { document.getElementById('modalEdit').style.display = 'none'; }

document.getElementById('modalTambah').addEventListener('click', e => { if(e.target===e.currentTarget) closeTambah(); });
document.getElementById('modalEdit').addEventListener('click',   e => { if(e.target===e.currentTarget) closeEdit(); });
@if(!$disetujui)
document.getElementById('modalAjukan').addEventListener('click', e => { if(e.target===e.currentTarget) document.getElementById('modalAjukan').style.display='none'; });
@if($errors->has('alasan'))
document.getElementById('modalAjukan').style.display = 'block';
@endif
@endif

@if($errors->hasAny(['nama','harga','satuan','nomor_wa','deskripsi']))
openTambah();
@endif
</script>
@endpush
