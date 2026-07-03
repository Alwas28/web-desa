@extends('layouts.admin')

@section('title', 'Indeks Desa Membangun (IDM)')
@section('page-title', 'Indeks Desa Membangun')
@section('page-sub', 'Input & rekam data IDM per tahun — sumber: idm.kemendesa.go.id')

@php
$sectionMeta = [
  'iks' => ['label'=>'IKS — Indeks Ketahanan Sosial',       'icon'=>'ti-users',         'color'=>'blue'],
  'ike' => ['label'=>'IKE — Indeks Ketahanan Ekonomi',      'icon'=>'ti-building-store', 'color'=>'amber'],
  'ikl' => ['label'=>'IKL — Indeks Ketahanan Lingkungan',   'icon'=>'ti-leaf',           'color'=>'emerald'],
];
@endphp

@section('content')

{{-- Flash --}}
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
@if($errors->any())
<div class="px-4 py-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 text-sm space-y-1">
  @foreach($errors->all() as $e)<div class="flex items-center gap-2"><i class="ti ti-alert-circle flex-shrink-0"></i>{{ $e }}</div>@endforeach
</div>
@endif

{{-- ── Kartu Ringkasan Tahun Terakhir ── --}}
@if($latest)
@php
  $sc = match($latest->status_idm) {
    'Mandiri'    => ['bg'=>'bg-emerald-50 dark:bg-emerald-500/10','border'=>'border-emerald-200 dark:border-emerald-500/20','text'=>'text-emerald-700 dark:text-emerald-400','badge'=>'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300'],
    'Maju'       => ['bg'=>'bg-blue-50 dark:bg-blue-500/10',      'border'=>'border-blue-200 dark:border-blue-500/20',      'text'=>'text-blue-700 dark:text-blue-400',    'badge'=>'bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300'],
    'Berkembang' => ['bg'=>'bg-amber-50 dark:bg-amber-500/10',    'border'=>'border-amber-200 dark:border-amber-500/20',    'text'=>'text-amber-700 dark:text-amber-400',  'badge'=>'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300'],
    'Tertinggal' => ['bg'=>'bg-orange-50 dark:bg-orange-500/10',  'border'=>'border-orange-200 dark:border-orange-500/20',  'text'=>'text-orange-700 dark:text-orange-400','badge'=>'bg-orange-100 dark:bg-orange-500/20 text-orange-800 dark:text-orange-300'],
    default      => ['bg'=>'bg-red-50 dark:bg-red-500/10',        'border'=>'border-red-200 dark:border-red-500/20',        'text'=>'text-red-700 dark:text-red-400',       'badge'=>'bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-300'],
  };
  $latestInd = $latest->indikators ?? [];
@endphp
<div class="rounded-2xl border {{ $sc['border'] }} {{ $sc['bg'] }} p-5">
  <div class="flex flex-wrap items-start gap-4">
    <div class="flex-shrink-0 text-center w-28">
      <div class="text-4xl font-black {{ $sc['text'] }}">{{ number_format($latest->skor_idm, 4) }}</div>
      <div class="text-xs text-slate-400 mt-1">Skor IDM {{ $latest->tahun }}</div>
      <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold {{ $sc['badge'] }}">{{ $latest->status_idm }}</span>
    </div>
    <div class="flex-1 min-w-0 grid grid-cols-3 gap-3">
      @foreach(['iks'=>['Ketahanan Sosial','ti-users',$latest->skor_iks],'ike'=>['Ketahanan Ekonomi','ti-building-store',$latest->skor_ike],'ikl'=>['Ketahanan Lingkungan','ti-leaf',$latest->skor_ikl]] as $k=>[$sub,$ico,$val])
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center flex-shrink-0">
            <i class="ti {{ $ico }} text-brand-600 dark:text-brand-400 text-sm"></i>
          </div>
          <div>
            <div class="font-bold text-xs text-slate-500">{{ strtoupper($k) }}</div>
            <div class="text-[10px] text-slate-400">{{ $sub }}</div>
          </div>
        </div>
        <div class="text-xl font-black text-slate-900 dark:text-slate-100">{{ number_format($val, 4) }}</div>
        <div class="mt-2 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
          <div class="h-full rounded-full bg-brand-600 transition-all" style="width:{{ min(100,$val*100) }}%"></div>
        </div>
        <div class="text-[10px] text-slate-400 mt-1">{{ count($latestInd[$k] ?? []) }} indikator</div>
      </div>
      @endforeach
    </div>
    <div class="flex-shrink-0 self-center flex flex-col gap-2">
      <button onclick='openEdit(@json($latest))'
        class="flex items-center gap-2 px-4 h-9 rounded-xl border border-slate-200 dark:border-slate-700 text-sm hover:bg-white dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400">
        <i class="ti ti-pencil text-sm"></i> Edit
      </button>
      @if($latest->indikators)
      <button onclick="openDetail({{ $latest->id }})"
        class="flex items-center gap-2 px-4 h-9 rounded-xl border border-slate-200 dark:border-slate-700 text-sm hover:bg-white dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400">
        <i class="ti ti-list text-sm"></i> Indikator
      </button>
      @endif
    </div>
  </div>
