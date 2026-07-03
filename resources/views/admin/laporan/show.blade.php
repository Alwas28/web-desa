@extends('layouts.admin')
@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')
@section('page-sub', 'Proses dan tindaklanjuti laporan warga')

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

<div class="mb-5">
  <a href="{{ route('admin.laporan.index') }}"
    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
    <i class="ti ti-arrow-left"></i> Kembali ke Daftar Laporan
  </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- ── Kolom Kiri: Info + Isi Laporan ── --}}
  <div class="lg:col-span-2 flex flex-col gap-6">

    {{-- Info Pelapor --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
      <div class="flex items-start justify-between gap-4 mb-5">
        <div class="flex items-center gap-4">
          @php
            $nama  = $laporan->user->name ?? '-';
            $init  = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->implode('');
          @endphp
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
            {{ $init }}
          </div>
          <div>
            <p class="font-bold text-slate-800 dark:text-slate-100 text-base">{{ $laporan->user->name ?? '-' }}</p>
            @if($laporan->user?->penduduk)
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $laporan->user->penduduk->nama_lengkap }}</p>
            @endif
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $laporan->user->email ?? '' }}</p>
          </div>
        </div>
        @php
          [$sBg, $sText, $sLabel] = match($laporan->status) {
            'diproses' => ['bg-blue-100 dark:bg-blue-900/30','text-blue-700 dark:text-blue-400','Diproses'],
            'selesai'  => ['bg-green-100 dark:bg-green-900/30','text-green-700 dark:text-green-400','Selesai'],
            default    => ['bg-amber-100 dark:bg-amber-900/30','text-amber-700 dark:text-amber-400','Menunggu'],
          };
        @endphp
        <span class="text-sm font-bold px-3 py-1.5 rounded-lg {{ $sBg }} {{ $sText }} whitespace-nowrap">{{ $sLabel }}</span>
      </div>

      <div class="grid grid-cols-2 gap-3 text-sm mb-5">
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
          <p class="text-xs text-slate-400 mb-1">Kategori</p>
          <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $laporan->kategori }}</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
          <p class="text-xs text-slate-400 mb-1">Dilaporkan</p>
          <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $laporan->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
        </div>
        @if($laporan->ditangani_at)
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3">
          <p class="text-xs text-green-500 mb-1">Selesai Ditangani</p>
          <p class="font-semibold text-green-700 dark:text-green-400">{{ $laporan->ditangani_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
        </div>
        @endif
        @if($laporan->dibalas_at)
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
          <p class="text-xs text-slate-400 mb-1">Dibalas oleh</p>
          <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $laporan->pembalas?->name ?? '-' }}</p>
        </div>
        @endif
      </div>

      <div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Judul Laporan</p>
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">{{ $laporan->judul }}</h2>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Isi Laporan</p>
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $laporan->isi }}</div>
      </div>
    </div>

    {{-- Rencana Tindak Lanjut --}}
    @if($laporan->rencana_tindak_lanjut)
    <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-2xl p-5">
      <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-content:center">
          <i class="ti ti-clipboard-list text-blue-600 dark:text-blue-400 text-base ml-1.5"></i>
        </div>
        <p class="text-sm font-bold text-blue-700 dark:text-blue-400">Rencana Tindak Lanjut</p>
      </div>
      <p class="text-sm text-blue-800 dark:text-blue-300 leading-relaxed whitespace-pre-wrap">{{ $laporan->rencana_tindak_lanjut }}</p>
    </div>
    @endif

    {{-- Laporan Penanganan --}}
    @if($laporan->laporan_penanganan)
    <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-2xl p-5">
      <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-content:center">
          <i class="ti ti-checklist text-green-600 dark:text-green-400 text-base ml-1.5"></i>
        </div>
        <p class="text-sm font-bold text-green-700 dark:text-green-400">Laporan Penanganan</p>
      </div>
      <p class="text-sm text-green-800 dark:text-green-300 leading-relaxed whitespace-pre-wrap">{{ $laporan->laporan_penanganan }}</p>
    </div>
    @endif

  </div>

  {{-- ── Kolom Kanan: Form Proses ── --}}
  <div class="flex flex-col gap-5">

    <form method="POST" action="{{ route('admin.laporan.update', $laporan) }}">
      @csrf
      @method('PUT')

      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-5 flex items-center gap-2">
          <i class="ti ti-settings text-brand-500"></i> Proses Laporan
        </p>

        {{-- Status --}}
        <div class="mb-4">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
          <div class="flex flex-col gap-2">
            @foreach(['menunggu'=>['Menunggu','ti-clock','amber'],'diproses'=>['Sedang Diproses','ti-loader','blue'],'selesai'=>['Selesai Ditangani','ti-circle-check','green']] as $val => [$lbl, $icon, $color])
            <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-colors
                          {{ $laporan->status === $val ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300' }}">
              <input type="radio" name="status" value="{{ $val }}" class="sr-only" {{ $laporan->status === $val ? 'checked' : '' }}
                onchange="this.closest('form').querySelectorAll('label').forEach(l=>l.classList.remove('border-brand-500','bg-brand-50','dark:bg-brand-500/10'));this.closest('label').classList.add('border-brand-500','bg-brand-50','dark:bg-brand-500/10')">
              <i class="ti {{ $icon }} text-{{ $color }}-500 text-base"></i>
              <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $lbl }}</span>
            </label>
            @endforeach
          </div>
        </div>

        {{-- Balasan ke Warga --}}
        <div class="mb-4">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Balasan ke Warga</label>
          <textarea name="balasan" rows="3" placeholder="Sampaikan tanggapan resmi kepada pelapor..."
            class="w-full text-sm border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('balasan', $laporan->balasan) }}</textarea>
          <p class="text-xs text-slate-400 mt-1">Balasan ini akan ditampilkan kepada pelapor di aplikasi warga.</p>
        </div>

        {{-- Rencana Tindak Lanjut --}}
        <div class="mb-4">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Rencana Tindak Lanjut</label>
          <textarea name="rencana_tindak_lanjut" rows="4" placeholder="Tuliskan langkah-langkah penanganan yang akan dilakukan, penanggung jawab, dan target waktu..."
            class="w-full text-sm border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('rencana_tindak_lanjut', $laporan->rencana_tindak_lanjut) }}</textarea>
        </div>

        {{-- Laporan Penanganan (hanya jika selesai) --}}
        <div class="mb-5">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
            Laporan Penanganan
            <span class="text-slate-300 font-normal ml-1">(untuk status Selesai)</span>
          </label>
          <textarea name="laporan_penanganan" rows="4" placeholder="Ceritakan hasil penanganan yang telah dilakukan, hambatan, dan rekomendasi ke depan..."
            class="w-full text-sm border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('laporan_penanganan', $laporan->laporan_penanganan) }}</textarea>
        </div>

        <button type="submit"
          class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
          <i class="ti ti-device-floppy"></i> Simpan Perubahan
        </button>
      </div>
    </form>

    {{-- Hapus Laporan --}}
    <form method="POST" action="{{ route('admin.laporan.destroy', $laporan) }}"
      onsubmit="return confirm('Hapus laporan ini secara permanen?')">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="w-full border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 font-semibold py-2.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm flex items-center justify-center gap-2">
        <i class="ti ti-trash"></i> Hapus Laporan
      </button>
    </form>

  </div>
</div>

@endsection
