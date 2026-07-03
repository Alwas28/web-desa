@extends('mobile.layouts.app')

@section('title', ($desa['desa.nama'] ?? 'Portal Desa') . ' — Beranda')

@section('content')

{{-- Hero --}}
<div class="hero-card">
  <div class="hero-badge">
    <span class="dot"></span>
    Portal Resmi Pemerintah Desa
  </div>
  <h2>Selamat Datang,<br>{{ $penduduk->nama_lengkap ?? $user->name }} 🌿</h2>
  <p style="margin-top:6px">{{ $desa['desa.kecamatan'] ? 'Kec. ' . $desa['desa.kecamatan'] . (isset($desa['desa.kabupaten']) ? ', ' . $desa['desa.kabupaten'] : '') : 'Layanan administrasi desa dalam genggaman Anda' }}</p>
</div>

{{-- Layanan Surat — 2×2 grid cards --}}
<div class="section-hd">
  <h3>Layanan Surat</h3>
  <a href="{{ route('portal.layanan') }}">Lihat semua</a>
</div>
@if($layanan->isNotEmpty())
@php
  $layananColors = [
    ['#15803d','#166534'],
    ['#1d4ed8','#1e40af'],
    ['#7c3aed','#6d28d9'],
  ];
@endphp
<div style="padding:0 16px 4px;display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
  @foreach($layanan as $j)
  @php $c = $layananColors[$loop->index % 3]; @endphp
  <a href="{{ route('portal.layanan') }}" style="text-decoration:none;display:flex;flex-direction:column;align-items:center;background:#fff;border-radius:16px;padding:14px 8px 12px;box-shadow:0 1px 6px rgba(0,0,0,.07);gap:9px;transition:.15s;border:1.5px solid transparent;text-align:center" ontouchstart="this.style.borderColor='#bbf7d0'" ontouchend="this.style.borderColor='transparent'">
    <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,{{ $c[0] }},{{ $c[1] }});display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
    </div>
    <div>
      <div style="font-size:11px;font-weight:700;color:#111827;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $j->nama }}</div>
      @if($j->kode)
      <div style="font-size:10px;color:#9ca3af;margin-top:3px;font-weight:600">{{ $j->kode }}</div>
      @endif
      @if($j->jumlah_pengajuan > 0)
      <div style="font-size:9px;color:#15803d;margin-top:4px;font-weight:600;background:#dcfce7;display:inline-block;padding:2px 7px;border-radius:99px">{{ $j->jumlah_pengajuan }}× diajukan</div>
      @endif
    </div>
  </a>
  @endforeach
</div>
@else
<div style="padding:0 16px 4px">
  <div style="background:#fff;border-radius:16px;padding:28px;text-align:center;box-shadow:0 1px 6px rgba(0,0,0,.07)">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" style="margin:0 auto 8px"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6"/></svg>
    <p style="font-size:13px;color:#9ca3af">Belum ada layanan tersedia</p>
  </div>
</div>
@endif

{{-- Berita Terbaru --}}
@if($berita->isNotEmpty())
<div class="section-hd">
  <h3>Berita Terbaru</h3>
  <a href="{{ route('portal.berita') }}">Lihat semua</a>
</div>
@php
  $bgColors = ['linear-gradient(135deg,#15803d,#166534)','linear-gradient(135deg,#1d4ed8,#1e40af)','linear-gradient(135deg,#7c3aed,#6d28d9)','linear-gradient(135deg,#d97706,#b45309)'];
@endphp
<div class="berita-list">
  @foreach($berita as $b)
  <div class="berita-row">
    <div class="berita-row-thumb" style="background:{{ $bgColors[$loop->index % 4] }};min-height:90px;position:relative">
      @if($b->gambar_url)
      <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;border-radius:0">
      @else
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.5" stroke-linecap="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
      @endif
    </div>
    <div class="berita-row-body">
      @if($b->kategori)
      <div class="berita-row-cat">{{ $b->kategori->nama }}</div>
      @endif
      <div class="berita-row-title">{{ $b->judul }}</div>
      <div class="berita-row-date">{{ $b->published_at ? $b->published_at->locale('id')->diffForHumans() : '' }}</div>
    </div>
  </div>
  @endforeach
</div>
@endif

{{-- Produk Terbaru --}}
@if($produkTerbaru->isNotEmpty())
<div class="section-hd">
  <h3>Produk Terbaru</h3>
  <a href="{{ route('portal.produk') }}">Lihat semua</a>
