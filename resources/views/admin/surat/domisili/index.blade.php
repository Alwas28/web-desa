@extends('layouts.admin')

@section('title', 'Surat Keterangan Domisili')
@section('page-title', 'Surat Keterangan Domisili')
@section('page-sub', 'Kelola pengajuan surat keterangan domisili warga')

@section('content')

{{-- Notifikasi pengajuan belum diproses --}}
@if($pending > 0)
<div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-800 dark:text-amber-300 text-sm">
  <i class="ti ti-bell-ringing text-lg flex-shrink-0 mt-0.5"></i>
  <div>
    <span class="font-semibold">{{ $pending }} pengajuan belum diproses.</span>
    Segera tinjau dan proses surat yang masuk dari warga.
    @if(request('status') !== 'pending')
      <a href="{{ route('admin.surat.domisili.index', ['status'=>'pending']) }}"
         class="underline font-medium ml-1">Lihat sekarang →</a>
    @endif
  </div>
</div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
  @foreach([
    ['label'=>'Total',    'val'=>$total,    'icon'=>'ti-mail',          'color'=>'brand'],
    ['label'=>'Menunggu', 'val'=>$pending,  'icon'=>'ti-clock',         'color'=>'amber'],
    ['label'=>'Diproses', 'val'=>$diproses, 'icon'=>'ti-refresh',       'color'=>'blue'],
    ['label'=>'Selesai',  'val'=>$selesai,  'icon'=>'ti-circle-check',  'color'=>'emerald'],
    ['label'=>'Ditolak',  'val'=>$ditolak,  'icon'=>'ti-circle-x',      'color'=>'rose'],
  ] as $s)
  <a href="{{ route('admin.surat.domisili.index', $s['label'] === 'Total' ? [] : ['status'=> $s['label']==='Menunggu'?'pending':strtolower($s['label'])]) }}"
     class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3 hover:border-{{ $s['color'] }}-300 dark:hover:border-{{ $s['color'] }}-500/30 transition-colors">
    <div class="w-9 h-9 rounded-lg bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </a>
  @endforeach
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.surat.domisili.index') }}"
      class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm flex-shrink-0"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / NIK…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>

  <select name="status"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    <option value="">Semua Status</option>
    <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Menunggu</option>
    <option value="diproses" {{ request('status')==='diproses' ? 'selected':'' }}>Diproses</option>
    <option value="selesai"  {{ request('status')==='selesai'  ? 'selected':'' }}>Selesai</option>
    <option value="ditolak"  {{ request('status')==='ditolak'  ? 'selected':'' }}>Ditolak</option>
  </select>

  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i> Filter
  </button>
  @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.surat.domisili.index') }}"
       class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
      <i class="ti ti-x text-sm"></i>
    </a>
  @endif

  <button type="button" onclick="openTambahModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors flex-shrink-0">
    <i class="ti ti-plus text-base"></i> Tambah Manual
  </button>
