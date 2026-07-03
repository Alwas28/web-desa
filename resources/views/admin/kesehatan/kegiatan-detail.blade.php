@extends('layouts.admin')
@section('title', 'Detail Kegiatan Posyandu')
@section('page-title', $posyandu->nama)
@section('page-sub', 'Kegiatan ' . $kegiatan->tanggal->translatedFormat('d F Y'))

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="flex items-center gap-2 mb-5 text-sm text-slate-400">
  <a href="{{ route('admin.kesehatan.kegiatan.index') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1">
    <i class="ti ti-clipboard-list text-xs"></i> Kegiatan Posyandu
  </a>
  <i class="ti ti-chevron-right text-xs"></i>
  <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $kegiatan->tanggal->translatedFormat('d F Y') }}</span>
</div>

{{-- ── Info sesi ── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
  @php
    $totalBalita = $rekams->where('kategori','balita')->count();
    $totalBumil  = $rekams->where('kategori','bumil')->count();
    $totalLansia = $rekams->where('kategori','lansia')->count();
    $totalUmum   = $rekams->where('kategori','umum')->count();
  @endphp
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 px-4 py-3">
    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $rekams->count() }}</div>
    <div class="text-xs text-slate-400 mt-0.5">Total Peserta</div>
  </div>
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 px-4 py-3">
    <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">{{ $totalBalita }}</div>
    <div class="text-xs text-slate-400 mt-0.5">Balita</div>
  </div>
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 px-4 py-3">
    <div class="text-2xl font-bold text-pink-500 dark:text-pink-400">{{ $totalBumil }}</div>
    <div class="text-xs text-slate-400 mt-0.5">Ibu Hamil</div>
  </div>
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 px-4 py-3">
    <div class="text-2xl font-bold text-amber-500 dark:text-amber-400">{{ $totalLansia }}</div>
    <div class="text-xs text-slate-400 mt-0.5">Lansia</div>
  </div>
</div>

{{-- ── Tabel peserta ── --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-slate-800">
    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">Daftar Peserta</span>
    @if(Auth::user()->hasPermission('tambah.posyandu'))
    <button type="button" onclick="openModal()"
      class="flex items-center gap-2 px-4 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition-colors">
      <i class="ti ti-user-plus"></i> Tambah Peserta
    </button>
    @endif
  </div>

  @if($rekams->isEmpty())
  <div class="p-16 text-center">
    <i class="ti ti-users text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
    <p class="text-sm text-slate-400">Belum ada peserta yang dicatat.</p>
    @if(Auth::user()->hasPermission('tambah.posyandu'))
    <button type="button" onclick="openModal()" class="mt-3 px-4 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium transition-colors">
      Catat Peserta Pertama
    </button>
    @endif
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
          <th class="px-5 py-3 text-left">Nama</th>
          <th class="px-3 py-3 text-left">Kategori</th>
          <th class="px-3 py-3 text-center">Umur</th>
          <th class="px-3 py-3 text-center">BB (kg)</th>
          <th class="px-3 py-3 text-center">TB (cm)</th>
          <th class="px-3 py-3 text-center">LK / TD</th>
          <th class="px-3 py-3 text-center">Status Gizi</th>
          <th class="px-3 py-3 text-left">Keterangan</th>
          @if(Auth::user()->hasPermission('hapus.posyandu'))
          <th class="px-3 py-3"></th>
          @endif
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
        @foreach($rekams as $r)
        @php $kBadge = ['balita'=>'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400','bumil'=>'bg-pink-100 text-pink-700 dark:bg-pink-500/20 dark:text-pink-400','lansia'=>'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400','umum'=>'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'][$r->kategori]; @endphp
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
          <td class="px-5 py-3">
            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $r->nama }}</div>
            @if($r->tanggal_lahir)<div class="text-xs text-slate-400">{{ $r->tanggal_lahir->format('d/m/Y') }}</div>@endif
          </td>
          <td class="px-3 py-3">
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $kBadge }}">{{ ucfirst($r->kategori) }}</span>
          </td>
          <td class="px-3 py-3 text-center text-slate-600 dark:text-slate-300 text-xs">{{ $r->umur }}</td>
          <td class="px-3 py-3 text-center font-mono text-slate-700 dark:text-slate-300">{{ $r->berat_badan ? number_format($r->berat_badan,1) : '—' }}</td>
          <td class="px-3 py-3 text-center font-mono text-slate-700 dark:text-slate-300">{{ $r->tinggi_badan ? number_format($r->tinggi_badan,1) : '—' }}</td>
          <td class="px-3 py-3 text-center text-xs text-slate-600 dark:text-slate-300">
            @if($r->kategori === 'balita'){{ $r->lingkar_kepala ? number_format($r->lingkar_kepala,1).' cm' : '—' }}
            @else{{ $r->tekanan_darah ?: '—' }}@endif
          </td>
          <td class="px-3 py-3 text-center">
            @if($r->status_gizi)
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $r->badge_status_gizi }}">{{ $r->label_status_gizi }}</span>
            @else<span class="text-slate-300 dark:text-slate-600">—</span>@endif
          </td>
          <td class="px-3 py-3 text-xs text-slate-400 max-w-[140px] truncate">{{ $r->keterangan ?: '' }}</td>
          @if(Auth::user()->hasPermission('hapus.posyandu'))
          <td class="px-3 py-3">
            <form method="POST" action="{{ route('admin.kesehatan.posyandu.kegiatan.rekam.destroy', $r) }}" class="m-0"
              onsubmit="return confirm('Hapus data {{ addslashes($r->nama) }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                <i class="ti ti-trash text-sm"></i>
              </button>
            </form>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

