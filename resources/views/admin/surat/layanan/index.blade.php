@extends('layouts.admin')

@section('title', 'Layanan Surat')
@section('page-title', 'Layanan Surat')
@section('page-sub', 'Kelola pengajuan surat keterangan warga desa')

@section('content')

{{-- Notifikasi pending --}}
@if($counts['diajukan'] > 0)
<div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20">
  <i class="ti ti-bell-ringing text-amber-500 text-lg flex-shrink-0 mt-0.5"></i>
  <div class="flex-1 text-sm">
    <span class="font-semibold text-amber-800 dark:text-amber-300">{{ $counts['diajukan'] }} pengajuan</span>
    <span class="text-amber-700 dark:text-amber-400"> menunggu diteruskan ke Kepala Desa.</span>
  </div>
  <a href="{{ route('admin.layanan-surat.index', ['status'=>'diajukan']) }}"
     class="flex-shrink-0 text-xs font-semibold text-amber-700 dark:text-amber-300 hover:underline">Lihat &rarr;</a>
</div>
@endif

@if($counts['menunggu_ttd'] > 0 && $canSetujui)
<div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20">
  <i class="ti ti-signature text-violet-500 text-lg flex-shrink-0 mt-0.5"></i>
  <div class="flex-1 text-sm">
    <span class="font-semibold text-violet-800 dark:text-violet-300">{{ $counts['menunggu_ttd'] }} surat</span>
    <span class="text-violet-700 dark:text-violet-400"> menunggu persetujuan Kepala Desa.</span>
  </div>
  <a href="{{ route('admin.layanan-surat.index', ['status'=>'menunggu_ttd']) }}"
     class="flex-shrink-0 text-xs font-semibold text-violet-700 dark:text-violet-300 hover:underline">Tinjau &rarr;</a>
</div>
@endif

@if($counts['disetujui'] > 0 && $canProses)
<div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20">
  <i class="ti ti-circle-check text-indigo-500 text-lg flex-shrink-0 mt-0.5"></i>
  <div class="flex-1 text-sm">
    <span class="font-semibold text-indigo-800 dark:text-indigo-300">{{ $counts['disetujui'] }} surat</span>
    <span class="text-indigo-700 dark:text-indigo-400"> telah disetujui kades — tambahkan nomor surat untuk menyelesaikan.</span>
  </div>
  <a href="{{ route('admin.layanan-surat.index', ['status'=>'disetujui']) }}"
     class="flex-shrink-0 text-xs font-semibold text-indigo-700 dark:text-indigo-300 hover:underline">Selesaikan &rarr;</a>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-3 md:grid-cols-6 gap-3">
  @foreach([
    ['Total',          $counts['total'],        'slate',  'ti-file-description'],
    ['Menunggu',       $counts['diajukan'],     'amber',  'ti-clock'],
    ['Menunggu Kades', $counts['menunggu_ttd'], 'violet', 'ti-signature'],
    ['Disetujui',      $counts['disetujui'],    'indigo', 'ti-circle-check'],
    ['Selesai',        $counts['selesai'],      'emerald','ti-check'],
    ['Ditolak',        $counts['ditolak'],      'rose',   'ti-x'],
  ] as [$label, $count, $color, $icon])
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-3 flex items-center gap-2">
    <div class="w-7 h-7 rounded-lg grid place-items-center flex-shrink-0 bg-{{ $color }}-50 dark:bg-{{ $color }}-500/10">
      <i class="ti {{ $icon }} text-sm text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
    </div>
    <div>
      <div class="text-base font-bold text-slate-900 dark:text-slate-100 leading-none">{{ number_format($count) }}</div>
      <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">{{ $label }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.layanan-surat.index') }}" class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIK…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 placeholder:text-slate-400">
  </div>
  <select name="status"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Status</option>
    <option value="diajukan"     {{ request('status')==='diajukan'?'selected':'' }}>Menunggu</option>
    <option value="menunggu_ttd" {{ request('status')==='menunggu_ttd'?'selected':'' }}>Menunggu Kades</option>
    <option value="disetujui"    {{ request('status')==='disetujui'?'selected':'' }}>Disetujui Kades</option>
    <option value="selesai"      {{ request('status')==='selesai'?'selected':'' }}>Selesai</option>
    <option value="ditolak"      {{ request('status')==='ditolak'?'selected':'' }}>Ditolak</option>
  </select>
  <select name="jenis"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Jenis</option>
    @foreach($jenisList as $js)
      <option value="{{ $js->nama }}" {{ request('jenis')===$js->nama?'selected':'' }}>{{ $js->nama }}</option>
    @endforeach
  </select>
  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i>
  </button>
  @if(request()->hasAny(['q','status','jenis']))
  <a href="{{ route('admin.layanan-surat.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
  @if($canBuat)
  <button type="button" onclick="openTambahModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus text-base"></i> Buat Surat
  </button>
  @endif