</form>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($surats->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-mail-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400">
        {{ request()->hasAny(['q','status']) ? 'Tidak ada pengajuan yang sesuai filter.' : 'Belum ada pengajuan surat domisili.' }}
      </p>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
            <th class="px-4 py-3">Pemohon</th>
            <th class="px-4 py-3">NIK</th>
            <th class="px-4 py-3">Keperluan</th>
            <th class="px-4 py-3">Tgl Pengajuan</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">No. Surat</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($surats as $s)
          @php
            $colors = [
              'pending'  => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
              'diproses' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
              'selesai'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
              'ditolak'  => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
            ];
          @endphp
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors {{ $s->status === 'pending' ? 'bg-amber-50/30 dark:bg-amber-500/5' : '' }}">

            {{-- Pemohon --}}
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $s->nama_pemohon }}</div>
              <div class="text-xs text-slate-400">{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </td>

            {{-- NIK --}}
            <td class="px-4 py-3 font-mono text-xs text-slate-500 dark:text-slate-400">
              {{ $s->nik }}
            </td>

            {{-- Keperluan --}}
            <td class="px-4 py-3 max-w-xs">
              <span class="text-slate-600 dark:text-slate-300 text-xs">{{ Str::limit($s->keperluan, 60) }}</span>
            </td>

            {{-- Tanggal --}}
            <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
              {{ $s->created_at->format('d M Y') }}
              <div class="text-[10px] text-slate-300 dark:text-slate-600">{{ $s->created_at->diffForHumans() }}</div>
            </td>

            {{-- Status --}}
            <td class="px-4 py-3">
              <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $colors[$s->status] }}">
                @if($s->status === 'pending')
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block animate-pulse"></span>
                @else
                  <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>
                @endif
                {{ $s->status_label }}
              </span>
            </td>

            {{-- Nomor Surat --}}
            <td class="px-4 py-3 text-xs font-mono text-slate-500 dark:text-slate-400">
              {{ $s->nomor_surat ?: '—' }}
            </td>

            {{-- Aksi --}}
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-1.5">
                <button type="button"
                  onclick="openDetailModal(@js($s->toArray()))"
                  class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <i class="ti ti-eye text-sm"></i>
                  @if($s->status === 'pending') Proses @else Detail @endif
                </button>
                <form method="POST" action="{{ route('admin.surat.domisili.destroy', $s) }}"
                      onsubmit="return confirm('Hapus pengajuan ini?')" class="m-0">
                  @csrf @method('DELETE')
                  <button type="submit"
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

    @if($surats->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $surats->links() }}
      </div>
    @endif
  @endif
</div>

{{-- ══════════════════════════════════════════════════
     MODAL DETAIL & PROSES
══════════════════════════════════════════════════ --}}
<div id="detailModal" style="display:none"
  class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDetailModal()"></div>
  <div class="relative z-10 w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 max-h-[90vh] overflow-y-auto">

    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900 z-10">
      <h2 class="font-semibold text-base" id="detailModalTitle">Detail Pengajuan</h2>
      <button type="button" onclick="closeDetailModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-400">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <div class="p-6 space-y-5">

      {{-- Data Pemohon (read-only) --}}
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Data Pemohon</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
          <div><span class="text-slate-400 text-xs block">Nama Lengkap</span><span id="d_nama" class="font-medium text-slate-900 dark:text-slate-100"></span></div>
          <div><span class="text-slate-400 text-xs block">NIK</span><span id="d_nik" class="font-mono text-slate-700 dark:text-slate-300"></span></div>
          <div><span class="text-slate-400 text-xs block">Tempat / Tgl Lahir</span><span id="d_ttl" class="text-slate-700 dark:text-slate-300"></span></div>
          <div><span class="text-slate-400 text-xs block">Jenis Kelamin</span><span id="d_jk" class="text-slate-700 dark:text-slate-300"></span></div>
          <div><span class="text-slate-400 text-xs block">Agama</span><span id="d_agama" class="text-slate-700 dark:text-slate-300"></span></div>
          <div><span class="text-slate-400 text-xs block">Pekerjaan</span><span id="d_pekerjaan" class="text-slate-700 dark:text-slate-300"></span></div>
          <div class="col-span-2"><span class="text-slate-400 text-xs block">Alamat</span><span id="d_alamat" class="text-slate-700 dark:text-slate-300"></span></div>
          <div class="col-span-2"><span class="text-slate-400 text-xs block">Keperluan</span><span id="d_keperluan" class="text-slate-700 dark:text-slate-300"></span></div>
        </div>
      </div>

      <div class="border-t border-slate-100 dark:border-slate-800"></div>

      {{-- Form Tindakan Admin --}}
      <form id="prosesForm" method="POST" class="space-y-4">
        @csrf
        <div id="metodeProsesField"></div>

        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tindakan Admin</h3>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">Perbarui Status</label>
            <select name="status" id="d_status"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
              <option value="pending">Menunggu</option>
              <option value="diproses">Diproses</option>
              <option value="selesai">Selesai</option>
              <option value="ditolak">Ditolak</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Nomor Surat <span class="text-slate-400 font-normal">(jika selesai)</span></label>
            <input type="text" name="nomor_surat" id="d_nomor_surat"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              placeholder="SKD/001/06/2026">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="catatan" id="d_catatan" rows="3"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Catatan untuk pemohon atau keterangan internal…"></textarea>
        </div>

        <div class="flex gap-2 pt-1">
          <button type="button" onclick="closeDetailModal()"
            class="flex-1 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Tutup
          </button>
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-device-floppy text-base"></i> Simpan Tindakan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL TAMBAH MANUAL
══════════════════════════════════════════════════ --}}
<div id="tambahModal" style="display:none"
  class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahModal()"></div>
  <div class="relative z-10 w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 max-h-[90vh] overflow-y-auto">

    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900 z-10">
      <h2 class="font-semibold text-base">Tambah Pengajuan Manual</h2>
      <button type="button" onclick="closeTambahModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-400">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.surat.domisili.store') }}" class="p-6 space-y-4">
      @csrf

      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
          <input type="text" name="nama_pemohon" required maxlength="150"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>

        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1.5">NIK <span class="text-rose-500">*</span></label>
          <input type="text" name="nik" required maxlength="16" minlength="16"
            pattern="[0-9]{16}" inputmode="numeric"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="16 digit NIK">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
          <input type="text" name="tempat_lahir" required maxlength="100"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
          <input type="date" name="tanggal_lahir" required
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
          <select name="jenis_kelamin" required
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">-- pilih --</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Agama <span class="text-rose-500">*</span></label>
          <select name="agama" required
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">-- pilih --</option>
            <option>Islam</option><option>Kristen</option><option>Katolik</option>
            <option>Hindu</option><option>Buddha</option><option>Konghucu</option>
          </select>
        </div>

        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1.5">Pekerjaan <span class="text-rose-500">*</span></label>
          <input type="text" name="pekerjaan" required maxlength="100"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>

        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1.5">Alamat <span class="text-rose-500">*</span></label>
          <textarea name="alamat" required rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">RT</label>
          <input type="text" name="rt" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="001">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">RW</label>
          <input type="text" name="rw" maxlength="5"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="002">
        </div>

        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1.5">Keperluan / Tujuan Surat <span class="text-rose-500">*</span></label>
          <textarea name="keperluan" required rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Contoh: Untuk keperluan melamar pekerjaan…"></textarea>
        </div>
      </div>

      <div class="flex gap-2 pt-1">
        <button type="button" onclick="closeTambahModal()"
          class="flex-1 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-plus text-base"></i> Tambahkan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