</div>
<div style="padding:0 16px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;display:flex;gap:10px;padding-bottom:4px">
  @foreach($produkTerbaru as $p)
  <a href="{{ route('portal.produk') }}" style="flex-shrink:0;width:140px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.07);text-decoration:none;display:block">
    <div style="height:110px;background:linear-gradient(135deg,#f59e0b,#d97706);position:relative;display:flex;align-items:center;justify-content:center">
      @if($p->foto_url)
      <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
      @else
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      @endif
      <span style="position:absolute;top:7px;left:7px;background:rgba(0,0,0,.5);color:#fff;font-size:9px;font-weight:700;padding:2px 7px;border-radius:99px;backdrop-filter:blur(4px)">Baru</span>
    </div>
    <div style="padding:10px">
      <div style="font-size:12px;font-weight:700;color:#111827;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $p->nama }}</div>
      <div style="font-size:12px;font-weight:800;color:#15803d;margin-top:5px">{{ $p->harga_format }}</div>
      @if($p->satuan)
      <div style="font-size:10px;color:#9ca3af;margin-top:1px">/ {{ $p->satuan }}</div>
      @endif
    </div>
  </a>
  @endforeach
</div>
@endif

{{-- Produk Terlaris --}}
@if($produkTerlaris->isNotEmpty())
<div class="section-hd">
  <h3>Produk Terlaris</h3>
  <a href="{{ route('portal.produk') }}">Lihat semua</a>
</div>
<div style="padding:0 16px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;display:flex;gap:10px;padding-bottom:4px">
  @foreach($produkTerlaris as $p)
  <a href="{{ route('portal.produk') }}" style="flex-shrink:0;width:140px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.07);text-decoration:none;display:block">
    <div style="height:110px;background:linear-gradient(135deg,#dc2626,#b91c1c);position:relative;display:flex;align-items:center;justify-content:center">
      @if($p->foto_url)
      <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
      @else
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      @endif
      <span style="position:absolute;top:7px;left:7px;background:rgba(220,38,38,.85);color:#fff;font-size:9px;font-weight:700;padding:2px 7px;border-radius:99px;backdrop-filter:blur(4px)">🔥 Terlaris</span>
    </div>
    <div style="padding:10px">
      <div style="font-size:12px;font-weight:700;color:#111827;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $p->nama }}</div>
      <div style="font-size:12px;font-weight:800;color:#15803d;margin-top:5px">{{ $p->harga_format }}</div>
      @if($p->satuan)
      <div style="font-size:10px;color:#9ca3af;margin-top:1px">/ {{ $p->satuan }}</div>
      @endif
    </div>
  </a>
  @endforeach
</div>
@endif

<div style="height:24px"></div>

