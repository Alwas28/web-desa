@extends('layouts.admin')
@section('title', 'Prediksi Stunting AI')
@section('page-title', 'Prediksi Stunting')
@section('page-sub', 'Skrining risiko stunting balita berbasis kecerdasan buatan')

@section('content')

{{-- ── Tab bar ── --}}
<div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl w-fit mb-6">
  <button id="tab-btn-generate" onclick="switchTab('generate')"
    class="flex items-center gap-2 px-4 h-8 rounded-[10px] text-sm font-semibold bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm transition-all">
    <i class="ti ti-brain text-brand-600 text-sm"></i> Analisis
  </button>
  <button id="tab-btn-riwayat" onclick="switchTab('riwayat')"
    class="flex items-center gap-2 px-4 h-8 rounded-[10px] text-sm font-semibold text-slate-500 dark:text-slate-400 transition-all">
    <i class="ti ti-history text-sm"></i> Riwayat
    <span id="riwayatCount" class="px-1.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ count($riwayat) }}</span>
  </button>
</div>

{{-- ══════════════════════════════════════════════════════
     PANEL ANALISIS
══════════════════════════════════════════════════════ --}}
<div id="panel-generate">

  @if(!$apiReady)
  <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-sm mb-4">
    <i class="ti ti-alert-triangle flex-shrink-0 mt-0.5"></i>
    <div>Kunci API belum dikonfigurasi. Buka <a href="{{ route('admin.settings.index') }}" class="underline font-semibold">Pengaturan → API</a> untuk mengisi kunci API terlebih dahulu.</div>
  </div>
  @endif

  {{-- ── View: Form Input ── --}}
  <div id="viewForm">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- Form --}}
      <div>
        <form id="formPrediksi" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
            <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100 flex items-center gap-2">
              <i class="ti ti-forms text-brand-600"></i> Data Antropometri Balita
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Isi data anak atau muat dari data balita terdaftar</p>
          </div>

          <div class="p-5 space-y-4">

            @if($balitas->isNotEmpty())
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Muat dari Data Balita Terdaftar</label>
              <div class="relative">
                <select id="selectBalita" onchange="loadBalita(this.value)"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 appearance-none">
                  <option value="">— Pilih untuk auto-isi —</option>
                  @foreach($balitas as $b)
                  @php
                    $umurBulan = $b->tanggal_lahir ? $b->tanggal_lahir->diffInMonths(now()) : null;
                    $bb = $b->timbangTerakhir?->berat_badan;
                    $tb = $b->timbangTerakhir?->tinggi_badan;
                  @endphp
                  <option value="{{ $b->id }}"
                    data-nama="{{ $b->nama }}"
                    data-jk="{{ $b->jenis_kelamin }}"
                    data-umur="{{ $umurBulan }}"
                    data-bb="{{ $bb }}"
                    data-tb="{{ $tb }}">
                    {{ $b->nama }}
                    ({{ $umurBulan !== null ? $umurBulan . ' bln' : '—' }})
                    {{ $bb ? '· ' . $bb . ' kg' : '' }}
                    {{ $tb ? '/ ' . $tb . ' cm' : '' }}
                  </option>
                  @endforeach
                </select>
                <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
              </div>
            </div>
            <div class="border-t border-slate-100 dark:border-slate-800 -mx-5"></div>
            @endif

            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Anak <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="ps_nama" required maxlength="150"
                  placeholder="Nama lengkap balita"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" id="ps_jk" required
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                  <option value="L">Laki-laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Usia (bulan) <span class="text-rose-500">*</span></label>
                <input type="number" name="umur_bulan" id="ps_umur" required min="0" max="60"
                  placeholder="cth: 24"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                <p class="text-[10px] text-slate-400 mt-0.5">0–60 bulan</p>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Berat Badan (kg) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" min="0.5" max="50" name="berat_badan" id="ps_bb" required
                  placeholder="cth: 10.5"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tinggi Badan (cm) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" min="30" max="130" name="tinggi_badan" id="ps_tb" required
                  placeholder="cth: 75.5"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Lingkar Kepala (cm)</label>
                <input type="number" step="0.1" min="20" max="60" name="lingkar_kepala" id="ps_lk"
                  placeholder="Opsional"
                  class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Catatan Tambahan</label>
                <textarea name="catatan" id="ps_catatan" rows="2" maxlength="500"
                  placeholder="Riwayat kesehatan, kondisi khusus, dll. (opsional)"
                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none resize-none focus:ring-2 focus:ring-brand-500"></textarea>
              </div>
            </div>

            <button type="button" id="btnGenerate" onclick="generate()"
              class="w-full h-10 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2">
              <i class="ti ti-brain text-base"></i> Analisis dengan AI
            </button>

          </div>
        </form>

        <div class="mt-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-xs text-slate-500 dark:text-slate-400 space-y-1.5">
          <p class="font-semibold text-slate-600 dark:text-slate-300 flex items-center gap-1.5"><i class="ti ti-info-circle text-sm"></i> Kriteria Stunting (WHO / Permenkes RI No. 2/2020)</p>
          <div class="grid grid-cols-2 gap-1 mt-2">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block flex-shrink-0"></span> Normal: Z-score ≥ −2 SD</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block flex-shrink-0"></span> Berisiko: −2 SD &gt; Z ≥ −2,5 SD</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500 inline-block flex-shrink-0"></span> Stunting: Z &lt; −2,5 SD</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-600 inline-block flex-shrink-0"></span> Sangat Pendek: Z &lt; −3 SD</span>
          </div>
          <p class="mt-2 text-[10px] leading-relaxed">Hasil analisis AI bersifat referensi — konfirmasi dengan pengukuran fisik dan konsultasi tenaga kesehatan.</p>
        </div>
      </div>

      {{-- Placeholder --}}
      <div class="hidden lg:flex flex-col items-center justify-center text-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800" style="min-height:420px">
        <div class="w-20 h-20 rounded-2xl bg-brand-50 dark:bg-brand-500/10 grid place-items-center mb-4">
          <i class="ti ti-brain text-4xl text-brand-500 dark:text-brand-600"></i>
        </div>
        <p class="font-semibold text-slate-500 text-sm">Siap Menganalisis</p>
        <p class="text-xs text-slate-400 mt-1">Isi data balita lalu klik<br><strong>Analisis dengan AI</strong></p>
      </div>
    </div>
  </div>

  {{-- ── View: Hasil Analisis (full-width, muncul setelah generate) ── --}}
  <div id="viewResult" style="display:none">

    {{-- Tombol kembali + salin --}}
    <div class="flex items-center justify-between mb-5">
      <button onclick="backToForm()"
        class="flex items-center gap-2 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-arrow-left text-xs"></i> Analisis Ulang
      </button>
      <button id="btnCopyResult" onclick="copyResult()"
        class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-copy text-xs"></i> Salin Ringkasan
      </button>
    </div>

    {{-- Header status --}}
    <div id="resHeader" class="rounded-2xl p-5 mb-4 flex items-center gap-5">
      <div id="resIconBox" class="w-16 h-16 rounded-2xl flex-shrink-0 grid place-items-center">
        <i id="resIcon" class="text-3xl"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-3 flex-wrap">
          <p id="resNama" class="font-bold text-lg"></p>
          <span id="resBadge" class="text-xs font-bold px-3 py-1 rounded-full"></span>
        </div>
        <p id="resInfo" class="text-sm opacity-80 mt-0.5"></p>
      </div>
    </div>

    {{-- Z-score cards --}}
    <div class="grid grid-cols-3 gap-3 mb-4">
      <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 text-center">
        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-1.5">TB/U Z-score</p>
        <p id="zTBU" class="text-2xl font-bold">—</p>
        <p class="text-[10px] text-slate-400 mt-1">Tinggi / Umur</p>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 text-center">
        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-1.5">BB/U Z-score</p>
        <p id="zBBU" class="text-2xl font-bold">—</p>
        <p class="text-[10px] text-slate-400 mt-1">Berat / Umur</p>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 text-center">
        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-1.5">BB/TB Z-score</p>
        <p id="zBBTB" class="text-2xl font-bold">—</p>
        <p class="text-[10px] text-slate-400 mt-1">Berat / Tinggi</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- Kolom kiri: Kesimpulan + Rujukan + Faktor --}}
      <div class="lg:col-span-1 space-y-4">

        {{-- Kesimpulan --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
            <i class="ti ti-clipboard-text text-sm"></i> Kesimpulan
          </p>
          <p id="resKesimpulan" class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed"></p>
        </div>

        {{-- Interpretasi TB/U --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
            <i class="ti ti-ruler-measure text-sm"></i> Interpretasi Tinggi / Umur
          </p>
          <p id="resInterpretasi" class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed"></p>
        </div>

        {{-- Faktor Risiko --}}
        <div id="resFaktorWrap" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
            <i class="ti ti-alert-triangle text-sm text-amber-500"></i> Faktor Risiko Teridentifikasi
          </p>
          <ul id="resFaktor" class="space-y-2"></ul>
        </div>

        {{-- Catatan klinis --}}
        <div id="resCatatanWrap" style="display:none" class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4 text-xs text-blue-700 dark:text-blue-300">
          <p class="font-semibold mb-1.5 flex items-center gap-1.5"><i class="ti ti-notes-medical text-sm"></i> Catatan Klinis</p>
          <p id="resCatatan" class="leading-relaxed"></p>
        </div>

      </div>

      {{-- Kolom kanan: Rencana Tindakan --}}
      <div class="lg:col-span-2 space-y-4">

        {{-- Rujukan darurat --}}
        <div id="rujukanWarn" style="display:none"
          class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border-2 border-red-300 dark:border-red-500/40">
          <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 grid place-items-center flex-shrink-0">
            <i class="ti ti-ambulance text-xl text-red-600 dark:text-red-400"></i>
          </div>
          <div>
            <p class="font-bold text-red-700 dark:text-red-400 text-sm mb-0.5">Perlu Rujukan Segera ke Puskesmas!</p>
            <p class="text-xs text-red-600 dark:text-red-300 leading-relaxed">Kondisi anak memerlukan penanganan tenaga kesehatan. Jangan tunda — segera bawa ke Puskesmas atau fasilitas kesehatan terdekat untuk pemeriksaan lanjut dan intervensi gizi intensif.</p>
          </div>
        </div>

        {{-- Rencana Tindakan --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
          <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
            <p class="font-bold text-sm text-slate-900 dark:text-slate-100 flex items-center gap-2">
              <i class="ti ti-list-check text-brand-600"></i> Rencana Tindakan
            </p>
            <p class="text-xs text-slate-400 mt-0.5">Langkah konkret untuk kader posyandu dan keluarga</p>
          </div>
          <div class="p-5">
            <ol id="resRekomendasi" class="space-y-3"></ol>
          </div>
        </div>

        {{-- Rekomendasi Makanan --}}
        <div id="resMakananWrap" style="display:none" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
          <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 bg-emerald-50 dark:bg-emerald-500/10">
            <p class="font-bold text-sm text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
              <i class="ti ti-salad text-emerald-600"></i> Rekomendasi Makanan & Gizi
            </p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">Bahan makanan bergizi tinggi yang dianjurkan untuk pemulihan</p>
          </div>
          <div class="p-5 space-y-3">
            <div id="resMakananList" class="space-y-2"></div>

            {{-- Pola makan --}}
            <div id="resPolaMakanWrap" style="display:none" class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
              <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <i class="ti ti-clock-hour-4 text-sm"></i> Pola Makan yang Dianjurkan
              </p>
              <p id="resPolaMakan" class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed"></p>
            </div>

            {{-- Suplemen --}}
            <div id="resSuplemenWrap" style="display:none" class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
              <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <i class="ti ti-pill text-sm text-amber-500"></i> Suplemen / Fortifikasi
              </p>
              <ul id="resSuplemen" class="space-y-1"></ul>
            </div>
          </div>
        </div>

        {{-- Disclaimer --}}
        <p class="text-[10px] text-slate-400 leading-relaxed text-center px-4">
          Hasil analisis AI bersifat referensi klinis. Selalu konfirmasi dengan pengukuran fisik langsung dan konsultasi tenaga kesehatan. Tidak menggantikan diagnosa medis profesional.
        </p>

      </div>
    </div>
  </div>

  {{-- Error inline --}}
  <div id="viewError" style="display:none" class="flex flex-col items-center justify-center text-center py-16">
    <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-500/10 grid place-items-center mb-4">
      <i class="ti ti-alert-triangle text-2xl text-red-500"></i>
    </div>
    <p class="font-semibold text-red-700 dark:text-red-400 mb-1">Analisis Gagal</p>
    <p id="errorMsg" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed max-w-sm"></p>
    <button onclick="backToForm()" class="mt-4 px-4 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
      Coba Lagi
    </button>
  </div>

</div>

{{-- ══════════════════════════════════════════════════════
     PANEL RIWAYAT
══════════════════════════════════════════════════════ --}}
<div id="panel-riwayat" style="display:none">
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
      <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100">Riwayat Prediksi</h3>
      <p class="text-xs text-slate-400 mt-0.5">Hasil analisis sebelumnya tersimpan di sini</p>
    </div>
    <div class="p-5">
      <div id="riwayatList" class="space-y-3"></div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL LOADING
══════════════════════════════════════════════════════ --}}
<div id="modalLoading" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,.7);backdrop-filter:blur(4px);align-items:center;justify-content:center">
  <div class="w-full max-w-sm mx-4 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-8 text-center">
    <div class="relative w-20 h-20 mx-auto mb-5">
      <div class="absolute inset-0 rounded-full border-4 border-brand-100 dark:border-brand-500/20"></div>
      <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-brand-600 animate-spin"></div>
      <div class="absolute inset-0 grid place-items-center">
        <i class="ti ti-brain text-2xl text-brand-600 dark:text-brand-400"></i>
      </div>
    </div>
    <p class="font-bold text-slate-900 dark:text-slate-100 text-base mb-1">Sedang Memproses Prediksi</p>
    <p id="loadingNama" class="text-sm text-brand-600 dark:text-brand-400 font-semibold mb-3"></p>
    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">AI sedang menganalisis data antropometri dan menghitung Z-score berdasarkan standar WHO.<br>Mohon tunggu 10–30 detik…</p>
    <div class="mt-5 flex gap-1.5 justify-center">
      <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-bounce" style="animation-delay:0ms"></span>
      <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-bounce" style="animation-delay:150ms"></span>
      <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-bounce" style="animation-delay:300ms"></span>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL DETAIL RIWAYAT
══════════════════════════════════════════════════════ --}}
<div id="modalRiwayat" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:1rem">
  <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col" style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <h3 id="modalRiwayatTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm"></h3>
      <button onclick="closeModal()" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
        <i class="ti ti-x text-sm"></i>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6" id="modalRiwayatBody"></div>
  </div>
</div>

@endsection

@section('scripts')
@php
$riwayatJs = $riwayat->map(fn($r) => [
    'id'         => $r->id,
    'created_at' => $r->created_at->toISOString(),
    'parameter'  => $r->parameter,
    'konten'     => $r->konten,
])->values()->all();
@endphp
<script>
const GENERATE_URL = '{{ route("admin.ai.generate") }}';
const DESTROY_BASE = '{{ url("admin/ai/riwayat") }}/';
const CSRF         = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let   RIWAYAT      = @json($riwayatJs);
let   _lastResult  = null;

/* ── Status config ── */
const STATUS_CFG = {
  normal: {
    label : 'Normal',
    badge : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
    header: 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-200',
    icon  : 'ti ti-circle-check text-emerald-500',
    iconBg: 'bg-emerald-100 dark:bg-emerald-500/20',
  },
  berisiko_stunting: {
    label : 'Berisiko Stunting',
    badge : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
    header: 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-200',
    icon  : 'ti ti-alert-circle text-amber-500',
    iconBg: 'bg-amber-100 dark:bg-amber-500/20',
  },
  stunting_sedang: {
    label : 'Stunting (Pendek)',
    badge : 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
    header: 'bg-orange-50 dark:bg-orange-500/10 text-orange-800 dark:text-orange-200',
    icon  : 'ti ti-alert-triangle text-orange-500',
    iconBg: 'bg-orange-100 dark:bg-orange-500/20',
  },
  stunting_berat: {
    label : 'Stunting Berat',
    badge : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
    header: 'bg-red-50 dark:bg-red-500/10 text-red-800 dark:text-red-200',
    icon  : 'ti ti-urgent text-red-500',
    iconBg: 'bg-red-100 dark:bg-red-500/20',
  },
};

/* ── Tab switching ── */
function switchTab(tab) {
  ['generate','riwayat'].forEach(t => {
    document.getElementById('panel-' + t).style.display = t === tab ? '' : 'none';
    const btn = document.getElementById('tab-btn-' + t);
    if (t === tab) {
      btn.classList.add('bg-white','dark:bg-slate-900','text-slate-900','dark:text-slate-100','shadow-sm');
      btn.classList.remove('text-slate-500','dark:text-slate-400');
    } else {
      btn.classList.remove('bg-white','dark:bg-slate-900','text-slate-900','dark:text-slate-100','shadow-sm');
      btn.classList.add('text-slate-500','dark:text-slate-400');
    }
  });
  if (tab === 'riwayat') renderRiwayat();
}

/* ── Load balita ── */
function loadBalita(val) {
  if (!val) return;
  const sel = document.getElementById('selectBalita');
  const opt = sel.options[sel.selectedIndex];
  if (!opt) return;
  document.getElementById('ps_nama').value = opt.dataset.nama  ?? '';
  document.getElementById('ps_jk').value   = opt.dataset.jk    ?? 'L';
  document.getElementById('ps_umur').value = opt.dataset.umur  ?? '';
  document.getElementById('ps_bb').value   = opt.dataset.bb    ?? '';
  document.getElementById('ps_tb').value   = opt.dataset.tb    ?? '';
}

/* ── View switching ── */
function showView(v) {
  ['viewForm','viewResult','viewError'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = id === v ? '' : 'none';
  });
}
function backToForm() { showView('viewForm'); }

