@extends('layouts.admin')

@section('title', 'APBDes ' . $apbdes->tahun)
@section('page-title', 'APBDes ' . $apbdes->tahun)
@section('page-sub', 'Kelola anggaran pendapatan, belanja, dan pembiayaan')

@php
$rp = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$pct = fn($a, $r) => $a > 0 ? round($r / $a * 100, 1) : 0;

$seksi = [
  'pendapatan' => [
    'label'   => 'A. Pendapatan',
    'icon'    => 'ti-arrow-down-circle',
    'color'   => 'emerald',
    'btnCls'  => 'bg-emerald-600 hover:bg-emerald-700',
    'iconBg'  => 'bg-emerald-100 dark:bg-emerald-500/20',
    'iconTxt' => 'text-emerald-600 dark:text-emerald-400',
    'rowBg'   => 'bg-emerald-50/60 dark:bg-emerald-500/5',
  ],
  'belanja' => [
    'label'   => 'B. Belanja',
    'icon'    => 'ti-arrow-up-circle',
    'color'   => 'blue',
    'btnCls'  => 'bg-blue-600 hover:bg-blue-700',
    'iconBg'  => 'bg-blue-100 dark:bg-blue-500/20',
    'iconTxt' => 'text-blue-600 dark:text-blue-400',
    'rowBg'   => 'bg-blue-50/60 dark:bg-blue-500/5',
  ],
  'pembiayaan' => [
    'label'   => 'C. Pembiayaan',
    'icon'    => 'ti-arrows-exchange',
    'color'   => 'violet',
    'btnCls'  => 'bg-violet-600 hover:bg-violet-700',
    'iconBg'  => 'bg-violet-100 dark:bg-violet-500/20',
    'iconTxt' => 'text-violet-600 dark:text-violet-400',
    'rowBg'   => 'bg-violet-50/60 dark:bg-violet-500/5',
  ],
];

$totA = []; $totR = [];
foreach (array_keys($seksi) as $j) {
  $totA[$j] = $apbdes->pos->where('jenis', $j)->sum('anggaran');
  $totR[$j] = $apbdes->pos->where('jenis', $j)->sum('realisasi');
}
$surplusA = $totA['pendapatan'] - $totA['belanja'];
$surplusR = $totR['pendapatan'] - $totR['belanja'];
$isFinal  = $apbdes->status === 'final';
@endphp

@section('content')

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 text-sm">
  <i class="ti ti-alert-circle flex-shrink-0"></i> {{ session('error') }}
</div>
@endif