{{-- ── FAB Laporan (fixed bottom-right) ── --}}
<button id="fabLaporan" onclick="bukaChat()"
  style="position:fixed;right:18px;bottom:78px;z-index:500;
         width:52px;height:52px;border-radius:50%;border:none;cursor:pointer;
         background:linear-gradient(135deg,#2563eb,#1d4ed8);
         box-shadow:0 4px 16px rgba(37,99,235,.45);
         display:flex;align-items:center;justify-content:center;transition:.2s">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round">
    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
  </svg>
  @php $unread = $laporan->where('status','menunggu')->count(); @endphp
  @if($unread > 0)
  <span style="position:absolute;top:-3px;right:-3px;background:#ef4444;color:#fff;
               font-size:9px;font-weight:800;min-width:16px;height:16px;border-radius:99px;
               display:flex;align-items:center;justify-content:center;padding:0 3px;border:2px solid #fff">
    {{ $unread }}
  </span>
  @endif
</button>

{{-- ── Chat Panel (bottom sheet) ── --}}
<div id="chatOverlay" onclick="tutupChat(event)"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9000;
         backdrop-filter:blur(3px);align-items:flex-end;justify-content:center">

  <div id="chatPanel" onclick="event.stopPropagation()"
    style="background:#fff;border-radius:24px 24px 0 0;width:100%;max-width:480px;
           max-height:85vh;display:flex;flex-direction:column;
           box-shadow:0 -8px 40px rgba(0,0,0,.15);
           transform:translateY(100%);transition:transform .3s cubic-bezier(.32,.72,0,1)">

    {{-- Handle + Header --}}
    <div style="padding:12px 20px 0;flex-shrink:0">
      <div style="width:36px;height:4px;background:#e5e7eb;border-radius:99px;margin:0 auto 14px"></div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:34px;height:34px;background:linear-gradient(135deg,#2563eb,#1d4ed8);
                      border-radius:10px;display:flex;align-items:center;justify-content:center">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          <div>
            <p style="font-size:14px;font-weight:800;color:#111;margin:0">Lapor ke Desa</p>
            <p style="font-size:11px;color:#6b7280;margin:0">Pemerintah Desa siap membantu</p>
          </div>
        </div>
        <button onclick="bukaFormLaporan()"
          style="background:#eff6ff;border:none;border-radius:99px;padding:6px 13px;
                 font-size:12px;font-weight:700;color:#2563eb;cursor:pointer">
          + Baru
        </button>
      </div>
      {{-- Tab --}}
      <div style="display:flex;gap:0;border-bottom:2px solid #f3f4f6;margin-bottom:0">
        <button id="tabRiwayat" onclick="switchTab('riwayat')"
          style="flex:1;border:none;background:transparent;padding:8px 0;font-size:13px;font-weight:700;
                 color:#2563eb;border-bottom:2px solid #2563eb;margin-bottom:-2px;cursor:pointer">
          Riwayat
        </button>
        <button id="tabForm" onclick="switchTab('form')"
          style="flex:1;border:none;background:transparent;padding:8px 0;font-size:13px;font-weight:600;
                 color:#9ca3af;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer">
          Buat Laporan
        </button>
      </div>
    </div>

    {{-- Scrollable body --}}
    <div style="flex:1;overflow-y:auto;padding:16px 20px 24px">

      {{-- Tab Riwayat --}}
      <div id="panelRiwayat">
        @if($laporan->isEmpty())
        <div style="text-align:center;padding:40px 0">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" style="margin:0 auto 10px;display:block"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <p style="font-size:13px;color:#9ca3af;margin:0">Belum ada laporan.</p>
          <p style="font-size:12px;color:#9ca3af;margin:4px 0 0">Tap "+ Baru" untuk membuat laporan pertama.</p>
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:10px">
          @foreach($laporan as $lp)
          @php
            $sBg    = match($lp->status) { 'diproses'=>'#fff7ed','selesai'=>'#f0fdf4',default=>'#f9fafb' };
            $sColor = match($lp->status) { 'diproses'=>'#d97706','selesai'=>'#059669',default=>'#6b7280' };
            $sLabel = match($lp->status) { 'diproses'=>'Diproses','selesai'=>'Selesai',default=>'Menunggu' };
          @endphp
          <div onclick="toggleLapDetail('ld-{{ $lp->id }}')"
            style="border:1.5px solid #f3f4f6;border-radius:14px;padding:13px;cursor:pointer">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px">
              <div style="flex:1;min-width:0">
                <p style="font-size:13px;font-weight:700;color:#111;margin:0 0 3px;
                           white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $lp->judul }}</p>
                <p style="font-size:11px;color:#6b7280;margin:0">{{ $lp->kategori }} · {{ $lp->created_at->diffForHumans() }}</p>
              </div>
              <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:99px;
                           background:{{ $sBg }};color:{{ $sColor }};white-space:nowrap;flex-shrink:0">
                {{ $sLabel }}
              </span>
            </div>
            <div id="ld-{{ $lp->id }}" style="display:none;margin-top:10px;border-top:1px solid #f3f4f6;padding-top:10px">
              <p style="font-size:12px;color:#374151;line-height:1.6;margin:0 0 8px">{{ $lp->isi }}</p>
              @if($lp->balasan)
              <div style="background:#eff6ff;border-radius:10px;padding:10px">
                <p style="font-size:10px;font-weight:700;color:#2563eb;margin:0 0 4px">BALASAN PEMERINTAH DESA</p>
                <p style="font-size:12px;color:#1e40af;line-height:1.5;margin:0">{{ $lp->balasan }}</p>
                @if($lp->dibalas_at)
                <p style="font-size:10px;color:#6b7280;margin:6px 0 0">{{ $lp->dibalas_at->diffForHumans() }}</p>
                @endif
              </div>
              @endif
              @if($lp->status === 'menunggu')
              <button onclick="hapusLaporan(event,{{ $lp->id }},this)"
                style="margin-top:8px;border:1px solid #fecaca;background:#fef2f2;border-radius:8px;
                       padding:6px 12px;font-size:11px;font-weight:600;color:#dc2626;cursor:pointer">
                Batalkan
              </button>
              @endif
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>

      {{-- Tab Form --}}
      <div id="panelForm" style="display:none;flex-direction:column;gap:13px">
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">KATEGORI</label>
          <select id="lapKategori"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                   font-size:14px;outline:none;box-sizing:border-box;background:#fff">
            <option value="Infrastruktur">Infrastruktur (jalan, jembatan, dll.)</option>
            <option value="Sosial">Sosial & Kemasyarakatan</option>
            <option value="Keamanan">Keamanan & Ketertiban</option>
            <option value="Layanan Publik">Layanan Publik</option>
            <option value="Lingkungan">Lingkungan Hidup</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">JUDUL LAPORAN</label>
          <input id="lapJudul" type="text" maxlength="200" placeholder="Tulis judul singkat..."
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                   font-size:14px;outline:none;box-sizing:border-box">
        </div>
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">DETAIL LAPORAN</label>
          <textarea id="lapIsi" rows="4" maxlength="2000"
            placeholder="Jelaskan masalah, lokasi, dan kondisi yang perlu ditindaklanjuti..."
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                   font-size:14px;outline:none;resize:none;box-sizing:border-box"></textarea>
          <p id="lapIsiCount" style="font-size:10px;color:#9ca3af;margin:4px 0 0;text-align:right">0 / 2000</p>
        </div>
        <div id="lapErr" style="display:none;background:#fef2f2;border:1px solid #fecaca;
                                 border-radius:10px;padding:10px 12px;font-size:13px;color:#dc2626"></div>
        <button id="lapBtn" onclick="kirimLaporan()"
          style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;
                 border-radius:14px;padding:14px;font-size:15px;font-weight:700;width:100%;cursor:pointer">
          Kirim Laporan
        </button>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function cekSurat() {
  const token = prompt('Masukkan nomor/token surat Anda:');
  if (token && token.trim()) {
    window.location.href = '/verifikasi/' + encodeURIComponent(token.trim());
  }
}

