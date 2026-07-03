@extends('layouts.admin')
@section('title', 'Peraturan Desa AI')
@section('page-title', 'Peraturan Desa')
@section('page-sub', 'Generate draft Peraturan Desa (Perdes) dengan bantuan AI')

@section('content')

{{-- ── Tab bar ── --}}
<div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl w-fit mb-6">
  <button id="tab-btn-generate" onclick="switchTab('generate')"
    class="flex items-center gap-2 px-4 h-8 rounded-[10px] text-sm font-semibold bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm transition-all">
    <i class="ti ti-sparkles text-brand-500 text-sm"></i> Generate
  </button>
  <button id="tab-btn-riwayat" onclick="switchTab('riwayat')"
    class="flex items-center gap-2 px-4 h-8 rounded-[10px] text-sm font-semibold text-slate-500 dark:text-slate-400 transition-all">
    <i class="ti ti-history text-sm"></i> Riwayat
    <span id="riwayatCount" class="px-1.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ count($riwayat) }}</span>
  </button>
</div>

{{-- ── Panel Generate ── --}}
<div id="panel-generate">

  @if(!$apiReady)
  <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-sm mb-4">
    <i class="ti ti-alert-triangle flex-shrink-0 mt-0.5"></i>
    <div>Kunci API belum dikonfigurasi. Buka <a href="{{ route('admin.settings.index') }}" class="underline font-semibold">Pengaturan → API</a> untuk mengisi kunci API terlebih dahulu.</div>
  </div>
  @endif

  <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-xs text-blue-700 dark:text-blue-300 mb-4">
    <i class="ti ti-info-circle text-base flex-shrink-0 mt-0.5"></i>
    Hasil generate adalah <strong>draft awal</strong> yang perlu ditinjau, disesuaikan, dan disahkan oleh Kepala Desa & BPD sesuai prosedur resmi. Jangan langsung dijadikan dokumen final tanpa revisi.
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ── Form ── --}}
    <div>
      <form id="formPerdes" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100">Parameter Peraturan Desa</h3>
          <p class="text-xs text-slate-400 mt-0.5">Isi detail perdes, AI akan menyusun draft lengkap berstruktur BAB & Pasal</p>
        </div>

        <div class="p-5 space-y-4">

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nomor Perdes <span class="text-red-500">*</span></label>
              <input type="text" name="nomor" id="f_nomor" required maxlength="50"
                placeholder="Contoh: 01 Tahun 2025"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tahun <span class="text-red-500">*</span></label>
              <input type="number" name="tahun" id="f_tahun" required min="2020" max="2099"
                value="{{ date('Y') }}"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tentang / Judul Perdes <span class="text-red-500">*</span></label>
            <input type="text" name="tentang" id="f_tentang" required maxlength="300"
              placeholder="Contoh: Pengelolaan Sampah dan Kebersihan Lingkungan Desa"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Latar Belakang / Pertimbangan <span class="text-red-500">*</span></label>
            <textarea name="latar_belakang" id="f_latar_belakang" required rows="3" maxlength="1000"
              placeholder="Contoh: Meningkatnya volume sampah di desa, belum adanya aturan pengelolaan sampah yang jelas..."
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
            <p class="text-[10px] text-slate-400 mt-1">Akan digunakan sebagai bagian "Menimbang" dalam Perdes.</p>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Pokok-pokok Pengaturan yang Diinginkan <span class="text-red-500">*</span></label>
            <textarea name="pokok_pokok" id="f_pokok_pokok" required rows="5" maxlength="2000"
              placeholder="Contoh:&#10;- Setiap rumah wajib menyediakan tempat sampah&#10;- Jadwal pengangkutan sampah 2x seminggu&#10;- Sanksi bagi pelanggar&#10;- Pembentukan tim kebersihan desa"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
            <p class="text-[10px] text-slate-400 mt-1">AI akan menyusun ini menjadi BAB dan Pasal yang terstruktur.</p>
          </div>

        </div>

        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <button type="button" data-generate
            onclick="generate('formPerdes','perdes')"
            {{ !$apiReady ? 'disabled' : '' }}
            class="w-full flex items-center justify-center gap-2 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold transition-colors">
            <i class="ti ti-sparkles text-base"></i> Generate Draft Perdes
          </button>
        </div>
      </form>
    </div>

    {{-- ── Output ── --}}
    @include('admin.ai._output')

  </div>
</div>

{{-- ── Panel Riwayat ── --}}
<div id="panel-riwayat" style="display:none">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Riwayat Generate</h3>
      <p class="text-xs text-slate-400 mt-0.5">Semua hasil generate tersimpan otomatis di sini</p>
    </div>
  </div>
  <div class="space-y-3" id="riwayatList">
    {{-- Rendered by JS on tab switch --}}
  </div>
</div>

@endsection

@section('scripts')
<script>
function paramSummary(p) {
  return 'No. ' + (p.nomor || '—') + ' Tahun ' + (p.tahun || '') + ' — ' + (p.tentang || '—');
}

function fillForm(p) {
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
  set('f_nomor',          p.nomor);
  set('f_tahun',          p.tahun);
  set('f_tentang',        p.tentang);
  set('f_latar_belakang', p.latar_belakang);
  set('f_pokok_pokok',    p.pokok_pokok);
}
</script>
@include('admin.ai._scripts')
@endsection