{{-- ── Header ── --}}
<div class="flex flex-wrap items-center justify-between gap-3">
  <div class="flex items-center gap-3">
    <a href="{{ route('admin.apbdes.index') }}"
       class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-arrow-left text-sm"></i>
    </a>
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
      {{ $isFinal ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300' }}">
      <i class="ti {{ $isFinal ? 'ti-circle-check' : 'ti-pencil' }} text-xs"></i>
      {{ $isFinal ? 'Final' : 'Draft' }}
    </span>
    @if($apbdes->catatan)
    <span class="text-xs text-slate-400 italic hidden sm:block">{{ $apbdes->catatan }}</span>
    @endif
  </div>
  <div class="flex items-center gap-2">
    <button onclick="openModalHeader()"
      class="flex items-center gap-2 px-4 h-9 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-settings text-sm"></i> Pengaturan
    </button>
    <form id="formStatus" method="POST" action="{{ route('admin.apbdes.update', $apbdes) }}">
      @csrf @method('PUT')
      <input type="hidden" name="tahun"   value="{{ $apbdes->tahun }}">
      <input type="hidden" name="catatan" value="{{ $apbdes->catatan }}">
      <input type="hidden" name="status"  value="{{ $isFinal ? 'draft' : 'final' }}">
      <button type="button" onclick="confirmStatus()"
        class="flex items-center gap-2 px-4 h-9 rounded-xl text-sm font-semibold transition-colors
          {{ $isFinal ? 'border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
        <i class="ti {{ $isFinal ? 'ti-rotate-ccw' : 'ti-circle-check' }} text-sm"></i>
        {{ $isFinal ? 'Ke Draft' : 'Finalisasi' }}
      </button>
    </form>
  </div>
</div>

{{-- ── Kartu Ringkasan ── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
  {{-- Pendapatan --}}
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
    <div class="flex items-center gap-2 mb-2">
      <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
        <i class="ti ti-arrow-down-circle text-emerald-600 dark:text-emerald-400 text-sm"></i>
      </div>
      <span class="text-xs font-semibold text-slate-500">Pendapatan</span>
    </div>
    <div class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $rp($totA['pendapatan']) }}</div>
    <div class="text-xs text-slate-400 mt-1">Realisasi {{ $rp($totR['pendapatan']) }}</div>
    <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
      <div class="h-full rounded-full bg-emerald-500 transition-all" style="width:{{ min(100,$pct($totA['pendapatan'],$totR['pendapatan'])) }}%"></div>
    </div>
    <div class="text-[10px] text-slate-400 mt-1">{{ $pct($totA['pendapatan'],$totR['pendapatan']) }}% terealisasi</div>
  </div>
  {{-- Belanja --}}
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
    <div class="flex items-center gap-2 mb-2">
      <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-500/10 grid place-items-center flex-shrink-0">
        <i class="ti ti-arrow-up-circle text-blue-600 dark:text-blue-400 text-sm"></i>
      </div>
      <span class="text-xs font-semibold text-slate-500">Belanja</span>
    </div>
    <div class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $rp($totA['belanja']) }}</div>
    <div class="text-xs text-slate-400 mt-1">Realisasi {{ $rp($totR['belanja']) }}</div>
    <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
      <div class="h-full rounded-full bg-blue-500 transition-all" style="width:{{ min(100,$pct($totA['belanja'],$totR['belanja'])) }}%"></div>
    </div>
    <div class="text-[10px] text-slate-400 mt-1">{{ $pct($totA['belanja'],$totR['belanja']) }}% terealisasi</div>
  </div>

  {{-- Surplus/Defisit --}}
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
    <div class="flex items-center gap-2 mb-2">
      <div class="w-7 h-7 rounded-lg {{ $surplusA >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/10' : 'bg-red-50 dark:bg-red-500/10' }} grid place-items-center flex-shrink-0">
        <i class="ti ti-scale text-sm {{ $surplusA >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}"></i>
      </div>
      <span class="text-xs font-semibold text-slate-500">{{ $surplusA >= 0 ? 'Surplus' : 'Defisit' }}</span>
    </div>
    <div class="font-bold text-sm {{ $surplusA >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">
      Rp {{ number_format(abs($surplusA), 0, ',', '.') }}
    </div>
    <div class="text-xs text-slate-400 mt-1">Realisasi: {{ $surplusR >= 0 ? '+' : '-' }}Rp {{ number_format(abs($surplusR), 0, ',', '.') }}</div>
  </div>

  {{-- Pembiayaan --}}
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
    <div class="flex items-center gap-2 mb-2">
      <div class="w-7 h-7 rounded-lg bg-violet-50 dark:bg-violet-500/10 grid place-items-center flex-shrink-0">
        <i class="ti ti-arrows-exchange text-violet-600 dark:text-violet-400 text-sm"></i>
      </div>
      <span class="text-xs font-semibold text-slate-500">Pembiayaan</span>
    </div>
    <div class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $rp($totA['pembiayaan']) }}</div>
    <div class="text-xs text-slate-400 mt-1">Realisasi {{ $rp($totR['pembiayaan']) }}</div>
    <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
      <div class="h-full rounded-full bg-violet-500 transition-all" style="width:{{ min(100,$pct($totA['pembiayaan'],$totR['pembiayaan'])) }}%"></div>
    </div>
    <div class="text-[10px] text-slate-400 mt-1">{{ $pct($totA['pembiayaan'],$totR['pembiayaan']) }}% terealisasi</div>
  </div>
