@extends('layouts.admin')
@section('title', 'Kata Sambutan AI')
@section('page-title', 'Kata Sambutan')
@section('page-sub', 'Generate kata sambutan kepala desa dengan bantuan AI')

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
      <form id="formSambutan" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100">Parameter Kata Sambutan</h3>
          <p class="text-xs text-slate-400 mt-0.5">Isi detail acara, AI akan menulis sambutan yang sesuai</p>
        </div>

        <div class="p-5 space-y-4">

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Acara / Kegiatan <span class="text-red-500">*</span></label>
            <input type="text" name="acara" id="f_acara" required maxlength="200"
              placeholder="Contoh: Musyawarah Desa (Musdes) Perencanaan Pembangunan 2025"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal & Tempat <span class="text-red-500">*</span></label>
            <input type="text" name="tanggal_tempat" id="f_tanggal_tempat" required maxlength="200"
              placeholder="Contoh: Sabtu, 15 Februari 2025 di Balai Desa {{ $desaNama }}"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Peserta / Undangan <span class="text-red-500">*</span></label>
            <input type="text" name="peserta" id="f_peserta" required maxlength="300"
              placeholder="Contoh: Tokoh masyarakat, BPD, Ketua RT/RW, dan warga desa"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Poin-poin yang Ingin Disampaikan</label>
            <textarea name="poin" id="f_poin" rows="4" maxlength="1000"
              placeholder="Contoh:&#10;- Apresiasi kehadiran peserta&#10;- Capaian pembangunan tahun lalu&#10;- Rencana prioritas tahun ini&#10;- Ajakan partisipasi aktif warga"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
            <p class="text-[10px] text-slate-400 mt-1">Opsional — jika kosong AI akan menyesuaikan sendiri berdasarkan jenis acara.</p>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Panjang Sambutan</label>
            <select name="panjang" id="f_panjang"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="singkat (sekitar 200 kata, cocok dibacakan 2-3 menit)">Singkat — ±200 kata (2–3 menit)</option>
              <option value="sedang (sekitar 400 kata, cocok dibacakan 4-5 menit)" selected>Sedang — ±400 kata (4–5 menit)</option>
              <option value="panjang (sekitar 600 kata, cocok dibacakan 6-8 menit)">Panjang — ±600 kata (6–8 menit)</option>
            </select>
          </div>

        </div>

        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <button type="button" data-generate
            onclick="generate('formSambutan','sambutan')"
            {{ !$apiReady ? 'disabled' : '' }}
            class="w-full flex items-center justify-center gap-2 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold transition-colors">
            <i class="ti ti-sparkles text-base"></i> Generate
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
  const panjang = (p.panjang || '').replace(/\s*\(.*\)/, '').trim();
  const label   = panjang.charAt(0).toUpperCase() + panjang.slice(1);
  return (p.acara || '—') + (label ? ' · ' + label : '');
}

function fillForm(p) {
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
  set('f_acara',           p.acara);
  set('f_tanggal_tempat',  p.tanggal_tempat);
  set('f_peserta',         p.peserta);
  set('f_poin',            p.poin);
  set('f_panjang',         p.panjang);
}
</script>
@include('admin.ai._scripts')
@endsection
