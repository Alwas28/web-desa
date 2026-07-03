@extends('layouts.admin')
@section('title', 'Data Ibu Hamil')
@section('page-title', 'Ibu Hamil')
@section('page-sub', 'Pemantauan kesehatan ibu hamil dan kunjungan ANC')

@section('content')

{{-- ── Stats ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
  @php
  $statItems = [
    ['label' => 'Sedang Hamil',    'val' => $stats['hamil'],         'icon' => 'ti-heart-plus',    'color' => 'text-pink-600 dark:text-pink-400',    'bg' => 'bg-pink-50 dark:bg-pink-500/10'],
    ['label' => 'Risiko Tinggi',   'val' => $stats['risiko_tinggi'], 'icon' => 'ti-alert-triangle', 'color' => 'text-red-600 dark:text-red-400',      'bg' => 'bg-red-50 dark:bg-red-500/10'],
    ['label' => 'Risiko Rendah',   'val' => $stats['risiko_rendah'], 'icon' => 'ti-alert-circle',  'color' => 'text-amber-600 dark:text-amber-400',   'bg' => 'bg-amber-50 dark:bg-amber-500/10'],
    ['label' => 'Selesai/Lahir',   'val' => $stats['selesai'],       'icon' => 'ti-baby',          'color' => 'text-emerald-600 dark:text-emerald-400','bg' => 'bg-emerald-50 dark:bg-emerald-500/10'],
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
  <form method="GET" action="{{ route('admin.kesehatan.ibuHamil.index') }}" class="flex flex-wrap items-center gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIK…"
      class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 w-52">
    <select name="posyandu_id"
      class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
      <option value="">Semua Posyandu</option>
      @foreach($posyandus as $pos)
      <option value="{{ $pos->id }}" {{ request('posyandu_id') == $pos->id ? 'selected' : '' }}>{{ $pos->nama }}</option>
      @endforeach
    </select>
    <select name="status"
      class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
      <option value="">Semua Status</option>
      <option value="hamil" {{ request('status') === 'hamil' ? 'selected' : '' }}>Sedang Hamil</option>
      <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai/Melahirkan</option>
      <option value="keguguran" {{ request('status') === 'keguguran' ? 'selected' : '' }}>Keguguran</option>
    </select>
    <button type="submit" class="px-4 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-sm font-medium transition-colors">
      <i class="ti ti-search text-sm"></i> Cari
    </button>
    @if(request('q') || request('posyandu_id') || request('status'))
    <a href="{{ route('admin.kesehatan.ibuHamil.index') }}" class="px-3 h-9 flex items-center rounded-lg border border-slate-200 dark:border-slate-700 text-xs text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-x text-xs mr-1"></i> Reset
    </a>
    @endif
  </form>

  @if(Auth::user()->hasPermission('tambah.ibu-hamil'))
  <button type="button" onclick="openModal('modalBumil')"
    class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus"></i> Tambah Ibu Hamil
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
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama & Suami</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Posyandu</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">HPHT / HPL</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Usia Kehamilan</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">ANC</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Risiko</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
          <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($ibuHamils as $i => $bml)
        @php
        $risikoColor = match($bml->status_risiko) {
            'risiko_tinggi' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            'risiko_rendah' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            default         => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        };
        $statusColor = match($bml->status) {
            'hamil'     => 'bg-pink-100 text-pink-700 dark:bg-pink-500/10 dark:text-pink-400',
            'selesai'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            'keguguran' => 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400',
        };
        @endphp
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
          <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
          <td class="px-4 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $bml->nama }}</div>
            @if($bml->nama_suami)<div class="text-xs text-slate-400 mt-0.5">Suami: {{ $bml->nama_suami }}</div>@endif
            @if($bml->nik)<div class="text-xs text-slate-400">NIK: {{ $bml->nik }}</div>@endif
          </td>
          <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $bml->posyandu?->nama ?? '—' }}</td>
          <td class="px-4 py-3">
            <div class="text-xs text-slate-700 dark:text-slate-300">{{ $bml->hpht->format('d M Y') }}</div>
            @if($bml->hpl)
            <div class="text-xs text-slate-400 mt-0.5">HPL: {{ $bml->hpl->format('d M Y') }}</div>
            @endif
          </td>
          <td class="px-4 py-3">
            @if($bml->status === 'hamil' && $bml->usia_kehamilan !== null)
            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $bml->usia_kehamilan }}</span>
            <span class="text-xs text-slate-400"> minggu</span>
            @else
            <span class="text-xs text-slate-400">—</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $bml->kunjungan->count() }}</span>
            <span class="text-xs text-slate-400"> kunjungan</span>
          </td>
          <td class="px-4 py-3">
            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full {{ $risikoColor }}">
              {{ $bml->label_status_risiko }}
            </span>
          </td>
          <td class="px-4 py-3">
            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusColor }}">
              {{ $bml->label_status }}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center justify-end gap-1.5">
              @if(Auth::user()->hasPermission('tambah.ibu-hamil') || Auth::user()->hasPermission('hapus.ibu-hamil'))
              <button type="button"
                onclick="openKunjungan({{ $bml->id }}, @js($bml->nama), @js($KUNJUNGAN[$bml->id] ?? []))"
                class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-pink-200 dark:border-pink-700 text-pink-600 dark:text-pink-400 text-xs font-medium hover:bg-pink-50 dark:hover:bg-pink-500/10 transition-colors">
                <i class="ti ti-stethoscope text-xs"></i> ANC
              </button>
              @endif
              @if(Auth::user()->hasPermission('edit.ibu-hamil'))
              <button type="button"
                onclick="openEditBumil({{ $bml->id }}, @js($bml->toArray()))"
                class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="ti ti-edit text-xs"></i>
              </button>
              @endif
              @if(Auth::user()->hasPermission('hapus.ibu-hamil'))
              <form method="POST" action="{{ route('admin.kesehatan.ibuHamil.destroy', $bml) }}" class="m-0">
                @csrf @method('DELETE')
                <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Data ibu hamil \'{{ addslashes($bml->nama) }}\' akan dihapus.', 'Hapus Data')"
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
          <td colspan="9" class="px-4 py-16 text-center">
            <i class="ti ti-heart-plus text-4xl text-slate-300 dark:text-slate-600 block mb-2"></i>
            <p class="text-sm text-slate-400">Belum ada data ibu hamil.</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ─── Modal Tambah / Edit Ibu Hamil ─── --}}
