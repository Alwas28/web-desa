@extends('layouts.admin')

@section('title', 'Detail Penerima Bansos — ' . $penerimaBanso->nama)
@section('page-title', 'Detail Penerima Bansos')
@section('page-sub', 'Informasi dan riwayat penyaluran bantuan sosial')

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

<div class="mb-4">
  <a href="{{ route('admin.bansos.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">
    <i class="ti ti-arrow-left"></i> Kembali ke Daftar
  </a>
</div>

<div class="grid grid-cols-3 gap-4">
  {{-- Info Penerima --}}
  <div class="col-span-1 space-y-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-slate-800 dark:text-slate-100">Informasi Penerima</h3>
        <button onclick="openEditModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
          <i class="ti ti-pencil text-sm"></i>
        </button>
      </div>

      <div class="flex flex-col items-center mb-5">
        <div class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-500/10 grid place-items-center mb-3">
          <i class="ti ti-user text-2xl text-blue-600 dark:text-blue-400"></i>
        </div>
        <div class="font-bold text-slate-800 dark:text-slate-100 text-center">{{ $penerimaBanso->nama }}</div>
        <span class="mt-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
          {{ $penerimaBanso->status === 'aktif' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : '' }}
          {{ $penerimaBanso->status === 'menunggu' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30' : '' }}
          {{ $penerimaBanso->status === 'nonaktif' ? 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700' : '' }}">
          {{ $penerimaBanso->status_label }}
        </span>
      </div>

      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Jenis Bansos</dt>
          <dd class="font-medium text-slate-700 dark:text-slate-200">{{ $penerimaBanso->jenisBansos->nama ?? '-' }}</dd>
        </div>
        @if($penerimaBanso->nik)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">NIK</dt>
          <dd class="font-mono text-slate-700 dark:text-slate-200">{{ $penerimaBanso->nik }}</dd>
        </div>
        @endif
        @if($penerimaBanso->no_kk)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">No. KK</dt>
          <dd class="font-mono text-slate-700 dark:text-slate-200">{{ $penerimaBanso->no_kk }}</dd>
        </div>
        @endif
        @if($penerimaBanso->alamat)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Alamat</dt>
          <dd class="text-slate-700 dark:text-slate-200">
            {{ $penerimaBanso->alamat }}
            @if($penerimaBanso->rt && $penerimaBanso->rw)
              <span class="text-slate-400">RT {{ $penerimaBanso->rt }}/RW {{ $penerimaBanso->rw }}</span>
            @endif
          </dd>
        </div>
        @endif
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Jenis Penerimaan</dt>
          <dd>
            @if($penerimaBanso->jenis_penerimaan === 'non_tunai')
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400 border border-violet-200 dark:border-violet-500/30">
                <i class="ti ti-credit-card text-xs"></i> Non Tunai
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                <i class="ti ti-cash text-xs"></i> Tunai
              </span>
            @endif
          </dd>
        </div>
        @if($penerimaBanso->tahun_ditetapkan)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Tahun Ditetapkan</dt>
          <dd class="text-slate-700 dark:text-slate-200">{{ $penerimaBanso->tahun_ditetapkan }}</dd>
        </div>
        @endif
        @if($penerimaBanso->keterangan)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Keterangan</dt>
          <dd class="text-slate-700 dark:text-slate-200 text-xs">{{ $penerimaBanso->keterangan }}</dd>
        </div>
        @endif
        @if($penerimaBanso->penduduk)
        <div>
          <dt class="text-xs text-slate-400 mb-0.5">Data Penduduk</dt>
          <dd>
            <a href="{{ route('admin.penduduk.show', $penerimaBanso->penduduk) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">
              <i class="ti ti-external-link"></i> Lihat profil penduduk
            </a>
          </dd>
        </div>
        @endif
      </dl>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-3">
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-list-check text-blue-600 dark:text-blue-400"></i>
        </div>
        <div>
          <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total_penyaluran'] }}</div>
          <div class="text-xs text-slate-400">Total Penyaluran</div>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-cash text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div>
          <div class="text-base font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</div>
          <div class="text-xs text-slate-400">Total Tersalurkan</div>
        </div>
      </div>
      @if($stats['terakhir'])
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-violet-50 dark:bg-violet-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-calendar-event text-violet-600 dark:text-violet-400"></i>
        </div>
        <div>
          <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $stats['terakhir']->periode }}</div>
          <div class="text-xs text-slate-400">Penyaluran Terakhir</div>
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- Riwayat Penyaluran --}}
  <div class="col-span-2">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
        <h3 class="font-semibold text-slate-800 dark:text-slate-100">Riwayat Penyaluran</h3>
        <button onclick="openSalurModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium transition-colors">
          <i class="ti ti-plus"></i> Catat Penyaluran
        </button>
      </div>

      @if($penerimaBanso->penyaluran->isEmpty())
        <div class="py-14 text-center text-slate-400 dark:text-slate-600">
          <i class="ti ti-clipboard-list text-4xl block mb-2"></i>
          <p class="text-sm">Belum ada riwayat penyaluran.</p>
          <button onclick="openSalurModal()" class="mt-3 text-blue-500 hover:underline text-xs">Catat penyaluran pertama</button>
        </div>
      @else
        <table class="w-full text-sm">
          <thead class="bg-slate-50 dark:bg-slate-800/60">
            <tr>
              <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Periode</th>
              <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Tanggal</th>
              <th class="text-left px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Nilai</th>
              <th class="text-center px-4 py-3 font-medium text-slate-500 dark:text-slate-400">Status</th>
              <th class="px-4 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($penerimaBanso->penyaluran->sortByDesc('created_at') as $sal)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="px-4 py-3">
                <div class="font-medium text-slate-800 dark:text-slate-100">{{ $sal->periode }}</div>
                @if($sal->keterangan)
                  <div class="text-xs text-slate-400 mt-0.5">{{ $sal->keterangan }}</div>
                @endif
              </td>
              <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                {{ $sal->tanggal_penyaluran ? $sal->tanggal_penyaluran->format('d/m/Y') : '-' }}
              </td>
              <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-200">
                {{ $sal->nilai_formatted }}
              </td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                  {{ $sal->status === 'disalurkan'  ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : '' }}
                  {{ $sal->status === 'terjadwal'   ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30' : '' }}
                  {{ $sal->status === 'tidak_hadir' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30' : '' }}
                  {{ $sal->status === 'ditolak'     ? 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30' : '' }}">
                  {{ $sal->status_label }}
                </span>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-1 justify-end">
                  <button onclick='openEditSalur(@json($sal))' class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">
                    <i class="ti ti-pencil text-sm"></i>
                  </button>
                  <form method="POST" action="{{ route('admin.bansos.penyaluran.destroy', [$penerimaBanso, $sal]) }}" onsubmit="return confirm('Hapus data penyaluran ini?')">
                    @csrf @method('DELETE')
                    <button class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
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
  </div>
</div>

{{-- Modal Catat/Edit Penyaluran --}}
<div id="salurOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md mx-4 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
      <h3 id="salurTitle" class="font-semibold text-slate-800 dark:text-slate-100">Catat Penyaluran</h3>
      <button onclick="closeSalurModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
        <i class="ti ti-x text-lg"></i>
      </button>
    </div>
    <form id="salurForm" method="POST" class="p-5 space-y-4">
      @csrf
      <input type="hidden" name="_method" id="salurMethod" value="POST">
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Periode <span class="text-red-500">*</span></label>
        <input type="text" name="periode" id="s_periode" placeholder="mis. Januari 2026 / Triwulan I 2026" required
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Tanggal Penyaluran</label>
          <input type="date" name="tanggal_penyaluran" id="s_tanggal"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nilai Disalurkan (Rp)</label>
          <input type="number" name="nilai_disalurkan" id="s_nilai" min="0" step="1000"
            placeholder="{{ $penerimaBanso->jenisBansos->nilai_bantuan ?? '0' }}"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status" id="s_status" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
          <option value="terjadwal">Terjadwal</option>
          <option value="disalurkan">Disalurkan</option>
          <option value="tidak_hadir">Tidak Hadir</option>
          <option value="ditolak">Ditolak</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Keterangan</label>
        <textarea name="keterangan" id="s_keterangan" rows="2" placeholder="Catatan tambahan..."
          class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none"></textarea>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeSalurModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Penerima (inline) --}}
<div id="editOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
  <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-xl mx-4 border border-slate-200 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Edit Penerima</h3>
      <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
        <i class="ti ti-x text-lg"></i>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.bansos.update', $penerimaBanso) }}" class="p-5 space-y-4">
      @csrf @method('PUT')
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Nama <span class="text-red-500">*</span></label>
          <input type="text" name="nama" value="{{ $penerimaBanso->nama }}" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">NIK</label>
          <input type="text" name="nik" value="{{ $penerimaBanso->nik }}" maxlength="16" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">No. KK</label>
          <input type="text" name="no_kk" value="{{ $penerimaBanso->no_kk }}" maxlength="16" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
          <textarea name="alamat" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none">{{ $penerimaBanso->alamat }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">RT</label>
          <input type="text" name="rt" value="{{ $penerimaBanso->rt }}" maxlength="5" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">RW</label>
          <input type="text" name="rw" value="{{ $penerimaBanso->rw }}" maxlength="5" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Tahun Ditetapkan</label>
          <input type="number" name="tahun_ditetapkan" value="{{ $penerimaBanso->tahun_ditetapkan }}" min="2000" max="2100" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Status</label>
          <select name="status" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            <option value="menunggu" @selected($penerimaBanso->status==='menunggu')>Menunggu</option>
            <option value="aktif" @selected($penerimaBanso->status==='aktif')>Aktif</option>
            <option value="nonaktif" @selected($penerimaBanso->status==='nonaktif')>Nonaktif</option>
          </select>
        </div>
        <input type="hidden" name="jenis_bansos_id" value="{{ $penerimaBanso->jenis_bansos_id }}">
        <div class="col-span-2">
          <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Jenis Penerimaan <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 rounded-xl border-2 cursor-pointer p-3 transition-colors
              {{ $penerimaBanso->jenis_penerimaan !== 'non_tunai' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10' : 'border-slate-200 dark:border-slate-700' }}">
              <input type="radio" name="jenis_penerimaan" value="tunai" class="accent-emerald-600"
                @checked($penerimaBanso->jenis_penerimaan !== 'non_tunai')>
              <div>
                <div class="flex items-center gap-1.5 text-sm font-medium text-slate-700 dark:text-slate-200">
                  <i class="ti ti-cash text-emerald-600 dark:text-emerald-400"></i> Tunai
                </div>
                <div class="text-xs text-slate-400 mt-0.5">Uang tunai langsung</div>
              </div>
            </label>
            <label class="flex items-center gap-3 rounded-xl border-2 cursor-pointer p-3 transition-colors
              {{ $penerimaBanso->jenis_penerimaan === 'non_tunai' ? 'border-violet-500 bg-violet-50 dark:bg-violet-500/10' : 'border-slate-200 dark:border-slate-700' }}">
              <input type="radio" name="jenis_penerimaan" value="non_tunai" class="accent-violet-600"
                @checked($penerimaBanso->jenis_penerimaan === 'non_tunai')>
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
          <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none">{{ $penerimaBanso->keterangan }}</textarea>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">Simpan</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
const SALUR_STORE = '{{ route('admin.bansos.salurkan', $penerimaBanso) }}';
const PENERIMA_ID = {{ $penerimaBanso->id }};

function openSalurModal() {
  document.getElementById('salurTitle').textContent = 'Catat Penyaluran';
  document.getElementById('salurForm').action = SALUR_STORE;
  document.getElementById('salurMethod').value = 'POST';
  ['s_periode','s_tanggal','s_nilai','s_keterangan'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('s_status').value = 'disalurkan';
  document.getElementById('salurOverlay').classList.remove('hidden');
  document.getElementById('salurOverlay').classList.add('flex');
}

function openEditSalur(data) {
  document.getElementById('salurTitle').textContent = 'Edit Penyaluran';
  document.getElementById('salurForm').action = `/admin/bansos/${PENERIMA_ID}/penyaluran/${data.id}`;
  document.getElementById('salurMethod').value = 'PUT';
  document.getElementById('s_periode').value     = data.periode || '';
  document.getElementById('s_tanggal').value     = data.tanggal_penyaluran ? data.tanggal_penyaluran.substring(0, 10) : '';
  document.getElementById('s_nilai').value       = data.nilai_disalurkan || '';
  document.getElementById('s_status').value      = data.status || 'disalurkan';
  document.getElementById('s_keterangan').value  = data.keterangan || '';
  document.getElementById('salurOverlay').classList.remove('hidden');
  document.getElementById('salurOverlay').classList.add('flex');
}

function closeSalurModal() {
  document.getElementById('salurOverlay').classList.add('hidden');
  document.getElementById('salurOverlay').classList.remove('flex');
}

function openEditModal() {
  document.getElementById('editOverlay').classList.remove('hidden');
  document.getElementById('editOverlay').classList.add('flex');
}

function closeEditModal() {
  document.getElementById('editOverlay').classList.add('hidden');
  document.getElementById('editOverlay').classList.remove('flex');
}

setTimeout(() => { const m = document.getElementById('flashMsg'); if (m) m.style.display = 'none'; }, 4000);
</script>
@endsection
