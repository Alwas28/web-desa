@extends('layouts.admin')

@section('title', 'Jenis Bantuan Sosial')
@section('page-title', 'Jenis Bantuan Sosial')
@section('page-sub', 'Kelola jenis-jenis program bantuan sosial yang tersedia')

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

{{-- Toolbar --}}
<div class="flex items-center justify-between mb-4">
  <p class="text-sm text-slate-500 dark:text-slate-400">
    Total <strong class="text-slate-700 dark:text-slate-200">{{ $jenis->count() }}</strong> jenis bansos terdaftar
  </p>
  <button onclick="openModal()" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 transition-colors">
    <i class="ti ti-plus"></i> Tambah Jenis
  </button>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($jenis->isEmpty())
    <div class="py-16 text-center text-slate-400 dark:text-slate-600">
      <i class="ti ti-gift-off text-4xl block mb-2"></i>
      <p class="text-sm">Belum ada jenis bansos. Tambahkan yang pertama.</p>
    </div>
  @else
    <table class="w-full text-sm">
      <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800">
        <tr>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Kode</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Nama Program</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Sumber Dana</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Nilai Bantuan</th>
          <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Penerima</th>
          <th class="text-center px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Status</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($jenis as $j)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3">
            <span class="font-mono text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-2 py-1 rounded">{{ $j->kode }}</span>
          </td>
          <td class="px-4 py-3">
            <div class="font-medium text-slate-800 dark:text-slate-100">{{ $j->nama }}</div>
            @if($j->deskripsi)
              <div class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $j->deskripsi }}</div>
            @endif
          </td>
          <td class="px-4 py-3">
            @php
              $sColor = match($j->sumber_dana) {
                'APBN'   => 'blue',
                'APBD'   => 'violet',
                'APBDes' => 'emerald',
                'Swasta' => 'amber',
                default  => 'slate',
              };
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $sColor }}-50 dark:bg-{{ $sColor }}-500/10 text-{{ $sColor }}-700 dark:text-{{ $sColor }}-400 border border-{{ $sColor }}-200 dark:border-{{ $sColor }}-500/30">
              {{ $j->sumber_dana }}
            </span>
          </td>
          <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
            {{ $j->nilai_bantuan_formatted }}
            @if($j->satuan)
              <span class="text-xs text-slate-400">/ {{ $j->satuan }}</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <a href="{{ route('admin.bansos.index', ['jenis' => $j->id]) }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
              {{ number_format($j->penerima_count) }} orang
            </a>
          </td>
          <td class="px-4 py-3 text-center">
            <form method="POST" action="{{ route('admin.bansos.jenis.toggle', $j) }}">
              @csrf
              <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full transition-colors
                {{ $j->aktif
                  ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 hover:bg-emerald-100'
                  : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200' }}">
                <i class="ti {{ $j->aktif ? 'ti-circle-check' : 'ti-circle-minus' }}"></i>
                {{ $j->aktif ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <button onclick='openEdit(@json($j))' class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
                <i class="ti ti-pencil text-sm"></i>
              </button>
              <form method="POST" action="{{ route('admin.bansos.jenis.destroy', $j) }}" onsubmit="return confirm('Hapus jenis bansos {{ $j->nama }}?')">
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
  @endif
</div>

{{-- Modal Tambah/Edit --}}
<div id="modalOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg mx-4 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h3 id="modalTitle" class="font-semibold text-slate-800 dark:text-slate-100">Tambah Jenis Bansos</h3>
      <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
        <i class="ti ti-x text-lg"></i>
      </button>
    </div>
    <form id="jenisForm" method="POST" class="p-5 space-y-4">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Kode <span class="text-red-500">*</span></label>
          <input type="text" name="kode" id="f_kode" maxlength="30" placeholder="mis. PKH" required
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 uppercase">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Sumber Dana <span class="text-red-500">*</span></label>
          <select name="sumber_dana" id="f_sumber" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            <option value="APBDes">APBDes</option>
            <option value="APBN">APBN</option>
            <option value="APBD">APBD</option>
            <option value="Swasta">Swasta</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nama Program <span class="text-red-500">*</span></label>
        <input type="text" name="nama" id="f_nama" maxlength="150" placeholder="mis. Program Keluarga Harapan" required
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Deskripsi</label>
        <textarea name="deskripsi" id="f_deskripsi" rows="2" placeholder="Keterangan singkat tentang program ini..."
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nilai Bantuan</label>
          <div class="flex rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500/40">
            <span class="flex items-center px-3 bg-slate-50 dark:bg-slate-800/60 border-r border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-sm font-medium select-none">Rp</span>
            <input type="text" id="f_nilai_display" inputmode="numeric" placeholder="0"
              class="flex-1 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none">
          </div>
          <input type="hidden" name="nilai_bantuan" id="f_nilai">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Satuan</label>
          <input type="text" name="satuan" id="f_satuan" maxlength="60" placeholder="mis. per KK/bulan"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
      </div>
      <div class="flex items-center gap-2">
        <input type="hidden" name="aktif" value="0">
        <input type="checkbox" name="aktif" id="f_aktif" value="1" checked class="rounded border-slate-300">
        <label for="f_aktif" class="text-sm text-slate-600 dark:text-slate-400">Status Aktif</label>
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
function formatRupiah(val) {
  const num = String(val).replace(/\D/g, '');
  if (!num) return '';
  return parseInt(num).toLocaleString('id-ID');
}

function setNilai(raw) {
  // parseFloat dulu agar "600000.00" dari DB tidak salah ketika . dihapus
  const num = raw !== '' && raw !== null ? Math.round(parseFloat(raw)) : '';
  document.getElementById('f_nilai').value         = num !== '' ? num : '';
  document.getElementById('f_nilai_display').value = num ? num.toLocaleString('id-ID') : '';
}

document.getElementById('f_nilai_display').addEventListener('input', function() {
  const raw = this.value.replace(/\D/g, '');
  document.getElementById('f_nilai').value = raw;
  const pos = this.selectionStart;
  const prevLen = this.value.length;
  this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
  // Pertahankan posisi kursor relatif
  const diff = this.value.length - prevLen;
  this.setSelectionRange(pos + diff, pos + diff);
});

function openModal() {
  document.getElementById('modalTitle').textContent = 'Tambah Jenis Bansos';
  document.getElementById('jenisForm').action = '{{ route('admin.bansos.jenis.store') }}';
  document.getElementById('formMethod').value = 'POST';
  ['kode','nama','deskripsi','satuan'].forEach(k => {
    const el = document.getElementById('f_' + k);
    if (el) el.value = '';
  });
  setNilai('');
  document.getElementById('f_sumber').value = 'APBDes';
  document.getElementById('f_aktif').checked = true;
  document.getElementById('modalOverlay').classList.remove('hidden');
  document.getElementById('modalOverlay').classList.add('flex');
}

function openEdit(data) {
  document.getElementById('modalTitle').textContent = 'Edit Jenis Bansos';
  const form = document.getElementById('jenisForm');
  form.action = `/admin/bansos/jenis/${data.id}`;
  document.getElementById('formMethod').value = 'PUT';
  document.getElementById('f_kode').value        = data.kode || '';
  document.getElementById('f_nama').value        = data.nama || '';
  document.getElementById('f_deskripsi').value   = data.deskripsi || '';
  document.getElementById('f_sumber').value      = data.sumber_dana || 'APBDes';
  setNilai(data.nilai_bantuan || '');
  document.getElementById('f_satuan').value      = data.satuan || '';
  document.getElementById('f_aktif').checked     = !!data.aktif;
  document.getElementById('modalOverlay').classList.remove('hidden');
  document.getElementById('modalOverlay').classList.add('flex');
}

function closeModal() {
  document.getElementById('modalOverlay').classList.add('hidden');
  document.getElementById('modalOverlay').classList.remove('flex');
}

document.getElementById('f_kode').addEventListener('input', function() {
  this.value = this.value.toUpperCase();
});

// Auto close flash
setTimeout(() => {
  const m = document.getElementById('flashMsg');
  if (m) m.style.display = 'none';
}, 4000);
</script>
@endsection