</div>

{{-- ══ Tiga Seksi ══ --}}
@foreach($seksi as $jenis => $meta)
@php
  $posJenis = $apbdes->pos->where('jenis', $jenis);
  $grouped  = $posJenis->groupBy('kelompok');
  $ttlA     = $totA[$jenis];
  $ttlR     = $totR[$jenis];
@endphp
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">

  {{-- Seksi header --}}
  <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-lg {{ $meta['iconBg'] }} grid place-items-center">
        <i class="ti {{ $meta['icon'] }} {{ $meta['iconTxt'] }} text-sm"></i>
      </div>
      <span class="font-bold text-slate-900 dark:text-slate-100">{{ $meta['label'] }}</span>
    </div>
    <button onclick="openModalPos('{{ $jenis }}')"
      class="flex items-center gap-1.5 px-3 h-8 rounded-lg {{ $meta['btnCls'] }} text-white text-xs font-semibold transition-colors">
      <i class="ti ti-plus text-xs"></i> Tambah Pos
    </button>
  </div>

  @if($posJenis->isEmpty())
  <div class="py-10 text-center text-slate-400 text-sm">
    <i class="ti ti-file-off text-3xl block mb-2 opacity-40"></i>
    Belum ada pos anggaran di seksi ini.
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-xs font-semibold text-slate-500 border-b border-slate-100 dark:border-slate-800">
          <th class="px-4 py-3 text-left w-1/2">Uraian</th>
          <th class="px-4 py-3 text-right">Anggaran (Rp)</th>
          <th class="px-4 py-3 text-right">Realisasi (Rp)</th>
          <th class="px-4 py-3 text-center w-16">%</th>
          <th class="px-4 py-3 w-20"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($grouped as $kelompok => $items)
        @php
          $kA = $items->sum('anggaran');
          $kR = $items->sum('realisasi');
          $kP = $pct($kA, $kR);
        @endphp
        {{-- Kelompok header --}}
        <tr class="border-b border-slate-100 dark:border-slate-800 {{ $meta['rowBg'] }}">
          <td class="px-4 py-2.5 font-semibold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wide">
            {{ $kelompok }}
          </td>
          <td class="px-4 py-2.5 text-right font-semibold text-slate-700 dark:text-slate-300 text-xs">
            {{ number_format($kA, 0, ',', '.') }}
          </td>
          <td class="px-4 py-2.5 text-right font-semibold text-slate-700 dark:text-slate-300 text-xs">
            {{ number_format($kR, 0, ',', '.') }}
          </td>
          <td class="px-4 py-2.5 text-center">
            <span class="text-xs font-bold {{ $kP >= 90 ? 'text-emerald-600 dark:text-emerald-400' : ($kP >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
              {{ $kP }}%
            </span>
          </td>
          <td></td>
        </tr>
        {{-- Pos rows --}}
        @foreach($items as $pos)
        @php $p = $pct($pos->anggaran, $pos->realisasi); @endphp
        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3 pl-8 text-slate-800 dark:text-slate-200">{{ $pos->uraian }}</td>
          <td class="px-4 py-3 text-right font-mono text-slate-700 dark:text-slate-300">
            {{ number_format($pos->anggaran, 0, ',', '.') }}
          </td>
          <td class="px-4 py-3 text-right font-mono text-slate-700 dark:text-slate-300">
            {{ number_format($pos->realisasi, 0, ',', '.') }}
          </td>
          <td class="px-4 py-3 text-center">
            <div class="flex flex-col items-center gap-1">
              <span class="text-xs font-semibold {{ $p >= 90 ? 'text-emerald-600 dark:text-emerald-400' : ($p >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-500 dark:text-red-400') }}">
                {{ $p }}%
              </span>
              <div class="w-12 h-1 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                <div class="h-full rounded-full transition-all
                  {{ $p >= 90 ? 'bg-emerald-500' : ($p >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                  style="width:{{ min(100,$p) }}%"></div>
              </div>
            </div>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <button onclick='openEditPos(@json($pos))'
                class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 transition-colors">
                <i class="ti ti-pencil text-xs"></i>
              </button>
              <form id="fDelPos{{ $pos->id }}" method="POST" action="{{ route('admin.apbdes.pos.destroy', [$apbdes, $pos]) }}">
                @csrf @method('DELETE')
                <button type="button"
                  onclick="openConfirm({title:'Hapus Pos Anggaran',message:'Pos &ldquo;{{ addslashes($pos->uraian) }}&rdquo; akan dihapus permanen.',okLabel:'Hapus',onOk:()=>document.getElementById('fDelPos{{ $pos->id }}').submit()})"
                  class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors">
                  <i class="ti ti-trash text-xs"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
        @endforeach

        {{-- Total seksi --}}
        <tr class="border-t-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60">
          <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100 uppercase text-xs tracking-wide">
            Total {{ $meta['label'] }}
          </td>
          <td class="px-4 py-3 text-right font-bold text-slate-900 dark:text-slate-100">
            {{ number_format($ttlA, 0, ',', '.') }}
          </td>
          <td class="px-4 py-3 text-right font-bold text-slate-900 dark:text-slate-100">
            {{ number_format($ttlR, 0, ',', '.') }}
          </td>
          <td class="px-4 py-3 text-center">
            @php $tp = $pct($ttlA, $ttlR); @endphp
            <span class="font-bold text-sm {{ $tp >= 90 ? 'text-emerald-600 dark:text-emerald-400' : ($tp >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-500 dark:text-red-400') }}">
              {{ $tp }}%
            </span>
          </td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
  @endif
</div>
@endforeach

{{-- ══ Modal Tambah / Edit Pos ══ --}}
<div id="modalPos" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-10"
       onclick="if(event.target===this)closeModalPos()">
    <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-500/10 grid place-items-center">
            <i class="ti ti-cash text-brand-600 dark:text-brand-400"></i>
          </div>
          <h3 id="modalPosTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Pos Anggaran</h3>
        </div>
        <button onclick="closeModalPos()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form id="formPos" method="POST">
        @csrf
        <span id="methodPos"></span>

        <div class="p-6 space-y-4">

          {{-- Jenis --}}
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jenis <span class="text-red-500">*</span></label>
            <select name="jenis" id="f_jenis" required onchange="onJenisChange(this.value, null)"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="pendapatan">Pendapatan</option>
              <option value="belanja">Belanja</option>
              <option value="pembiayaan">Pembiayaan</option>
            </select>
          </div>

          {{-- Kelompok --}}
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Kelompok <span class="text-red-500">*</span></label>
            <select name="kelompok" id="f_kelompok" required
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            </select>
          </div>

          {{-- Uraian --}}
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Uraian / Nama Pos <span class="text-red-500">*</span></label>
            <input type="text" name="uraian" id="f_uraian" required maxlength="255"
              placeholder="Contoh: Dana Desa Tahap 1, Pembangunan Jalan, dll."
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div class="grid grid-cols-2 gap-4">
            {{-- Anggaran --}}
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Anggaran <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 pointer-events-none select-none">Rp</span>
                <input type="text" id="f_anggaran_disp" inputmode="numeric" placeholder="0"
                  oninput="syncRp(this,'f_anggaran')"
                  class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono outline-none focus:ring-2 focus:ring-brand-500">
                <input type="hidden" name="anggaran" id="f_anggaran" value="0">
              </div>
              <p class="text-[10px] text-slate-400 mt-1">Jumlah yang direncanakan/ditetapkan dalam APBDes.</p>
            </div>
            {{-- Realisasi --}}
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Realisasi <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 pointer-events-none select-none">Rp</span>
                <input type="text" id="f_realisasi_disp" inputmode="numeric" placeholder="0"
                  oninput="syncRp(this,'f_realisasi')"
                  class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono outline-none focus:ring-2 focus:ring-brand-500">
                <input type="hidden" name="realisasi" id="f_realisasi" value="0">
              </div>
              <p class="text-[10px] text-slate-400 mt-1">Jumlah yang sudah benar-benar diterima/dibelanjakan. Isi 0 jika belum ada.</p>
            </div>
          </div>

        </div>

        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl">
          <button type="button" onclick="closeModalPos()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white transition-colors flex items-center gap-2">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ══ Modal Pengaturan Header ══ --}}
<div id="modalHeader" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-20"
       onclick="if(event.target===this)closeModalHeader()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm">Pengaturan APBDes</h3>
        <button onclick="closeModalHeader()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.apbdes.update', $apbdes) }}">
        @csrf @method('PUT')
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tahun Anggaran</label>
            <input type="number" name="tahun" required min="2000" max="2099"
              value="{{ $apbdes->tahun }}"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
            <select name="status"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="draft"  {{ !$isFinal ? 'selected' : '' }}>Draft</option>
              <option value="final"  {{ $isFinal  ? 'selected' : '' }}>Final</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Catatan</label>
            <textarea name="catatan" rows="2" maxlength="1000"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ $apbdes->catatan }}</textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl">
          <button type="button" onclick="closeModalHeader()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white flex items-center gap-2">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ══ Modal Konfirmasi ══ --}}
