<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Surat – {{ config('app.name') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">

<div class="w-full max-w-lg">

  {{-- Card --}}
  <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    {{-- KOP Surat --}}
    @php
      $isSelesai  = $surat->status === 'selesai';
      $kopLines   = array_values(array_filter(explode("\n", $kopRendered ?? ''), fn($l) => trim($l) !== ''));
      $isAbove    = ($logoPos ?? 'above') === 'above';
      $isCenter   = ($kopAlign ?? 'center') === 'center';
      $fontCss    = \App\Http\Controllers\Admin\SuratPengaturanController::FONTS[$kopFont ?? 'Inter'] ?? 'Inter, sans-serif';
      $szMap = [
          0 => ($kopSizes['l1'] ?? 11) . 'px',
          1 => ($kopSizes['l2'] ?? 18) . 'px',
          2 => ($kopSizes['l3'] ?? 11) . 'px',
          3 => ($kopSizes['l4'] ?? 11) . 'px',
          4 => ($kopSizes['l5'] ?? 11) . 'px',
      ];
      $wrapClass = $isAbove
        ? 'flex flex-col ' . ($isCenter ? 'items-center' : 'items-start')
        : 'flex flex-row items-start gap-4';
      $logoClass = $isAbove ? 'w-14 h-14 object-contain mb-2' : 'w-14 h-14 object-contain flex-shrink-0';
      $textClass = $isCenter ? 'text-center' : 'text-left';
    @endphp
    <div class="px-6 pt-5 pb-4">
      <div class="{{ $wrapClass }}">
        @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" class="{{ $logoClass }}">
        @endif
        <div class="{{ $textClass }}" style="font-family: {{ $fontCss }};">
          @foreach($kopLines as $i => $line)
            @php $sz = $szMap[min($i, 4)]; @endphp
            @if($i === 0)
              <p class="font-semibold text-slate-600 uppercase leading-tight" style="font-size:{{ $sz }}">{{ $line }}</p>
            @elseif($i === 1)
              <p class="font-extrabold text-slate-900 uppercase leading-tight" style="font-size:{{ $sz }}">{{ $line }}</p>
            @else
              <p class="text-slate-500 mt-0.5 leading-snug" style="font-size:{{ $sz }}">{{ $line }}</p>
            @endif
          @endforeach
          @if(!$kopLines)
            <p class="font-bold text-slate-800 uppercase">{{ config('app.name') }}</p>
          @endif
        </div>
      </div>
      <div class="mt-3 border-b-[3px] border-slate-800"></div>
      <div class="mt-0.5 border-b border-slate-800"></div>
    </div>

    {{-- Status Banner --}}
    @if($isSelesai)
    <div class="bg-emerald-500 text-white px-6 py-3.5 flex items-center gap-3">
      <i class="ti ti-circle-check text-xl flex-shrink-0"></i>
      <div>
        <div class="font-bold text-sm">Surat Terverifikasi</div>
        <div class="text-emerald-100 text-xs">Dokumen ini telah disetujui dan sah secara sistem</div>
      </div>
    </div>
    @else
    <div class="bg-slate-400 text-white px-6 py-3.5 flex items-center gap-3">
      <i class="ti ti-clock text-xl flex-shrink-0"></i>
      <div>
        <div class="font-bold text-sm">Surat Belum Selesai</div>
        <div class="text-slate-100 text-xs">Status: {{ $surat->status_label }}</div>
      </div>
    </div>
    @endif

    <div class="p-6 space-y-5">

      {{-- Jenis & Nomor --}}
      <div class="text-center pb-4 border-b border-slate-100">
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Jenis Surat</div>
        <div class="text-lg font-bold text-slate-900">{{ $surat->jenis_surat }}</div>
        @if($surat->nomor_surat)
        <div class="mt-2 inline-block font-mono text-sm px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-semibold">
          {{ $surat->nomor_surat }}
        </div>
        @endif
      </div>

      {{-- Data Pemohon --}}
      <div>
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Data Pemohon</div>
        <dl class="space-y-2">
          @php $p = $surat->penduduk; @endphp
          @if($p)
          <div class="flex gap-3">
            <dt class="text-xs text-slate-400 w-36 flex-shrink-0 pt-0.5">Nama Lengkap</dt>
            <dd class="text-sm font-semibold text-slate-800">{{ $p->nama_lengkap }}</dd>
          </div>
          <div class="flex gap-3">
            <dt class="text-xs text-slate-400 w-36 flex-shrink-0 pt-0.5">NIK</dt>
            <dd class="text-sm font-mono text-slate-600">{{ $p->nik }}</dd>
          </div>
          <div class="flex gap-3">
            <dt class="text-xs text-slate-400 w-36 flex-shrink-0 pt-0.5">Tempat / Tgl Lahir</dt>
            <dd class="text-sm text-slate-600">{{ $p->tempat_lahir }}, {{ $p->tanggal_lahir->translatedFormat('d F Y') }}</dd>
          </div>
          @else
          <p class="text-sm text-slate-400 italic">Data pemohon tidak tersedia</p>
          @endif
        </dl>
      </div>

      {{-- Keperluan --}}
      <div class="bg-slate-50 rounded-xl px-4 py-3">
        <div class="text-xs text-slate-400 mb-1">Keperluan Surat</div>
        <p class="text-sm text-slate-700">{{ $surat->keperluan }}</p>
      </div>

      {{-- Penandatangan --}}
      @if($isSelesai && $surat->nama_kades)
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
        <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600 mb-3">Penandatangan</div>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-emerald-100 grid place-items-center flex-shrink-0">
            <i class="ti ti-user-check text-emerald-600 text-lg"></i>
          </div>
          <div>
            <div class="font-bold text-slate-800">{{ $surat->nama_kades }}</div>
            <div class="text-xs text-slate-500">{{ $surat->jabatan_kades }}</div>
            @if($surat->tanggal_selesai)
            <div class="text-xs text-slate-400 mt-0.5">{{ $surat->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB</div>
            @endif
          </div>
        </div>
      </div>
      @endif

      {{-- QR Code --}}
      @if($isSelesai)
      <div class="text-center pt-2 border-t border-slate-100">
        <div class="text-xs text-slate-400 mb-3">QR Code Verifikasi</div>
        <div class="inline-block p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
          <canvas id="qrCanvas"></canvas>
        </div>
        <p class="text-[10px] text-slate-400 mt-2 font-mono break-all">{{ route('surat.verifikasi', $surat->token) }}</p>
      </div>
      @endif

    </div>

    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-center">
      <p class="text-[11px] text-slate-400">
        Verifikasi dokumen ini melalui QR Code. &copy; {{ date('Y') }} {{ config('app.name') }}
      </p>
    </div>

  </div>
</div>

@if($isSelesai)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  new QRCode(document.getElementById('qrCanvas'), {
    text:   '{{ route('surat.verifikasi', $surat->token) }}',
    width:  160,
    height: 160,
    colorDark: '#1e293b',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.M,
  });
</script>
@endif

</body>
</html>