const LAPORAN_STORE_URL   = '{{ route("portal.laporan.store") }}';
const LAPORAN_DESTROY_URL = '{{ url("portal/laporan") }}';
const CSRF_TOKEN = '{{ csrf_token() }}';

function bukaChat() {
  const overlay = document.getElementById('chatOverlay');
  const panel   = document.getElementById('chatPanel');
  overlay.style.display = 'flex';
  requestAnimationFrame(() => { panel.style.transform = 'translateY(0)'; });
}

function tutupChat(e) {
  if (e && e.target !== document.getElementById('chatOverlay')) return;
  const panel = document.getElementById('chatPanel');
  panel.style.transform = 'translateY(100%)';
  setTimeout(() => { document.getElementById('chatOverlay').style.display = 'none'; }, 300);
}

function switchTab(tab) {
  const isRiwayat = tab === 'riwayat';
  document.getElementById('panelRiwayat').style.display = isRiwayat ? 'block' : 'none';
  document.getElementById('panelForm').style.display    = isRiwayat ? 'none' : 'flex';
  document.getElementById('tabRiwayat').style.color          = isRiwayat ? '#2563eb' : '#9ca3af';
  document.getElementById('tabRiwayat').style.borderBottomColor = isRiwayat ? '#2563eb' : 'transparent';
  document.getElementById('tabRiwayat').style.fontWeight     = isRiwayat ? '700' : '600';
  document.getElementById('tabForm').style.color          = isRiwayat ? '#9ca3af' : '#2563eb';
  document.getElementById('tabForm').style.borderBottomColor = isRiwayat ? 'transparent' : '#2563eb';
  document.getElementById('tabForm').style.fontWeight     = isRiwayat ? '600' : '700';
}

function bukaFormLaporan() {
  switchTab('form');
  document.getElementById('lapJudul').value = '';
  document.getElementById('lapIsi').value   = '';
  document.getElementById('lapErr').style.display = 'none';
  document.getElementById('lapIsiCount').textContent = '0 / 2000';
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('lapIsi')?.addEventListener('input', function() {
    document.getElementById('lapIsiCount').textContent = this.value.length + ' / 2000';
  });
});

async function kirimLaporan() {
  const kategori = document.getElementById('lapKategori').value;
  const judul    = document.getElementById('lapJudul').value.trim();
  const isi      = document.getElementById('lapIsi').value.trim();
  const errEl    = document.getElementById('lapErr');
  const btn      = document.getElementById('lapBtn');

  if (!judul) { errEl.textContent = 'Judul laporan wajib diisi.'; errEl.style.display = 'block'; return; }
  if (!isi)   { errEl.textContent = 'Detail laporan wajib diisi.'; errEl.style.display = 'block'; return; }
  errEl.style.display = 'none';
  btn.disabled = true;
  btn.textContent = 'Mengirim...';

  try {
    const res = await fetch(LAPORAN_STORE_URL, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({ kategori, judul, isi }).toString(),
    });
    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Gagal mengirim laporan.');
    window.location.reload();
  } catch (e) {
    errEl.textContent = e.message;
    errEl.style.display = 'block';
    btn.disabled = false;
    btn.textContent = 'Kirim Laporan';
  }
}

function toggleLapDetail(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

async function hapusLaporan(event, id, btn) {
  event.stopPropagation();
  if (!confirm('Batalkan laporan ini?')) return;
  btn.disabled = true;
  btn.textContent = 'Membatalkan...';
  try {
    const res = await fetch(LAPORAN_DESTROY_URL + '/' + id, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (!res.ok) throw new Error();
    window.location.reload();
  } catch {
    btn.disabled = false;
    btn.textContent = 'Batalkan';
    alert('Gagal membatalkan laporan.');
  }
}
</script>
@endpush
