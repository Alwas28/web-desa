@extends('layouts.admin')

@section('page-title', 'Notifikasi')
@section('page-sub', 'Riwayat aktivitas pengajuan surat')

@section('content')
<div class="max-w-2xl mx-auto space-y-3">
  @forelse($notifs as $n)
  @php
    $data  = $n->data;
    $read  = $n->read_at !== null;
    $isNew = !$read;
  @endphp
  <div class="flex gap-4 p-4 rounded-2xl border transition
              {{ $isNew ? 'bg-brand-50 dark:bg-brand-900/20 border-brand-200 dark:border-brand-800' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700' }}">
    {{-- Icon --}}
    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                {{ $isNew ? 'bg-brand-100 dark:bg-brand-900/40' : 'bg-slate-100 dark:bg-slate-700' }}">
      @if(isset($data['status']))
        @php
          $iconMap = ['selesai'=>'ti-circle-check text-green-600','ditolak'=>'ti-circle-x text-red-500','disetujui'=>'ti-thumb-up text-blue-600','menunggu_ttd'=>'ti-writing text-purple-600'];
          $icon = $iconMap[$data['status']] ?? 'ti-bell text-brand-600';
        @endphp
        <i class="ti {{ $icon }}"></i>
      @else
        <i class="ti ti-file-plus {{ $isNew ? 'text-brand-600' : 'text-slate-400' }}"></i>
      @endif
    </div>
    {{-- Body --}}
    <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold text-slate-800 dark:text-white leading-snug">{{ $data['message'] ?? '-' }}</p>
      @if(!empty($data['catatan']) && ($data['status'] ?? '') === 'ditolak')
      <p class="text-xs text-red-500 mt-1">Alasan: {{ $data['catatan'] }}</p>
      @endif
      <p class="text-xs text-slate-400 mt-1.5">{{ $n->created_at->locale('id')->diffForHumans() }}</p>
    </div>
    {{-- Badge & aksi --}}
    <div class="flex flex-col items-end gap-2 flex-shrink-0">
      @if($isNew)
      <span class="w-2 h-2 rounded-full bg-brand-500 mt-1"></span>
      @endif
      @if(!empty($data['url']))
      <a href="{{ $data['url'] }}"
         class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline whitespace-nowrap">
        Lihat →
      </a>
      @endif
    </div>
  </div>
  @empty
  <div class="text-center py-20 text-slate-400">
    <i class="ti ti-bell-off text-5xl mb-3 block opacity-30"></i>
    <p class="font-medium">Belum ada notifikasi</p>
  </div>
  @endforelse

  {{-- Pagination --}}
  @if($notifs->hasPages())
  <div class="pt-2">{{ $notifs->links() }}</div>
  @endif
</div>
@endsection