</div>
@else
<div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 p-10 text-center">
  <i class="ti ti-chart-bar text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
  <p class="font-medium text-slate-500">Belum ada data IDM</p>
  <p class="text-xs text-slate-400 mt-1">Import CSV dari idm.kemendesa.go.id atau klik "Tambah Manual"</p>
</div>
@endif

{{-- ── Toolbar ── --}}
<div class="flex flex-wrap items-center justify-between gap-3">
  <h3 class="font-semibold text-slate-800 dark:text-slate-200">Riwayat Data IDM</h3>
  <div class="flex items-center gap-2">
    <form method="POST" action="{{ route('admin.idm.import') }}" enctype="multipart/form-data" id="importForm">
      @csrf
      <label for="importFile" id="importLabel"
        class="flex items-center gap-2 px-4 h-9 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
        <i class="ti ti-file-upload text-base"></i>
        <span id="importLabelText">Import CSV</span>
      </label>
      <input type="file" name="file" id="importFile" accept=".csv,.txt" class="hidden" onchange="submitImport(this)">
    </form>
  </div>
</div>

<div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-xs text-blue-700 dark:text-blue-300">
  <i class="ti ti-info-circle text-base flex-shrink-0 mt-0.5"></i>
  <div>Buka <strong>idm.kemendesa.go.id</strong> → cari desa Anda → klik <strong>Export / Unduh CSV</strong> → lalu klik <strong>Import CSV</strong> di atas.
  Semua 50 indikator akan tersimpan otomatis. Jika tahun sudah ada, data akan diperbarui.</div>
</div>

