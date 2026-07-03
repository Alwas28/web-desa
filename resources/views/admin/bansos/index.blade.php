@extends('layouts.admin')

@section('title', 'Data Penerima Bansos')
@section('page-title', 'Bantuan Sosial')
@section('page-sub', 'Pendataan dan penyaluran bantuan sosial kepada warga')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div id="flashMsg" class="mb-4 flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
  <i class="ti ti-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div id="flashMsg" class="mb-4 flex items-center gap-2 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
  <i class="ti ti-alert-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-4 gap-3 mb-5">
  @foreach([
    ['label'=>'Total Penerima', 'val'=>$stats['total'],    'icon'=>'ti-users',         'color'=>'blue'],
    ['label'=>'Aktif',          'val'=>$stats['aktif'],    'icon'=>'ti-circle-check',  'color'=>'emerald'],
    ['label'=>'Menunggu',       'val'=>$stats['menunggu'], 'icon'=>'ti-clock',         'color'=>'amber'],
    ['label'=>'Nonaktif',       'val'=>$stats['nonaktif'], 'icon'=>'ti-circle-minus',  'color'=>'slate'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($s['val']) }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Filter Bar --}}
<form method="GET" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 mb-4 flex flex-wrap items-end gap-3">
  <div class="flex-1 min-w-40">
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Cari Nama / NIK</label>
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama atau NIK..."
      class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
  </div>
  <div>
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Jenis Bansos</label>
    <select name="jenis" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      <option value="">Semua Jenis</option>
      @foreach($jenisList as $j)
        <option value="{{ $j->id }}" @selected(request('jenis') == $j->id)>{{ $j->nama }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Status</label>
    <select name="status" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      <option value="">Semua Status</option>
      <option value="aktif" @selected(request('status')=='aktif')>Aktif</option>
      <option value="menunggu" @selected(request('status')=='menunggu')>Menunggu</option>
      <option value="nonaktif" @selected(request('status')=='nonaktif')>Nonaktif</option>
    </select>
  </div>
  <div>
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Tahun</label>
    <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="{{ date('Y') }}" min="2000" max="2100"
      class="w-24 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
  </div>
  <div class="flex gap-2">
    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
      <i class="ti ti-search"></i> Cari
    </button>
    @if(request()->hasAny(['cari','jenis','status','tahun']))
      <a href="{{ route('admin.bansos.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-x"></i>
      </a>
    @endif
  </div>
  <div class="ml-auto">
    <button type="button" onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
      <i class="ti ti-user-plus"></i> Tambah Penerima
    </button>
  </div>
</form>

{{-- Table --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($penerima->isEmpty())
    <div class="py-16 text-center text-slate-400 dark:text-slate-600">
      <i class="ti ti-users-group text-4xl block mb-2"></i>
      <p class="text-sm">Belum ada data penerima bansos.</p>
      @if(request()->hasAny(['cari','jenis','status','tahun']))
        <a href="{{ route('admin.bansos.index') }}" class="text-blue-500 hover:underline text-xs mt-1 block">Hapus filter</a>
      @endif
    </div>
  @else
    <table class="w-full text-sm">
      <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800">
        <tr>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Nama Penerima</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">NIK</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Jenis Bansos</th>
          <th class="text-center px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Penerimaan</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Tahun</th>
          <th class="text-center px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Status</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($penerima as $p)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3">
            <div class="font-medium text-slate-800 dark:text-slate-100">{{ $p->nama }}</div>
            @if($p->alamat)
              <div class="text-xs text-slate-400 mt-0.5">
                {{ $p->alamat }}
                @if($p->rt && $p->rw)RT {{ $p->rt }}/RW {{ $p->rw }}@endif
              </div>
            @endif
          </td>
          <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $p->nik ?: '-' }}</td>
          <td class="px-4 py-3">
            <span class="text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 px-2 py-0.5 rounded-full">
              {{ $p->jenisBansos->nama ?? '-' }}
            </span>
          </td>
          <td class="px-4 py-3 text-center">
            @if($p->jenis_penerimaan === 'non_tunai')
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400 border border-violet-200 dark:border-violet-500/30">
                <i class="ti ti-credit-card text-xs"></i> Non Tunai
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                <i class="ti ti-cash text-xs"></i> Tunai
              </span>
            @endif
          </td>
          <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $p->tahun_ditetapkan ?: '-' }}</td>
          <td class="px-4 py-3 text-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
              {{ $p->status === 'aktif' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : '' }}
              {{ $p->status === 'menunggu' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30' : '' }}
              {{ $p->status === 'nonaktif' ? 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700' : '' }}">
              {{ $p->status_label }}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <a href="{{ route('admin.bansos.show', $p) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                <i class="ti ti-eye"></i> Detail
              </a>
              <button onclick='openEdit(@json($p))' class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
                <i class="ti ti-pencil text-sm"></i>
              </button>
              <form method="POST" action="{{ route('admin.bansos.destroy', $p) }}" onsubmit="return confirm('Hapus data penerima {{ $p->nama }}?')">
                @csrf @method('DELETE')
                <button class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Hapus">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{-- Pagination --}}
    @if($penerima->hasPages())
    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800">
      {{ $penerima->links() }}
    </div>
    @endif
  @endif
</div>

{{-- Modal Tambah/Edit Penerima --}}
<div id="modalOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 border border-slate-200 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900 z-10">
      <h3 id="modalTitle" class="font-semibold text-slate-800 dark:text-slate-100">Tambah Penerima Bansos</h3>
      <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
        <i class="ti ti-x text-lg"></i>
      </button>
    </div>
    <form id="penerimaForm" method="POST" class="p-5 space-y-4">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <input type="hidden" name="penduduk_id" id="f_penduduk_id">

      {{-- Cari dari data penduduk --}}
      <div class="bg-blue-50 dark:bg-blue-500/10 rounded-xl border border-blue-200 dark:border-blue-500/30 p-3">
        <p class="text-xs text-blue-700 dark:text-blue-400 mb-2 font-medium">Isi otomatis dari data penduduk (opsional)</p>
        <div class="flex gap-2">
          <input type="text" id="cariPenduduk" placeholder="Ketik NIK atau nama penduduk..." oninput="searchPenduduk(this.value)"
            class="flex-1 rounded-xl border border-blue-200 dark:border-blue-500/30 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div id="pendudukResults" class="mt-2 hidden divide-y divide-slate-100 dark:divide-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden max-h-36 overflow-y-auto"></div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Jenis Bansos <span class="text-red-500">*</span></label>
          <select name="jenis_bansos_id" id="f_jenis" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            <option value="">-- Pilih Jenis Bansos --</option>
            @foreach($jenisList as $j)
              <option value="{{ $j->id }}">{{ $j->nama }} ({{ $j->kode }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
          <input type="text" name="nama" id="f_nama" maxlength="150" placeholder="Nama lengkap" required
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">NIK</label>
          <input type="text" name="nik" id="f_nik" maxlength="16" placeholder="16 digit NIK"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">No. KK</label>
          <input type="text" name="no_kk" id="f_no_kk" maxlength="16" placeholder="16 digit No. KK"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
          <textarea name="alamat" id="f_alamat" rows="2" placeholder="Alamat lengkap"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">RT</label>
          <input type="text" name="rt" id="f_rt" maxlength="5" placeholder="001"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">RW</label>
          <input type="text" name="rw" id="f_rw" maxlength="5" placeholder="001"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Tahun Ditetapkan</label>
          <input type="number" name="tahun_ditetapkan" id="f_tahun" min="2000" max="2100" placeholder="{{ date('Y') }}"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Status <span class="text-red-500">*</span></label>
          <select name="status" id="f_status" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            <option value="menunggu">Menunggu Verifikasi</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Jenis Penerimaan <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-3">
            <label class="relative flex items-center gap-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 p-3 cursor-pointer transition-colors has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
              <input type="radio" name="jenis_penerimaan" id="f_tunai" value="tunai" class="accent-emerald-600" checked>
              <div>
                <div class="flex items-center gap-1.5 text-sm font-medium text-slate-700 dark:text-slate-200">
                  <i class="ti ti-cash text-emerald-600 dark:text-emerald-400"></i> Tunai
                </div>
                <div class="text-xs text-slate-400 mt-0.5">Uang tunai langsung</div>
              </div>
            </label>
            <label class="relative flex items-center gap-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 p-3 cursor-pointer transition-colors has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 dark:has-[:checked]:bg-violet-500/10">
              <input type="radio" name="jenis_penerimaan" id="f_non_tunai" value="non_tunai" class="accent-violet-600">
              <div>
                <div class="flex items-center gap-1.5 text-sm font-medium text-slate-700 dark:text-slate-200">
                  <i class="ti ti-credit-card text-violet-600 dark:text-violet-400"></i> Non Tunai
                </div>
                <div class="text-xs text-slate-400 mt-0.5">Transfer / e-wallet</div>
              </div>
            </label>
          </div>
        </div>
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Keterangan</label>
          <textarea name="keterangan" id="f_keterangan" rows="2" placeholder="Catatan tambahan..."
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
let searchTimer;
let _pdkCache = []; // cache hasil pencarian penduduk — hindari JSON di onclick attribute

function openModal() {
  document.getElementById('modalTitle').textContent = 'Tambah Penerima Bansos';
  document.getElementById('penerimaForm').action = '{{ route('admin.bansos.store') }}';
  document.getElementById('formMethod').value = 'POST';
  resetForm();
  showModal();
}

function openEdit(data) {
  document.getElementById('modalTitle').textContent = 'Edit Penerima Bansos';
  document.getElementById('penerimaForm').action = `/admin/bansos/${data.id}`;
  document.getElementById('formMethod').value = 'PUT';
  document.getElementById('f_penduduk_id').value = data.penduduk_id || '';
  document.getElementById('f_jenis').value      = data.jenis_bansos_id || '';
  document.getElementById('f_nama').value       = data.nama || '';
  document.getElementById('f_nik').value        = data.nik || '';
  document.getElementById('f_no_kk').value      = data.no_kk || '';
  document.getElementById('f_alamat').value     = data.alamat || '';
  document.getElementById('f_rt').value         = data.rt || '';
  document.getElementById('f_rw').value         = data.rw || '';
  document.getElementById('f_tahun').value      = data.tahun_ditetapkan || '';
  document.getElementById('f_status').value     = data.status || 'menunggu';
  document.getElementById('f_keterangan').value = data.keterangan || '';
  // set jenis penerimaan
  const jp = data.jenis_penerimaan || 'tunai';
  document.getElementById(jp === 'non_tunai' ? 'f_non_tunai' : 'f_tunai').checked = true;
  document.getElementById('cariPenduduk').value = '';
  document.getElementById('pendudukResults').classList.add('hidden');
  showModal();
}

function resetForm() {
  ['f_penduduk_id','f_nama','f_nik','f_no_kk','f_alamat','f_rt','f_rw','f_tahun','f_keterangan'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('f_jenis').value  = '';
  document.getElementById('f_status').value = 'menunggu';
  document.getElementById('f_tunai').checked = true;
  document.getElementById('cariPenduduk').value = '';
  document.getElementById('pendudukResults').classList.add('hidden');
  _pdkCache = [];
}

function showModal() {
  document.getElementById('modalOverlay').classList.remove('hidden');
  document.getElementById('modalOverlay').classList.add('flex');
}

function closeModal() {
  document.getElementById('modalOverlay').classList.add('hidden');
  document.getElementById('modalOverlay').classList.remove('flex');
}

function searchPenduduk(q) {
  clearTimeout(searchTimer);
  const box = document.getElementById('pendudukResults');
  if (!q || q.length < 2) { box.classList.add('hidden'); _pdkCache = []; return; }
  searchTimer = setTimeout(async () => {
    try {
      const res  = await fetch(`{{ route('admin.bansos.cari-penduduk') }}?q=${encodeURIComponent(q)}`);
      _pdkCache  = await res.json();
      if (!_pdkCache.length) {
        box.innerHTML = '<p class="text-xs text-slate-400 px-3 py-2">Tidak ditemukan</p>';
      } else {
        box.innerHTML = _pdkCache.map((p, i) => `
          <button type="button" onclick="pilihPenduduk(${i})"
            class="w-full text-left px-3 py-2 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">
            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">${escHtml(p.nama)}</div>
            <div class="text-xs text-slate-400">${escHtml(p.nik)} • ${escHtml(p.alamat || '-')}</div>
          </button>`).join('');
      }
      box.classList.remove('hidden');
    } catch(e) { console.error(e); }
  }, 300);
}

function pilihPenduduk(idx) {
  const p = _pdkCache[idx];
  if (!p) return;
  document.getElementById('f_penduduk_id').value = p.id;
  document.getElementById('f_nama').value        = p.nama;
  document.getElementById('f_nik').value         = p.nik;
  document.getElementById('f_alamat').value      = p.alamat || '';
  document.getElementById('cariPenduduk').value  = p.nama + ' — ' + p.nik;
  document.getElementById('pendudukResults').classList.add('hidden');
}

function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Auto close flash
setTimeout(() => { const m = document.getElementById('flashMsg'); if (m) m.style.display = 'none'; }, 4000);
</script>
@endsection
