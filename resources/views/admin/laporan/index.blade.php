@extends('layouts.admin')
@section('title', 'Laporan Warga')
@section('page-title', 'Laporan Warga')
@section('page-sub', 'Kelola dan tindaklanjuti laporan dari masyarakat')

@section('content')

@if(session('toast_success'))
<div id="adminToast"
  style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;
         background:#16a34a;color:#fff;padding:12px 18px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.18);
         font-size:14px;font-weight:600;opacity:0;transform:translateY(-10px);
         transition:opacity .3s ease,transform .3s ease;pointer-events:none">
  <i class="ti ti-check" style="font-size:16px"></i>
  <span>{{ session('toast_success') }}</span>
</div>
<script>
(function(){
  var t = document.getElementById('adminToast');
  if (!t) return;
  setTimeout(function(){ t.style.opacity='1'; t.style.transform='translateY(0)'; t.style.pointerEvents='auto'; }, 50);
  setTimeout(function(){ t.style.opacity='0'; t.style.transform='translateY(-10px)'; }, 4000);
})();
</script>
@endif

{{-- Filter Tab --}}
<div class="flex gap-2 mb-6 flex-wrap">
  @foreach(['semua'=>'Semua','menunggu'=>'Menunggu','diproses'=>'Diproses','selesai'=>'Selesai'] as $key => $label)
  @php
    $active = $status === $key;
    $badge  = $counts[$key] ?? 0;
    $colors = match($key) {
      'menunggu' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
      'diproses' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
      'selesai'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
      default    => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
    };
  @endphp
  <a href="{{ route('admin.laporan.index', ['status' => $key]) }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors
           {{ $active ? 'bg-brand-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
    {{ $label }}
    <span class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ $active ? 'bg-white/20 text-white' : $colors }}">{{ $badge }}</span>
  </a>
  @endforeach
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
  @if($laporan->isEmpty())
  <div class="py-20 text-center">
    <i class="ti ti-message-off text-5xl text-slate-300 dark:text-slate-600"></i>
    <p class="mt-3 text-slate-500 dark:text-slate-400 font-medium">Tidak ada laporan</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <tr>
          <th class="text-left px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">Pelapor</th>
          <th class="text-left px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">Laporan</th>
          <th class="text-left px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
          <th class="text-left px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">Status</th>
          <th class="text-left px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">Waktu</th>
          <th class="px-5 py-3.5"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($laporan as $lp)
        @php
          [$sBg, $sText, $sLabel] = match($lp->status) {
            'diproses' => ['bg-blue-100 dark:bg-blue-900/30',  'text-blue-700 dark:text-blue-400',  'Diproses'],
            'selesai'  => ['bg-green-100 dark:bg-green-900/30','text-green-700 dark:text-green-400','Selesai'],
            default    => ['bg-amber-100 dark:bg-amber-900/30','text-amber-700 dark:text-amber-400','Menunggu'],
          };
        @endphp
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
          <td class="px-5 py-4">
            <div class="font-semibold text-slate-800 dark:text-slate-100">{{ $lp->user->name ?? '-' }}</div>
            <div class="text-xs text-slate-400 mt-0.5">{{ $lp->user->penduduk->nama_lengkap ?? '' }}</div>
          </td>
          <td class="px-5 py-4 max-w-xs">
            <div class="font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $lp->judul }}</div>
            <div class="text-xs text-slate-400 mt-0.5 truncate">{{ Str::limit($lp->isi, 60) }}</div>
          </td>
          <td class="px-5 py-4">
            <span class="text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2.5 py-1 rounded-lg">{{ $lp->kategori }}</span>
          </td>
          <td class="px-5 py-4">
            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $sBg }} {{ $sText }}">{{ $sLabel }}</span>
          </td>
          <td class="px-5 py-4 text-xs text-slate-400 whitespace-nowrap">{{ $lp->created_at->diffForHumans() }}</td>
          <td class="px-5 py-4 text-right">
            <a href="{{ route('admin.laporan.show', $lp) }}"
              class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg
                     bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-brand-500/20 transition-colors">
              <i class="ti ti-eye text-sm"></i> Proses
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($laporan->hasPages())
  <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
    {{ $laporan->links() }}
  </div>
  @endif
  @endif
</div>

@endsection