<div id="modalConfirm" style="display:none"
     class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-start gap-4 mb-5">
      <div id="cIcon" class="w-11 h-11 rounded-xl grid place-items-center flex-shrink-0">
        <i id="cIconI" class="ti text-xl"></i>
      </div>
      <div>
        <h3 id="cTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm"></h3>
        <p id="cMessage" class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed"></p>
      </div>
    </div>
    <div class="flex gap-2 justify-end">
      <button onclick="closeConfirm()"
        class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        Batal
      </button>
      <button id="cOkBtn" onclick="doConfirm()"
        class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors">
      </button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route("admin.apbdes.pos.store", $apbdes) }}';
const UPDATE_BASE = '{{ url("admin/apbdes/" . $apbdes->id . "/pos") }}/';

const KELOMPOK_OPTIONS = @json($kelompokOptions);

/* ── Rupiah mask ── */
function syncRp(el, hiddenId) {
  const raw = el.value.replace(/\D/g, '').slice(0, 15);
  const num = parseInt(raw, 10) || 0;
  el.value = num > 0 ? num.toLocaleString('id-ID') : '';
  document.getElementById(hiddenId).value = num;
}

function setRpDisp(dispId, hiddenId, value) {
  const num = Math.round(parseFloat(value) || 0);
  document.getElementById(dispId).value  = num > 0 ? num.toLocaleString('id-ID') : '';
  document.getElementById(hiddenId).value = num;
}