<div id="modalBumil" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <h3 id="modalBumilTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Data Ibu Hamil</h3>
      <button onclick="closeModal('modalBumil')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
      <form id="bumilForm" method="POST" action="{{ route('admin.kesehatan.ibuHamil.store') }}" class="space-y-4">
        @csrf
        <div id="bumilMethodField"></div>

        {{-- ── Toggle warga / non-warga ── --}}
        <div id="bumilWargaToggleWrap">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status Kependudukan</label>
          <div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-lg w-fit">
            <button type="button" id="btnBumilWarga" onclick="setBumilWargaMode(true)"
              class="px-4 h-7 rounded-md text-xs font-semibold transition-all bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm">
              Warga Desa
            </button>
            <button type="button" id="btnBumilNonWarga" onclick="setBumilWargaMode(false)"
              class="px-4 h-7 rounded-md text-xs font-semibold transition-all text-slate-500 dark:text-slate-400">
              Non-Warga
            </button>
          </div>
        </div>

        {{-- ── Pencarian warga ── --}}
        <div id="bumilWargaSearchBlock">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari Nama Ibu dari Data Penduduk</label>
          <div class="relative">
            <input type="text" id="searchBumil" autocomplete="off"
              placeholder="Ketik nama atau NIK ibu…"
              oninput="filterBumilWarga()"
              onfocus="showDropdown('dropdownBumil')"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 pr-8">
            <i class="ti ti-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
            <div id="dropdownBumil"
              class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden"
              style="display:none;max-height:200px;overflow-y:auto"></div>
          </div>
          <p class="text-[10px] text-slate-400 mt-1">Pilih dari daftar untuk auto-isi data ibu. Hanya menampilkan warga perempuan.</p>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Ibu <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" id="bml_nama" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Nama lengkap ibu hamil">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">NIK</label>
            <input type="text" name="nik" id="bml_nik" maxlength="20"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Opsional">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="bml_tgl_lahir"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Suami</label>
            <input type="text" name="nama_suami" id="bml_suami" maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Opsional">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">HPHT <span class="text-rose-500">*</span></label>
            <input type="date" name="hpht" id="bml_hpht" required max="{{ date('Y-m-d') }}"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <p class="text-[10px] text-slate-400 mt-0.5">Hari Pertama Haid Terakhir</p>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">HPL</label>
            <input type="date" name="hpl" id="bml_hpl"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <p class="text-[10px] text-slate-400 mt-0.5">Hari Perkiraan Lahir</p>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status Risiko <span class="text-rose-500">*</span></label>
            <select name="status_risiko" id="bml_risiko" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="normal">Normal</option>
              <option value="risiko_rendah">Risiko Rendah</option>
              <option value="risiko_tinggi">Risiko Tinggi</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status Kehamilan <span class="text-rose-500">*</span></label>
            <select name="status" id="bml_status" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="hamil">Sedang Hamil</option>
              <option value="selesai">Selesai/Melahirkan</option>
              <option value="keguguran">Keguguran</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Posyandu</label>
            <select name="posyandu_id" id="bml_posyandu"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">— Pilih Posyandu —</option>
              @foreach($posyandus as $pos)
              <option value="{{ $pos->id }}">{{ $pos->nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Alamat</label>
            <input type="text" name="alamat" id="bml_alamat" maxlength="300"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="Alamat tempat tinggal">
          </div>
          <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Keterangan</label>
            <textarea name="keterangan" id="bml_ket" rows="2" maxlength="1000"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none resize-none focus:ring-2 focus:ring-brand-500"
              placeholder="Catatan medis atau kondisi khusus…"></textarea>
          </div>
        </div>

        <div class="flex gap-2 pt-2">
          <button type="submit"
            class="flex-1 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
            Simpan
          </button>
          <button type="button" onclick="closeModal('modalBumil')"
            class="px-5 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ─── Modal Riwayat Kunjungan ANC ─── --}}
<div id="modalANC" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <div>
        <h3 id="modalANCTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Riwayat Kunjungan ANC</h3>
        <p id="modalANCSub" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <button onclick="closeModal('modalANC')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-4">

      {{-- Tambah Kunjungan --}}
      @if(Auth::user()->hasPermission('tambah.ibu-hamil'))
      <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
        <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-3">Tambah Kunjungan ANC</h4>
        <form id="kunjunganForm" method="POST" action="" class="space-y-3">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal <span class="text-rose-500">*</span></label>
              <input type="date" name="tanggal" id="kf_tanggal" required max="{{ date('Y-m-d') }}"
                value="{{ date('Y-m-d') }}"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Kunjungan ke-</label>
              <input type="number" name="kunjungan_ke" id="kf_ke" min="1" max="20"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="Otomatis jika kosong">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Tekanan Darah</label>
              <input type="text" name="tekanan_darah" id="kf_td" maxlength="20"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="120/80">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Berat Badan (kg)</label>
              <input type="number" name="berat_badan" id="kf_bb" step="0.1" min="20" max="200"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="60.5">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Usia Kehamilan (minggu)</label>
              <input type="number" name="usia_kehamilan" id="kf_uk" min="1" max="45"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="20">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Keterangan</label>
              <input type="text" name="keterangan" id="kf_ket" maxlength="500"
                class="w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="Catatan...">
            </div>
          </div>
          <button type="submit"
            class="w-full h-8 rounded-lg bg-pink-600 hover:bg-pink-700 text-white text-xs font-semibold transition-colors">
            <i class="ti ti-plus text-xs mr-1"></i> Simpan Kunjungan
          </button>
        </form>
      </div>
      @endif

      {{-- Riwayat list --}}
      <div id="riwayatANCList"></div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
@php
$pendudukBumilJs = $penduduk->map(fn($p) => [
    'id'            => $p->id,
    'nik'           => $p->nik ?? '',
    'nama'          => $p->nama_lengkap,
    'tanggal_lahir' => $p->tanggal_lahir?->format('Y-m-d') ?? '',
    'jenis_kelamin' => $p->jenis_kelamin,
]);
$kunjunganJs = $ibuHamils->mapWithKeys(fn($bml) => [
    $bml->id => $bml->kunjungan->map(fn($k) => [
        'id'              => $k->id,
        'tanggal'         => $k->tanggal->format('d M Y'),
        'kunjungan_ke'    => $k->kunjungan_ke,
        'tekanan_darah'   => $k->tekanan_darah,
        'berat_badan'     => $k->berat_badan,
        'usia_kehamilan'  => $k->usia_kehamilan,
        'keterangan'      => $k->keterangan,
    ])->values()->all(),
]);
@endphp
<script>
const PENDUDUK_BUMIL = @json($pendudukBumilJs);
let _bumilResults    = [];
const KUNJUNGAN      = @json($kunjunganJs);
const STORE_BUMIL    = '{{ route('admin.kesehatan.ibuHamil.store') }}';
const UPDATE_BUMIL   = '{{ url('admin/kesehatan/ibu-hamil') }}/';
const KUNJUNGAN_BASE = '{{ url('admin/kesehatan/ibu-hamil') }}/';
const HAPUS_KNJ      = '{{ url('admin/kesehatan/kunjungan') }}/';
const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

['modalBumil','modalANC'].forEach(id => {
  document.getElementById(id).addEventListener('click', function(e) {
    if (e.target === this) closeModal(id);
  });
});

function showDropdown(id) {
  const el = document.getElementById(id);
  if (el && el.innerHTML.trim()) el.style.display = '';
}

/* ── Warga / Non-warga toggle bumil ── */
function setBumilWargaMode(isWarga) {
  document.getElementById('bumilWargaSearchBlock').style.display = isWarga ? '' : 'none';

  const btnW  = document.getElementById('btnBumilWarga');
  const btnNW = document.getElementById('btnBumilNonWarga');
  btnW.className  = 'px-4 h-7 rounded-md text-xs font-semibold transition-all ' +
    (isWarga ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400');
  btnNW.className = 'px-4 h-7 rounded-md text-xs font-semibold transition-all ' +
    (!isWarga ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400');

  if (!isWarga) {
    document.getElementById('bml_nama').value      = '';
    document.getElementById('bml_nik').value       = '';
    document.getElementById('bml_tgl_lahir').value = '';
    document.getElementById('searchBumil').value   = '';
  }
}

function filterBumilWarga() {
  const q    = document.getElementById('searchBumil').value.toLowerCase().trim();
  const drop = document.getElementById('dropdownBumil');

  _bumilResults = q.length < 1 ? [] : PENDUDUK_BUMIL.filter(p =>
    p.nama.toLowerCase().includes(q) || p.nik.includes(q)
  ).slice(0, 20);

  drop.innerHTML = _bumilResults.length
    ? _bumilResults.map((p, i) => `
      <div class="px-3 py-2 cursor-pointer hover:bg-brand-50 dark:hover:bg-brand-500/10 text-sm transition-colors border-b border-slate-100 dark:border-slate-700 last:border-0"
        onmousedown="pilihWargaBumil(${i})">
        <div class="font-medium text-slate-800 dark:text-slate-100">${p.nama}</div>
        <div class="text-xs text-slate-400">${p.nik || 'NIK —'} · ${p.tanggal_lahir || '—'}</div>
      </div>`).join('')
    : `<div class="px-3 py-3 text-sm text-slate-400 text-center">Tidak ditemukan</div>`;

  drop.style.display = q.length >= 1 ? '' : 'none';
}

function pilihWargaBumil(i) {
  const p = _bumilResults[i];
  if (!p) return;
  document.getElementById('bml_nama').value      = p.nama;
  document.getElementById('bml_nik').value       = p.nik;
  document.getElementById('bml_tgl_lahir').value = p.tanggal_lahir;
  document.getElementById('searchBumil').value   = p.nama;
  document.getElementById('dropdownBumil').style.display = 'none';
}

document.addEventListener('click', e => {
  if (!e.target.closest('#bumilWargaSearchBlock')) {
    const d = document.getElementById('dropdownBumil');
    if (d) d.style.display = 'none';
  }
});

function openEditBumil(id, data) {
  document.getElementById('modalBumilTitle').textContent          = 'Edit Data Ibu Hamil';
  document.getElementById('bumilWargaToggleWrap').style.display  = 'none';
  document.getElementById('bumilWargaSearchBlock').style.display = 'none';

  document.getElementById('bml_nama').value      = data.nama ?? '';
  document.getElementById('bml_nik').value       = data.nik ?? '';
  document.getElementById('bml_tgl_lahir').value = data.tanggal_lahir ?? '';
  document.getElementById('bml_suami').value     = data.nama_suami ?? '';
  document.getElementById('bml_hpht').value      = data.hpht ?? '';
  document.getElementById('bml_hpl').value       = data.hpl ?? '';
  document.getElementById('bml_risiko').value    = data.status_risiko ?? 'normal';
  document.getElementById('bml_status').value    = data.status ?? 'hamil';
  document.getElementById('bml_posyandu').value  = data.posyandu_id ?? '';
  document.getElementById('bml_alamat').value    = data.alamat ?? '';
  document.getElementById('bml_ket').value       = data.keterangan ?? '';
  document.getElementById('bumilMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('bumilForm').action    = UPDATE_BUMIL + id;
  openModal('modalBumil');
}

/* Reset ke mode tambah saat klik tombol Tambah Ibu Hamil */
document.querySelector('[onclick="openModal(\'modalBumil\')"]')?.addEventListener('click', () => {
  document.getElementById('modalBumilTitle').textContent = 'Tambah Data Ibu Hamil';
  document.getElementById('bumilWargaToggleWrap').style.display  = '';
  document.getElementById('bumilWargaSearchBlock').style.display = '';
  document.getElementById('searchBumil').value = '';
  document.getElementById('bumilForm').reset();
  document.getElementById('bumilMethodField').innerHTML = '';
  document.getElementById('bumilForm').action = STORE_BUMIL;
  setBumilWargaMode(true);
});

function openKunjungan(id, nama, kunjunganData) {
  document.getElementById('modalANCTitle').textContent = 'Riwayat Kunjungan ANC';
  document.getElementById('modalANCSub').textContent   = nama;
  const kf = document.getElementById('kunjunganForm');
  if (kf) kf.action = KUNJUNGAN_BASE + id + '/kunjungan';
  renderKunjunganList(kunjunganData ?? []);
  openModal('modalANC');
}

function renderKunjunganList(list) {
  const el = document.getElementById('riwayatANCList');
  if (!list.length) {
    el.innerHTML = '<p class="text-center text-sm text-slate-400 py-4">Belum ada riwayat kunjungan ANC.</p>';
    return;
  }
  el.innerHTML = list.map(k => `
    <div class="flex items-start justify-between gap-3 py-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
      <div class="flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">${k.tanggal}</span>
          ${k.kunjungan_ke ? `<span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-pink-100 text-pink-700 dark:bg-pink-500/10 dark:text-pink-400">ANC ke-${k.kunjungan_ke}</span>` : ''}
        </div>
        <div class="text-xs text-slate-500 mt-1 flex flex-wrap gap-x-3">
          ${k.tekanan_darah ? `<span>TD: <b>${k.tekanan_darah}</b></span>` : ''}
          ${k.berat_badan ? `<span>BB: <b>${k.berat_badan} kg</b></span>` : ''}
          ${k.usia_kehamilan ? `<span>UK: <b>${k.usia_kehamilan} minggu</b></span>` : ''}
        </div>
        ${k.keterangan ? `<div class="text-xs text-slate-400 mt-0.5">${k.keterangan}</div>` : ''}
      </div>
      @if(Auth::user()->hasPermission('hapus.ibu-hamil'))
      <form method="POST" action="${HAPUS_KNJ}${k.id}" class="m-0">
        <input type="hidden" name="_token" value="${CSRF}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" onclick="return confirm('Hapus data kunjungan ini?')"
          class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors flex-shrink-0">
          <i class="ti ti-trash text-xs"></i>
        </button>
      </form>
      @endif
    </div>`).join('');
}
</script>
@endsection
