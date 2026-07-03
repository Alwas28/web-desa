@extends('layouts.admin')

@section('title', 'APBDes')
@section('page-title', 'APBDes')
@section('page-sub', 'Anggaran Pendapatan dan Belanja Desa')

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

{{-- Toolbar --}}
<div class="flex items-center justify-between gap-3">
  <h3 class="font-semibold text-slate-800 dark:text-slate-200">Daftar APBDes</h3>
  <button onclick="openModal()"
    class="flex items-center gap-2 px-4 h-9 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold transition-colors">
    <i class="ti ti-plus"></i> Buat APBDes Baru
  </button>
</div>

{{-- List --}}
@if($rows->isEmpty())
<div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 p-14 text-center">
  <i class="ti ti-cash text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
  <p class="font-medium text-slate-500">Belum ada data APBDes</p>
  <p class="text-xs text-slate-400 mt-1">Klik "Buat APBDes Baru" untuk mulai menginput anggaran desa.</p>
</div>
@else
<div class="space-y-3">
  @foreach($rows as $apb)
  @php
    $tPendA = $apb->pos->where('jenis','pendapatan')->sum('anggaran');
    $tPendR = $apb->pos->where('jenis','pendapatan')->sum('realisasi');
    $tBelA  = $apb->pos->where('jenis','belanja')->sum('anggaran');
    $tBelR  = $apb->pos->where('jenis','belanja')->sum('realisasi');
    $selA   = $tPendA - $tBelA;
    $selR   = $tPendR - $tBelR;
    $pctA   = $tPendA > 0 ? round($tPendR / $tPendA * 100, 1) : 0;
    $pctB   = $tBelA  > 0 ? round($tBelR  / $tBelA  * 100, 1) : 0;
    $isFinal = $apb->status === 'final';
  @endphp
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-cash text-2xl text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div>
          <div class="font-bold text-lg text-slate-900 dark:text-slate-100">APBDes {{ $apb->tahun }}</div>
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
            {{ $isFinal ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300' }}">
            <i class="ti {{ $isFinal ? 'ti-circle-check' : 'ti-pencil' }} text-xs"></i>
            {{ $isFinal ? 'Final' : 'Draft' }}
          </span>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.apbdes.show', $apb) }}"
          class="flex items-center gap-2 px-4 h-9 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
          <i class="ti ti-layout-list text-sm"></i> Kelola
        </a>
        <form id="fDelApb{{ $apb->id }}" method="POST" action="{{ route('admin.apbdes.destroy', $apb) }}">
          @csrf @method('DELETE')
          <button type="button"
            onclick="openConfirm({title:'Hapus APBDes {{ $apb->tahun }}',message:'Semua pos anggaran ({{ $apb->pos->count() }} pos) akan ikut terhapus permanen.',okLabel:'Hapus',onOk:()=>document.getElementById('fDelApb{{ $apb->id }}').submit()})"
            class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors">
            <i class="ti ti-trash text-sm"></i>
          </button>
        </form>
      </div>
    </div>

    @if($apb->pos->isNotEmpty())
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
      {{-- Pendapatan --}}
      <div class="rounded-xl border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-6 h-6 rounded-md bg-emerald-100 dark:bg-emerald-500/20 grid place-items-center">
            <i class="ti ti-arrow-down-circle text-emerald-600 dark:text-emerald-400 text-xs"></i>
          </div>
          <span class="text-xs font-semibold text-slate-500">Pendapatan</span>
        </div>
        <div class="text-sm font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($tPendA, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-0.5">Realisasi {{ $pctA }}%</div>
        <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
          <div class="h-full rounded-full bg-emerald-500 transition-all" style="width:{{ min(100,$pctA) }}%"></div>
        </div>
      </div>
      {{-- Belanja --}}
      <div class="rounded-xl border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-6 h-6 rounded-md bg-blue-100 dark:bg-blue-500/20 grid place-items-center">
            <i class="ti ti-arrow-up-circle text-blue-600 dark:text-blue-400 text-xs"></i>
          </div>
          <span class="text-xs font-semibold text-slate-500">Belanja</span>
        </div>
        <div class="text-sm font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($tBelA, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-0.5">Realisasi {{ $pctB }}%</div>
        <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
          <div class="h-full rounded-full bg-blue-500 transition-all" style="width:{{ min(100,$pctB) }}%"></div>
        </div>
      </div>
      {{-- Surplus/Defisit --}}
      <div class="rounded-xl border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-6 h-6 rounded-md {{ $selA >= 0 ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-red-100 dark:bg-red-500/20' }} grid place-items-center">
            <i class="ti ti-scale text-xs {{ $selA >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}"></i>
          </div>
          <span class="text-xs font-semibold text-slate-500">{{ $selA >= 0 ? 'Surplus' : 'Defisit' }}</span>
        </div>
        <div class="text-sm font-bold {{ $selA >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">
          Rp {{ number_format(abs($selA), 0, ',', '.') }}
        </div>
        <div class="text-xs text-slate-400 mt-0.5">Realisasi: {{ $selR >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selR), 0, ',', '.') }}</div>
      </div>
    </div>
    @else
    <div class="mt-4 text-xs text-slate-400 flex items-center gap-1.5">
      <i class="ti ti-info-circle"></i> Belum ada pos anggaran — klik "Kelola" untuk mulai mengisi.
    </div>
    @endif
  </div>
  @endforeach
</div>
@endif

{{-- Modal Buat APBDes --}}
<div id="modalBuat" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-20"
       onclick="if(event.target===this)closeModal()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 grid place-items-center">
            <i class="ti ti-cash text-emerald-600 dark:text-emerald-400"></i>
          </div>
          <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm">Buat APBDes Baru</h3>
        </div>
        <button onclick="closeModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.apbdes.store') }}">
        @csrf
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tahun Anggaran <span class="text-red-500">*</span></label>
            <input type="number" name="tahun" required min="2000" max="2099"
              placeholder="Contoh: {{ date('Y') }}"
              value="{{ old('tahun') }}"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Catatan <span class="font-normal">(opsional)</span></label>
            <textarea name="catatan" rows="2" maxlength="1000"
              placeholder="Keterangan atau sumber dokumen…"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('catatan') }}</textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl">
          <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white transition-colors flex items-center gap-2">
            <i class="ti ti-plus"></i> Buat
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="modalConfirm" style="display:none"
     class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-start gap-4 mb-5">
      <div id="cIcon" class="w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 grid place-items-center flex-shrink-0">
        <i id="cIconI" class="ti ti-alert-triangle text-xl text-red-600 dark:text-red-400"></i>
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
        class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
      </button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal()  { document.getElementById('modalBuat').style.display = 'block'; }
function closeModal() { document.getElementById('modalBuat').style.display = 'none'; }

let _confirmCb = null;
function openConfirm({ title, message, okLabel = 'Ya', okClass = 'bg-red-600 hover:bg-red-700', onOk }) {
  document.getElementById('cTitle').textContent   = title;
  document.getElementById('cMessage').innerHTML   = message;
  const btn = document.getElementById('cOkBtn');
  btn.textContent = okLabel;
  btn.className   = `px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors ${okClass}`;
  _confirmCb = onOk;
  document.getElementById('modalConfirm').style.display = 'flex';
}
function closeConfirm() { document.getElementById('modalConfirm').style.display = 'none'; _confirmCb = null; }
function doConfirm()    { const cb = _confirmCb; closeConfirm(); if (cb) cb(); }

document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); closeConfirm(); } });
</script>
@if($errors->any())
<script>openModal();</script>
@endif
@endsection