/* ── Loading modal ── */
function showLoading(nama) {
  document.getElementById('loadingNama').textContent = nama || '';
  document.getElementById('modalLoading').style.display = 'flex';
}
function hideLoading() {
  document.getElementById('modalLoading').style.display = 'none';
}

/* ── Z-score helpers ── */
function fmtZ(val) {
  if (val === null || val === undefined || val === '' || isNaN(parseFloat(val))) return '—';
  const n = parseFloat(val);
  return (n >= 0 ? '+' : '') + n.toFixed(2);
}
function zClass(val) {
  const n = parseFloat(val);
  if (isNaN(n)) return 'text-slate-500';
  if (n >= -2)   return 'text-emerald-600 dark:text-emerald-400';
  if (n >= -2.5) return 'text-amber-600 dark:text-amber-400';
  if (n >= -3)   return 'text-orange-600 dark:text-orange-400';
  return 'text-red-600 dark:text-red-400';
}

/* ── Render hasil analisis ── */
function renderResult(data, formData) {
  _lastResult = { data, formData };
  const cfg = STATUS_CFG[data.status] ?? STATUS_CFG.normal;

  /* Header */
  const header = document.getElementById('resHeader');
  header.className = 'rounded-2xl p-5 mb-4 flex items-center gap-5 ' + cfg.header;
  const iconBox = document.getElementById('resIconBox');
  iconBox.className = 'w-16 h-16 rounded-2xl flex-shrink-0 grid place-items-center ' + cfg.iconBg;
  document.getElementById('resIcon').className = cfg.icon + ' text-3xl';
  document.getElementById('resNama').textContent = formData.nama;
  const badge = document.getElementById('resBadge');
  badge.textContent = cfg.label;
  badge.className = 'text-xs font-bold px-3 py-1 rounded-full ' + cfg.badge;
  document.getElementById('resInfo').textContent =
    (formData.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') +
    ' · ' + formData.umur_bulan + ' bulan · ' +
    formData.berat_badan + ' kg / ' + formData.tinggi_badan + ' cm';

  /* Z-scores */
  const zTBU  = document.getElementById('zTBU');
  const zBBU  = document.getElementById('zBBU');
  const zBBTB = document.getElementById('zBBTB');
  zTBU.textContent  = fmtZ(data.tb_u_zscore);
  zBBU.textContent  = fmtZ(data.bb_u_zscore);
  zBBTB.textContent = fmtZ(data.bb_tb_zscore);
  zTBU.className    = 'text-2xl font-bold ' + zClass(data.tb_u_zscore);
  zBBU.className    = 'text-2xl font-bold ' + zClass(data.bb_u_zscore);
  zBBTB.className   = 'text-2xl font-bold ' + zClass(data.bb_tb_zscore);

  /* Rujukan */
  document.getElementById('rujukanWarn').style.display = data.perlu_rujukan ? '' : 'none';

  /* Teks */
  document.getElementById('resKesimpulan').textContent   = data.kesimpulan ?? '';
  document.getElementById('resInterpretasi').textContent = data.interpretasi_tb_u ?? '';

  /* Faktor risiko */
  const faktor = data.faktor_risiko ?? [];
  document.getElementById('resFaktorWrap').style.display = faktor.length ? '' : 'none';
  document.getElementById('resFaktor').innerHTML = faktor.map(f =>
    `<li class="flex items-start gap-2 text-sm text-slate-700 dark:text-slate-300">
      <i class="ti ti-point-filled text-amber-500 text-xs mt-1 flex-shrink-0"></i>
      <span>${escHtml(f)}</span>
    </li>`
  ).join('');

  /* Rekomendasi / Rencana Tindakan */
  const rek = data.rekomendasi ?? [];
  document.getElementById('resRekomendasi').innerHTML = rek.length
    ? rek.map((r, i) =>
        `<li class="flex items-start gap-3">
          <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 text-xs font-bold grid place-items-center">${i+1}</span>
          <span class="text-sm text-slate-700 dark:text-slate-300 pt-0.5 leading-relaxed">${escHtml(r)}</span>
        </li>`
      ).join('')
    : '<li class="text-sm text-slate-400">Tidak ada rekomendasi spesifik.</li>';

  /* Rekomendasi Makanan */
  const makanan = data.rekomendasi_makanan ?? [];
  const polaMakan = data.pola_makan ?? '';
  const suplemen  = data.suplemen_gizi ?? [];
  const adaMakanan = makanan.length || polaMakan.trim() || suplemen.length;

  document.getElementById('resMakananWrap').style.display = adaMakanan ? '' : 'none';

  document.getElementById('resMakananList').innerHTML = makanan.map(m =>
    `<div class="flex items-start gap-3 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
      <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 grid place-items-center flex-shrink-0 mt-0.5">
        <i class="ti ti-leaf text-emerald-600 dark:text-emerald-400 text-sm"></i>
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm text-emerald-800 dark:text-emerald-200">${escHtml(m.nama ?? '')}</p>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 leading-relaxed">${escHtml(m.manfaat ?? '')}</p>
        ${m.contoh_menu ? `<p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1 flex items-start gap-1"><i class="ti ti-chef-hat text-xs flex-shrink-0 mt-0.5"></i>${escHtml(m.contoh_menu)}</p>` : ''}
      </div>
    </div>`
  ).join('');

  if (polaMakan.trim()) {
    document.getElementById('resPolaMakan').textContent = polaMakan;
    document.getElementById('resPolaMakanWrap').style.display = '';
  } else {
    document.getElementById('resPolaMakanWrap').style.display = 'none';
  }

  if (suplemen.length) {
    document.getElementById('resSuplemen').innerHTML = suplemen.map(s =>
      `<li class="flex items-start gap-1.5 text-sm text-slate-700 dark:text-slate-300">
        <i class="ti ti-pill text-amber-500 text-xs mt-0.5 flex-shrink-0"></i>
        <span>${escHtml(s)}</span>
      </li>`
    ).join('');
    document.getElementById('resSuplemenWrap').style.display = '';
  } else {
    document.getElementById('resSuplemenWrap').style.display = 'none';
  }

  /* Catatan klinis */
  const catKlinis = data.catatan_klinis ?? '';
  if (catKlinis.trim()) {
    document.getElementById('resCatatan').textContent = catKlinis;
    document.getElementById('resCatatanWrap').style.display = '';
  } else {
    document.getElementById('resCatatanWrap').style.display = 'none';
  }
}

/* ── Generate ── */
async function generate() {
  const form = document.getElementById('formPrediksi');
  if (!form.reportValidity()) return;

  const fd   = new FormData(form);
  const data = { type: 'prediksi-stunting' };
  fd.forEach((v, k) => { data[k] = v; });

  const nama = data.nama || 'balita';
  showLoading(nama);

  const btn = document.getElementById('btnGenerate');
  btn.disabled = true;

  try {
    const res  = await fetch(GENERATE_URL, {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body:    JSON.stringify(data),
    });
    const rawText = await res.text();
    let json;
    try { json = JSON.parse(rawText); }
    catch { throw new Error('Server error (' + res.status + '). Coba muat ulang halaman atau login ulang.'); }

    if (json.error) {
      hideLoading();
      document.getElementById('errorMsg').textContent = json.error;
      showView('viewError');
      return;
    }

    // Server sudah parse JSON — gunakan langsung
    const parsed = json.parsed;
    if (!parsed || typeof parsed !== 'object') {
      hideLoading();
      document.getElementById('errorMsg').textContent =
        'Respons AI tidak dapat diproses. Coba ulangi analisis.';
      showView('viewError');
      return;
    }

    renderResult(parsed, data);
    hideLoading();
    showView('viewResult');

    if (json.riwayat) {
      RIWAYAT.unshift({ ...json.riwayat, konten: json.content, _parsed: parsed });
      document.getElementById('riwayatCount').textContent = RIWAYAT.length;
    }

  } catch (e) {
    hideLoading();
    document.getElementById('errorMsg').textContent = 'Koneksi gagal: ' + e.message;
    showView('viewError');
  } finally {
    btn.disabled = false;
  }
}

/* ── Copy hasil ── */
async function copyResult() {
  if (!_lastResult) return;
  const { data: r, formData: f } = _lastResult;
  const cfg = STATUS_CFG[r.status] ?? STATUS_CFG.normal;
  const lines = [
    '=== HASIL PREDIKSI STUNTING ===',
    'Nama     : ' + (f.nama ?? '—'),
    'Status   : ' + cfg.label,
    'TB/U Z   : ' + fmtZ(r.tb_u_zscore),
    'BB/U Z   : ' + fmtZ(r.bb_u_zscore),
    'BB/TB Z  : ' + fmtZ(r.bb_tb_zscore),
    '',
    'KESIMPULAN:',
    r.kesimpulan ?? '',
    '',
    'RENCANA TINDAKAN:',
    ...(r.rekomendasi ?? []).map((rec, i) => `${i+1}. ${rec}`),
  ];
  if ((r.rekomendasi_makanan ?? []).length) {
    lines.push('', 'REKOMENDASI MAKANAN:');
    r.rekomendasi_makanan.forEach((m, i) => {
      lines.push(`${i+1}. ${m.nama} — ${m.manfaat}`);
      if (m.contoh_menu) lines.push(`   Menu: ${m.contoh_menu}`);
    });
  }
  if (r.pola_makan?.trim()) {
    lines.push('', 'POLA MAKAN:', r.pola_makan);
  }
  if ((r.suplemen_gizi ?? []).length) {
    lines.push('', 'SUPLEMEN/FORTIFIKASI:', ...(r.suplemen_gizi).map((s, i) => `${i+1}. ${s}`));
  }
  if (r.perlu_rujukan) {
    lines.push('', '⚠ PERLU RUJUKAN SEGERA KE PUSKESMAS');
  }
  if (r.catatan_klinis?.trim()) {
    lines.push('', 'CATATAN KLINIS:', r.catatan_klinis);
  }
  try {
    await navigator.clipboard.writeText(lines.join('\n'));
    const btn = document.getElementById('btnCopyResult');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-check text-xs"></i> Tersalin!';
    btn.classList.add('text-emerald-600','border-emerald-300');
    setTimeout(() => {
      btn.innerHTML = orig;
      btn.classList.remove('text-emerald-600','border-emerald-300');
    }, 2000);
  } catch { alert('Salin tidak tersedia di browser ini.'); }
}

/* ── Riwayat ── */
function timeAgo(iso) {
  const d = Math.floor((Date.now() - new Date(iso)) / 1000);
  if (d < 60)    return d + ' dtk lalu';
  if (d < 3600)  return Math.floor(d/60) + ' mnt lalu';
  if (d < 86400) return Math.floor(d/3600) + ' jam lalu';
  return Math.floor(d/86400) + ' hari lalu';
}

function renderRiwayat() {
  const el = document.getElementById('riwayatList');
  if (!RIWAYAT.length) {
    el.innerHTML = `<div class="py-16 text-center text-slate-400">
      <i class="ti ti-history text-4xl block mb-3 opacity-40"></i>
      <p class="font-medium text-sm">Belum ada riwayat prediksi</p>
      <p class="text-xs mt-1">Hasil analisis tersimpan otomatis di sini</p>
    </div>`;
    return;
  }
  el.innerHTML = RIWAYAT.map(r => {
    const p = r.parameter ?? {};
    const parsed = r._parsed ?? tryParseJson(r.konten);
    const status = parsed?.status ?? 'normal';
    const cfg    = STATUS_CFG[status] ?? STATUS_CFG.normal;
    return `
    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-4" id="ritem-${r.id}">
      <div class="flex items-start justify-between gap-3 mb-2">
        <div>
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm">${escHtml(p.nama ?? '—')}</p>
          <p class="text-xs text-slate-400 mt-0.5">
            ${p.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'} ·
            ${p.umur_bulan ?? '—'} bln ·
            ${p.berat_badan ?? '—'} kg / ${p.tinggi_badan ?? '—'} cm
          </p>
        </div>
        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 ${cfg.badge}">${cfg.label}</span>
      </div>
      ${parsed?.kesimpulan ? `<p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-3">${escHtml(parsed.kesimpulan)}</p>` : ''}
      <div class="flex items-center justify-between gap-2">
        <span class="text-xs text-slate-400 flex items-center gap-1"><i class="ti ti-clock text-xs"></i> ${timeAgo(r.created_at)}</span>
        <div class="flex items-center gap-1.5">
          <button onclick="lihatRiwayat(${r.id})"
            class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i class="ti ti-eye text-xs"></i> Lihat
          </button>
          <button onclick="muatRiwayat(${r.id})"
            class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-brand-200 dark:border-brand-700 text-xs font-medium text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
            <i class="ti ti-refresh text-xs"></i> Muat Ulang
          </button>
          <button onclick="hapusRiwayat(${r.id})"
            class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors">
            <i class="ti ti-trash text-xs"></i>
          </button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function lihatRiwayat(id) {
  const r = RIWAYAT.find(x => x.id === id);
  if (!r) return;
  const p = r.parameter ?? {};
  const parsed = r._parsed ?? tryParseJson(r.konten);
  const status = parsed?.status ?? 'normal';
  const cfg    = STATUS_CFG[status] ?? STATUS_CFG.normal;

  document.getElementById('modalRiwayatTitle').textContent =
    (p.nama ?? '—') + ' · ' + (p.umur_bulan ?? '—') + ' bln';

  document.getElementById('modalRiwayatBody').innerHTML = !parsed
    ? `<pre class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-wrap">${escHtml(r.konten)}</pre>`
    : `<div class="space-y-4 text-sm">
        <div class="flex items-center justify-between">
          <span class="text-slate-500">Status</span>
          <span class="font-bold text-xs px-2 py-0.5 rounded-full ${cfg.badge}">${cfg.label}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-2.5 text-center">
            <div class="font-bold ${zClass(parsed.tb_u_zscore)}">${fmtZ(parsed.tb_u_zscore)}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">TB/U</div>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-2.5 text-center">
            <div class="font-bold ${zClass(parsed.bb_u_zscore)}">${fmtZ(parsed.bb_u_zscore)}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">BB/U</div>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-2.5 text-center">
            <div class="font-bold ${zClass(parsed.bb_tb_zscore)}">${fmtZ(parsed.bb_tb_zscore)}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">BB/TB</div>
          </div>
        </div>
        ${parsed.perlu_rujukan ? `<div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 text-xs font-semibold"><i class="ti ti-ambulance"></i> Perlu Rujukan Segera ke Puskesmas</div>` : ''}
        ${parsed.kesimpulan ? `<div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Kesimpulan</p><p class="text-slate-700 dark:text-slate-300 leading-relaxed">${escHtml(parsed.kesimpulan)}</p></div>` : ''}
        ${(parsed.rekomendasi?.length) ? `<div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Rencana Tindakan</p><ol class="space-y-2">${parsed.rekomendasi.map((rec, i) => `<li class="flex items-start gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 text-[10px] font-bold grid place-items-center">${i+1}</span><span class="text-slate-700 dark:text-slate-300">${escHtml(rec)}</span></li>`).join('')}</ol></div>` : ''}
        ${(parsed.rekomendasi_makanan?.length) ? `<div class="pt-2 border-t border-slate-100 dark:border-slate-800"><p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide mb-2 flex items-center gap-1"><i class="ti ti-salad text-xs"></i> Rekomendasi Makanan</p><div class="space-y-2">${parsed.rekomendasi_makanan.map(m => `<div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20"><p class="font-semibold text-xs text-emerald-800 dark:text-emerald-200">${escHtml(m.nama??'')}</p><p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">${escHtml(m.manfaat??'')}</p>${m.contoh_menu?`<p class="text-[11px] text-emerald-700 dark:text-emerald-300 mt-0.5"><i class="ti ti-chef-hat text-[10px]"></i> ${escHtml(m.contoh_menu)}</p>`:''}</div>`).join('')}</div></div>` : ''}
        ${(parsed.suplemen_gizi?.length) ? `<div class="pt-2 border-t border-slate-100 dark:border-slate-800"><p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide mb-1.5 flex items-center gap-1"><i class="ti ti-pill text-xs"></i> Suplemen/Fortifikasi</p><ul class="space-y-1">${parsed.suplemen_gizi.map(s=>`<li class="text-xs text-slate-700 dark:text-slate-300 flex items-start gap-1.5"><i class="ti ti-point-filled text-amber-400 text-[10px] mt-0.5 flex-shrink-0"></i>${escHtml(s)}</li>`).join('')}</ul></div>` : ''}
        ${parsed.pola_makan?.trim() ? `<div class="pt-2 border-t border-slate-100 dark:border-slate-800"><p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Pola Makan</p><p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">${escHtml(parsed.pola_makan)}</p></div>` : ''}
      </div>`;

  document.getElementById('modalRiwayat').style.display = 'flex';
}

function muatRiwayat(id) {
  const r = RIWAYAT.find(x => x.id === id);
  if (!r) return;
  const p = r.parameter ?? {};
  document.getElementById('ps_nama').value        = p.nama ?? '';
  document.getElementById('ps_jk').value          = p.jenis_kelamin ?? 'L';
  document.getElementById('ps_umur').value        = p.umur_bulan ?? '';
  document.getElementById('ps_bb').value          = p.berat_badan ?? '';
  document.getElementById('ps_tb').value          = p.tinggi_badan ?? '';
  document.getElementById('ps_lk').value          = p.lingkar_kepala ?? '';
  document.getElementById('ps_catatan').value     = p.catatan ?? '';
  showView('viewForm');
  switchTab('generate');
}

async function hapusRiwayat(id) {
  if (!confirm('Hapus riwayat prediksi ini?')) return;
  try {
    const res = await fetch(DESTROY_BASE + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
    if (res.ok) {
      RIWAYAT = RIWAYAT.filter(r => r.id !== id);
      document.getElementById('riwayatCount').textContent = RIWAYAT.length;
      document.getElementById('ritem-' + id)?.remove();
      if (!RIWAYAT.length) renderRiwayat();
    }
  } catch(e) { console.error(e); }
}

function closeModal() { document.getElementById('modalRiwayat').style.display = 'none'; }
document.getElementById('modalRiwayat').addEventListener('click', e => {
  if (e.target === document.getElementById('modalRiwayat')) closeModal();
});

function tryParseJson(text) {
  if (!text) return null;
  // strip markdown code blocks
  text = text.replace(/```(?:json)?\s*/gi, '').replace(/```/g, '').trim();
  try { return JSON.parse(text); } catch {}
  const m = text.match(/\{[\s\S]*\}/);
  if (m) { try { return JSON.parse(m[0]); } catch {} }
  return null;
}

function escHtml(s) {
  return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
@endsection
