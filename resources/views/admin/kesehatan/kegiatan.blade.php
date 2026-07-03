@extends('layouts.admin')
@section('title', 'Kegiatan Posyandu')
@section('page-title', 'Kegiatan Posyandu')
@section('page-sub', 'Pencatatan setiap sesi pelaksanaan posyandu')

@section('content')

{{-- ── Tabel kegiatan ── --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-slate-800">
    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $kegiatans->count() }} sesi tercatat</span>
    @if(Auth::user()->hasPermission('tambah.posyandu'))
    <button type="button" onclick="openModal()"
      class="flex items-center gap-2 px-4 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition-colors">
      <i class="ti ti-plus"></i> Tambah Sesi
    </button>
    @endif
  </div>

  @if($kegiatans->isEmpty())
  <div class="p-16 text-center">
    <i class="ti ti-clipboard-list text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
    <p class="text-sm text-slate-400">Belum ada sesi kegiatan yang dicatat.</p>
    @if(Auth::user()->hasPermission('tambah.posyandu'))
    <button type="button" onclick="openModal()" class="mt-3 px-4 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium transition-colors">
      Tambah Sesi Pertama
    </button>
    @endif
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
          <th class="px-5 py-3 text-left">Tanggal</th>
          <th class="px-4 py-3 text-left">Posyandu</th>
          <th class="px-4 py-3 text-left">Petugas</th>
          <th class="px-4 py-3 text-center">Peserta</th>
          <th class="px-4 py-3 text-left">Keterangan</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
        @foreach($kegiatans as $k)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
          <td class="px-5 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $k->tanggal->translatedFormat('d F Y') }}</div>
            <div class="text-xs text-slate-400">{{ $k->tanggal->translatedFormat('l') }}</div>
          </td>
          <td class="px-4 py-3">
            <div class="font-medium text-slate-700 dark:text-slate-300">{{ $k->posyandu?->nama ?? '—' }}</div>
            @if($k->posyandu?->dusun)
            <div class="text-xs text-slate-400">{{ $k->posyandu->dusun }}</div>
            @endif
          </td>
          <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $k->petugas ?: '—' }}</td>
          <td class="px-4 py-3 text-center">
            @if($k->rekams_count > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 text-xs font-bold">
              <i class="ti ti-users text-[10px]"></i> {{ $k->rekams_count }} orang
            </span>
            @else
            <span class="text-xs text-slate-300 dark:text-slate-600">Belum ada</span>
            @endif
          </td>
          <td class="px-4 py-3 text-xs text-slate-400 max-w-[160px] truncate">{{ $k->keterangan ?: '' }}</td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <a href="{{ route('admin.kesehatan.posyandu.kegiatan.show', [$k->posyandu_id, $k]) }}"
                class="flex items-center gap-1 px-3 h-7 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-semibold hover:bg-brand-100 dark:hover:bg-brand-500/20 transition-colors">
                <i class="ti ti-table text-xs"></i> Buka
              </a>
              @if(Auth::user()->hasPermission('hapus.posyandu'))
              <form method="POST" action="{{ route('admin.kesehatan.posyandu.kegiatan.destroy', $k) }}" class="m-0"
                onsubmit="return confirm('Hapus sesi {{ $k->tanggal->format('d/m/Y') }}? Semua data peserta ikut terhapus.')">
                @csrf @method('DELETE')
                <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

@if(Auth::user()->hasPermission('tambah.posyandu'))
{{-- ── Modal Tambah Sesi ── --}}
<div id="modalSesi" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
      <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Sesi Kegiatan</h3>
      <button onclick="closeModal()" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="p-6">
      <form id="sesiForm" method="POST" action="" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Posyandu <span class="text-rose-500">*</span></label>
          <select name="_posyandu_id" id="sf_posyandu" required onchange="updateAction()"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <option value="">— Pilih Posyandu —</option>
            @foreach($posyandus as $pos)
            <option value="{{ $pos->id }}" data-url="{{ route('admin.kesehatan.posyandu.kegiatan.store', $pos) }}">{{ $pos->nama }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
          <input type="date" name="tanggal" id="sf_tanggal" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Petugas / Kader</label>
          <input type="text" name="petugas" maxlength="200"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Nama petugas yang bertugas">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Keterangan</label>
          <input type="text" name="keterangan" maxlength="1000"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Catatan singkat…">
        </div>
        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
            Buat & Catat Peserta
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
@if(Auth::user()->hasPermission('tambah.posyandu'))
<script>
const modal = document.getElementById('modalSesi');
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

function openModal() {
  document.getElementById('sesiForm').reset();
  document.getElementById('sf_tanggal').value = new Date().toISOString().slice(0,10);
  document.getElementById('sesiForm').action  = '';
  modal.style.display = 'flex';
}
function closeModal() { modal.style.display = 'none'; }
function updateAction() {
  const sel = document.getElementById('sf_posyandu');
  document.getElementById('sesiForm').action = sel.options[sel.selectedIndex].dataset.url ?? '';
}
</script>
@endif
@endsection
