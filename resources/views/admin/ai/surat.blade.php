@extends('layouts.admin')
@section('title', 'Konten Surat AI')
@section('page-title', 'Konten Surat')
@section('page-sub', 'Generate isi surat resmi desa dengan bantuan AI')

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

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ── Form ── --}}
    <div>
      <form id="formSurat" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100">Parameter Surat</h3>
          <p class="text-xs text-slate-400 mt-0.5">AI menghasilkan isi surat (pembuka, isi, penutup) — bukan kop surat</p>
        </div>

        <div class="p-5 space-y-4">

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jenis Surat <span class="text-red-500">*</span></label>
            <input type="text" name="jenis" id="f_jenis" required maxlength="100"
              placeholder="Contoh: Surat Permohonan, Surat Keterangan, Surat Undangan"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Ditujukan Kepada <span class="text-red-500">*</span></label>
            <input type="text" name="kepada" id="f_kepada" required maxlength="200"
              placeholder="Contoh: Bapak Camat Kecamatan Tekonea"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Jabatan / Instansi Penerima</label>
            <input type="text" name="jabatan" id="f_jabatan" maxlength="200"
              placeholder="Contoh: Kantor Kecamatan Tekonea"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
            <p class="text-[10px] text-slate-400 mt-1">Opsional — jika diisi akan dicantumkan dalam surat.</p>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Perihal <span class="text-red-500">*</span></label>
            <input type="text" name="perihal" id="f_perihal" required maxlength="200"
              placeholder="Contoh: Permohonan Bantuan Dana Pembangunan Jalan Desa"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Poin-poin Isi Surat <span class="text-red-500">*</span></label>
            <textarea name="poin" id="f_poin" required rows="5" maxlength="2000"
              placeholder="Contoh:&#10;- Panjang jalan yang perlu diperbaiki 500 meter&#10;- Kondisi jalan rusak berat&#10;- Dana yang dibutuhkan sekitar Rp 150 juta&#10;- Mohon bantuan dari APBD kabupaten"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Surat</label>
            <input type="text" name="tanggal" id="f_tanggal"
              value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
              maxlength="50"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

        </div>

        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <button type="button" data-generate
            onclick="generate('formSurat','surat')"
            {{ !$apiReady ? 'disabled' : '' }}
            class="w-full flex items-center justify-center gap-2 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold transition-colors">
            <i class="ti ti-sparkles text-base"></i> Generate Konten Surat
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
  return (p.jenis || '—') + ' · Kepada: ' + (p.kepada || '—') + (p.perihal ? ' · ' + p.perihal : '');
}

function fillForm(p) {
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
  set('f_jenis',   p.jenis);
  set('f_kepada',  p.kepada);
  set('f_jabatan', p.jabatan);
  set('f_perihal', p.perihal);
  set('f_poin',    p.poin);
  set('f_tanggal', p.tanggal);
}
</script>
@include('admin.ai._scripts')
@endsection
