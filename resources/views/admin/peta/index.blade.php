@extends('layouts.admin')

@section('title', 'Peta Desa (GIS)')
@section('page-title', 'Peta Desa')
@section('page-sub', 'Sistem Informasi Geografis — visualisasi lokasi dan batas wilayah desa')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.css">
<style>
#map { border-radius: 1rem; isolation: isolate; }
#lokasiOverlay, #wilayahOverlay, #kategoriOverlay {
  transform: translateZ(0);
  -webkit-transform: translateZ(0);
}
.leaflet-popup-content-wrapper {
  border-radius: 12px; padding: 0; overflow: hidden;
  box-shadow: 0 4px 24px rgba(0,0,0,0.15); min-width: 200px;
}
.leaflet-popup-content { margin: 0; }
.leaflet-popup-tip-container { display: none; }
.dark .leaflet-popup-content-wrapper { background:#1e293b; border:1px solid #334155; }
.marker-pin svg path { transition: fill 0.2s; }
/* Override Leaflet.draw toolbar styling */
.leaflet-draw-toolbar a { background-color: white !important; }
.dark .leaflet-draw-toolbar a { background-color: #1e293b !important; }
.leaflet-draw-actions a { background-color: #1e293b; }
.draw-mode-active #map { cursor: crosshair !important; }
.draw-mode-active .leaflet-interactive { cursor: crosshair !important; }
</style>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-4 gap-3 mb-4">
  @foreach([
    ['label'=>'Total Lokasi',  'val'=>$stats['total'],    'icon'=>'ti-map-pin',    'color'=>'blue'],
    ['label'=>'Aktif',         'val'=>$stats['aktif'],    'icon'=>'ti-eye',        'color'=>'emerald'],
    ['label'=>'Kategori',      'val'=>$stats['kategori'], 'icon'=>'ti-category',   'color'=>'violet'],
    ['label'=>'Batas Wilayah', 'val'=>$stats['wilayah'],  'icon'=>'ti-vector',     'color'=>'amber'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-sm text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Main Layout: Sidebar + Map --}}
<div id="mapWrapper" class="flex gap-4" style="height: calc(100vh - 240px); min-height: 500px;">

  {{-- ── Sidebar ─────────────────────────────────────────────────────────── --}}
  <div class="w-72 flex-shrink-0 flex flex-col gap-3 overflow-y-auto overflow-x-hidden">

    {{-- Search --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-3 flex-shrink-0">
      <div class="relative">
        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" id="searchLokasi" placeholder="Cari lokasi..."
          class="w-full pl-8 pr-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      </div>
    </div>

    {{-- Category filters --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-3 flex-shrink-0">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Filter Kategori</span>
        <button onclick="openKategoriModal()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
          <i class="ti ti-settings text-xs"></i> Atur
        </button>
      </div>
      <div id="kategoriFilters" class="space-y-1.5">
        <label class="flex items-center gap-2 cursor-pointer group">
          <input type="checkbox" id="filterSemua" checked onchange="toggleSemuaFilter(this)"
            class="rounded border-slate-300 text-blue-600">
          <span class="text-sm text-slate-700 dark:text-slate-300">Semua Lokasi</span>
        </label>
        <div id="filterList"></div>
      </div>
    </div>

    {{-- Location list --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col flex-shrink-0" style="max-height:280px">
      <div class="px-3 py-2.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Lokasi</span>
        <span id="lokasiCount" class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 px-1.5 py-0.5 rounded-full">0</span>
      </div>
      <div id="lokasiList" class="overflow-y-auto flex-1 divide-y divide-slate-50 dark:divide-slate-800/50">
        <div class="py-8 text-center text-slate-400 dark:text-slate-600 text-xs">
          <i class="ti ti-map-pin-off text-2xl block mb-1"></i>Memuat...
        </div>
      </div>
      <div class="p-3 border-t border-slate-100 dark:border-slate-800 flex-shrink-0">
        <button onclick="openAddModal(null,null)"
          class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-plus"></i> Tambah Lokasi
        </button>
      </div>
    </div>

    {{-- ── Batas Wilayah panel ──────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col flex-shrink-0">
      <button onclick="toggleWilayahPanel()" class="px-3 py-2.5 flex items-center justify-between w-full text-left">
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Batas Wilayah</span>
        <div class="flex items-center gap-1.5">
          <span id="wilayahCount" class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 px-1.5 py-0.5 rounded-full">0</span>
          <i id="wilayahChevron" class="ti ti-chevron-down text-slate-400 text-xs transition-transform"></i>
        </div>
      </button>
      <div id="wilayahPanel" class="border-t border-slate-100 dark:border-slate-800">
        <div id="wilayahList" class="divide-y divide-slate-50 dark:divide-slate-800/50 max-h-48 overflow-y-auto">
          <div class="py-5 text-center text-xs text-slate-400 dark:text-slate-600">
            <i class="ti ti-vector text-xl block mb-1"></i>Belum ada batas wilayah
          </div>
        </div>
        <div class="p-3 border-t border-slate-100 dark:border-slate-800">
          <button id="btnStartDraw" onclick="startDraw()"
            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium transition-colors">
            <i class="ti ti-pencil-plus"></i> Gambar Batas Wilayah
          </button>
        </div>
      </div>
    </div>

  </div>

  {{-- ── Map ──────────────────────────────────────────────────────────────── --}}
  <div class="flex-1 min-w-0 relative">
    <div id="map" class="w-full h-full shadow-sm border border-slate-200 dark:border-slate-800"></div>

    {{-- Draw mode banner --}}
    <div id="drawBanner" class="hidden absolute top-3 left-1/2 -translate-x-1/2 z-[450] items-center gap-3
      bg-amber-500 text-white text-xs font-medium px-4 py-2 rounded-full shadow-lg whitespace-nowrap">
      <span class="animate-pulse w-2 h-2 rounded-full bg-white inline-block"></span>
      <span id="drawBannerText">Mode Menggambar — klik titik di peta, klik ganda untuk selesai</span>
      <button onclick="cancelDraw()" class="ml-2 underline hover:no-underline">Batalkan</button>
    </div>

    {{-- Map tip --}}
    <div id="mapTip" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/70 text-white text-xs px-3 py-1.5 rounded-full pointer-events-none z-[400]" style="transition:opacity 0.5s">
      <i class="ti ti-hand-click"></i> Klik peta untuk menambah lokasi baru
    </div>

    {{-- Fit all button --}}
    <button onclick="fitAll()" title="Tampilkan semua"
      class="absolute top-3 right-3 z-[400] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-2 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-arrows-maximize text-slate-600 dark:text-slate-300"></i>
    </button>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     Modal: Tambah / Edit Lokasi
══════════════════════════════════════════════════════════════════════════ --}}
<div id="lokasiOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md mx-4 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h3 id="lokasiModalTitle" class="font-semibold text-slate-800 dark:text-slate-100">Tambah Lokasi</h3>
      <button onclick="closeLokasiModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="ti ti-x text-lg"></i></button>
    </div>
    <div class="p-5 space-y-4">
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nama Lokasi <span class="text-red-500">*</span></label>
        <input type="text" id="l_nama" maxlength="150" placeholder="mis. Kantor Desa"
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Kategori</label>
        <select id="l_kategori" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          <option value="">-- Tanpa Kategori --</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Latitude <span class="text-red-500">*</span></label>
          <input type="number" id="l_lat" step="any" placeholder="-3.9721"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Longitude <span class="text-red-500">*</span></label>
          <input type="number" id="l_lng" step="any" placeholder="122.5146"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
      </div>
      <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl px-3 py-2 text-xs text-slate-400 flex items-center gap-2">
        <i class="ti ti-info-circle"></i> Klik titik di peta untuk mengisi koordinat otomatis
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
        <textarea id="l_alamat" rows="2" placeholder="Alamat lengkap (opsional)"
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Deskripsi</label>
        <textarea id="l_deskripsi" rows="2" placeholder="Keterangan singkat..."
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" id="l_aktif" checked class="rounded border-slate-300 text-blue-600">
        <label for="l_aktif" class="text-sm text-slate-600 dark:text-slate-400">Tampilkan di peta</label>
      </div>
      <div id="lokasiError" class="hidden text-sm text-red-600 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl px-3 py-2"></div>
    </div>
    <div class="flex justify-between items-center px-5 py-4 border-t border-slate-100 dark:border-slate-800">
      <button id="lokasiDeleteBtn" onclick="confirmDeleteLokasi()" class="hidden text-sm text-red-600 hover:text-red-700 font-medium">
        <i class="ti ti-trash"></i> Hapus
      </button>
      <div class="flex gap-2 ml-auto">
        <button onclick="closeLokasiModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Batal</button>
        <button onclick="saveLokasi()" id="lokasiSaveBtn" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">Simpan</button>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     Modal: Batas Wilayah
══════════════════════════════════════════════════════════════════════════ --}}
<div id="wilayahOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm mx-4 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h3 id="wilayahModalTitle" class="font-semibold text-slate-800 dark:text-slate-100">Simpan Batas Wilayah</h3>
      <button onclick="closeWilayahModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="ti ti-x text-lg"></i></button>
    </div>
    <div class="p-5 space-y-4">
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nama Wilayah <span class="text-red-500">*</span></label>
        <input type="text" id="w_nama" maxlength="150" placeholder="mis. Batas Desa Tontonunu"
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Tipe Wilayah</label>
        <select id="w_tipe" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          <option value="desa">Batas Desa</option>
          <option value="dusun">Batas Dusun</option>
          <option value="rt_rw">Batas RT/RW</option>
          <option value="sawah">Area Sawah/Lahan</option>
          <option value="hutan">Area Hutan</option>
          <option value="lainnya">Lainnya</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Warna Batas</label>
          <div class="flex items-center gap-2">
            <input type="color" id="w_warna" value="#3B82F6"
              class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer p-0.5 bg-white dark:bg-slate-800"
              oninput="document.getElementById('w_warnaHex').value=this.value">
            <input type="text" id="w_warnaHex" value="#3B82F6" maxlength="7"
              oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)) document.getElementById('w_warna').value=this.value"
              class="flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-2 text-xs font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          </div>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Transparansi isi</label>
          <div class="flex items-center gap-2 mt-1">
            <input type="range" id="w_opacity" min="0" max="1" step="0.05" value="0.25"
              oninput="document.getElementById('w_opacityVal').textContent=Math.round(this.value*100)+'%'"
              class="flex-1 accent-blue-600">
            <span id="w_opacityVal" class="text-xs text-slate-500 w-8 text-right">25%</span>
          </div>
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Keterangan</label>
        <textarea id="w_keterangan" rows="2" placeholder="Catatan tambahan..."
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" id="w_aktif" checked class="rounded border-slate-300 text-blue-600">
        <label for="w_aktif" class="text-sm text-slate-600 dark:text-slate-400">Tampilkan di peta</label>
      </div>
      <div id="wilayahError" class="hidden text-sm text-red-600 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl px-3 py-2"></div>
    </div>
    <div class="flex justify-between items-center px-5 py-4 border-t border-slate-100 dark:border-slate-800">
      <button id="wilayahDeleteBtn" onclick="confirmDeleteWilayah()" class="hidden text-sm text-red-600 hover:text-red-700 font-medium">
        <i class="ti ti-trash"></i> Hapus
      </button>
      <div class="flex gap-2 ml-auto">
        <button id="wilayahCancelBtn" onclick="closeWilayahModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Batal</button>
        <button onclick="saveWilayah()" id="wilayahSaveBtn" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">Simpan</button>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     Modal: Atur Kategori
══════════════════════════════════════════════════════════════════════════ --}}
<div id="kategoriOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg mx-4 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Atur Kategori Lokasi</h3>
      <button onclick="closeKategoriModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="ti ti-x text-lg"></i></button>
    </div>
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
      <p id="katFormTitle" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Tambah Kategori Baru</p>
      <div class="grid grid-cols-3 gap-3 mb-3">
        <div>
          <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Ikon (Tabler)</label>
          <div class="relative">
            <i id="katIkonPreview" class="ti ti-map-pin absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input type="text" id="k_ikon" value="ti-map-pin" placeholder="ti-building" oninput="updateIkonPreview(this.value)"
              class="w-full pl-8 pr-2 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          </div>
        </div>
        <div>
          <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Warna</label>
          <div class="flex items-center gap-2">
            <input type="color" id="k_warna" value="#3B82F6" class="w-9 h-9 rounded-lg border border-slate-200 cursor-pointer p-0.5 bg-white dark:bg-slate-800">
            <input type="text" id="k_warnaHex" value="#3B82F6" maxlength="7" oninput="syncColorHex(this.value)"
              class="flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-2 text-xs font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          </div>
        </div>
        <div>
          <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Nama <span class="text-red-500">*</span></label>
          <input type="text" id="k_nama" maxlength="100" placeholder="Pemerintahan"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
      </div>
      <div class="flex gap-2">
        <input type="hidden" id="k_editId">
        <button onclick="saveKategori()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium transition-colors">
          <i class="ti ti-check"></i> <span id="katSaveLabel">Tambah</span>
        </button>
        <button onclick="resetKatForm()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Reset</button>
      </div>
    </div>
    <div id="kategoriTableWrap" class="max-h-64 overflow-y-auto">
      <table class="w-full text-sm">
        <tbody id="kategoriTableBody" class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
      </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
      <p class="text-xs text-slate-400">Ikon: <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">ti-building</code>, <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">ti-school</code>, <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">ti-heart-plus</code>, dst.</p>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
// ── Constants ──────────────────────────────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const API  = { lokasi:'/admin/peta/lokasi', kategori:'/admin/peta/kategori', wilayah:'/admin/peta/wilayah' };

// ── State ──────────────────────────────────────────────────────────────────────
let _map, _markers = {}, _allLokasi = [], _allKategori = [];
let _editLokasiId = null, _tempMarker = null;
let _activeFilters = new Set(['semua']);

// Wilayah state
let _wilayahGroup, _drawControl, _drawHandler = null;
let _allWilayah = [], _wilayahLayers = {};
let _editWilayahId = null, _pendingGeojson = null, _editWilayahGeojson = null;
let _wilayahPanelOpen = true;

// ── Map init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Pindah modal ke body agar lepas dari stacking context Leaflet
  ['lokasiOverlay', 'wilayahOverlay', 'kategoriOverlay'].forEach(id => {
    const el = document.getElementById(id);
    if (el) document.body.appendChild(el);
  });

  initMap();
  loadKategori().then(() => loadLokasi());
  loadWilayah();

  document.getElementById('k_warna').addEventListener('input', function() {
    document.getElementById('k_warnaHex').value = this.value;
  });
  document.getElementById('searchLokasi').addEventListener('input', function() {
    renderLokasiList(_allLokasi, this.value.toLowerCase());
  });

  // Auto-hide tip
  setTimeout(() => {
    const t = document.getElementById('mapTip');
    if (t) t.style.opacity = '0';
  }, 4000);
});

function initMap() {
  const lat  = parseFloat(localStorage.getItem('peta_lat')  || '-3.9721');
  const lng  = parseFloat(localStorage.getItem('peta_lng')  || '122.5146');
  const zoom = parseInt(localStorage.getItem('peta_zoom')   || '13');

  _map = L.map('map', { zoomControl: false }).setView([lat, lng], zoom);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(_map);

  L.control.zoom({ position: 'topright' }).addTo(_map);

  // Wilayah feature group (draw edits on this group)
  _wilayahGroup = new L.FeatureGroup().addTo(_map);

  // Leaflet.draw control (hidden by default — we manually activate draw handler)
  _drawControl = new L.Control.Draw({
    position: 'topright',
    draw: {
      polygon: {
        allowIntersection: false,
        drawError: { color: '#ef4444', message: 'Garis tidak boleh bersilangan!' },
        shapeOptions: { color: '#F59E0B', weight: 2, fillOpacity: 0.2 },
        showArea: true,
      },
      rectangle: {
        shapeOptions: { color: '#F59E0B', weight: 2, fillOpacity: 0.2 },
      },
      polyline: false, circle: false, circlemarker: false, marker: false,
    },
    edit: { featureGroup: _wilayahGroup, remove: false },
  });

  // After drawing a shape
  _map.on(L.Draw.Event.CREATED, (e) => {
    _pendingGeojson = JSON.stringify(e.layer.toGeoJSON().geometry);
    openWilayahModal(null); // new wilayah
  });

  // After editing wilayah vertices
  _map.on(L.Draw.Event.EDITED, (e) => {
    e.layers.eachLayer(layer => {
      const id = layer.options._wilayahId;
      if (id) patchWilayahGeojson(id, JSON.stringify(layer.toGeoJSON().geometry));
    });
    stopEditMode();
  });

  _map.on(L.Draw.Event.EDITSTOP, () => stopEditMode());

  // Save map position
  _map.on('moveend', () => {
    const c = _map.getCenter();
    localStorage.setItem('peta_lat', c.lat);
    localStorage.setItem('peta_lng', c.lng);
    localStorage.setItem('peta_zoom', _map.getZoom());
  });

  // Click to add lokasi (only when not in draw mode)
  _map.on('click', (e) => {
    if (_drawHandler) return; // drawing mode active — ignore
    openAddModal(e.latlng.lat, e.latlng.lng);
  });
}

// ── Wilayah: draw controls ─────────────────────────────────────────────────────
function startDraw() {
  if (_drawHandler) return;
  _map.addControl(_drawControl);
  _drawHandler = new L.Draw.Polygon(_map, _drawControl.options.draw.polygon);
  _drawHandler.enable();
  showDrawBanner('Mode Menggambar — klik titik di peta, klik ganda untuk selesai');
  document.getElementById('mapTip').style.opacity = '0';
}

function cancelDraw() {
  if (_drawHandler) { _drawHandler.disable(); _drawHandler = null; }
  _map.removeControl(_drawControl);
  hideDrawBanner();
  _pendingGeojson = null;
}

function stopEditMode() {
  _map.removeControl(_drawControl);
  hideDrawBanner();
}

function showDrawBanner(text) {
  const el = document.getElementById('drawBanner');
  document.getElementById('drawBannerText').textContent = text;
  el.classList.remove('hidden');
  el.classList.add('flex');
}

function hideDrawBanner() {
  const el = document.getElementById('drawBanner');
  el.classList.add('hidden');
  el.classList.remove('flex');
}

// ── Wilayah: load & render ─────────────────────────────────────────────────────
async function loadWilayah() {
  try {
    const r = await fetch(API.wilayah);
    _allWilayah = await r.json();
    renderWilayahLayers(_allWilayah);
    renderWilayahList(_allWilayah);
    document.getElementById('wilayahCount').textContent = _allWilayah.length;
  } catch(e) { console.error('Load wilayah:', e); }
}

function renderWilayahLayers(wilayah) {
  // Clear existing
  Object.values(_wilayahLayers).forEach(l => _wilayahGroup.removeLayer(l));
  _wilayahLayers = {};

  wilayah.forEach(w => {
    if (!w.aktif) return;
    try {
      const layer = L.geoJSON(w.geojson, {
        style: { color: w.warna, fillColor: w.warna, fillOpacity: w.opacity, weight: 2 },
        _wilayahId: w.id,
      });

      layer.on('click', (e) => {
        L.DomEvent.stopPropagation(e); // prevent map click triggering add-lokasi
        openWilayahPopup(w, e.latlng);
      });

      layer.addTo(_wilayahGroup);
      _wilayahLayers[w.id] = layer;
    } catch(err) { console.error('Render wilayah', w.id, err); }
  });
}

function openWilayahPopup(w, latlng) {
  const tipeColors = {
    desa:'#3B82F6', dusun:'#8B5CF6', rt_rw:'#10B981',
    sawah:'#65A30D', hutan:'#064E3B', lainnya:'#64748b',
  };
  const c = tipeColors[w.tipe] || '#64748b';

  const popup = L.popup({ maxWidth: 260 })
    .setLatLng(latlng)
    .setContent(`<div style="min-width:200px;font-family:inherit;">
      <div style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">
        <div style="font-weight:600;font-size:0.875rem;color:#0f172a;margin-bottom:3px;">${escHtml(w.nama)}</div>
        <span style="display:inline-block;font-size:0.7rem;padding:1px 8px;border-radius:20px;background:${c}22;color:${c};border:1px solid ${c}44;">${escHtml(w.tipe_label)}</span>
      </div>
      ${w.keterangan ? `<div style="padding:8px 14px;font-size:0.75rem;color:#475569;">${escHtml(w.keterangan)}</div>` : ''}
      <div style="padding:8px 10px;border-top:1px solid #f1f5f9;display:flex;gap:6px;justify-content:flex-end;">
        <button onclick="openEditWilayah(${w.id})" style="font-size:0.75rem;padding:4px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#334155;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
          <i class="ti ti-pencil"></i> Edit
        </button>
        <button onclick="startRedraw(${w.id})" style="font-size:0.75rem;padding:4px 10px;border-radius:8px;border:1px solid #fde68a;background:#fffbeb;color:#92400e;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
          <i class="ti ti-pencil-plus"></i> Gambar Ulang
        </button>
      </div>
    </div>`)
    .openOn(_map);
}

function renderWilayahList(wilayah) {
  const box = document.getElementById('wilayahList');
  document.getElementById('wilayahCount').textContent = wilayah.length;

  if (!wilayah.length) {
    box.innerHTML = '<div class="py-5 text-center text-xs text-slate-400 dark:text-slate-600"><i class="ti ti-vector text-xl block mb-1"></i>Belum ada batas wilayah</div>';
    return;
  }

  box.innerHTML = wilayah.map(w => `
    <div class="px-3 py-2.5 flex items-center gap-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <span class="w-3 h-3 rounded-sm flex-shrink-0 border" style="background:${w.warna}44;border-color:${w.warna}"></span>
      <div class="flex-1 min-w-0 cursor-pointer" onclick="zoomToWilayah(${w.id})">
        <div class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">${escHtml(w.nama)}</div>
        <div class="text-xs text-slate-400">${escHtml(w.tipe_label)}${!w.aktif ? ' · Disembunyikan' : ''}</div>
      </div>
      <div class="flex gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
        <button onclick="openEditWilayah(${w.id})" class="p-1 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit info">
          <i class="ti ti-pencil text-xs"></i>
        </button>
        <button onclick="startRedraw(${w.id})" class="p-1 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors" title="Gambar ulang batas">
          <i class="ti ti-pencil-plus text-xs"></i>
        </button>
      </div>
    </div>`).join('');
}

function zoomToWilayah(id) {
  const layer = _wilayahLayers[id];
  if (layer) _map.fitBounds(layer.getBounds().pad(0.1));
}

// ── Wilayah: modal open/close ──────────────────────────────────────────────────
function openWilayahModal(editId) {
  _editWilayahId = editId;
  const w = editId ? _allWilayah.find(x => x.id === editId) : null;

  document.getElementById('wilayahModalTitle').textContent = w ? 'Edit Batas Wilayah' : 'Simpan Batas Wilayah';
  document.getElementById('wilayahDeleteBtn').classList.toggle('hidden', !w);
  document.getElementById('wilayahError').classList.add('hidden');

  document.getElementById('w_nama').value        = w ? w.nama : '';
  document.getElementById('w_tipe').value        = w ? w.tipe : 'lainnya';
  document.getElementById('w_warna').value       = w ? w.warna : '#3B82F6';
  document.getElementById('w_warnaHex').value    = w ? w.warna : '#3B82F6';
  document.getElementById('w_opacity').value     = w ? w.opacity : 0.25;
  document.getElementById('w_opacityVal').textContent = Math.round((w ? w.opacity : 0.25) * 100) + '%';
  document.getElementById('w_keterangan').value  = w ? (w.keterangan || '') : '';
  document.getElementById('w_aktif').checked     = w ? w.aktif : true;

  showOverlay('wilayahOverlay');
}

function closeWilayahModal() {
  hideOverlay('wilayahOverlay');
  // If modal was for new wilayah but closed, clear pending
  if (!_editWilayahId) { _pendingGeojson = null; cancelDraw(); }
  _editWilayahId = null;
}

function openEditWilayah(id) {
  _map.closePopup();
  openWilayahModal(id);
}

function startRedraw(id) {
  _map.closePopup();
  _editWilayahId = id;
  _pendingGeojson = null;

  // Remove existing layer temporarily
  if (_wilayahLayers[id]) {
    _wilayahGroup.removeLayer(_wilayahLayers[id]);
    delete _wilayahLayers[id];
  }

  // Start draw mode
  _map.addControl(_drawControl);
  _drawHandler = new L.Draw.Polygon(_map, _drawControl.options.draw.polygon);
  _drawHandler.enable();
  showDrawBanner('Gambar ulang batas — klik titik, klik ganda untuk selesai');
}

// ── Wilayah: save / delete ─────────────────────────────────────────────────────
async function saveWilayah() {
  const nama  = document.getElementById('w_nama').value.trim();
  const errEl = document.getElementById('wilayahError');
  errEl.classList.add('hidden');

  if (!nama) { showErr(errEl, 'Nama wilayah wajib diisi.'); return; }
  if (!_editWilayahId && !_pendingGeojson) { showErr(errEl, 'Tidak ada data poligon. Gambar batas terlebih dahulu.'); return; }

  const payload = {
    nama,
    tipe:       document.getElementById('w_tipe').value,
    warna:      document.getElementById('w_warna').value,
    opacity:    parseFloat(document.getElementById('w_opacity').value),
    keterangan: document.getElementById('w_keterangan').value.trim(),
    aktif:      document.getElementById('w_aktif').checked ? 1 : 0,
  };
  if (_pendingGeojson) payload.geojson = _pendingGeojson;

  const btn = document.getElementById('wilayahSaveBtn');
  btn.disabled = true; btn.textContent = 'Menyimpan...';

  try {
    const url    = _editWilayahId ? `${API.wilayah}/${_editWilayahId}` : API.wilayah;
    const method = _editWilayahId ? 'PUT' : 'POST';
    const res  = await fetch(url, {
      method,
      headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (!res.ok) { showErr(errEl, data.message || 'Gagal menyimpan.'); return; }

    // Update state
    if (_editWilayahId) {
      const idx = _allWilayah.findIndex(x => x.id === _editWilayahId);
      if (idx !== -1) _allWilayah[idx] = data.wilayah;
    } else {
      _allWilayah.push(data.wilayah);
    }

    renderWilayahLayers(_allWilayah);
    renderWilayahList(_allWilayah);
    cancelDraw();
    closeWilayahModal();
    _pendingGeojson = null;
    _editWilayahId  = null;
  } catch(e) {
    showErr(errEl, 'Terjadi kesalahan. Coba lagi.');
  } finally {
    btn.disabled = false; btn.textContent = 'Simpan';
  }
}

async function patchWilayahGeojson(id, geojson) {
  const w = _allWilayah.find(x => x.id === id);
  if (!w) return;
  try {
    await fetch(`${API.wilayah}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
      body: JSON.stringify({ nama:w.nama, tipe:w.tipe, warna:w.warna, opacity:w.opacity, aktif:w.aktif?1:0, geojson }),
    });
    await loadWilayah();
  } catch(e) { console.error(e); }
}

async function confirmDeleteWilayah() {
  if (!confirm('Hapus batas wilayah ini?')) return;
  try {
    const res  = await fetch(`${API.wilayah}/${_editWilayahId}`, {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message || 'Gagal menghapus.'); return; }
    _allWilayah = _allWilayah.filter(x => x.id !== _editWilayahId);
    if (_wilayahLayers[_editWilayahId]) { _wilayahGroup.removeLayer(_wilayahLayers[_editWilayahId]); delete _wilayahLayers[_editWilayahId]; }
    renderWilayahList(_allWilayah);
    closeWilayahModal();
  } catch(e) { alert('Terjadi kesalahan.'); }
}

// Toggle wilayah panel
function toggleWilayahPanel() {
  _wilayahPanelOpen = !_wilayahPanelOpen;
  document.getElementById('wilayahPanel').style.display = _wilayahPanelOpen ? '' : 'none';
  document.getElementById('wilayahChevron').style.transform = _wilayahPanelOpen ? '' : 'rotate(-90deg)';
}

// ── Lokasi: marker icon & popup ────────────────────────────────────────────────
function makePinIcon(warna) {
  const c = warna || '#64748b';
  return L.divIcon({
    className: 'marker-pin',
    html: `<svg width="28" height="36" viewBox="0 0 28 36" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M14 0C6.268 0 0 6.268 0 14c0 9.8 14 22 14 22S28 23.8 28 14C28 6.268 21.732 0 14 0z" fill="${c}"/>
      <circle cx="14" cy="14" r="7" fill="white" fill-opacity="0.9"/></svg>`,
    iconSize: [28, 36], iconAnchor: [14, 36], popupAnchor: [0, -38],
  });
}

function makePopupHtml(l) {
  const bc = l.kategori ? `background:${l.kategori.warna}22;color:${l.kategori.warna};border-color:${l.kategori.warna}44;` : 'background:#f1f5f9;color:#64748b;border-color:#e2e8f0;';
  return `<div style="min-width:210px;font-family:inherit;">
    <div style="padding:12px 14px;border-bottom:1px solid #f1f5f9;">
      <div style="font-weight:600;font-size:0.875rem;color:#0f172a;margin-bottom:4px;">${escHtml(l.nama)}</div>
      ${l.kategori ? `<span style="display:inline-flex;align-items:center;gap:4px;font-size:0.7rem;padding:2px 8px;border-radius:20px;border:1px solid;${bc}"><i class="ti ${l.kategori.ikon}" style="font-size:0.7rem;"></i>${escHtml(l.kategori.nama)}</span>` : ''}
    </div>
    ${l.alamat||l.deskripsi ? `<div style="padding:8px 14px;font-size:0.75rem;color:#475569;">
      ${l.alamat ? `<div><i class="ti ti-map-pin" style="font-size:0.75rem;"></i> ${escHtml(l.alamat)}</div>` : ''}
      ${l.deskripsi ? `<div style="margin-top:2px;color:#64748b;">${escHtml(l.deskripsi)}</div>` : ''}
    </div>` : ''}
    <div style="padding:8px 10px;border-top:1px solid #f1f5f9;display:flex;gap:6px;justify-content:flex-end;">
      <button onclick="openEditModal(${l.id})" style="font-size:0.75rem;padding:4px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#334155;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"><i class="ti ti-pencil"></i> Edit</button>
    </div>
  </div>`;
}

// ── Lokasi: load & render ──────────────────────────────────────────────────────
async function loadLokasi() {
  try {
    const r = await fetch(API.lokasi + '?semua=1');
    _allLokasi = await r.json();
    renderMarkers(_allLokasi);
    renderLokasiList(_allLokasi);
  } catch(e) { console.error(e); }
}

async function loadKategori() {
  try {
    const r = await fetch(API.kategori);
    _allKategori = await r.json();
    renderKategoriFilters(_allKategori);
    renderKategoriSelect(_allKategori);
    renderKategoriTable(_allKategori);
  } catch(e) { console.error(e); }
}

function renderMarkers(lokasi) {
  Object.values(_markers).forEach(m => _map.removeLayer(m));
  _markers = {};
  lokasi.forEach(l => {
    if (!l.aktif) return;
    const m = L.marker([l.lat, l.lng], { icon: makePinIcon(l.kategori?.warna) })
      .bindPopup(makePopupHtml(l), { maxWidth: 260 })
      .addTo(_map);
    _markers[l.id] = m;
  });
}

function renderLokasiList(lokasi, query = '') {
  const box = document.getElementById('lokasiList');
  const filtered = lokasi.filter(l => {
    if (query && !l.nama.toLowerCase().includes(query) && !(l.alamat||'').toLowerCase().includes(query)) return false;
    if (!_activeFilters.has('semua')) {
      const kid = l.kategori ? String(l.kategori.id) : 'uncategorized';
      if (!_activeFilters.has(kid)) return false;
    }
    return true;
  });
  document.getElementById('lokasiCount').textContent = filtered.length;
  if (!filtered.length) {
    box.innerHTML = '<div class="py-6 text-center text-xs text-slate-400"><i class="ti ti-map-pin-off text-xl block mb-1"></i>Tidak ada lokasi</div>';
    return;
  }
  box.innerHTML = filtered.map(l => `
    <button onclick="zoomToLokasi(${l.id})" class="w-full text-left px-3 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-2.5">
      <span class="mt-1 w-3 h-3 rounded-full flex-shrink-0" style="background:${l.kategori?.warna||'#94a3b8'}"></span>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">${escHtml(l.nama)}</div>
        <div class="text-xs text-slate-400 truncate">${l.kategori ? escHtml(l.kategori.nama) : 'Tanpa kategori'}${l.alamat ? ' · '+escHtml(l.alamat) : ''}</div>
      </div>
      ${!l.aktif ? '<span class="text-xs text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full flex-shrink-0">Disembunyikan</span>' : ''}
    </button>`).join('');
}

function renderKategoriFilters(kategori) {
  const box = document.getElementById('filterList');
  box.innerHTML = [
    { id: 'uncategorized', nama: 'Tanpa Kategori', warna: '#94a3b8' }, ...kategori,
  ].map(k => {
    const id = String(k.id);
    const checked = _activeFilters.has('semua') || _activeFilters.has(id);
    return `<label class="flex items-center gap-2 cursor-pointer">
      <input type="checkbox" data-kid="${id}" ${checked?'checked':''} onchange="toggleKategoriFilter(this)" class="rounded border-slate-300 text-blue-600">
      <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:${k.warna||'#94a3b8'}"></span>
      <span class="text-sm text-slate-700 dark:text-slate-300 truncate">${escHtml(k.nama)}</span>
    </label>`;
  }).join('');
}

function renderKategoriSelect(kategori) {
  const sel = document.getElementById('l_kategori');
  sel.innerHTML = '<option value="">-- Tanpa Kategori --</option>' + kategori.map(k => `<option value="${k.id}">${escHtml(k.nama)}</option>`).join('');
}

function renderKategoriTable(kategori) {
  const tbody = document.getElementById('kategoriTableBody');
  if (!kategori.length) { tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-6 text-center text-xs text-slate-400">Belum ada kategori</td></tr>'; return; }
  tbody.innerHTML = kategori.map(k => `
    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
      <td class="px-4 py-2.5 flex items-center gap-2.5">
        <span class="w-5 h-5 rounded-full flex-shrink-0 grid place-items-center" style="background:${k.warna}22;border:1px solid ${k.warna}44;">
          <i class="ti ${k.ikon} text-xs" style="color:${k.warna}"></i></span>
        <span class="text-sm font-medium text-slate-800 dark:text-slate-100">${escHtml(k.nama)}</span>
      </td>
      <td class="px-4 py-2.5 text-xs text-slate-400">${k.lokasi_count||0} lokasi</td>
      <td class="px-4 py-2.5">
        <div class="flex gap-1 justify-end">
          <button onclick="editKategori(${JSON.stringify(k).replace(/"/g,'&quot;')})" class="p-1 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"><i class="ti ti-pencil text-xs"></i></button>
          <button onclick="deleteKategori(${k.id})" class="p-1 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"><i class="ti ti-trash text-xs"></i></button>
        </div>
      </td>
    </tr>`).join('');
}

// ── Filter logic ───────────────────────────────────────────────────────────────
function toggleSemuaFilter(cb) {
  document.querySelectorAll('#filterList input[type=checkbox]').forEach(b => b.checked = cb.checked);
  _activeFilters = cb.checked ? new Set(['semua']) : new Set();
  renderLokasiList(_allLokasi, document.getElementById('searchLokasi').value.toLowerCase());
  applyMarkerFilter();
}

function toggleKategoriFilter(cb) {
  const semuaCb = document.getElementById('filterSemua');
  cb.checked ? _activeFilters.add(cb.dataset.kid) : (_activeFilters.delete(cb.dataset.kid), _activeFilters.delete('semua'), semuaCb.checked = false);
  const allBoxes = document.querySelectorAll('#filterList input[type=checkbox]');
  if ([...allBoxes].every(b => b.checked)) { _activeFilters = new Set(['semua']); semuaCb.checked = true; }
  renderLokasiList(_allLokasi, document.getElementById('searchLokasi').value.toLowerCase());
  applyMarkerFilter();
}

function applyMarkerFilter() {
  _allLokasi.forEach(l => {
    const marker = _markers[l.id];
    if (!marker) return;
    const kid = l.kategori ? String(l.kategori.id) : 'uncategorized';
    const vis = l.aktif && (_activeFilters.has('semua') || _activeFilters.has(kid));
    vis ? (!_map.hasLayer(marker) && marker.addTo(_map)) : (_map.hasLayer(marker) && _map.removeLayer(marker));
  });
}

function fitAll() {
  const bounds = [...Object.values(_markers).filter(m => _map.hasLayer(m)).map(m => m.getLatLng()),
    ...(Object.keys(_wilayahLayers).length ? [_wilayahGroup.getBounds().getSouthWest(), _wilayahGroup.getBounds().getNorthEast()] : [])];
  if (bounds.length) _map.fitBounds(L.latLngBounds(bounds).pad(0.2));
}

function zoomToLokasi(id) {
  const l = _allLokasi.find(x => x.id === id);
  if (l) { _map.setView([l.lat, l.lng], 17, {animate:true}); _markers[id]?.openPopup(); }
}

// ── Modal: Add / Edit Lokasi ───────────────────────────────────────────────────
function openAddModal(lat, lng) {
  _editLokasiId = null;
  document.getElementById('lokasiModalTitle').textContent = 'Tambah Lokasi';
  document.getElementById('lokasiDeleteBtn').classList.add('hidden');
  document.getElementById('lokasiError').classList.add('hidden');
  ['l_nama','l_alamat','l_deskripsi'].forEach(id => document.getElementById(id).value='');
  document.getElementById('l_kategori').value='';
  document.getElementById('l_lat').value = lat!==null ? lat.toFixed(7):'';
  document.getElementById('l_lng').value = lng!==null ? lng.toFixed(7):'';
  document.getElementById('l_aktif').checked=true;
  if (lat!==null&&lng!==null) {
    if (_tempMarker) _map.removeLayer(_tempMarker);
    _tempMarker = L.marker([lat,lng],{icon:makePinIcon('#64748b'),draggable:true}).addTo(_map);
    _tempMarker.on('dragend',e=>{const p=e.target.getLatLng();document.getElementById('l_lat').value=p.lat.toFixed(7);document.getElementById('l_lng').value=p.lng.toFixed(7);});
  }
  showOverlay('lokasiOverlay');
}

function openEditModal(id) {
  const l = _allLokasi.find(x=>x.id===id); if (!l) return;
  _editLokasiId=id;
  document.getElementById('lokasiModalTitle').textContent='Edit Lokasi';
  document.getElementById('lokasiDeleteBtn').classList.remove('hidden');
  document.getElementById('lokasiError').classList.add('hidden');
  document.getElementById('l_nama').value=l.nama;
  document.getElementById('l_kategori').value=l.kategori?l.kategori.id:'';
  document.getElementById('l_lat').value=l.lat;
  document.getElementById('l_lng').value=l.lng;
  document.getElementById('l_alamat').value=l.alamat||'';
  document.getElementById('l_deskripsi').value=l.deskripsi||'';
  document.getElementById('l_aktif').checked=l.aktif;
  showOverlay('lokasiOverlay');
  _map.closePopup();
}

function closeLokasiModal() {
  hideOverlay('lokasiOverlay');
  if (_tempMarker){_map.removeLayer(_tempMarker);_tempMarker=null;}
}

async function saveLokasi() {
  const nama=document.getElementById('l_nama').value.trim();
  const lat=parseFloat(document.getElementById('l_lat').value);
  const lng=parseFloat(document.getElementById('l_lng').value);
  const errEl=document.getElementById('lokasiError');errEl.classList.add('hidden');
  if(!nama){showErr(errEl,'Nama lokasi wajib diisi.');return;}
  if(isNaN(lat)||isNaN(lng)){showErr(errEl,'Koordinat tidak valid.');return;}
  const payload={nama,peta_kategori_id:document.getElementById('l_kategori').value||null,latitude:lat,longitude:lng,
    alamat:document.getElementById('l_alamat').value.trim(),deskripsi:document.getElementById('l_deskripsi').value.trim(),
    aktif:document.getElementById('l_aktif').checked?1:0};
  const btn=document.getElementById('lokasiSaveBtn');btn.disabled=true;btn.textContent='Menyimpan...';
  try{
    const res=await fetch(_editLokasiId?`${API.lokasi}/${_editLokasiId}`:API.lokasi,{
      method:_editLokasiId?'PUT':'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
      body:JSON.stringify(payload)});
    const data=await res.json();
    if(!res.ok){showErr(errEl,data.message||'Gagal menyimpan.');return;}
    if(_editLokasiId){const idx=_allLokasi.findIndex(x=>x.id===_editLokasiId);if(idx!==-1)_allLokasi[idx]=data.lokasi;}
    else _allLokasi.push(data.lokasi);
    renderMarkers(_allLokasi);renderLokasiList(_allLokasi);closeLokasiModal();
  }catch(e){showErr(errEl,'Terjadi kesalahan.');}
  finally{btn.disabled=false;btn.textContent='Simpan';}
}

async function confirmDeleteLokasi() {
  if(!confirm('Hapus lokasi ini?'))return;
  try{
    const res=await fetch(`${API.lokasi}/${_editLokasiId}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}});
    const data=await res.json();if(!res.ok){alert(data.message);return;}
    _allLokasi=_allLokasi.filter(x=>x.id!==_editLokasiId);
    if(_markers[_editLokasiId]){_map.removeLayer(_markers[_editLokasiId]);delete _markers[_editLokasiId];}
    renderLokasiList(_allLokasi);closeLokasiModal();
  }catch(e){alert('Terjadi kesalahan.');}
}