{{-- ── Tabel Riwayat ── --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($rows->isEmpty())
  <div class="py-14 text-center text-slate-400">
    <i class="ti ti-chart-bar text-4xl block mb-3"></i>
    <p>Belum ada data IDM yang direkam.</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 text-xs text-slate-500 font-semibold">
          <th class="px-4 py-3 text-left">Tahun</th>
          <th class="px-4 py-3 text-center">Skor IDM</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-center">IKS</th>
          <th class="px-4 py-3 text-center">IKE</th>
          <th class="px-4 py-3 text-center">IKL</th>
          <th class="px-4 py-3 text-center">Indikator</th>
          <th class="px-4 py-3 w-28"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
        @php
          $badge = match($row->status_idm) {
            'Mandiri'    => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300',
            'Maju'       => 'bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300',
            'Berkembang' => 'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300',
            'Tertinggal' => 'bg-orange-100 dark:bg-orange-500/20 text-orange-800 dark:text-orange-300',
            default      => 'bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-300',
          };
          $hasInd = !empty($row->indikators);
          $totalInd = $hasInd ? collect($row->indikators)->flatten()->count() : 0;
        @endphp
        {{-- Baris utama --}}
        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100">{{ $row->tahun }}</td>
          <td class="px-4 py-3 text-center font-mono font-bold text-slate-900 dark:text-slate-100">{{ number_format($row->skor_idm, 4) }}</td>
          <td class="px-4 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ $row->status_idm }}</span></td>
          <td class="px-4 py-3 text-center font-mono text-xs text-slate-600 dark:text-slate-400">{{ number_format($row->skor_iks, 4) }}</td>
          <td class="px-4 py-3 text-center font-mono text-xs text-slate-600 dark:text-slate-400">{{ number_format($row->skor_ike, 4) }}</td>
          <td class="px-4 py-3 text-center font-mono text-xs text-slate-600 dark:text-slate-400">{{ number_format($row->skor_ikl, 4) }}</td>
          <td class="px-4 py-3 text-center">
            @if($hasInd)
            <button onclick="openDetail({{ $row->id }})"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-brand-500/20 transition-colors">
              <i class="ti ti-list text-xs"></i> {{ $totalInd }}
            </button>
            @else
            <span class="text-xs text-slate-400">—</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <button onclick='openEdit(@json($row))'
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 transition-colors">
                <i class="ti ti-pencil text-sm"></i>
              </button>
              <form method="POST" action="{{ route('admin.idm.destroy', $row) }}"
                    onsubmit="return confirm('Hapus data IDM tahun {{ $row->tahun }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

{{-- Data store untuk JS --}}
@foreach($rows as $row)
<script>
window._idmData = window._idmData || {};
window._idmData[{{ $row->id }}] = {
  id: {{ $row->id }},
  tahun: {{ $row->tahun }},
  skor_iks: {{ $row->skor_iks }},
  skor_ike: {{ $row->skor_ike }},
  skor_ikl: {{ $row->skor_ikl }},
  skor_idm: {{ $row->skor_idm }},
  status_idm: @json($row->status_idm),
  catatan: @json($row->catatan),
  indikators: @json($row->indikators ?? []),
};
</script>
@endforeach

{{-- ══════════════════════════════════════════════════
     MODAL EDIT (skor saja — detail hanya via import)
══════════════════════════════════════════════════ --}}
<div id="modalIdm" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-10"
       onclick="if(event.target===this)closeModal()">
    <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-500/10 grid place-items-center">
            <i class="ti ti-chart-bar text-brand-600 dark:text-brand-400"></i>
          </div>
          <div>
            <h3 id="modalTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Data IDM</h3>
            <p class="text-xs text-slate-400">Untuk detail 50 indikator, gunakan Import CSV</p>
          </div>
        </div>
        <button onclick="closeModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form id="formIdm" method="POST">
        @csrf
        <span id="methodField"></span>
        <div class="p-6 space-y-5">

          {{-- Preview IDM hasil hitung --}}
          <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
            <div>
              <div class="text-xs text-slate-400 mb-1">Skor IDM (dihitung otomatis)</div>
              <div id="idm_display" class="text-2xl font-black text-slate-900 dark:text-slate-100">—</div>
            </div>
            <div>
              <div class="text-xs text-slate-400 mb-1">Status IDM</div>
              <div id="status_display" class="text-sm font-bold text-slate-700 dark:text-slate-300">—</div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tahun <span class="text-red-500">*</span></label>
            <input type="number" name="tahun" id="f_tahun" required min="2000" max="2099" placeholder="Contoh: 2024"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div class="grid grid-cols-3 gap-4">
            @foreach([['f_skor_iks','skor_iks','IKS','Ketahanan Sosial','blue'],['f_skor_ike','skor_ike','IKE','Ketahanan Ekonomi','amber'],['f_skor_ikl','skor_ikl','IKL','Ketahanan Lingkungan','emerald']] as [$fid,$fname,$lbl,$sub,$clr])
            <div>
              <label class="block text-xs font-semibold text-{{ $clr }}-600 dark:text-{{ $clr }}-400 uppercase tracking-wide mb-1.5">{{ $lbl }} <span class="text-red-500">*</span></label>
              <input type="number" name="{{ $fname }}" id="{{ $fid }}" required step="0.0001" min="0" max="1" placeholder="0.0000"
                oninput="calcIdm()"
                class="w-full px-3 py-2 rounded-lg border border-{{ $clr }}-200 dark:border-{{ $clr }}-700 bg-white dark:bg-slate-800 text-sm font-mono outline-none focus:ring-2 focus:ring-{{ $clr }}-400">
              <div class="text-[10px] text-slate-400 mt-1">{{ $sub }}</div>
            </div>
            @endforeach
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Catatan <span class="font-normal">(opsional)</span></label>
            <textarea name="catatan" id="f_catatan" rows="2" maxlength="1000"
              placeholder="Sumber data atau keterangan tambahan…"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl">
          <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white transition-colors flex items-center gap-2">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL DETAIL INDIKATOR
══════════════════════════════════════════════════ --}}
<div id="modalDetail" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-6"
       onclick="if(event.target===this)closeDetail()">
    <div class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">

      {{-- Header --}}
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-900 rounded-t-2xl z-10">
        <div>
          <h3 id="detailTitle" class="font-bold text-slate-900 dark:text-slate-100">Detail Indikator IDM</h3>
          <p class="text-xs text-slate-400 mt-0.5">50 indikator IDM sesuai idm.kemendesa.go.id</p>
        </div>
        <button onclick="closeDetail()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <div id="detailBody" class="p-6 space-y-6"></div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const BASE = '{{ url('admin/idm') }}/';