/* ── Kelompok select ── */
function onJenisChange(jenis, selectedVal) {
  const sel = document.getElementById('f_kelompok');
  sel.innerHTML = '<option value="">— Pilih Kelompok —</option>';
  (KELOMPOK_OPTIONS[jenis] || []).forEach(opt => {
    const o = document.createElement('option');
    o.value = opt;
    o.textContent = opt;
    if (opt === selectedVal) o.selected = true;
    sel.appendChild(o);
  });
}

/* ── Modal pos ── */
function openModalPos(jenis) {
  document.getElementById('modalPosTitle').textContent = 'Tambah Pos Anggaran';
  document.getElementById('formPos').action = STORE_URL;
  document.getElementById('methodPos').innerHTML = '';
  document.getElementById('f_uraian').value = '';
  document.getElementById('f_jenis').value  = jenis || 'pendapatan';
  onJenisChange(jenis || 'pendapatan', null);
  setRpDisp('f_anggaran_disp', 'f_anggaran', 0);
  setRpDisp('f_realisasi_disp', 'f_realisasi', 0);
  document.getElementById('modalPos').style.display = 'block';
}

function openEditPos(pos) {
  document.getElementById('modalPosTitle').textContent = 'Edit Pos Anggaran';
  document.getElementById('formPos').action = UPDATE_BASE + pos.id;
  document.getElementById('methodPos').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('f_jenis').value  = pos.jenis;
  onJenisChange(pos.jenis, pos.kelompok);
  document.getElementById('f_uraian').value = pos.uraian;
  setRpDisp('f_anggaran_disp',  'f_anggaran',  pos.anggaran);
  setRpDisp('f_realisasi_disp', 'f_realisasi', pos.realisasi);
  document.getElementById('modalPos').style.display = 'block';
}