const UPDATE_BASE = '{{ url('admin/surat/domisili') }}/';

function openDetailModal(data) {
  document.getElementById('d_nama').textContent      = data.nama_pemohon ?? '';
  document.getElementById('d_nik').textContent       = data.nik ?? '';
  document.getElementById('d_ttl').textContent       = (data.tempat_lahir ?? '') + ', ' + (data.tanggal_lahir ?? '');
  document.getElementById('d_jk').textContent        = data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
  document.getElementById('d_agama').textContent     = data.agama ?? '';
  document.getElementById('d_pekerjaan').textContent = data.pekerjaan ?? '';
  const rt  = data.rt ? ' RT ' + data.rt : '';
  const rw  = data.rw ? ' RW ' + data.rw : '';
  document.getElementById('d_alamat').textContent    = (data.alamat ?? '') + rt + rw;
  document.getElementById('d_keperluan').textContent = data.keperluan ?? '';

  document.getElementById('d_status').value          = data.status ?? 'pending';
  document.getElementById('d_nomor_surat').value     = data.nomor_surat ?? '';
  document.getElementById('d_catatan').value         = data.catatan ?? '';

  document.getElementById('metodeProsesField').innerHTML =
    '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('prosesForm').action = UPDATE_BASE + data.id;

  const isPending = data.status === 'pending';
  document.getElementById('detailModalTitle').textContent =
    isPending ? 'Proses Pengajuan Surat' : 'Detail Pengajuan Surat';

  document.getElementById('detailModal').style.display = 'flex';
}

function closeDetailModal() {
  document.getElementById('detailModal').style.display = 'none';
}

function openTambahModal() {
  document.getElementById('tambahModal').style.display = 'flex';
}

function closeTambahModal() {
  document.getElementById('tambahModal').style.display = 'none';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeDetailModal(); closeTambahModal(); }
});
</script>
@endsection