const STATUS_THRESHOLDS = [
  [0.8155,'Mandiri'],[0.7072,'Maju'],[0.5989,'Berkembang'],[0.4907,'Tertinggal'],[0,'Sangat Tertinggal']
];
const STATUS_COLORS = {
  'Mandiri':'#065f46','Maju':'#1e40af','Berkembang':'#92400e','Tertinggal':'#9a3412','Sangat Tertinggal':'#7f1d1d'
};

function getStatus(s) {
  for (const [min,lbl] of STATUS_THRESHOLDS) if (s >= min) return lbl;
  return 'Sangat Tertinggal';
}

/* ── Definisi indikator (harus cocok dengan key di JSON) ── */
const IND_DEFS = @json($indikatorDefs);

const SECTION_META = {
  iks: {label:'IKS — Indeks Ketahanan Sosial',      color:'#2563eb', bg:'#eff6ff'},
  ike: {label:'IKE — Indeks Ketahanan Ekonomi',     color:'#d97706', bg:'#fffbeb'},
  ikl: {label:'IKL — Indeks Ketahanan Lingkungan',  color:'#059669', bg:'#ecfdf5'},
};

/* ── Kalkulasi IDM ── */
function calcIdm() {
  const iks = parseFloat(document.getElementById('f_skor_iks').value);
  const ike = parseFloat(document.getElementById('f_skor_ike').value);
  const ikl = parseFloat(document.getElementById('f_skor_ikl').value);
  if (!isNaN(iks) && !isNaN(ike) && !isNaN(ikl)) {
    const idm    = (iks + ike + ikl) / 3;
    const status = getStatus(idm);
    document.getElementById('idm_display').textContent   = idm.toFixed(4);
    document.getElementById('status_display').textContent = status;
    document.getElementById('status_display').style.color = STATUS_COLORS[status] || '';
  } else {
    document.getElementById('idm_display').textContent   = '—';
    document.getElementById('status_display').textContent = '—';
  }
}

/* ── Modal edit ── */
function openModal() {
  document.getElementById('modalTitle').textContent = 'Tambah Data IDM';
  document.getElementById('formIdm').action = '{{ route("admin.idm.store") }}';
  document.getElementById('methodField').innerHTML = '';
  document.getElementById('formIdm').reset();
  document.getElementById('idm_display').textContent   = '—';
  document.getElementById('status_display').textContent = '—';
  document.getElementById('modalIdm').style.display = 'block';
}

function openEdit(data) {
  document.getElementById('modalTitle').textContent = 'Edit Data IDM ' + data.tahun;
  document.getElementById('formIdm').action = BASE + data.id;
  document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('f_tahun').value   = data.tahun;
  document.getElementById('f_skor_iks').value = data.skor_iks;
  document.getElementById('f_skor_ike').value = data.skor_ike;
  document.getElementById('f_skor_ikl').value = data.skor_ikl;
  document.getElementById('f_catatan').value  = data.catatan || '';
  calcIdm();
  document.getElementById('modalIdm').style.display = 'block';
}

