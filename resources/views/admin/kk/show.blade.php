@extends('layouts.admin')

@section('title', 'Detail KK – '.$kartuKeluarga->nomor_kk)
@section('page-title', 'Detail Kartu Keluarga')
@section('page-sub', 'Data anggota keluarga dalam kartu keluarga ini')

@section('content')

{{-- Back + Edit --}}
<div class="flex items-center justify-between">
  <a href="{{ route('admin.kk.index') }}"
     class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
    <i class="ti ti-arrow-left text-base"></i> Kembali ke Kartu Keluarga
  </a>
  <button type="button" onclick="openEditKK()"
    class="flex items-center gap-2 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-pencil text-sm"></i> Edit KK
  </button>
</div>

{{-- KK Info Card --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">
  <div class="flex items-start gap-5">
    <div class="w-14 h-14 rounded-2xl grid place-items-center flex-shrink-0 bg-brand-50 dark:bg-brand-500/10">
      <i class="ti ti-home text-2xl text-brand-600 dark:text-brand-400"></i>
    </div>
    <div class="flex-1 min-w-0">
      <div class="font-mono text-lg font-bold text-slate-900 dark:text-slate-100 tracking-wide">
        {{ $kartuKeluarga->nomor_kk }}
      </div>
      <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $kartuKeluarga->alamat }}</div>
      <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-400">
        @if($kartuKeluarga->rt || $kartuKeluarga->rw)
          <span>
            {{ $kartuKeluarga->rt ? 'RT '.$kartuKeluarga->rt : '' }}
            {{ ($kartuKeluarga->rt && $kartuKeluarga->rw) ? ' / ' : '' }}
            {{ $kartuKeluarga->rw ? 'RW '.$kartuKeluarga->rw : '' }}
          </span>
        @endif
        @if($kartuKeluarga->dusun)
          <span>Dusun {{ $kartuKeluarga->dusun }}</span>
        @endif
        @if($kartuKeluarga->kode_pos)
          <span>Kode Pos {{ $kartuKeluarga->kode_pos }}</span>
        @endif
      </div>
    </div>
    <div class="flex-shrink-0 text-right">
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $kartuKeluarga->penduduks->count() }}</div>
      <div class="text-xs text-slate-400 mt-0.5">Anggota Keluarga</div>
    </div>
  </div>
</div>

{{-- Anggota Table --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
    <i class="ti ti-users text-brand-500 text-lg"></i>
    <h2 class="font-semibold text-sm">Daftar Anggota Keluarga</h2>
  </div>

  @if($kartuKeluarga->penduduks->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-user-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400">Belum ada anggota terdaftar di KK ini.</p>
      <a href="{{ route('admin.penduduk.index') }}"
         class="mt-4 inline-flex items-center gap-2 text-sm text-brand-600 dark:text-brand-400 hover:underline">
        <i class="ti ti-plus text-sm"></i> Tambah Penduduk
      </a>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 uppercase tracking-wide text-left">
            <th class="px-4 py-3">No</th>
            <th class="px-4 py-3">Nama / NIK</th>
            <th class="px-4 py-3">Tgl Lahir / Umur</th>
            <th class="px-4 py-3">JK</th>
            <th class="px-4 py-3">Hubungan KK</th>
            <th class="px-4 py-3 text-right">Profil</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($kartuKeluarga->penduduks as $i => $p)
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">
            <td class="px-4 py-3 text-xs text-slate-400 font-mono">{{ $i + 1 }}</td>
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $p->nama_lengkap }}</div>
              <div class="text-xs font-mono text-slate-400">{{ $p->nik }}</div>
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
              <div>{{ $p->tanggal_lahir->format('d M Y') }}</div>
              <div class="font-semibold text-slate-700 dark:text-slate-300">
                {{ $p->umur }} thn
                <span class="text-slate-400 font-normal">({{ $p->kelompok_umur }})</span>
              </div>
            </td>
            <td class="px-4 py-3">
              @if($p->jenis_kelamin === 'L')
                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                  ♂ L
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-semibold bg-pink-100 text-pink-700 dark:bg-pink-500/20 dark:text-pink-400">
                  ♀ P
                </span>
              @endif
            </td>
            <td class="px-4 py-3">
              @if($p->hubungan_keluarga === 'kepala_keluarga')
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-brand-100 text-brand-700 dark:bg-brand-500/20 dark:text-brand-400">
                  {{ $p->hubungan_label }}
                </span>
              @else
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $p->hubungan_label }}</span>
              @endif
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.penduduk.show', $p) }}"
                 class="inline-flex items-center gap-1.5 px-3 h-8 rounded-lg border border-brand-200 dark:border-brand-500/30 text-brand-600 dark:text-brand-400 text-xs hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                <i class="ti ti-user text-sm"></i> Profil
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

{{-- ══ MODAL EDIT KK ══════════════════════════════════════════════════════ --}}
<div id="modalEditKK" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditKK()"></div>
  <div class="relative z-10 w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-base">Edit Kartu Keluarga</h2>
      <button type="button" onclick="closeEditKK()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.kk.update', $kartuKeluarga) }}" class="p-6 space-y-4">
      @csrf @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1.5">Nomor KK <span class="text-rose-500">*</span></label>
        <input type="text" name="nomor_kk" value="{{ $kartuKeluarga->nomor_kk }}" required maxlength="16" minlength="16"
          inputmode="numeric" pattern="[0-9]{16}"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Alamat <span class="text-rose-500">*</span></label>
        <textarea name="alamat" required rows="2"
          class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500">{{ $kartuKeluarga->alamat }}</textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1.5">RT</label>
          <input type="text" name="rt" value="{{ $kartuKeluarga->rt }}" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="001">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">RW</label>
          <input type="text" name="rw" value="{{ $kartuKeluarga->rw }}" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="002">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Dusun</label>
          <input type="text" name="dusun" value="{{ $kartuKeluarga->dusun }}" maxlength="100"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Kode Pos</label>
          <input type="text" name="kode_pos" value="{{ $kartuKeluarga->kode_pos }}" maxlength="5" inputmode="numeric"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex gap-3">
        <button type="button" onclick="closeEditKK()"
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
function openEditKK()  { document.getElementById('modalEditKK').style.display = 'flex'; }
function closeEditKK() { document.getElementById('modalEditKK').style.display = 'none'; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditKK(); });
</script>
@endsection