@if(Auth::user()->hasPermission('tambah.posyandu'))
{{-- ── Modal Tambah Peserta ── --}}
<div id="modalPeserta" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Peserta</h3>
      <button onclick="closeModal()" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
      <form method="POST" action="{{ route('admin.kesehatan.posyandu.kegiatan.rekam.store', $kegiatan) }}" class="space-y-4">
        @csrf

        {{-- Kategori card selection --}}
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
            Kategori <span class="text-rose-500">*</span>
          </label>
          <input type="hidden" name="kategori" id="inputKategori">
          <div class="grid grid-cols-2 gap-2">
            <button type="button" id="card_balita" onclick="onKategoriChange('balita')"
              class="kat-card flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-400 transition-all hover:border-slate-300 text-left w-full">
              <i class="ti ti-baby-carriage text-base"></i>
              <span class="text-xs font-semibold">Balita</span>
            </button>
            <button type="button" id="card_bumil" onclick="onKategoriChange('bumil')"
              class="kat-card flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-400 transition-all hover:border-slate-300 text-left w-full">
              <i class="ti ti-heart-plus text-base"></i>
              <span class="text-xs font-semibold">Bumil</span>
            </button>
            <button type="button" id="card_lansia" onclick="onKategoriChange('lansia')"
              class="kat-card flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-400 transition-all hover:border-slate-300 text-left w-full">
              <i class="ti ti-accessibility text-base"></i>
              <span class="text-xs font-semibold">Lansia</span>
            </button>
            <button type="button" id="card_umum" onclick="onKategoriChange('umum')"
              class="kat-card flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-400 transition-all hover:border-slate-300 text-left w-full">
              <i class="ti ti-user text-base"></i>
              <span class="text-xs font-semibold">Umum</span>
            </button>
          </div>
          <p id="kategoriError" style="display:none" class="mt-1.5 text-xs text-rose-500">
            <i class="ti ti-alert-circle text-sm"></i> Kategori wajib dipilih sebelum menyimpan.
          </p>
        </div>

        {{-- Cari dari data terdaftar --}}
        <div id="cariTerdaftarWrap">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari dari Data Terdaftar</label>
          <div class="relative">
            <input type="text" id="searchPeserta" autocomplete="off" placeholder="Ketik nama…"
              oninput="filterPeserta()" onfocus="showDrop()"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 pr-8">
            <i class="ti ti-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
            <div id="dropPeserta"
              class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg"
              style="display:none;max-height:180px;overflow-y:auto"></div>
          </div>
          <p class="text-[10px] text-slate-400 mt-1">Pilih untuk auto-isi, atau isi manual di bawah.</p>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" id="rk_nama" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Nama peserta">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="rk_tgl_lahir" max="{{ date('Y-m-d') }}"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="rk_jk"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">—</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Berat Badan (kg)</label>
            <input type="number" step="0.1" min="0.1" max="999" name="berat_badan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="kg">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tinggi Badan (cm)</label>
            <input type="number" step="0.1" min="1" max="300" name="tinggi_badan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="cm">
          </div>
          <div id="wrapLK">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Lingkar Kepala (cm)</label>
            <input type="number" step="0.1" min="1" max="99" name="lingkar_kepala"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="cm">
          </div>
          <div id="wrapTD" style="display:none">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tekanan Darah</label>
            <input type="text" name="tekanan_darah" maxlength="20"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="120/80">
          </div>
        </div>

        <div id="wrapStatusGizi">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status Gizi</label>
          <select name="status_gizi"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <option value="">— Pilih —</option>
            <option value="normal">Normal</option>
            <option value="kurang">Gizi Kurang</option>
            <option value="buruk">Gizi Buruk</option>
            <option value="lebih">Gizi Lebih</option>
            <option value="stunting">Stunting</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Keterangan</label>
          <input type="text" name="keterangan" maxlength="500"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Catatan tambahan…">
        </div>

        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
            <i class="ti ti-device-floppy mr-1"></i> Simpan
          </button>
          <button type="button" onclick="closeModal()"
            class="px-5 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts')