function closeModalPos()    { document.getElementById('modalPos').style.display    = 'none'; }
function openModalHeader()  { document.getElementById('modalHeader').style.display = 'block'; }
function closeModalHeader() { document.getElementById('modalHeader').style.display = 'none'; }

/* ── Modal konfirmasi ── */
let _confirmCb = null;

function openConfirm({ title, message, okLabel = 'Ya', okClass = 'bg-red-600 hover:bg-red-700',
                       icon = 'ti-alert-triangle', iconBg = 'bg-red-100 dark:bg-red-500/10',
                       iconColor = 'text-red-600 dark:text-red-400', onOk }) {
  document.getElementById('cTitle').textContent   = title;
  document.getElementById('cMessage').innerHTML   = message;
  const btn = document.getElementById('cOkBtn');
  btn.textContent = okLabel;
  btn.className   = `px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors ${okClass}`;
  document.getElementById('cIcon').className  = `w-11 h-11 rounded-xl grid place-items-center flex-shrink-0 ${iconBg}`;
  document.getElementById('cIconI').className = `ti ${icon} text-xl ${iconColor}`;
  _confirmCb = onOk;
  document.getElementById('modalConfirm').style.display = 'flex';
}

function closeConfirm() {
  document.getElementById('modalConfirm').style.display = 'none';
  _confirmCb = null;
}

function doConfirm() {
  const cb = _confirmCb;
  closeConfirm();
  if (cb) cb();
}

/* ── Finalisasi / ke-draft confirm ── */
function confirmStatus() {
  @if($isFinal)
  openConfirm({
    title: 'Kembalikan ke Draft',
    message: 'APBDes {{ $apbdes->tahun }} akan dikembalikan ke status <strong>Draft</strong> dan dapat diedit kembali.',
    okLabel: 'Ya, ke Draft',
    okClass: 'bg-amber-600 hover:bg-amber-700',
    icon: 'ti-rotate-ccw',
    iconBg: 'bg-amber-100 dark:bg-amber-500/10',
    iconColor: 'text-amber-600 dark:text-amber-400',
    onOk: () => document.getElementById('formStatus').submit(),
  });
  @else
  openConfirm({
    title: 'Finalisasi APBDes {{ $apbdes->tahun }}',
    message: 'APBDes akan diubah ke status <strong>Final</strong>. Pastikan semua data sudah benar sebelum finalisasi.',
    okLabel: 'Finalisasi',
    okClass: 'bg-emerald-600 hover:bg-emerald-700',
    icon: 'ti-circle-check',
    iconBg: 'bg-emerald-100 dark:bg-emerald-500/10',
    iconColor: 'text-emerald-600 dark:text-emerald-400',
    onOk: () => document.getElementById('formStatus').submit(),
  });
  @endif
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeModalPos(); closeModalHeader(); closeConfirm(); }
});
</script>
@endsection
