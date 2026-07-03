@extends('layouts.admin')

@section('title', 'Periode')
@section('page-title', 'Periode')
@section('page-sub', 'Daftar periode pemerintahan desa')

@section('content')

@php
  $aktif = $periodes->filter(fn($p) => is_null($p->selesai) || $p->selesai->isFuture())->count();
@endphp

<div class="grid grid-cols-2 gap-3 max-w-sm">
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-calendar-time text-brand-600 dark:text-brand-100 text-lg"></i>
    </div>
    <div>
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $periodes->count() }}</div>
      <div class="text-xs text-slate-400">Total Periode</div>
    </div>
  </div>
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-circle-check text-emerald-600 dark:text-emerald-400 text-lg"></i>
    </div>
    <div>
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $aktif }}</div>
      <div class="text-xs text-slate-400">Sedang Aktif</div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

  {{-- ── Kiri: Daftar Periode ──────────────────────── --}}
  <div class="lg:col-span-2 space-y-4">

    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-base">Daftar Periode</h2>
      <span class="text-xs text-slate-400">{{ $periodes->count() }} periode</span>
    </div>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      @forelse($periodes as $per)
        @php $isAktif = is_null($per->selesai) || $per->selesai->isFuture(); @endphp
        <div class="flex items-center gap-4 px-5 py-4 {{ !$loop->last ? 'border-b border-slate-100 dark:border-slate-800' : '' }} hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">

          {{-- Status dot --}}
          <div class="flex-shrink-0">
            @if($isAktif)
              <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
              </span>
            @else
              <span class="block h-2.5 w-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
            @endif
          </div>

          {{-- Konten --}}
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="font-semibold text-sm text-slate-900 dark:text-slate-100">{{ $per->nama }}</span>
              @if($isAktif)
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">Aktif</span>
              @else
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">Selesai</span>
              @endif
            </div>
            <div class="mt-0.5 flex items-center gap-1 text-xs text-slate-400">
              <i class="ti ti-calendar text-sm"></i>
              {{ $per->mulai->format('d M Y') }} — {{ $per->selesai ? $per->selesai->format('d M Y') : 'sekarang' }}
            </div>
            @if($per->keterangan)
              <p class="text-xs text-slate-400 mt-1 italic">{{ $per->keterangan }}</p>
            @endif
          </div>

          {{-- Aksi --}}
          @if(Auth::user()->hasPermission('edit.periode') || Auth::user()->hasPermission('hapus.periode'))
          <div class="flex items-center gap-1.5 flex-shrink-0">
            @if(Auth::user()->hasPermission('edit.periode'))
            <button type="button"
              onclick="openEdit({{ $per->id }}, @js($per->nama), @js($per->mulai->format('Y-m-d')), @js($per->selesai?->format('Y-m-d') ?? ''), @js($per->keterangan ?? ''))"
              class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-edit text-sm"></i>
            </button>
            @endif
            @if(Auth::user()->hasPermission('hapus.periode'))
            <form method="POST" action="{{ route('admin.master.periode.destroy', $per) }}" class="m-0">
              @csrf @method('DELETE')
              <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Periode ini akan dihapus permanen.', 'Hapus Periode')"
                class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                <i class="ti ti-trash text-sm"></i>
              </button>
            </form>
            @endif
          </div>
          @endif
        </div>
      @empty
        <div class="p-12 text-center">
          <i class="ti ti-calendar-off text-3xl text-slate-300 dark:text-slate-600 block mb-2"></i>
          <p class="text-sm text-slate-400">Belum ada periode tercatat. Tambahkan periode pertama di panel kanan.</p>
        </div>
      @endforelse
    </div>
  </div>

  {{-- ── Kanan: Form ───────────────────────────────── --}}
  @if(Auth::user()->hasPermission('tambah.periode') || Auth::user()->hasPermission('edit.periode'))
  <div class="space-y-4">
    <h2 class="font-semibold text-base" id="formTitle">Tambah Periode</h2>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <form method="POST" id="periodeForm" action="{{ route('admin.master.periode.store') }}" class="space-y-4">
        @csrf
        <div id="methodField"></div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Nama Periode <span class="text-rose-500">*</span></label>
          <input type="text" name="nama" id="f_nama" required
            class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('nama') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
            value="{{ old('nama') }}" placeholder="Contoh: 2021–2026">
          @error('nama')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium mb-1.5">Mulai <span class="text-rose-500">*</span></label>
            <input type="date" name="mulai" id="f_mulai" required
              class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('mulai') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
              value="{{ old('mulai') }}">
            @error('mulai')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">
              Selesai <span class="text-slate-400 font-normal text-xs">(kosong = aktif)</span>
            </label>
            <input type="date" name="selesai" id="f_selesai"
              class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('selesai') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
              value="{{ old('selesai') }}">
            @error('selesai')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Keterangan <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="keterangan" id="f_keterangan" rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Catatan tambahan…">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-plus text-base" id="btnIcon"></i>
            <span id="btnLabel">Tambah Periode</span>
          </button>
          <button type="button" id="cancelBtn" onclick="resetForm()" style="display:none"
            class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4" id="legendCard">
      <h3 class="text-sm font-medium mb-2.5">Keterangan Status</h3>
      <div class="space-y-2 text-xs text-slate-500 dark:text-slate-400">
        <div class="flex items-center gap-2.5">
          <span class="flex h-2.5 w-2.5 relative flex-shrink-0">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          <span><b>Aktif</b> — tanggal selesai kosong atau belum lewat</span>
        </div>
        <div class="flex items-center gap-2.5">
          <span class="block h-2.5 w-2.5 rounded-full bg-slate-300 dark:bg-slate-600 flex-shrink-0"></span>
          <span><b>Selesai</b> — tanggal selesai sudah terlewati</span>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route('admin.master.periode.store') }}';
const UPDATE_BASE = '{{ url('admin/master/periode') }}/';

function openEdit(id, nama, mulai, selesai, keterangan) {
  document.getElementById('formTitle').textContent    = 'Edit Periode';
  document.getElementById('f_nama').value             = nama;
  document.getElementById('f_mulai').value            = mulai;
  document.getElementById('f_selesai').value          = selesai;
  document.getElementById('f_keterangan').value       = keterangan;
  document.getElementById('methodField').innerHTML    = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('periodeForm').action       = UPDATE_BASE + id;
  document.getElementById('btnIcon').className        = 'ti ti-device-floppy text-base';
  document.getElementById('btnLabel').textContent     = 'Simpan Perubahan';
  document.getElementById('cancelBtn').style.display = '';
  document.getElementById('f_nama').scrollIntoView({ behavior: 'smooth', block: 'center' });
  document.getElementById('f_nama').focus();
}

function resetForm() {
  document.getElementById('formTitle').textContent    = 'Tambah Periode';
  document.getElementById('periodeForm').reset();
  document.getElementById('periodeForm').action       = STORE_URL;
  document.getElementById('methodField').innerHTML    = '';
  document.getElementById('btnIcon').className        = 'ti ti-plus text-base';
  document.getElementById('btnLabel').textContent     = 'Tambah Periode';
  document.getElementById('cancelBtn').style.display  = 'none';
}
</script>
@endsection
