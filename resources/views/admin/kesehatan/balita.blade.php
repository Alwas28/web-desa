@extends('layouts.admin')
@section('title', 'Data Balita')
@section('page-title', 'Data Balita')
@section('page-sub', 'Pemantauan pertumbuhan dan gizi balita')

@section('content')

{{-- ── Stats ── --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
  @php
  $statItems = [
    ['label' => 'Total Balita',  'val' => $stats['total'],   'icon' => 'ti-users',       'color' => 'text-brand-600 dark:text-brand-400',    'bg' => 'bg-brand-50 dark:bg-brand-500/10'],
    ['label' => 'Gizi Normal',   'val' => $stats['normal'],  'icon' => 'ti-circle-check', 'color' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10'],
    ['label' => 'Stunting',      'val' => $stats['stunting'],'icon' => 'ti-alert-triangle','color' => 'text-purple-600 dark:text-purple-400',  'bg' => 'bg-purple-50 dark:bg-purple-500/10'],
    ['label' => 'Gizi Masalah',  'val' => $stats['masalah'], 'icon' => 'ti-alert-circle', 'color' => 'text-amber-600 dark:text-amber-400',    'bg' => 'bg-amber-50 dark:bg-amber-500/10'],
    ['label' => 'Belum Timbang', 'val' => $stats['belum'],   'icon' => 'ti-clock',        'color' => 'text-slate-500 dark:text-slate-400',    'bg' => 'bg-slate-100 dark:bg-slate-800'],
  ];
  @endphp
  @foreach($statItems as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} {{ $s['color'] }} text-lg"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- ── Toolbar ── --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
  <form method="GET" action="{{ route('admin.kesehatan.balita.index') }}" class="flex flex-wrap items-center gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIK, nama ibu…"
      class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 w-60">
    <select name="posyandu_id"
      class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
      <option value="">Semua Posyandu</option>
      @foreach($posyandus as $pos)
      <option value="{{ $pos->id }}" {{ request('posyandu_id') == $pos->id ? 'selected' : '' }}>{{ $pos->nama }}</option>
      @endforeach
    </select>
    <button type="submit" class="px-4 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-sm font-medium transition-colors">
      <i class="ti ti-search text-sm"></i> Cari
    </button>
    @if(request('q') || request('posyandu_id'))
    <a href="{{ route('admin.kesehatan.balita.index') }}" class="px-3 h-9 flex items-center rounded-lg border border-slate-200 dark:border-slate-700 text-xs text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-x text-xs mr-1"></i> Reset
    </a>
    @endif
  </form>

  @if(Auth::user()->hasPermission('tambah.balita'))
  <button type="button" onclick="openModal('modalBalita')"
    class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus"></i> Tambah Balita
  </button>
  @endif
</div>

{{-- ── Tabel ── --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama & Orang Tua</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Umur / JK</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Posyandu</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Timbang Terakhir</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status Gizi</th>
          <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($balitas as $i => $b)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
          <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
          <td class="px-4 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $b->nama }}</div>
            <div class="text-xs text-slate-400 mt-0.5">Ibu: {{ $b->nama_ibu }}</div>
            @if($b->nik)<div class="text-xs text-slate-400">NIK: {{ $b->nik }}</div>@endif
          </td>
          <td class="px-4 py-3">
            <div class="text-slate-700 dark:text-slate-300">{{ $b->umur }}</div>
            <div class="text-xs mt-0.5">
              <span class="{{ $b->jenis_kelamin === 'L' ? 'text-blue-600 dark:text-blue-400' : 'text-pink-600 dark:text-pink-400' }} font-medium">
                {{ $b->jenis_kelamin === 'L' ? '♂ Laki-laki' : '♀ Perempuan' }}
              </span>
            </div>
          </td>
          <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">
            {{ $b->posyandu?->nama ?? '—' }}
          </td>
          <td class="px-4 py-3">
            @if($b->timbangTerakhir)
            <div class="text-slate-700 dark:text-slate-300 font-medium">{{ $b->timbangTerakhir->berat_badan }} kg
              @if($b->timbangTerakhir->tinggi_badan) / {{ $b->timbangTerakhir->tinggi_badan }} cm @endif
            </div>
            <div class="text-xs text-slate-400 mt-0.5">{{ $b->timbangTerakhir->tanggal->format('d M Y') }}</div>
            @else
            <span class="text-xs text-slate-400">Belum ada rekam</span>
            @endif
          </td>
          <td class="px-4 py-3">
            @if($b->timbangTerakhir)
            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full {{ $b->timbangTerakhir->badge_color }}">
              {{ $b->timbangTerakhir->label_status_gizi }}
            </span>
            @else
            <span class="text-xs text-slate-300">—</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center justify-end gap-1.5">
              @if(Auth::user()->hasPermission('tambah.balita') || Auth::user()->hasPermission('hapus.balita'))
              <button type="button"
                onclick="openRekam({{ $b->id }}, @js($b->nama), @js($TIMBANG[$b->id] ?? []))"
                class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-emerald-200 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 text-xs font-medium hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors">
                <i class="ti ti-scale text-xs"></i> Timbang
              </button>
              @endif
              @if(Auth::user()->hasPermission('edit.balita'))
              <button type="button"
                onclick="openEditBalita({{ $b->id }}, @js($b->toArray()))"
                class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="ti ti-edit text-xs"></i>
              </button>
              @endif
              @if(Auth::user()->hasPermission('hapus.balita'))
              <form method="POST" action="{{ route('admin.kesehatan.balita.destroy', $b) }}" class="m-0">
                @csrf @method('DELETE')
                <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Data balita \'{{ addslashes($b->nama) }}\' akan dihapus.', 'Hapus Balita')"
                  class="w-7 h-7 rounded-lg flex items-center justify-center border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                  <i class="ti ti-trash text-xs"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-4 py-16 text-center">
            <i class="ti ti-baby-carriage text-4xl text-slate-300 dark:text-slate-600 block mb-2"></i>
            <p class="text-sm text-slate-400">Belum ada data balita.</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ─── Modal Tambah / Edit Balita ─── --}}
<div id="modalBalita" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <h3 id="modalBalitaTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Data Balita</h3>
      <button onclick="closeModal('modalBalita')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
      <form id="balitaForm" method="POST" action="{{ route('admin.kesehatan.balita.store') }}" class="space-y-4">
        @csrf
        <div id="balitaMethodField"></div>

        {{-- ── Toggle warga / non-warga ── --}}
        <div id="wargaToggleWrap">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status Kependudukan</label>
          <div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-lg w-fit">
            <button type="button" id="btnWarga" onclick="setWargaMode(true)"
              class="px-4 h-7 rounded-md text-xs font-semibold transition-all bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm">
              Warga Desa
            </button>
            <button type="button" id="btnNonWarga" onclick="setWargaMode(false)"
              class="px-4 h-7 rounded-md text-xs font-semibold transition-all text-slate-500 dark:text-slate-400">
              Non-Warga
            </button>
          </div>
        </div>

        {{-- ── Pencarian warga ── --}}
        <div id="wargaSearchBlock">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari Nama Balita dari Data Penduduk</label>
          <div class="relative">
            <input type="text" id="searchBalita" autocomplete="off"
              placeholder="Ketik nama atau NIK balita…"
              oninput="filterWarga()"
              onfocus="showDropdown('dropdownBalita')"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 pr-8">
            <i class="ti ti-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
            <div id="dropdownBalita"
              class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden"
              style="display:none;max-height:200px;overflow-y:auto"></div>
          </div>
          <p class="text-[10px] text-slate-400 mt-1">Pilih dari daftar untuk auto-isi data balita.</p>
        </div>

        {{-- ── Field nama (manual jika non-warga, readonly jika warga) ── --}}
        <div class="grid grid-cols-2 gap-3">
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Balita <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" id="fb_nama" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Nama lengkap balita">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">NIK</label>
            <input type="text" name="nik" id="fb_nik" maxlength="20"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Opsional">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
            <select name="jenis_kelamin" id="fb_jenis_kelamin" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
            <input type="date" name="tanggal_lahir" id="fb_tanggal_lahir" required max="{{ date('Y-m-d') }}"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Posyandu</label>
            <select name="posyandu_id" id="fb_posyandu_id"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">— Pilih Posyandu —</option>
              @foreach($posyandus as $pos)
              <option value="{{ $pos->id }}">{{ $pos->nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Ibu <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_ibu" id="fb_nama_ibu" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Nama ibu kandung">
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Ayah</label>
            <input type="text" name="nama_ayah" id="fb_nama_ayah" maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Opsional">
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Alamat</label>
            <input type="text" name="alamat" id="fb_alamat" maxlength="300"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Alamat tempat tinggal">
          </div>
        </div>

        <div class="flex gap-2 pt-2">
          <button type="submit"
            class="flex-1 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
            Simpan
          </button>
          <button type="button" onclick="closeModal('modalBalita')"
            class="px-5 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ─── Modal Riwayat Timbang ─── --}}
<div id="modalTimbang" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <div>
        <h3 id="modalTimbangTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Riwayat Timbang</h3>
        <p id="modalTimbangSub" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <button onclick="closeModal('modalTimbang')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-4">

      {{-- Tambah Rekam --}}
      @if(Auth::user()->hasPermission('tambah.balita'))
      <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
        <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-3">Tambah Rekam Timbang</h4>
        <form id="rekamForm" method="POST" action="" class="space-y-3">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal <span class="text-rose-500">*</span></label>
              <input type="date" name="tanggal" id="rf_tanggal" required max="{{ date('Y-m-d') }}"
                value="{{ date('Y-m-d') }}"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Berat Badan (kg) <span class="text-rose-500">*</span></label>
              <input type="number" name="berat_badan" id="rf_berat" required step="0.1" min="0.1" max="99"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="10.5">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Tinggi Badan (cm)</label>
              <input type="number" name="tinggi_badan" id="rf_tinggi" step="0.1" min="1" max="200"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="75.0">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Lingkar Kepala (cm)</label>
              <input type="number" name="lingkar_kepala" id="rf_lk" step="0.1" min="1" max="99"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="42.0">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Status Gizi <span class="text-rose-500">*</span></label>
              <select name="status_gizi" id="rf_status" required
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                <option value="normal">Normal</option>
                <option value="kurang">Gizi Kurang</option>
                <option value="buruk">Gizi Buruk</option>
                <option value="lebih">Gizi Lebih</option>
                <option value="stunting">Stunting</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Keterangan</label>
              <input type="text" name="keterangan" id="rf_ket" maxlength="500"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="Catatan...">
            </div>
          </div>
          <button type="submit"
            class="w-full h-8 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors">
            <i class="ti ti-plus text-xs mr-1"></i> Simpan Rekam Timbang
          </button>
        </form>
      </div>
      @endif

      {{-- Riwayat list --}}
      <div id="riwayatTimbangList"></div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
@php
$pendudukJs = $penduduk->map(fn($p) => [
    'id'            => $p->id,
    'nik'           => $p->nik ?? '',
    'nama'          => $p->nama_lengkap,
    'tanggal_lahir' => $p->tanggal_lahir?->format('Y-m-d') ?? '',
    'jenis_kelamin' => $p->jenis_kelamin,
]);
$timbangJs = $balitas->mapWithKeys(fn($b) => [
    $b->id => $b->timbang->map(fn($t) => [
        'id'            => $t->id,
        'tanggal'       => $t->tanggal->format('d M Y'),
        'berat_badan'   => $t->berat_badan,
        'tinggi_badan'  => $t->tinggi_badan,
        'lingkar_kepala'=> $t->lingkar_kepala,
        'status_gizi'   => $t->status_gizi,
        'label'         => $t->label_status_gizi,
        'keterangan'    => $t->keterangan,
    ])->values()->all(),
]);
@endphp
<script>
const PENDUDUK_BALITA = @json($pendudukJs);
const TIMBANG         = @json($timbangJs);
const STORE_BALITA    = '{{ route('admin.kesehatan.balita.store') }}';
const UPDATE_BASE     = '{{ url('admin/kesehatan/balita') }}/';
const REKAM_BASE      = '{{ url('admin/kesehatan/balita') }}/';
const HAPUS_REKAM     = '{{ url('admin/kesehatan/timbang') }}/';
const CSRF            = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

let _currentBalitaId = null;
let _isWargaMode     = true;
let _wargaResults    = [];

function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

/* ── Warga / Non-warga toggle ── */
function setWargaMode(isWarga) {
  _isWargaMode = isWarga;
  document.getElementById('wargaSearchBlock').style.display = isWarga ? '' : 'none';

  const btnW  = document.getElementById('btnWarga');
  const btnNW = document.getElementById('btnNonWarga');
  btnW.className  = 'px-4 h-7 rounded-md text-xs font-semibold transition-all ' +
    (isWarga ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400');
  btnNW.className = 'px-4 h-7 rounded-md text-xs font-semibold transition-all ' +
    (!isWarga ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400');

  if (!isWarga) {
    document.getElementById('fb_nama').value          = '';
    document.getElementById('fb_nik').value           = '';
    document.getElementById('fb_tanggal_lahir').value = '';
    document.getElementById('searchBalita').value     = '';
  }
}

/* ── Filter dan tampilkan dropdown ── */
function filterWarga() {
  const q    = document.getElementById('searchBalita').value.toLowerCase().trim();
  const drop = document.getElementById('dropdownBalita');

  _wargaResults = q.length < 1 ? [] : PENDUDUK_BALITA.filter(p =>
    p.nama.toLowerCase().includes(q) || p.nik.includes(q)
  ).slice(0, 20);

  drop.innerHTML = _wargaResults.length
    ? _wargaResults.map((p, i) => `
      <div class="px-3 py-2 cursor-pointer hover:bg-brand-50 dark:hover:bg-brand-500/10 text-sm transition-colors border-b border-slate-100 dark:border-slate-700 last:border-0"
        onmousedown="pilihWargaBalita(${i})">
        <div class="font-medium text-slate-800 dark:text-slate-100">${p.nama}</div>
        <div class="text-xs text-slate-400">${p.nik || 'NIK —'} · ${p.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'} · ${p.tanggal_lahir || '—'}</div>
      </div>`).join('')
    : `<div class="px-3 py-3 text-sm text-slate-400 text-center">Tidak ditemukan</div>`;

  drop.style.display = q.length >= 1 ? '' : 'none';
}

function showDropdown(id) {
  const el = document.getElementById(id);
  if (el && el.innerHTML.trim()) el.style.display = '';
}

function pilihWargaBalita(i) {
  const p = _wargaResults[i];
  if (!p) return;
  document.getElementById('fb_nama').value          = p.nama;
  document.getElementById('fb_nik').value           = p.nik;
  document.getElementById('fb_jenis_kelamin').value = p.jenis_kelamin;
  document.getElementById('fb_tanggal_lahir').value = p.tanggal_lahir;
  document.getElementById('searchBalita').value     = p.nama;
  document.getElementById('dropdownBalita').style.display = 'none';
}

/* Tutup dropdown klik luar */
document.addEventListener('click', e => {
  if (!e.target.closest('#wargaSearchBlock')) {
    const d = document.getElementById('dropdownBalita');
    if (d) d.style.display = 'none';
  }
});

function openEditBalita(id, data) {
  document.getElementById('modalBalitaTitle').textContent  = 'Edit Data Balita';
  document.getElementById('wargaToggleWrap').style.display = 'none';
  document.getElementById('wargaSearchBlock').style.display = 'none';

  document.getElementById('fb_nama').value          = data.nama ?? '';
  document.getElementById('fb_nik').value           = data.nik ?? '';
  document.getElementById('fb_jenis_kelamin').value = data.jenis_kelamin ?? 'L';
  document.getElementById('fb_tanggal_lahir').value = data.tanggal_lahir ?? '';
  document.getElementById('fb_posyandu_id').value   = data.posyandu_id ?? '';
  document.getElementById('fb_nama_ibu').value      = data.nama_ibu ?? '';
  document.getElementById('fb_nama_ayah').value     = data.nama_ayah ?? '';
  document.getElementById('fb_alamat').value        = data.alamat ?? '';
  document.getElementById('balitaMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('balitaForm').action = UPDATE_BASE + id;
  openModal('modalBalita');
}

document.getElementById('modalBalita').addEventListener('click', function(e) {
  if (e.target === this) closeModal('modalBalita');
});
document.getElementById('modalTimbang').addEventListener('click', function(e) {
  if (e.target === this) closeModal('modalTimbang');
});

/* Reset modal balita ke mode tambah */
const origClose = closeModal;
function resetBalitaModal() {
  document.getElementById('wargaToggleWrap').style.display = '';
  document.getElementById('wargaSearchBlock').style.display = '';
  document.getElementById('searchBalita').value = '';
  document.getElementById('dropdownBalita').style.display = 'none';
  document.getElementById('balitaForm').reset();
  document.getElementById('balitaMethodField').innerHTML = '';
  document.getElementById('balitaForm').action = STORE_BALITA;
  setWargaMode(true);
}

document.querySelector('[onclick="openModal(\'modalBalita\')"]')?.addEventListener('click', resetBalitaModal);

function openRekam(balitaId, namaBalita, timbangData) {
  _currentBalitaId = balitaId;
  document.getElementById('modalTimbangTitle').textContent = 'Riwayat Timbang';
  document.getElementById('modalTimbangSub').textContent   = namaBalita;
  const rf = document.getElementById('rekamForm');
  if (rf) rf.action = REKAM_BASE + balitaId + '/rekam';
  renderTimbangList(timbangData ?? []);
  openModal('modalTimbang');
}

const giziColor = {
  normal:   'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
  kurang:   'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
  buruk:    'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
  lebih:    'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
  stunting: 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
};

function renderTimbangList(list) {
  const el = document.getElementById('riwayatTimbangList');
  if (!list.length) {
    el.innerHTML = '<p class="text-center text-sm text-slate-400 py-4">Belum ada riwayat timbang.</p>';
    return;
  }
  el.innerHTML = list.map(t => `
    <div class="flex items-start justify-between gap-3 py-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
      <div class="flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">${t.tanggal}</span>
          <span class="inline-block text-[11px] font-bold px-2 py-0.5 rounded-full ${giziColor[t.status_gizi] ?? ''}">
            ${t.label}
          </span>
        </div>
        <div class="text-xs text-slate-500 mt-1">
          BB: <b>${t.berat_badan} kg</b>
          ${t.tinggi_badan ? ' · TB: <b>' + t.tinggi_badan + ' cm</b>' : ''}
          ${t.lingkar_kepala ? ' · LK: <b>' + t.lingkar_kepala + ' cm</b>' : ''}
        </div>
        ${t.keterangan ? `<div class="text-xs text-slate-400 mt-0.5">${t.keterangan}</div>` : ''}
      </div>
      @if(Auth::user()->hasPermission('hapus.balita'))
      <form method="POST" action="${HAPUS_REKAM}${t.id}" class="m-0">
        <input type="hidden" name="_token" value="${CSRF}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" onclick="return confirm('Hapus rekam timbang ini?')"
          class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors flex-shrink-0">
          <i class="ti ti-trash text-xs"></i>
        </button>
      </form>
      @endif
    </div>`).join('');
}
</script>
@endsection