function closeModal() {
  document.getElementById('modalIdm').style.display = 'none';
}

/* ── Modal detail indikator ── */
function scoreColor(s) {
  if (s >= 5) return '#15803d';
  if (s >= 4) return '#16a34a';
  if (s >= 3) return '#d97706';
  if (s >= 2) return '#ea580c';
  if (s >= 1) return '#dc2626';
  return '#991b1b';
}

function openDetail(rowId) {
  const data = window._idmData[rowId];
  if (!data) return;

  document.getElementById('detailTitle').textContent = 'Detail Indikator IDM ' + data.tahun;
  const body = document.getElementById('detailBody');
  body.innerHTML = '';

  const ind = data.indikators || {};

  for (const [section, defs] of Object.entries(IND_DEFS)) {
    const meta  = SECTION_META[section];
    const skor  = section === 'iks' ? data.skor_iks : (section === 'ike' ? data.skor_ike : data.skor_ikl);
    const secInd = ind[section] || {};

    const wrap = document.createElement('div');
    wrap.className = 'rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden';

    // Header seksi
    const hdr = document.createElement('div');
    hdr.className = 'flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800';
    hdr.style.background = meta.bg;
    hdr.innerHTML = `
      <div class="font-bold text-sm" style="color:${meta.color}">${meta.label}</div>
      <div class="font-mono font-black text-lg" style="color:${meta.color}">${Number(skor).toFixed(4)}</div>`;
    wrap.appendChild(hdr);

    // Tabel indikator
    const tbl = document.createElement('table');
    tbl.className = 'w-full text-sm';
    tbl.innerHTML = `<thead>
      <tr class="bg-slate-50 dark:bg-slate-800/60 text-xs text-slate-500 font-semibold border-b border-slate-100 dark:border-slate-800">
        <th class="px-3 py-2 w-8 text-center">#</th>
        <th class="px-3 py-2 text-left">Indikator</th>
        <th class="px-3 py-2 w-20 text-center">Skor</th>
        <th class="px-3 py-2 w-32 text-center">Nilai Maks: 5</th>
      </tr></thead>`;
    const tbody = document.createElement('tbody');
    tbody.className = 'divide-y divide-slate-100 dark:divide-slate-800';

    defs.forEach((def, i) => {
      const s   = secInd[def.key];
      const val = (s !== undefined && s !== null) ? s : null;
      const pct = val !== null ? (val / 5 * 100).toFixed(0) : 0;
      const clr = val !== null ? scoreColor(val) : '#94a3b8';
      const tr  = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-3 py-2.5 text-center text-xs text-slate-400">${i + 1}</td>
        <td class="px-3 py-2.5 text-slate-700 dark:text-slate-300">${def.label}</td>
        <td class="px-3 py-2.5 text-center font-mono font-bold text-sm" style="color:${clr}">
          ${val !== null ? val : '—'}
        </td>
        <td class="px-3 py-2.5">
          <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
            <div class="h-full rounded-full transition-all" style="width:${pct}%;background:${clr}"></div>
          </div>
        </td>`;
      tbody.appendChild(tr);
    });

    tbl.appendChild(tbody);
    wrap.appendChild(tbl);
    body.appendChild(wrap);
  }

  document.getElementById('modalDetail').style.display = 'block';
}

function closeDetail() {
  document.getElementById('modalDetail').style.display = 'none';
}

/* ── Import CSV ── */
function submitImport(input) {
  if (!input.files?.[0]) return;
  document.getElementById('importLabelText').textContent = 'Mengimpor…';
  document.getElementById('importLabel').style.opacity = '0.6';
  document.getElementById('importLabel').style.pointerEvents = 'none';
  document.getElementById('importForm').submit();
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeModal(); closeDetail(); }
});
</script>
@endsection