// ── Modal: Kategori ────────────────────────────────────────────────────────────
function openKategoriModal(){showOverlay('kategoriOverlay');}
function closeKategoriModal(){hideOverlay('kategoriOverlay');resetKatForm();}
function resetKatForm(){document.getElementById('k_editId').value='';document.getElementById('k_nama').value='';document.getElementById('k_ikon').value='ti-map-pin';document.getElementById('k_warna').value='#3B82F6';document.getElementById('k_warnaHex').value='#3B82F6';updateIkonPreview('ti-map-pin');document.getElementById('katFormTitle').textContent='Tambah Kategori Baru';document.getElementById('katSaveLabel').textContent='Tambah';}
function editKategori(k){document.getElementById('k_editId').value=k.id;document.getElementById('k_nama').value=k.nama;document.getElementById('k_ikon').value=k.ikon;document.getElementById('k_warna').value=k.warna;document.getElementById('k_warnaHex').value=k.warna;updateIkonPreview(k.ikon);document.getElementById('katFormTitle').textContent='Edit Kategori';document.getElementById('katSaveLabel').textContent='Perbarui';}
async function saveKategori(){
  const id=document.getElementById('k_editId').value;const nama=document.getElementById('k_nama').value.trim();
  if(!nama){alert('Nama wajib diisi.');return;}
  try{
    const res=await fetch(id?`${API.kategori}/${id}`:API.kategori,{method:id?'PUT':'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({nama,ikon:document.getElementById('k_ikon').value.trim()||'ti-map-pin',warna:document.getElementById('k_warna').value})});
    const data=await res.json();if(!res.ok){alert(data.message||'Gagal.');return;}
    await loadKategori();await loadLokasi();resetKatForm();
  }catch(e){alert('Terjadi kesalahan.');}
}
async function deleteKategori(id){
  if(!confirm('Hapus kategori ini?'))return;
  try{const res=await fetch(`${API.kategori}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}});const data=await res.json();if(!res.ok){alert(data.message);return;}await loadKategori();await loadLokasi();}catch(e){alert('Terjadi kesalahan.');}
}

// ── Helpers ────────────────────────────────────────────────────────────────────
function showOverlay(id) {
  const el = document.getElementById(id);
  document.body.appendChild(el); // re-append ke akhir body, lepas dari stacking context Leaflet
  el.classList.remove('hidden');
  el.classList.add('flex');
}
function hideOverlay(id) {
  const el = document.getElementById(id);
  el.classList.add('hidden');
  el.classList.remove('flex');
}
function updateIkonPreview(v){document.getElementById('katIkonPreview').className=`ti ${v.trim()||'ti-map-pin'} absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500`;}
function syncColorHex(v){if(/^#[0-9A-Fa-f]{6}$/.test(v))document.getElementById('k_warna').value=v;}
function showErr(el,msg){el.textContent=msg;el.classList.remove('hidden');}
function escHtml(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
</script>
@endsection
