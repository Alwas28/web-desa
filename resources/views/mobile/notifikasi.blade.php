@extends('mobile.layouts.app')

@section('title', 'Notifikasi')

@section('content')

<div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:20px 16px 24px;color:#fff">
  <p style="font-size:11px;opacity:.7;margin-bottom:4px">AKTIVITAS SURAT</p>
  <h2 style="font-size:20px;font-weight:800">Notifikasi</h2>
  <p style="font-size:12px;opacity:.8;margin-top:4px">Pembaruan status pengajuan surat Anda</p>
</div>

<div style="padding:14px 16px;display:flex;flex-direction:column;gap:10px">
  @forelse($notifs as $n)
  @php
    $data = $n->data;
    $read = $n->read_at !== null;
    $status = $data['status'] ?? null;
    $statusColors = [
      'selesai'      => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','dot'=>'#15803d','icon'=>'✅'],
      'ditolak'      => ['bg'=>'#fef2f2','border'=>'#fecaca','dot'=>'#dc2626','icon'=>'❌'],
      'disetujui'    => ['bg'=>'#eff6ff','border'=>'#bfdbfe','dot'=>'#1d4ed8','icon'=>'👍'],
      'menunggu_ttd' => ['bg'=>'#f5f3ff','border'=>'#ddd6fe','dot'=>'#7c3aed','icon'=>'✍️'],
    ];
    $sc = $statusColors[$status] ?? ['bg'=>'#f9fafb','border'=>'#e5e7eb','dot'=>'#6b7280','icon'=>'📄'];
    $bgColor = $read ? '#fff' : ($sc['bg']);
    $borderColor = $read ? '#f3f4f6' : ($sc['border']);
  @endphp
  <div style="background:{{ $bgColor }};border:1.5px solid {{ $borderColor }};border-radius:16px;padding:14px;
              display:flex;gap:12px;align-items:flex-start">
    {{-- Icon --}}
    <div style="width:40px;height:40px;border-radius:12px;background:{{ $sc['bg'] }};border:1px solid {{ $sc['border'] }};
                display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
      {{ $sc['icon'] }}
    </div>
    {{-- Body --}}
    <div style="flex:1;min-width:0">
      <p style="font-size:13px;font-weight:{{ $read ? '500' : '700' }};color:#111827;line-height:1.4">
        {{ $data['message'] ?? '-' }}
      </p>
      @if(!empty($data['catatan']) && $status === 'ditolak')
      <p style="font-size:11px;color:#dc2626;margin-top:4px;font-weight:600">
        Alasan: {{ $data['catatan'] }}
      </p>
      @endif
      <p style="font-size:10px;color:#9ca3af;margin-top:5px">
        {{ $n->created_at->locale('id')->diffForHumans() }}
      </p>
    </div>
    {{-- Unread dot --}}
    @if(!$read)
    <div style="width:8px;height:8px;border-radius:50%;background:#7c3aed;flex-shrink:0;margin-top:4px"></div>
    @endif
  </div>
  @empty
  <div style="text-align:center;padding:60px 24px;color:#9ca3af">
    <div style="font-size:48px;margin-bottom:12px;opacity:.4">🔔</div>
    <p style="font-size:14px;font-weight:600">Belum ada notifikasi</p>
    <small style="font-size:12px;display:block;margin-top:4px">Pembaruan status surat akan muncul di sini</small>
  </div>
  @endforelse
</div>

@if($notifs->hasPages())
<div style="padding:0 16px 8px">{{ $notifs->links() }}</div>
@endif

<div style="height:24px"></div>

@endsection