<style>
.kat-card                { border-color:#e2e8f0; background:#fff;    color:#94a3b8; }
.kat-card.active-balita  { border-color:#38bdf8; background:#f0f9ff; color:#0369a1; }
.kat-card.active-bumil   { border-color:#f472b6; background:#fdf2f8; color:#be185d; }
.kat-card.active-lansia  { border-color:#fbbf24; background:#fffbeb; color:#92400e; }
.kat-card.active-umum    { border-color:#94a3b8; background:#f1f5f9; color:#334155; }
.dark .kat-card               { border-color:#334155; background:#1e293b; color:#64748b; }
.dark .kat-card.active-balita { border-color:#38bdf8; background:rgba(56,189,248,.1); color:#7dd3fc; }
.dark .kat-card.active-bumil  { border-color:#f472b6; background:rgba(244,114,182,.1); color:#f9a8d4; }
.dark .kat-card.active-lansia { border-color:#fbbf24; background:rgba(251,191,36,.1);  color:#fcd34d; }
.dark .kat-card.active-umum   { border-color:#94a3b8; background:#1e293b; color:#cbd5e1; }
</style>
@if(Auth::user()->hasPermission('tambah.posyandu'))
@php
$balitaJs = $balitas->map(fn($b) => [
    'nama'          => $b->nama,
    'tanggal_lahir' => $b->tanggal_lahir?->format('Y-m-d') ?? '',
    'jk'            => $b->jenis_kelamin,
    'kategori'      => 'balita',
]);
$bumilJs = $bumils->map(fn($b) => [
    'nama'          => $b->nama,
    'tanggal_lahir' => $b->tanggal_lahir?->format('Y-m-d') ?? '',
    'jk'            => 'P',
    'kategori'      => 'bumil',
]);
@endphp
<script>
const BALITA_DATA    = @json($balitaJs);
const BUMIL_DATA     = @json($bumilJs);
let   _pesertaResults = [];
let   _kategori       = 'balita';

const modal = document.getElementById('modalPeserta');
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

function openModal() {
  modal.querySelector('form').reset();
  document.getElementById('searchPeserta').value       = '';
  document.getElementById('dropPeserta').style.display = 'none';
  document.getElementById('kategoriError').style.display = 'none';
  document.getElementById('cariTerdaftarWrap').style.display = 'none';
  document.getElementById('wrapLK').style.display            = 'none';
  document.getElementById('wrapTD').style.display            = 'none';
  document.getElementById('wrapStatusGizi').style.display    = 'none';
  _kategori = '';
  modal.style.display = 'flex';
}
function closeModal() { modal.style.display = 'none'; }

function onKategoriChange(val) {
  _kategori = val;
  document.getElementById('inputKategori').value         = val;
  document.getElementById('kategoriError').style.display = 'none';
  document.getElementById('searchPeserta').value         = '';
  document.getElementById('dropPeserta').style.display   = 'none';
  document.getElementById('rk_nama').value               = '';
  document.getElementById('rk_tgl_lahir').value          = '';
  document.getElementById('rk_jk').value                 = '';

  document.querySelectorAll('.kat-card').forEach(c => {
    c.className = c.className.replace(/\bactive-\w+\b/g, '').trim();
  });
  document.getElementById('card_' + val).classList.add('active-' + val);

  const showSearch = val === 'balita' || val === 'bumil';
  document.getElementById('cariTerdaftarWrap').style.display = showSearch ? '' : 'none';
  document.getElementById('wrapLK').style.display            = val === 'balita' ? '' : 'none';
  document.getElementById('wrapTD').style.display            = (val === 'bumil' || val === 'lansia') ? '' : 'none';
  document.getElementById('wrapStatusGizi').style.display    = (val === 'balita' || val === 'bumil') ? '' : 'none';
}

modal.querySelector('form').addEventListener('submit', function(e) {
  if (!_kategori) {
    e.preventDefault();
    const err = document.getElementById('kategoriError');
    err.style.display = 'flex';
    err.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

function filterPeserta() {
  const q    = document.getElementById('searchPeserta').value.toLowerCase().trim();
  const src  = _kategori === 'bumil' ? BUMIL_DATA : BALITA_DATA;
  const drop = document.getElementById('dropPeserta');

  _pesertaResults = q.length < 1 ? [] : src.filter(p => p.nama.toLowerCase().includes(q)).slice(0, 20);

  drop.innerHTML = _pesertaResults.length
    ? _pesertaResults.map((p, i) => `
      <div class="px-3 py-2 cursor-pointer hover:bg-brand-50 dark:hover:bg-brand-500/10 text-sm transition-colors border-b border-slate-100 dark:border-slate-700 last:border-0"
        onmousedown="pilihPeserta(${i})">
        <div class="font-medium text-slate-800 dark:text-slate-100">${p.nama}</div>
        <div class="text-xs text-slate-400">${p.tanggal_lahir || '—'} · ${p.jk === 'L' ? 'Laki-laki' : 'Perempuan'}</div>
      </div>`).join('')
    : `<div class="px-3 py-3 text-sm text-slate-400 text-center">Tidak ditemukan</div>`;

  drop.style.display = q.length >= 1 ? '' : 'none';
}

function showDrop() {
  const el = document.getElementById('dropPeserta');
  if (el && el.innerHTML.trim()) el.style.display = '';
}

function pilihPeserta(i) {
  const p = _pesertaResults[i];
  if (!p) return;
  document.getElementById('rk_nama').value      = p.nama;
  document.getElementById('rk_tgl_lahir').value = p.tanggal_lahir;
  document.getElementById('rk_jk').value        = p.jk;
  document.getElementById('searchPeserta').value = p.nama;
  document.getElementById('dropPeserta').style.display = 'none';
}

document.addEventListener('click', e => {
  if (!e.target.closest('#cariTerdaftarWrap')) {
    const d = document.getElementById('dropPeserta');
    if (d) d.style.display = 'none';
  }
});
</script>
@endif
@endsection