</form>

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($surats->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-file-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400">
        {{ request()->hasAny(['q','status','jenis']) ? 'Tidak ada surat yang sesuai filter.' : 'Belum ada pengajuan surat.' }}
      </p>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 uppercase tracking-wide text-left">
            <th class="px-4 py-3">Pemohon</th>
            <th class="px-4 py-3">Jenis Surat</th>
            <th class="px-4 py-3">Keperluan</th>
            <th class="px-4 py-3">Tgl Pengajuan</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($surats as $s)
          @php
            $isFinal    = in_array($s->status, ['selesai','ditolak']);
            $needsAdmin = $s->status === 'disetujui' && $canProses;
            $color      = $s->status_color;
            $canAct     = (!$isFinal && ($canProses || $canSetujui)) || $needsAdmin;
            $modalData  = [
              'id'              => $s->id,
              'token'           => $s->token,
              'jenis_surat'     => $s->jenis_surat,
              'keperluan'       => $s->keperluan,
              'keterangan'      => $s->keterangan,
              'status'          => $s->status,
              'status_label'    => $s->status_label,
              'status_color'    => $color,
              'nomor_surat'     => $s->nomor_surat,
              'nama_kades'      => $s->nama_kades,
              'jabatan_kades'   => $s->jabatan_kades,
              'catatan'         => $s->catatan,
              'tanggal_selesai' => $s->tanggal_selesai?->format('d M Y, H:i'),
              'verifikasi_url'  => $s->token ? route('surat.verifikasi', $s->token) : null,
              'penduduk' => $s->penduduk ? [
                'id'          => $s->penduduk->id,
                'nik'         => $s->penduduk->nik,
                'nama'        => $s->penduduk->nama_lengkap,
                'ttl'         => $s->penduduk->tempat_lahir.', '.$s->penduduk->tanggal_lahir->format('d/m/Y'),
                'jk'          => $s->penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'agama'       => $s->penduduk->agama,
                'status_kawin'=> $s->penduduk->status_perkawinan_label,
                'pekerjaan'   => $s->penduduk->pekerjaan ?: '-',
              ] : null,
            ];
          @endphp
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors
            {{ $s->status === 'diajukan' ? 'bg-amber-50/40 dark:bg-amber-500/5' : '' }}
            {{ $s->status === 'menunggu_ttd' ? 'bg-violet-50/30 dark:bg-violet-500/5' : '' }}
            {{ $s->status === 'disetujui' ? 'bg-indigo-50/30 dark:bg-indigo-500/5' : '' }}">
            <td class="px-4 py-3">
              @if($s->penduduk)
                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                  @if($s->status === 'diajukan')<span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse flex-shrink-0"></span>@endif
                  @if($s->status === 'menunggu_ttd')<span class="w-1.5 h-1.5 rounded-full bg-violet-500 animate-pulse flex-shrink-0"></span>@endif
                  @if($s->status === 'disetujui')<span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse flex-shrink-0"></span>@endif
                  {{ $s->penduduk->nama_lengkap }}
                </div>
                <div class="text-xs font-mono text-slate-400">{{ $s->penduduk->nik }}</div>
              @else
                <span class="text-xs text-slate-400 italic">Pemohon tidak tersedia</span>
              @endif
            </td>
            <td class="px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300">{{ $s->jenis_surat }}</td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 max-w-[180px]">
              <div class="line-clamp-2">{{ $s->keperluan }}</div>
            </td>
            <td class="px-4 py-3 text-xs text-slate-400">{{ $s->created_at->format('d M Y') }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-semibold
                           bg-{{ $color }}-100 text-{{ $color }}-700 dark:bg-{{ $color }}-500/20 dark:text-{{ $color }}-400">
                {{ $s->status_label }}
              </span>
              @if($s->nomor_surat)
              <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $s->nomor_surat }}</div>
              @endif
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-1.5">
                @if($s->status === 'selesai')
                <a href="{{ route('admin.layanan-surat.pdf', $s) }}" target="_blank"
                   class="flex items-center gap-1 px-2.5 h-8 rounded-lg border border-emerald-200 dark:border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                   title="Unduh PDF Surat">
                  <i class="ti ti-file-type-pdf text-sm"></i>
                </a>
                @if($s->token)
                <a href="{{ route('surat.verifikasi', $s->token) }}" target="_blank"
                   class="flex items-center gap-1 px-2.5 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                   title="Lihat QR Verifikasi">
                  <i class="ti ti-qrcode text-sm"></i>
                </a>
                @endif
                @endif
                <button type="button" onclick="openDetailModal(@js($modalData))"
                  class="flex items-center gap-1 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <i class="ti ti-eye text-sm"></i>
                  {{ $canAct ? 'Proses' : 'Detail' }}
                </button>
                @if($canHapus && in_array($s->status, ['diajukan','ditolak']))
                <form method="POST" action="{{ route('admin.layanan-surat.destroy', $s) }}" class="m-0">
                  @csrf @method('DELETE')
                  <button type="button"
                    onclick="konfirmasiHapus(this.closest('form'), 'Pengajuan surat ini akan dihapus permanen dan tidak dapat dikembalikan.', 'Hapus Pengajuan Surat')"
                    class="flex items-center px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
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
    @if($surats->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $surats->links() }}
      </div>
    @endif
  @endif
