@extends('layouts.admin')
@section('title', 'Data Posyandu')
@section('page-title', 'Posyandu')
@section('page-sub', 'Master data pos pelayanan terpadu desa')

@section('content')

{{-- ── Toolbar ── --}}
<div class="flex items-center justify-between mb-4">
  <span class="text-sm text-slate-400">{{ $posyandus->count() }} posyandu terdaftar</span>
  @if(Auth::user()->hasPermission('tambah.posyandu'))
  <button type="button" onclick="openModal()"
    class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus"></i> Tambah Posyandu
  </button>
  @endif
</div>

{{-- ── Daftar ── --}}
<div class="space-y-3">
  @forelse($posyandus as $p)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-3 flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl grid place-items-center
          {{ $p->aktif ? 'bg-emerald-50 dark:bg-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800' }}">
          <i class="ti ti-building-hospital text-lg {{ $p->aktif ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}"></i>
        </div>
        <div class="min-w-0 flex-1">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $p->nama }}</span>
            @if($p->aktif)
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400">Aktif</span>
            @else
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500">Nonaktif</span>
            @endif
          </div>
          <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
            @if($p->dusun)
            <span class="flex items-center gap-1"><i class="ti ti-map-pin text-xs"></i> {{ $p->dusun }}</span>
            @endif
            @if($p->kader)
            <span class="flex items-center gap-1"><i class="ti ti-user text-xs"></i> {{ $p->kader }}</span>
            @endif
            @if($p->jadwal)
            <span class="flex items-center gap-1"><i class="ti ti-calendar text-xs"></i> {{ $p->jadwal }}</span>
            @endif
          </div>
          @if($p->lokasi)
          <p class="text-xs text-slate-400 mt-1">{{ $p->lokasi }}</p>
          @endif
        </div>
      </div>

      <div class="flex items-center gap-2 flex-shrink-0">
        @if(Auth::user()->hasPermission('edit.posyandu'))
        <button type="button"
          onclick="openEdit({{ $p->id }}, @js($p->nama), @js($p->dusun ?? ''), @js($p->lokasi ?? ''), @js($p->kader ?? ''), @js($p->jadwal ?? ''), {{ $p->aktif ? 1 : 0 }}, @js($p->keterangan ?? ''))"
          class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-edit text-sm"></i> Edit
        </button>
        @endif
        @if(Auth::user()->hasPermission('hapus.posyandu'))
        <form method="POST" action="{{ route('admin.kesehatan.posyandu.destroy', $p) }}" class="m-0">
          @csrf @method('DELETE')
          <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Posyandu \'{{ addslashes($p->nama) }}\' akan dihapus.', 'Hapus Posyandu')"
            class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
            <i class="ti ti-trash text-sm"></i>
          </button>
        </form>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-16 text-center">
    <i class="ti ti-building-hospital text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
    <p class="text-sm text-slate-400">Belum ada data posyandu.</p>
    @if(Auth::user()->hasPermission('tambah.posyandu'))
    <button type="button" onclick="openModal()" class="mt-3 px-4 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium transition-colors">
      Tambah Sekarang
    </button>
    @endif
  </div>
  @endforelse
</div>

{{-- ── Modal Tambah / Edit Posyandu ── --}}
<div id="modalPosyandu" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <h3 id="modalTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Posyandu</h3>
      <button onclick="closeModal()" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
      <form id="posyanduForm" method="POST" action="{{ route('admin.kesehatan.posyandu.store') }}" class="space-y-4">
        @csrf
        <div id="methodField"></div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Posyandu <span class="text-rose-500">*</span></label>
          <input type="text" name="nama" id="f_nama" required maxlength="150"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Contoh: Posyandu Mawar">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Dusun / Wilayah</label>
            <input type="text" name="dusun" id="f_dusun" maxlength="100"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Dusun I">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
            <select name="aktif" id="f_aktif"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Lokasi / Alamat</label>
          <input type="text" name="lokasi" id="f_lokasi" maxlength="300"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Rumah Bu Sari, RT 02">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Kader</label>
          <input type="text" name="kader" id="f_kader" maxlength="200"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Nama kader atau petugas">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jadwal Pelaksanaan</label>
          <input type="text" name="jadwal" id="f_jadwal" maxlength="200"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Setiap Senin minggu ke-2, 08.00 WIB">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Keterangan</label>
          <textarea name="keterangan" id="f_keterangan" rows="2" maxlength="1000"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none resize-none focus:ring-2 focus:ring-brand-500"
            placeholder="Catatan tambahan…"></textarea>
        </div>

        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
            Simpan
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

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route('admin.kesehatan.posyandu.store') }}';
const UPDATE_BASE = '{{ url('admin/kesehatan/posyandu') }}/';

const modal = document.getElementById('modalPosyandu');
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

function openModal() {
  document.getElementById('modalTitle').textContent   = 'Tambah Posyandu';
  document.getElementById('posyanduForm').reset();
  document.getElementById('posyanduForm').action      = STORE_URL;
  document.getElementById('methodField').innerHTML    = '';
  document.getElementById('f_aktif').value            = '1';
  modal.style.display = 'flex';
  setTimeout(() => document.getElementById('f_nama').focus(), 50);
}

function openEdit(id, nama, dusun, lokasi, kader, jadwal, aktif, keterangan) {
  document.getElementById('modalTitle').textContent   = 'Edit Posyandu';
  document.getElementById('f_nama').value             = nama;
  document.getElementById('f_dusun').value            = dusun;
  document.getElementById('f_lokasi').value           = lokasi;
  document.getElementById('f_kader').value            = kader;
  document.getElementById('f_jadwal').value           = jadwal;
  document.getElementById('f_aktif').value            = aktif ? '1' : '0';
  document.getElementById('f_keterangan').value       = keterangan;
  document.getElementById('methodField').innerHTML    = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('posyanduForm').action      = UPDATE_BASE + id;
  modal.style.display = 'flex';
}

function closeModal() { modal.style.display = 'none'; }
</script>
@endsection
