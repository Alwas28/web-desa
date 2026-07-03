@extends('layouts.admin')

@section('title', 'Verifikasi Penjual')
@section('page-title', 'Verifikasi Penjual')
@section('page-sub', 'Kelola permohonan warga yang ingin berjualan produk')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
  @foreach([
    ['label'=>'Total',     'val'=>$totalSemua,  'icon'=>'ti-users',        'color'=>'slate'],
    ['label'=>'Menunggu',  'val'=>$totalTunggu, 'icon'=>'ti-clock',        'color'=>'amber'],
    ['label'=>'Disetujui', 'val'=>$totalSetuju, 'icon'=>'ti-circle-check', 'color'=>'emerald'],
    ['label'=>'Ditolak',   'val'=>$totalTolak,  'icon'=>'ti-circle-x',     'color'=>'rose'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-lg text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 text-sm">
  <i class="ti ti-alert-circle flex-shrink-0"></i> {{ session('error') }}
</div>
@endif

{{-- Filter --}}
<form method="GET" class="flex items-center gap-2">
  <select name="status" onchange="this.form.submit()"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Status</option>
    <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected':'' }}>Menunggu</option>
    <option value="disetujui" {{ request('status')==='disetujui' ? 'selected':'' }}>Disetujui</option>
    <option value="ditolak"   {{ request('status')==='ditolak'   ? 'selected':'' }}>Ditolak</option>
  </select>
  @if(request('status'))
  <a href="{{ route('admin.penjual.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
</form>

{{-- Tabel --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($pengajuans->isEmpty())
  <div class="py-16 text-center text-slate-400">
    <i class="ti ti-shopping-bag text-4xl block mb-3"></i>
    <p class="font-medium">Belum ada pengajuan penjual</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60">
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Warga</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Alasan / Produk</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Tanggal</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Status</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($pengajuans as $p)
        @php
          $badgeClass = match($p->status) {
            'menunggu'  => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
            'ditolak'   => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
            default     => 'bg-slate-100 text-slate-600 border-slate-200',
          };
          $iconClass = match($p->status) {
            'menunggu'  => 'ti-clock',
            'disetujui' => 'ti-circle-check',
            'ditolak'   => 'ti-circle-x',
            default     => 'ti-minus',
          };
        @endphp
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100">
              {{ $p->penduduk?->nama_lengkap ?? $p->user->name }}
            </div>
            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $p->penduduk?->nik ?? $p->user->email }}</div>
          </td>
          <td class="px-4 py-3 max-w-xs">
            <p class="text-slate-700 dark:text-slate-300 line-clamp-2 text-xs leading-relaxed">{{ $p->alasan }}</p>
            @if($p->status !== 'menunggu' && $p->catatan_admin)
            <p class="text-xs text-slate-400 mt-1 italic">Catatan: {{ $p->catatan_admin }}</p>
            @endif
          </td>
          <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
            {{ $p->created_at->locale('id')->translatedFormat('d M Y') }}
            @if($p->ditinjau_at)
            <div class="text-slate-400 mt-0.5">Ditinjau: {{ $p->ditinjau_at->locale('id')->translatedFormat('d M Y') }}</div>
            @endif
          </td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
              <i class="ti {{ $iconClass }}"></i>
              {{ $p->status_label }}
            </span>
          </td>
          <td class="px-4 py-3">
            @if($p->status === 'menunggu')
            <div class="flex items-center gap-2 justify-end">
              {{-- Tombol Setujui --}}
              <button type="button"
                onclick="openSetujui({{ $p->id }}, '{{ addslashes($p->penduduk?->nama_lengkap ?? $p->user->name) }}')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-xs font-semibold border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 transition-colors">
                <i class="ti ti-circle-check"></i> Setujui
              </button>
              {{-- Tombol Tolak --}}
              <button type="button"
                onclick="openTolak({{ $p->id }}, '{{ addslashes($p->penduduk?->nama_lengkap ?? $p->user->name) }}')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 text-xs font-semibold border border-rose-200 dark:border-rose-500/20 hover:bg-rose-100 transition-colors">
                <i class="ti ti-circle-x"></i> Tolak
              </button>
            </div>
            @elseif($p->ditinjauOleh)
            <span class="text-xs text-slate-400">oleh {{ $p->ditinjauOleh->name }}</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($pengajuans->hasPages())
  <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800">
    {{ $pengajuans->links() }}
  </div>
  @endif
  @endif
</div>

{{-- Modal Setujui --}}
<div id="modalSetujui" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closeSetujui()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h2 class="font-semibold text-base text-emerald-600 dark:text-emerald-400">
          <i class="ti ti-circle-check mr-1.5"></i>Setujui Permohonan
        </h2>
        <button onclick="closeSetujui()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form id="formSetujui" method="POST" class="p-6 space-y-4">
        @csrf
        <p class="text-sm text-slate-600 dark:text-slate-400">
          Setujui permohonan penjual dari <strong id="setujuiNama" class="text-slate-900 dark:text-slate-100"></strong>?
          Warga akan dinotifikasi dan dapat mulai berjualan produk.
        </p>
        <div>
          <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
            Catatan untuk Warga <span class="text-slate-400 font-normal">(opsional)</span>
          </label>
          <textarea name="catatan" rows="2" maxlength="500"
            placeholder="Mis: Selamat! Pastikan foto produk jelas dan harga sesuai..."
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none resize-none"></textarea>
        </div>
        <div class="flex gap-3 pt-1">
          <button type="button" onclick="closeSetujui()"
            class="flex-1 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
            <i class="ti ti-circle-check"></i> Setujui
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closeTolak()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h2 class="font-semibold text-base text-rose-600 dark:text-rose-400">
          <i class="ti ti-circle-x mr-1.5"></i>Tolak Permohonan
        </h2>
        <button onclick="closeTolak()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form id="formTolak" method="POST" class="p-6 space-y-4">
        @csrf
        <p class="text-sm text-slate-600 dark:text-slate-400">
          Tolak permohonan dari <strong id="tolakNama" class="text-slate-900 dark:text-slate-100"></strong>?
          Warga akan dinotifikasi beserta alasannya.
        </p>
        <div>
          <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
            Alasan Penolakan <span class="text-rose-500">*</span>
          </label>
          <textarea name="catatan" rows="3" required maxlength="500"
            placeholder="Jelaskan alasan penolakan agar warga dapat memperbaiki pengajuannya..."
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none resize-none"></textarea>
          @error('catatan')
          <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="flex gap-3 pt-1">
          <button type="button" onclick="closeTolak()"
            class="flex-1 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition-colors flex items-center justify-center gap-2">
            <i class="ti ti-circle-x"></i> Tolak Permohonan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const BASE_PENJUAL = '{{ url('admin/pasar-desa/penjual') }}/';

function openSetujui(id, nama) {
  document.getElementById('setujuiNama').textContent = nama;
  document.getElementById('formSetujui').action = BASE_PENJUAL + id + '/setujui';
  document.getElementById('formSetujui').querySelector('textarea').value = '';
  document.getElementById('modalSetujui').style.display = 'block';
}
function closeSetujui() {
  document.getElementById('modalSetujui').style.display = 'none';
}

function openTolak(id, nama) {
  document.getElementById('tolakNama').textContent = nama;
  document.getElementById('formTolak').action = BASE_PENJUAL + id + '/tolak';
  document.getElementById('formTolak').querySelector('textarea').value = '';
  document.getElementById('modalTolak').style.display = 'block';
}
function closeTolak() {
  document.getElementById('modalTolak').style.display = 'none';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeSetujui(); closeTolak(); } });

@if($errors->has('catatan'))
openTolak('{{ old('_id','') }}', '');
@endif
</script>
@endsection