</div>


{{-- ══ MODAL TAMBAH ════════════════════════════════════════════════════════ --}}
@if($canBuat)
<div id="modalTambah" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closeTambahModal()">
    <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h2 class="font-semibold text-base">Buat Pengajuan Surat</h2>
        <button type="button" onclick="closeTambahModal()"
          class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form method="POST" action="{{ route('admin.layanan-surat.store') }}" class="p-6 space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1.5">Pemohon <span class="text-rose-500">*</span></label>
          <input type="hidden" name="penduduk_id" id="tm_penduduk_id">
          <div class="flex gap-2">
            <div class="flex-1 relative">
              <div class="flex items-center h-9 px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus-within:ring-2 focus-within:ring-brand-500">
                <i class="ti ti-user-search text-slate-400 text-sm mr-2 flex-shrink-0"></i>
                <input type="text" id="tm_search" placeholder="Ketik nama atau NIK…" autocomplete="off"
                  class="flex-1 bg-transparent text-sm outline-none border-0 p-0">
              </div>
              <div id="tm_results" style="display:none"
                class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl z-[60] max-h-56 overflow-y-auto">
              </div>
            </div>
            <button type="button" onclick="cariPenduduk()"
              class="px-4 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
              Cari
            </button>
          </div>
        </div>

        <div id="tm_preview" style="display:none"
          class="rounded-lg border border-brand-200 dark:border-brand-500/30 bg-brand-50 dark:bg-brand-500/5 p-3.5">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-brand-700 dark:text-brand-400 uppercase tracking-wide">Penduduk Dipilih</span>
            <button type="button" onclick="clearPenduduk()" class="text-xs text-slate-400 hover:text-rose-500">
              <i class="ti ti-x"></i> Ganti
            </button>
          </div>
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100" id="tm_p_nama"></div>
          <div class="font-mono text-xs text-slate-400" id="tm_p_nik"></div>
          <div class="text-xs text-slate-500 dark:text-slate-400 grid grid-cols-2 gap-x-4 gap-y-0.5 mt-1.5">
            <span id="tm_p_ttl"></span><span id="tm_p_jk"></span>
            <span id="tm_p_sp"></span><span id="tm_p_pekerjaan"></span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Jenis Surat <span class="text-rose-500">*</span></label>
          <select name="jenis_surat" required
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <option value="">— Pilih Jenis Surat —</option>
            @foreach($jenisList as $js)
              <option value="{{ $js->nama }}">{{ $js->nama }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Keperluan <span class="text-rose-500">*</span></label>
          <textarea name="keperluan" required rows="2"
            placeholder="Sebutkan tujuan / keperluan surat…"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Keterangan Tambahan</label>
          <textarea name="keterangan" rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"></textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex gap-3">
          <button type="button" onclick="closeTambahModal()"
            class="flex-1 h-10 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Batal</button>
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-10 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-device-floppy text-base"></i> Buat Pengajuan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif


{{-- ══ MODAL DETAIL / PROSES ═══════════════════════════════════════════════ --}}
<div id="modalDetail" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closeDetailModal()">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <div>
          <h2 class="font-semibold text-base">Detail Pengajuan Surat</h2>
          <p class="text-xs text-slate-400 mt-0.5" id="mdJenis"></p>
        </div>
        <div class="flex items-center gap-2">
          <span id="mdStatusBadge" class="text-xs font-semibold px-2.5 py-1 rounded-full"></span>
          <button type="button" onclick="closeDetailModal()"
            class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400">
            <i class="ti ti-x"></i>
          </button>
        </div>
      </div>

      <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">

        {{-- Data Pemohon --}}
        <div>
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Data Pemohon</h3>
          <div class="rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 p-4 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div><span class="text-slate-400 text-xs">Nama</span><div class="font-semibold text-slate-900 dark:text-slate-100 mt-0.5" id="mdNama">—</div></div>
            <div><span class="text-slate-400 text-xs">NIK</span><div class="font-mono text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdNik">—</div></div>
            <div><span class="text-slate-400 text-xs">TTL</span><div class="text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdTtl">—</div></div>
            <div><span class="text-slate-400 text-xs">Jenis Kelamin</span><div class="text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdJk">—</div></div>
            <div><span class="text-slate-400 text-xs">Agama</span><div class="text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdAgama">—</div></div>
            <div><span class="text-slate-400 text-xs">Status Perkawinan</span><div class="text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdSp">—</div></div>
            <div class="col-span-2"><span class="text-slate-400 text-xs">Pekerjaan</span><div class="text-xs mt-0.5 text-slate-600 dark:text-slate-300" id="mdPekerjaan">—</div></div>
          </div>
        </div>

        {{-- Keperluan --}}
        <div>
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Keperluan</h3>
          <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed" id="mdKeperluan"></p>
          <p class="text-xs text-slate-400 mt-1" id="mdKeterangan"></p>
        </div>

        {{-- Info Kades Approved (disetujui & selesai) --}}
        <div id="mdInfoKades" style="display:none">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Persetujuan Kepala Desa</h3>
          <div class="rounded-lg bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 grid place-items-center flex-shrink-0">
              <i class="ti ti-user-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
            </div>
            <div>
              <div class="font-bold text-slate-800 dark:text-slate-200" id="mdKadesNama">—</div>
              <div class="text-xs text-slate-500" id="mdKadesJabatan">—</div>
            </div>
          </div>
        </div>

        {{-- Info Selesai dengan QR (hanya selesai) --}}
        <div id="mdInfoSelesai" style="display:none">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Informasi Surat Selesai</h3>
          <div class="rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 p-4 space-y-3">
            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
              <div>
                <span class="text-slate-400 text-xs">Nomor Surat</span>
                <div class="font-semibold font-mono text-sm text-emerald-700 dark:text-emerald-400 mt-0.5" id="mdNomorSurat">—</div>
              </div>
              <div>
                <span class="text-slate-400 text-xs">Tanggal Selesai</span>
                <div class="text-xs text-slate-600 dark:text-slate-300 mt-0.5" id="mdTglSelesai">—</div>
              </div>
            </div>
            <a id="mdVerifikasiLink" href="#" target="_blank"
               class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400 hover:underline">
              <i class="ti ti-external-link text-sm"></i> Buka Halaman Verifikasi
            </a>
          </div>
          <div class="mt-3 flex justify-center">
            <div class="p-3 bg-white border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm inline-block">
              <div id="mdQrCode"></div>
            </div>
          </div>
        </div>

        {{-- Catatan --}}
        <div id="mdCatatanBox" style="display:none">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Catatan</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50" id="mdCatatanTeks"></p>
        </div>

        {{-- Alur Status --}}
        <div>
          <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Alur Pengajuan</h3>
          <div id="mdAlurStatus" class="flex items-center gap-1 flex-wrap"></div>
        </div>

        {{-- Form Tindakan --}}
        <form id="fmDetail" method="POST" class="space-y-3">
          @csrf @method('PUT')

          {{-- Admin form: diajukan, diproses, disetujui --}}
          <div id="mdFormProses" style="display:none">
            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3" id="mdFormProsesTitle">Tindakan Admin</h3>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium mb-1.5">Ubah Status</label>
                <select name="status" id="mdStatusProses" onchange="onStatusProsesChange(this.value)"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                </select>
              </div>

              {{-- Nomor surat (ditampilkan saat status=selesai) --}}
              <div id="mdNomorWrap" style="display:none">
                <label class="block text-sm font-medium mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                <div class="flex gap-2">
                  <input type="text" name="nomor_surat" id="mdNomorInput"
                    placeholder="Nomor akan dibuat otomatis…"
                    class="flex-1 px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 outline-none">
                  <button type="button" onclick="fetchNomorOtomatis()" title="Regenerasi nomor otomatis"
                    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="ti ti-refresh text-sm text-slate-500"></i>
                  </button>
                </div>
                <p class="text-xs text-slate-400 mt-1">Nomor dibuat otomatis dari pengaturan — bisa diedit.</p>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1.5" id="prosesLabel">Catatan (opsional)</label>
                <textarea name="catatan" id="mdPrCatatan" rows="2"
                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
                  placeholder="Catatan untuk pemohon…"></textarea>
              </div>
            </div>
          </div>

          {{-- Kades form: menunggu_ttd --}}
          <div id="mdFormKades" style="display:none">
            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Persetujuan Kepala Desa</h3>
            <div class="space-y-3">
              @if($kades)
              <div class="rounded-lg border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5 p-3.5 flex items-center gap-3">
                <i class="ti ti-user-check text-emerald-600 dark:text-emerald-400 text-xl flex-shrink-0"></i>
                <div>
                  <div class="text-xs text-slate-400 mb-0.5">Kepala Desa Aktif</div>
                  <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">{{ $kades->nama }}</div>
                  <div class="text-xs text-slate-400">{{ $kades->jabatan?->nama ?? 'Kepala Desa' }}</div>
                </div>
              </div>
              @else
              <div class="rounded-lg border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/5 p-3.5 flex items-center gap-3">
                <i class="ti ti-alert-triangle text-amber-500 text-xl flex-shrink-0"></i>
                <p class="text-xs text-amber-700 dark:text-amber-400">
                  Belum ada kepala desa aktif. Tambahkan di
                  <a href="{{ route('admin.master.pejabat.index') }}" class="underline font-semibold">Master Pejabat</a>.
                </p>
              </div>
              @endif
              <div>
                <label class="block text-sm font-medium mb-1.5">Catatan (opsional)</label>
                <textarea name="catatan" id="mdKadCatatan" rows="2"
                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
                  placeholder="Catatan…"></textarea>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="submitKades('ditolak')"
                  class="flex items-center justify-center gap-2 h-10 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-sm font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                  <i class="ti ti-x text-base"></i> Tolak
                </button>
                <button type="button" onclick="submitKades('disetujui')"
                  class="flex items-center justify-center gap-2 h-10 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                  <i class="ti ti-check text-base"></i> Setujui
                </button>
              </div>
              <input type="hidden" name="status" id="mdKadesStatus" disabled>
            </div>
          </div>

          {{-- Tombol aksi form admin --}}
          <div id="mdFormProsesFooter" style="display:none">
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex gap-3">
              <button type="button" onclick="closeDetailModal()"
                class="flex-1 h-10 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Tutup</button>
              <button type="submit"
                class="flex-1 flex items-center justify-center gap-2 h-10 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
                <i class="ti ti-device-floppy text-base"></i> Simpan
              </button>
            </div>
          </div>

          {{-- Hanya tutup (final/readonly) --}}
          <div id="mdFormClosed" style="display:none">
            <button type="button" onclick="closeDetailModal()"
              class="w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Tutup</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
const CARI_URL   = '{{ route('admin.penduduk.cari') }}';
const BASE_URL   = '{{ url('admin/layanan-surat') }}/';
const CAN_PROSES  = @js($canProses);
const CAN_SETUJUI = @js($canSetujui);

let _currentSuratId = null;

const STATUS_META = {
  diajukan:     { label:'Menunggu',       color:'amber' },
  menunggu_ttd: { label:'Menunggu Kades', color:'violet' },
  disetujui:    { label:'Disetujui Kades',color:'indigo' },
  selesai:      { label:'Selesai',        color:'emerald' },
  ditolak:      { label:'Ditolak',        color:'rose' },
};
const FLOW = ['diajukan','menunggu_ttd','disetujui','selesai'];

/* ══ Alur status visual ════════════════════════════════════════════════ */
function buildAlurStatus(currentStatus) {
  const isTolak = currentStatus === 'ditolak';
  const steps   = isTolak ? ['diajukan','menunggu_ttd','disetujui','ditolak'] : FLOW;
  const ci      = steps.indexOf(currentStatus);

  return steps.map((s, i) => {
    const meta   = STATUS_META[s] ?? { label: s, color:'slate' };
    const done   = i < ci;
    const active = i === ci;
    const dot = active
      ? `<span class="w-2.5 h-2.5 rounded-full bg-${meta.color}-500 ring-2 ring-${meta.color}-200 flex-shrink-0"></span>`
      : done
        ? `<span class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600 flex-shrink-0"></span>`
        : `<span class="w-2.5 h-2.5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex-shrink-0"></span>`;
    const lc = `text-xs ${active ? 'font-bold text-'+meta.color+'-600 dark:text-'+meta.color+'-400' : done ? 'text-slate-400' : 'text-slate-300 dark:text-slate-600'}`;
    const connector = i < steps.length - 1
      ? `<span class="flex-1 h-px ${i < ci ? 'bg-slate-300 dark:bg-slate-600' : 'bg-slate-200 dark:bg-slate-700'} min-w-[10px]"></span>`
      : '';
    return `<div class="flex items-center gap-1">${dot}<span class="${lc}">${meta.label}</span></div>${connector}`;
  }).join('');
}

/* ══ MODAL TAMBAH ═══════════════════════════════════════════════════════ */
let searchTimer = null;

function openTambahModal() {
  clearPenduduk();
  document.getElementById('modalTambah').style.display = 'block';
}
function closeTambahModal() { document.getElementById('modalTambah').style.display = 'none'; }

document.getElementById('tm_search')?.addEventListener('input', function () {
  clearTimeout(searchTimer);
  if (this.value.trim().length < 2) { document.getElementById('tm_results').style.display = 'none'; return; }
  searchTimer = setTimeout(cariPenduduk, 350);
});
document.getElementById('tm_search')?.addEventListener('blur', () => {
  setTimeout(() => { document.getElementById('tm_results').style.display = 'none'; }, 200);
});

function cariPenduduk() {
  const q = document.getElementById('tm_search')?.value.trim();
  if (!q) return;
  fetch(`${CARI_URL}?q=${encodeURIComponent(q)}`)
    .then(r => r.json())
    .then(renderCariResults);
}

function renderCariResults(data) {
  const box = document.getElementById('tm_results');
  box.innerHTML = data.length
    ? data.map(p => `
        <button type="button"
          class="w-full text-left px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0"
          onmousedown="pilihPenduduk(${JSON.stringify(p).replace(/"/g,'&quot;')})">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">${p.nama}</div>
          <div class="text-xs text-slate-400 font-mono">${p.nik} · ${p.jk}</div>
        </button>`).join('')
    : '<div class="px-4 py-3 text-sm text-slate-400">Penduduk tidak ditemukan</div>';
  box.style.display = 'block';
}

function pilihPenduduk(p) {
  document.getElementById('tm_penduduk_id').value      = p.id;
  document.getElementById('tm_search').value            = p.nama;
  document.getElementById('tm_results').style.display   = 'none';
  document.getElementById('tm_p_nama').textContent      = p.nama;
  document.getElementById('tm_p_nik').textContent       = p.nik;
  document.getElementById('tm_p_ttl').textContent       = p.ttl;
  document.getElementById('tm_p_jk').textContent        = p.jk;
  document.getElementById('tm_p_sp').textContent        = p.status_kawin;
  document.getElementById('tm_p_pekerjaan').textContent = p.pekerjaan;
  document.getElementById('tm_preview').style.display   = 'block';
}

function clearPenduduk() {
  document.getElementById('tm_penduduk_id').value    = '';
  document.getElementById('tm_search').value          = '';
  document.getElementById('tm_preview').style.display = 'none';
}

/* ══ MODAL DETAIL / PROSES ════════════════════════════════════════════ */
function openDetailModal(d) {
  _currentSuratId = d.id;
  const p = d.penduduk;

  document.getElementById('mdJenis').textContent       = d.jenis_surat;
  document.getElementById('mdKeperluan').textContent   = d.keperluan;
  document.getElementById('mdKeterangan').textContent  = d.keterangan ? 'Ket: ' + d.keterangan : '';

  const badge = document.getElementById('mdStatusBadge');
  badge.textContent = d.status_label;
  badge.className   = 'text-xs font-semibold px-2.5 py-1 rounded-full '
    + `bg-${d.status_color}-100 text-${d.status_color}-700 dark:bg-${d.status_color}-500/20 dark:text-${d.status_color}-400`;

  document.getElementById('mdNama').textContent      = p?.nama ?? '—';
  document.getElementById('mdNik').textContent       = p?.nik  ?? '—';
  document.getElementById('mdTtl').textContent       = p?.ttl  ?? '—';
  document.getElementById('mdJk').textContent        = p?.jk   ?? '—';
  document.getElementById('mdAgama').textContent     = p?.agama ?? '—';
  document.getElementById('mdSp').textContent        = p?.status_kawin ?? '—';
  document.getElementById('mdPekerjaan').textContent = p?.pekerjaan ?? '—';

  document.getElementById('mdAlurStatus').innerHTML = buildAlurStatus(d.status);

  // Catatan
  const hasCatatan = !!d.catatan;
  document.getElementById('mdCatatanTeks').textContent   = d.catatan ?? '';
  document.getElementById('mdCatatanBox').style.display  = hasCatatan ? 'block' : 'none';

  // Info kades (disetujui + selesai)
  const showKadesInfo = d.status === 'disetujui' || d.status === 'selesai';
  document.getElementById('mdInfoKades').style.display = showKadesInfo ? 'block' : 'none';
  if (showKadesInfo) {
    document.getElementById('mdKadesNama').textContent    = d.nama_kades ?? '—';
    document.getElementById('mdKadesJabatan').textContent = d.jabatan_kades ?? '—';
  }

  // Info selesai dengan QR
  document.getElementById('mdInfoSelesai').style.display = d.status === 'selesai' ? 'block' : 'none';
  if (d.status === 'selesai') {
    document.getElementById('mdNomorSurat').textContent = d.nomor_surat ?? '—';
    document.getElementById('mdTglSelesai').textContent = d.tanggal_selesai ?? '—';
    document.getElementById('mdVerifikasiLink').href    = d.verifikasi_url ?? '#';
    const qrEl = document.getElementById('mdQrCode');
    qrEl.innerHTML = '';
    if (d.verifikasi_url) {
      new QRCode(qrEl, { text: d.verifikasi_url, width: 140, height: 140,
        colorDark: '#1e293b', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.M });
    }
  }

  const show = (id, v) => { document.getElementById(id).style.display = v ? 'block' : 'none'; };

  // Admin: diajukan atau disetujui (finalisasi)
  const adminCanAct = CAN_PROSES && (d.status === 'diajukan' || d.status === 'disetujui');
  // Kades: menunggu_ttd
  const kadesCanAct = CAN_SETUJUI && d.status === 'menunggu_ttd';
  const isFinal     = d.status === 'selesai' || d.status === 'ditolak';

  show('mdFormProses',       adminCanAct);
  show('mdFormProsesFooter', adminCanAct);
  show('mdFormKades',        kadesCanAct);
  show('mdFormClosed',       isFinal || (!adminCanAct && !kadesCanAct));

  // Disable inactive catatan fields to prevent duplicate name conflicts
  document.getElementById('mdPrCatatan').disabled  = !adminCanAct;
  document.getElementById('mdKadCatatan').disabled = !kadesCanAct;

  if (adminCanAct) {
    document.getElementById('fmDetail').action = BASE_URL + d.id;
    const sel = document.getElementById('mdStatusProses');
    sel.innerHTML = '';

    let opts;
    if (d.status === 'diajukan') {
      opts = [{v:'menunggu_ttd',label:'Teruskan ke Kepala Desa'},{v:'ditolak',label:'Tolak Pengajuan'}];
      document.getElementById('mdFormProsesTitle').textContent = 'Tindakan Admin';
    } else {
      // disetujui → finalisasi
      opts = [{v:'selesai',label:'Selesaikan (isi nomor surat)'},{v:'ditolak',label:'Batalkan / Tolak'}];
      document.getElementById('mdFormProsesTitle').textContent = 'Finalisasi Surat';
    }

    opts.forEach(o => {
      const op = document.createElement('option');
      op.value = o.v; op.textContent = o.label;
      sel.appendChild(op);
    });

    onStatusProsesChange(sel.value, d.status);
    document.getElementById('mdPrCatatan').value = d.catatan ?? '';
  }

  if (kadesCanAct) {
    document.getElementById('fmDetail').action = BASE_URL + d.id;
    document.getElementById('mdKadCatatan').value = d.catatan ?? '';
  }

  document.getElementById('modalDetail').style.display = 'block';
}

function closeDetailModal() { document.getElementById('modalDetail').style.display = 'none'; }

async function onStatusProsesChange(val, currentStatus) {
  const label    = document.getElementById('prosesLabel');
  const nomorWrap = document.getElementById('mdNomorWrap');

  if (label) label.textContent = val === 'ditolak' ? 'Alasan Penolakan' : 'Catatan (opsional)';

  if (val === 'selesai') {
    nomorWrap.style.display = 'block';
    if (!document.getElementById('mdNomorInput').value.trim()) {
      fetchNomorOtomatis();
    }
  } else {
    nomorWrap.style.display = 'none';
  }
}

async function fetchNomorOtomatis() {
  if (!_currentSuratId) return;
  try {
    const res = await fetch(BASE_URL + _currentSuratId + '/nomor-otomatis');
    const data = await res.json();
    if (data.nomor) document.getElementById('mdNomorInput').value = data.nomor;
  } catch (e) {
    console.error('Gagal mengambil nomor otomatis', e);
  }
}

function submitKades(status) {
  const hiddenStatus = document.getElementById('mdKadesStatus');
  hiddenStatus.disabled = false;
  hiddenStatus.value    = status;
  // Sync catatan from kades textarea to form
  const kadescat = document.getElementById('mdKadCatatan')?.value;
  document.getElementById('fmDetail').submit();
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeTambahModal(); closeDetailModal(); }
});
</script>
@endsection
