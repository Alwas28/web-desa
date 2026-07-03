@extends('mobile.layouts.app')

@section('title', 'Persurata')

@section('content')

@php $isKK = $penduduk?->isKepalaKeluarga(); @endphp

<div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 48px;color:#fff">
  <p style="font-size:11px;opacity:.7;margin-bottom:4px">PORTAL PERSURATA</p>
  <h2 style="font-size:20px;font-weight:800">Persurata</h2>
  <p style="font-size:12px;opacity:.8;margin-top:4px">
    {{ $isKK ? 'Kelola surat untuk seluruh anggota keluarga' : 'Pantau riwayat dan ajukan surat desa' }}
  </p>
</div>

{{-- Info badge KK head --}}
@if($isKK)
<div style="margin:10px 16px 0;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;
            display:flex;align-items:center;gap:10px">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
  <p style="font-size:12px;color:#15803d;font-weight:600">Anda adalah Kepala Keluarga — bisa mengajukan surat untuk seluruh anggota keluarga.</p>
</div>
@endif

{{-- Sub-tab switcher --}}
<div style="margin:{{ $isKK ? '10px' : '-26px' }} 16px 0;margin-bottom:14px">
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
      <p style="font-size:12px;font-weight:600;color:#6b7280">{{ $riwayat->count() }} pengajuan{{ $isKK ? ' keluarga' : '' }}</p>
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
    @php $sc = $statusColorMap[$r->status] ?? ['bg'=>'#f3f4f6','text'=>'#374151','border'=>'#e5e7eb']; @endphp
    <div style="background:#fff;border-radius:16px;padding:14px;margin-bottom:10px;
                box-shadow:0 1px 6px rgba(0,0,0,.06);border:1px solid #f3f4f6">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px">
        <div style="flex:1;min-width:0">
          <h4 style="font-size:13px;font-weight:700;color:#111827;margin-bottom:2px;
                     white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $r->jenis_surat }}</h4>
          {{-- Tampilkan nama penerima jika KK head mengajukan untuk anggota lain --}}
          @if($isKK && $r->penduduk)
          <p style="font-size:11px;color:#15803d;font-weight:600;margin-bottom:1px">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline;vertical-align:middle;margin-right:2px"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            {{ $r->penduduk->nama_lengkap }}
            @if($r->is_wakil)
              <span style="font-size:10px;color:#9ca3af;font-weight:400"> (diajukan oleh Anda)</span>
            @endif
          </p>
          @endif
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

{{-- ── Modal Buat Surat ── --}}
<div id="modalSurat" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
  <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:430px;
              background:#fff;border-radius:24px 24px 0 0;padding:20px;
              padding-bottom:calc(20px + env(safe-area-inset-bottom,0));max-height:90vh;overflow-y:auto">
    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:0 auto 16px"></div>

    {{-- Header modal --}}
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

      {{-- Pilih penerima (hanya untuk KK head) --}}
      @if($isKK && $anggotaKK->isNotEmpty())
      <div style="margin-bottom:14px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Surat Untuk <span style="color:#dc2626">*</span>
        </label>
        <div style="position:relative">
          <select name="untuk_penduduk_id" id="inputUntukPenduduk"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 40px 11px 14px;
                   font-size:13px;font-family:inherit;color:#111827;background:#fff;outline:none;
                   appearance:none;-webkit-appearance:none;cursor:pointer;transition:border-color .2s"
            onfocus="this.style.borderColor='#15803d'"
            onblur="this.style.borderColor='#e5e7eb'">
            <option value="">Saya Sendiri ({{ $penduduk->nama_lengkap }})</option>
            @foreach($anggotaKK as $anggota)
            <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }} — {{ $anggota->hubungan_label }}</option>
            @endforeach
          </select>
          <svg style="position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none"
               width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.5" stroke-linecap="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <p style="font-size:11px;color:#9ca3af;margin-top:4px">Pilih anggota keluarga jika surat bukan untuk Anda</p>
      </div>
      @endif

      {{-- Keperluan --}}
      <div style="margin-bottom:14px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Keperluan <span style="color:#dc2626">*</span>
        </label>
        <textarea name="keperluan" id="inputKeperluan" rows="3" maxlength="500"
          placeholder="Jelaskan keperluan pengajuan surat ini..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:13px;font-family:inherit;color:#111827;resize:vertical;outline:none;transition:border-color .2s"
          onfocus="this.style.borderColor='#15803d'"
          onblur="this.style.borderColor='#e5e7eb'"></textarea>
      </div>

      {{-- Keterangan --}}
      <div style="margin-bottom:20px">
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
          Keterangan Tambahan <span style="font-weight:400;color:#9ca3af">(opsional)</span>
        </label>
        <textarea name="keterangan" id="inputKeterangan" rows="2" maxlength="1000"
          placeholder="Informasi tambahan jika diperlukan..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:13px;font-family:inherit;color:#111827;resize:vertical;outline:none;transition:border-color .2s"
          onfocus="this.style.borderColor='#15803d'"
          onblur="this.style.borderColor='#e5e7eb'"></textarea>
      </div>

      <div style="display:flex;gap:10px">
        <button type="button" onclick="closeModal()"
          style="flex:1;padding:13px;border-radius:14px;background:#f3f4f6;border:none;color:#374151;font-size:13px;font-weight:700;cursor:pointer">
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

@endsection

@push('scripts')
<script>
function switchLayananTab(tab) {
  const isRiwayat = tab === 'riwayat';
  document.getElementById('lpane-riwayat').style.display = isRiwayat ? '' : 'none';
  document.getElementById('lpane-buat').style.display    = isRiwayat ? 'none' : '';
  const rBtn = document.getElementById('ltab-riwayat');
  const bBtn = document.getElementById('ltab-buat');
  if (isRiwayat) {
    rBtn.style.background = 'linear-gradient(135deg,#15803d,#166534)'; rBtn.style.color = '#fff';
    bBtn.style.background = 'transparent'; bBtn.style.color = '#6b7280';
  } else {
    bBtn.style.background = 'linear-gradient(135deg,#15803d,#166534)'; bBtn.style.color = '#fff';
    rBtn.style.background = 'transparent'; rBtn.style.color = '#6b7280';
  }
}

function filterLayanan(q) {
  const term = q.toLowerCase().trim();
  document.querySelectorAll('#layananList .layanan-card').forEach(card => {
    card.style.display = (card.dataset.nama || '').includes(term) ? '' : 'none';
  });
}

function buatSurat(id, nama) {
  document.getElementById('inputJenisSuratId').value = id;
  document.getElementById('modalSuratNama').textContent = nama;
  document.getElementById('inputKeperluan').value = '';
  document.getElementById('inputKeterangan').value = '';
  // Reset pilihan penerima ke "Saya Sendiri"
  const sel = document.getElementById('inputUntukPenduduk');
  if (sel) sel.value = '';
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
</script>
@endpush
