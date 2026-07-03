@extends('layouts.admin')

@section('title', 'Produk Pasar Desa')
@section('page-title', 'Produk Pasar Desa')
@section('page-sub', 'Daftar produk yang diunggah warga terverifikasi')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-3">
  @foreach([
    ['label'=>'Total Produk', 'val'=>$totalAktif + $totalNonaktif, 'icon'=>'ti-shopping-bag', 'color'=>'amber'],
    ['label'=>'Aktif',        'val'=>$totalAktif,                   'icon'=>'ti-circle-check', 'color'=>'emerald'],
    ['label'=>'Nonaktif',     'val'=>$totalNonaktif,                'icon'=>'ti-circle-minus',  'color'=>'slate'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-lg text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Filter --}}
<form method="GET" class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>
  <select name="status" onchange="this.form.submit()"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Status</option>
    <option value="aktif"    {{ request('status')==='aktif'    ? 'selected':'' }}>Aktif</option>
    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
  </select>
  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-search text-sm"></i>
  </button>
  @if(request()->hasAny(['q','status']))
  <a href="{{ route('admin.produk.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
</form>

{{-- Tabel --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($produks->isEmpty())
  <div class="py-16 text-center text-slate-400">
    <i class="ti ti-shopping-bag text-4xl block mb-3"></i>
    <p class="font-medium">Belum ada produk yang diunggah</p>
    <p class="text-xs mt-1">Produk akan muncul setelah warga terverifikasi mengunggahnya</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60">
          <th class="px-4 py-3 w-14"></th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Produk</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Penjual</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Harga</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">WhatsApp</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Tanggal</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Status</th>
          <th class="px-4 py-3 w-10"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($produks as $p)
        @php
          $previewData = json_encode([
            'nama'        => $p->nama,
            'foto_url'    => $p->foto_url,
            'harga_format'=> $p->harga_format,
            'satuan'      => $p->satuan,
            'deskripsi'   => $p->deskripsi,
            'nomor_wa'    => $p->nomor_wa,
            'nomor_wa_link'=> $p->nomor_wa_link,
            'penjual'     => $p->penduduk?->nama_lengkap ?? $p->user->name,
            'nik'         => $p->penduduk?->nik ?? '-',
            'status'      => $p->status,
            'tanggal'     => $p->created_at->locale('id')->translatedFormat('d M Y'),
          ]);
        @endphp
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3">
            @if($p->foto_url)
            <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}"
              class="w-12 h-12 rounded-lg object-cover border border-slate-200 dark:border-slate-700 cursor-pointer hover:opacity-80 transition-opacity"
              onclick='openPreview({{ $p->id }})'>
            @else
            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center border border-slate-200 dark:border-slate-700">
              <i class="ti ti-photo text-slate-300 text-lg"></i>
            </div>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $p->nama }}</div>
            @if($p->deskripsi)
            <div class="text-xs text-slate-400 mt-0.5 truncate max-w-[200px]">{{ $p->deskripsi }}</div>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
              {{ $p->penduduk?->nama_lengkap ?? $p->user->name }}
            </div>
            <div class="text-xs text-slate-400 font-mono">{{ $p->penduduk?->nik ?? '-' }}</div>
          </td>
          <td class="px-4 py-3 whitespace-nowrap">
            <div class="font-bold text-amber-600 dark:text-amber-400">{{ $p->harga_format }}</div>
            <div class="text-xs text-slate-400">/ {{ $p->satuan }}</div>
          </td>
          <td class="px-4 py-3">
            <a href="{{ $p->nomor_wa_link }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-medium hover:underline">
              <i class="ti ti-brand-whatsapp"></i> {{ $p->nomor_wa }}
            </a>
          </td>
          <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
            {{ $p->created_at->locale('id')->translatedFormat('d M Y') }}
          </td>
          <td class="px-4 py-3">
            @if($p->status === 'aktif')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
              <i class="ti ti-circle-check"></i> Aktif
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600">
              <i class="ti ti-circle-minus"></i> Nonaktif
            </span>
            @endif
          </td>
          <td class="px-4 py-3">
            <button onclick='openPreview({{ $p->id }})'
              class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition-colors"
              title="Preview">
              <i class="ti ti-eye text-base"></i>
            </button>
          </td>
        </tr>
        {{-- Data produk untuk JS (tersembunyi) --}}
        <script>window._produk = window._produk || {}; window._produk[{{ $p->id }}] = {!! $previewData !!};</script>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($produks->hasPages())
  <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800">
    {{ $produks->links() }}
  </div>
  @endif
  @endif
</div>

