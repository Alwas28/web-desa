{{-- Panel output AI --}}
<div class="lg:sticky lg:top-4 self-start space-y-3">
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col"
       style="min-height:520px">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-500/10 grid place-items-center">
          <i class="ti ti-sparkles text-brand-600 dark:text-brand-400 text-sm"></i>
        </div>
        <span class="font-semibold text-sm text-slate-800 dark:text-slate-200">Hasil Generate</span>
        <span id="editBadge" style="display:none"
          class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">
          EDIT
        </span>
      </div>
      <div class="flex items-center gap-1.5">
        <span id="wordCount" class="text-xs text-slate-400 hidden mr-1"></span>

        {{-- Edit / Selesai --}}
        <button id="btnEdit" onclick="toggleEdit()" style="display:none"
          class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-pencil text-xs" id="btnEditIcon"></i>
          <span id="btnEditLabel">Edit</span>
        </button>

        {{-- Salin --}}
        <button id="btnCopy" onclick="copyOutput()" style="display:none"
          class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-copy text-xs"></i> Salin
        </button>

        {{-- Unduh PDF --}}
        <button id="btnPdf" onclick="downloadPdf()" style="display:none"
          class="flex items-center gap-1 px-2.5 h-7 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
          <i class="ti ti-file-type-pdf text-xs"></i> PDF
        </button>
      </div>
    </div>

    {{-- Body --}}
    <div class="flex-1 relative p-5">

      <div id="statePlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-8">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 grid place-items-center mb-4">
          <i class="ti ti-robot text-3xl text-slate-400"></i>
        </div>
        <p class="font-medium text-slate-500 text-sm">Belum ada konten</p>
        <p class="text-xs text-slate-400 mt-1">Isi form lalu klik <strong>Generate</strong></p>
      </div>

      <div id="stateLoading" style="display:none" class="absolute inset-0 flex flex-col items-center justify-center gap-4">
        <div class="w-12 h-12 rounded-full border-4 border-brand-200 border-t-brand-600 animate-spin"></div>
        <p class="text-sm text-slate-500">AI sedang menulis konten…</p>
        <p class="text-xs text-slate-400">Mungkin membutuhkan 10–30 detik</p>
      </div>

      <div id="stateError" style="display:none" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-500/10 grid place-items-center mb-3">
          <i class="ti ti-alert-triangle text-2xl text-red-500"></i>
        </div>
        <p class="font-semibold text-sm text-red-700 dark:text-red-400 mb-1">Gagal Generate</p>
        <p id="errorMsg" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed"></p>
      </div>

      <div id="stateContent" style="display:none">
        {{-- View mode --}}
        <div id="outputText"
          class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed font-sans overflow-y-auto"
          style="max-height:460px; white-space:pre-wrap;"></div>

        {{-- Edit mode: Quill editor --}}
        <div id="quillWrapper" style="display:none; margin:-1.25rem; margin-top:0;">
          <div id="quillEditor"></div>
        </div>
      </div>

    </div>
  </div>
  <p id="regenHint" style="display:none" class="text-xs text-slate-400 text-center">
    Tidak puas? Ubah parameter lalu klik Generate lagi.
  </p>
</div>

{{-- Modal dipindah ke _scripts.blade.php agar tidak terpengaruh display:none panel --}}
