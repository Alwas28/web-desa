@extends('layouts.admin')

@section('title', 'Kartu Keluarga')
@section('page-title', 'Kartu Keluarga')
@section('page-sub', 'Kelola data Kartu Keluarga warga desa')

@section('content')

{{-- Summary --}}
<div class="flex items-center gap-3 px-5 py-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-fit">
  <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 grid place-items-center">
    <i class="ti ti-home text-lg text-brand-600 dark:text-brand-400"></i>
  </div>
  <div>
    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($total) }}</div>
    <div class="text-xs text-slate-400">Total Kartu Keluarga</div>
  </div>
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.kk.index') }}" class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-sm px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. KK, alamat, atau nama kepala keluarga…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>
  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i>
  </button>
  @if(request('q'))
  <a href="{{ route('admin.kk.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
  <button type="button" onclick="openKKModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus text-base"></i> Tambah KK
  </button>
</form>

{{-- Tabel KK --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($kks->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-home-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400">
        {{ request('q') ? 'Tidak ada KK yang sesuai pencarian.' : 'Belum ada data Kartu Keluarga.' }}
      </p>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 uppercase tracking-wide text-left">
            <th class="px-4 py-3">No. KK</th>
            <th class="px-4 py-3">Kepala Keluarga</th>
            <th class="px-4 py-3">Alamat</th>
            <th class="px-4 py-3">RT / RW</th>
            <th class="px-4 py-3">Dusun</th>
            <th class="px-4 py-3 text-center">Anggota</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($kks as $kk)
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">
            <td class="px-4 py-3 font-mono text-xs font-semibold text-slate-700 dark:text-slate-300">
              {{ $kk->nomor_kk }}
            </td>
            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">
              {{ $kk->kepalaKeluarga?->nama_lengkap ?? '—' }}
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 max-w-xs">
              {{ Str::limit($kk->alamat, 55) }}
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
              @if($kk->rt || $kk->rw)
                {{ $kk->rt ? 'RT '.$kk->rt : '' }}{{ ($kk->rt && $kk->rw) ? ' / ' : '' }}{{ $kk->rw ? 'RW '.$kk->rw : '' }}
              @else — @endif
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
              {{ $kk->dusun ?: '—' }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-semibold
                           {{ $kk->penduduks_count > 0
                              ? 'bg-brand-100 text-brand-700 dark:bg-brand-500/20 dark:text-brand-400'
                              : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500' }}">
                <i class="ti ti-users text-[11px]"></i> {{ $kk->penduduks_count }} orang
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('admin.kk.show', $kk) }}"
                  class="flex items-center gap-1 px-3 h-8 rounded-lg border border-brand-200 dark:border-brand-500/30 text-brand-600 dark:text-brand-400 text-xs hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                  <i class="ti ti-users text-sm"></i> Detail
                </a>
                <button type="button" onclick="openKKModal(@js($kk->toArray()))"
                  class="flex items-center gap-1 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <i class="ti ti-pencil text-sm"></i>
                </button>
                <form method="POST" action="{{ route('admin.kk.destroy', $kk) }}" class="m-0">
                  @csrf @method('DELETE')
                  <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'KK {{ $kk->nomor_kk }} akan dihapus. Data anggota tidak ikut terhapus.', 'Hapus Kartu Keluarga')"
                    class="flex items-center px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
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
    @if($kks->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $kks->links() }}
      </div>
    @endif
  @endif
</div>

{{-- ══ MODAL TAMBAH / EDIT KK ═════════════════════════════════════════════ --}}
<div id="modalKK" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeKKModal()"></div>
  <div class="relative z-10 w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-base" id="mkTitle">Tambah Kartu Keluarga</h2>
      <button type="button" onclick="closeKKModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form id="fmKK" method="POST" class="p-6 space-y-4">
      @csrf
      <div id="mkMethod"></div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Nomor KK <span class="text-rose-500">*</span></label>
        <input type="text" name="nomor_kk" id="mk_nomor" required maxlength="16" minlength="16"
          inputmode="numeric" pattern="[0-9]{16}"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
          placeholder="16 digit nomor KK">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Alamat <span class="text-rose-500">*</span></label>
        <textarea name="alamat" id="mk_alamat" required rows="2"
          class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1.5">RT</label>
          <input type="text" name="rt" id="mk_rt" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="001">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">RW</label>
          <input type="text" name="rw" id="mk_rw" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="002">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Dusun</label>
          <input type="text" name="dusun" id="mk_dusun" maxlength="100"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Kode Pos</label>
          <input type="text" name="kode_pos" id="mk_kodepos" maxlength="5" inputmode="numeric"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex gap-3">
        <button type="button" onclick="closeKKModal()"
          class="flex-1 h-10 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="flex-1 flex items-center justify-center gap-2 h-10 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-device-floppy text-base"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
const STORE_KK = '{{ route('admin.kk.store') }}';
const BASE_KK  = '{{ url('admin/kartu-keluarga') }}/';

function openKKModal(data = null) {
  document.getElementById('mkTitle').textContent = data ? 'Edit Kartu Keluarga' : 'Tambah Kartu Keluarga';
  document.getElementById('mkMethod').innerHTML  = data ? '<input type="hidden" name="_method" value="PUT">' : '';
  document.getElementById('fmKK').action         = data ? BASE_KK + data.id : STORE_KK;

  const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
  set('mk_nomor',   data?.nomor_kk ?? '');
  set('mk_alamat',  data?.alamat ?? '');
  set('mk_rt',      data?.rt ?? '');
  set('mk_rw',      data?.rw ?? '');
  set('mk_dusun',   data?.dusun ?? '');
  set('mk_kodepos', data?.kode_pos ?? '');

  document.getElementById('modalKK').style.display = 'flex';
}
function closeKKModal() { document.getElementById('modalKK').style.display = 'none'; }

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeKKModal(); });
</script>
@endsection