{{-- Modal Preview Produk --}}
<div id="modalPreview" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closePreview()">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

      {{-- Foto produk --}}
      <div id="pv_foto_wrap" class="relative" style="display:none">
        <img id="pv_foto" src="" alt=""
          class="w-full h-56 object-cover block">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <button onclick="closePreview()"
          class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 text-white border-none cursor-pointer flex items-center justify-center hover:bg-black/60 transition-colors">
          <i class="ti ti-x text-sm"></i>
        </button>
        <div class="absolute bottom-3 left-4 right-4">
          <h2 id="pv_nama" class="text-white font-bold text-lg leading-tight"></h2>
          <div id="pv_status_wrap" class="mt-1"></div>
        </div>
      </div>

      {{-- Placeholder tanpa foto --}}
      <div id="pv_no_foto" style="display:none" class="relative bg-amber-50 dark:bg-amber-500/10 h-32 flex items-center justify-center">
        <i class="ti ti-shopping-bag text-5xl text-amber-200 dark:text-amber-500/30"></i>
        <button onclick="closePreview()"
          class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/10 dark:bg-white/10 text-slate-600 dark:text-slate-400 border-none cursor-pointer flex items-center justify-center hover:bg-black/20 transition-colors">
          <i class="ti ti-x text-sm"></i>
        </button>
        <div class="absolute bottom-3 left-4">
          <h2 id="pv_nama2" class="text-slate-800 dark:text-slate-100 font-bold text-lg"></h2>
        </div>
      </div>

      {{-- Detail --}}
      <div class="p-5 space-y-4">

        {{-- Harga --}}
        <div class="flex items-end gap-2">
          <span id="pv_harga" class="text-2xl font-extrabold text-amber-500"></span>
          <span class="text-sm text-slate-400 mb-0.5">/ <span id="pv_satuan"></span></span>
        </div>

        {{-- Deskripsi --}}
        <div id="pv_deskripsi_wrap">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Deskripsi</p>
          <p id="pv_deskripsi" class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"></p>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-3">
          {{-- Penjual --}}
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center flex-shrink-0">
              <i class="ti ti-user text-slate-400 text-sm"></i>
            </div>
            <div>
              <p class="text-xs text-slate-400">Penjual</p>
              <p id="pv_penjual" class="text-sm font-semibold text-slate-800 dark:text-slate-200"></p>
              <p id="pv_nik" class="text-xs text-slate-400 font-mono"></p>
            </div>
          </div>
          {{-- WhatsApp --}}
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
              <i class="ti ti-brand-whatsapp text-emerald-500 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-xs text-slate-400">WhatsApp</p>
              <a id="pv_wa" href="#" target="_blank"
                class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:underline"></a>
            </div>
          </div>
          {{-- Tanggal --}}
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center flex-shrink-0">
              <i class="ti ti-calendar text-slate-400 text-sm"></i>
            </div>
            <div>
              <p class="text-xs text-slate-400">Diunggah</p>
              <p id="pv_tanggal" class="text-sm font-semibold text-slate-800 dark:text-slate-200"></p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openPreview(id) {
  const d = window._produk[id];
  if (!d) return;

  // Foto
  if (d.foto_url) {
    document.getElementById('pv_foto').src = d.foto_url;
    document.getElementById('pv_foto_wrap').style.display = '';
    document.getElementById('pv_no_foto').style.display   = 'none';
    document.getElementById('pv_nama').textContent = d.nama;
  } else {
    document.getElementById('pv_foto_wrap').style.display = 'none';
    document.getElementById('pv_no_foto').style.display   = 'flex';
    document.getElementById('pv_nama2').textContent = d.nama;
  }

  // Status badge
  const isAktif = d.status === 'aktif';
  document.getElementById('pv_status_wrap').innerHTML = `
    <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:99px;
                 font-size:11px;font-weight:700;background:${isAktif ? 'rgba(16,185,129,.2)' : 'rgba(148,163,184,.2)'};
                 color:${isAktif ? '#6ee7b7' : '#cbd5e1'};border:1px solid ${isAktif ? 'rgba(16,185,129,.4)' : 'rgba(148,163,184,.3)'}">
      <i class="ti ${isAktif ? 'ti-circle-check' : 'ti-circle-minus'}"></i>
      ${isAktif ? 'Aktif' : 'Nonaktif'}
    </span>`;

  // Detail
  document.getElementById('pv_harga').textContent   = d.harga_format;
  document.getElementById('pv_satuan').textContent  = d.satuan;
  document.getElementById('pv_penjual').textContent = d.penjual;
  document.getElementById('pv_nik').textContent     = d.nik;

  const waEl = document.getElementById('pv_wa');
  waEl.textContent = d.nomor_wa;
  waEl.href        = d.nomor_wa_link;

  document.getElementById('pv_tanggal').textContent = d.tanggal;

  // Deskripsi
  const deskWrap = document.getElementById('pv_deskripsi_wrap');
  if (d.deskripsi) {
    document.getElementById('pv_deskripsi').textContent = d.deskripsi;
    deskWrap.style.display = '';
  } else {
    deskWrap.style.display = 'none';
  }

  document.getElementById('modalPreview').style.display = 'block';
}

function closePreview() {
  document.getElementById('modalPreview').style.display = 'none';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });
</script>
@endsection
