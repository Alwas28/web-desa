@extends('layouts.admin')

@section('title', $berita ? 'Edit Berita' : 'Buat Berita')
@section('page-title', $berita ? 'Edit Berita' : 'Buat Berita')
@section('page-sub', $berita ? 'Perbarui konten artikel' : 'Tulis artikel atau informasi baru')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
<style>
  /* ─ Quill container ─ */
  #quillEditor {
    min-height: 420px;
    font-size: 0.9375rem;
    line-height: 1.75;
    font-family: inherit;
    border: none;
    border-radius: 0;
  }
  .ql-toolbar.ql-snow {
    border: none;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 16px;
    background: #f8fafc;
    border-radius: 0;
    flex-wrap: wrap;
  }
  .ql-container.ql-snow {
    border: none;
  }
  .ql-editor { padding: 20px 24px; min-height: 380px; cursor: text; }
  .ql-editor.ql-blank::before {
    font-style: normal;
    color: #94a3b8;
    left: 24px;
  }
  /* Dark mode */
  .dark .ql-toolbar.ql-snow {
    background: #1e293b;
    border-bottom-color: #334155;
  }
  .dark .ql-toolbar.ql-snow .ql-stroke { stroke: #94a3b8; }
  .dark .ql-toolbar.ql-snow .ql-fill  { fill:   #94a3b8; }
  .dark .ql-toolbar.ql-snow .ql-picker-label  { color: #94a3b8; }
  .dark .ql-toolbar.ql-snow .ql-picker-options { background: #0f172a; border-color: #334155; }
  .dark .ql-toolbar.ql-snow button:hover .ql-stroke,
  .dark .ql-toolbar.ql-snow .ql-active  .ql-stroke { stroke: #f1f5f9; }
  .dark .ql-toolbar.ql-snow button:hover .ql-fill,
  .dark .ql-toolbar.ql-snow .ql-active  .ql-fill   { fill:   #f1f5f9; }
  .dark #quillEditor { background: #1e293b; color: #f1f5f9; }
  /* SEO counter colors */
  .seo-ok   { color: #10b981; }
  .seo-warn { color: #f59e0b; }
  .seo-over { color: #ef4444; }
  /* ─ Source mode ─ */
  #sourceEditor {
    display: none; width: 100%; min-height: 380px;
    padding: 20px 24px; font-family: 'Consolas','Monaco',monospace;
    font-size: 13px; line-height: 1.6; border: none; outline: none;
    resize: vertical; background: #f8fafc; color: #1e293b; box-sizing: border-box;
  }
  .dark #sourceEditor { background: #1e293b; color: #f1f5f9; }
  .ql-toolbar .ql-source.ql-active { background: #dbeafe; border-radius: 4px; }
  .dark .ql-toolbar .ql-source.ql-active { background: #1e3a5f; }
  /* ─ Table picker ─ */
  .ql-table-picker {
    position: fixed; z-index: 9999; background: white;
    border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px;
    box-shadow: 0 4px 24px rgba(0,0,0,.12);
  }
  .dark .ql-table-picker { background: #1e293b; border-color: #334155; }
  .ql-table-grid { display: grid; grid-template-columns: repeat(8, 20px); gap: 2px; }
  .ql-table-cell { width: 20px; height: 20px; border: 1px solid #cbd5e1; border-radius: 2px; cursor: pointer; }
  .ql-table-cell.ql-table-cell-hover { background: #bfdbfe; border-color: #3b82f6; }
  .dark .ql-table-cell { border-color: #475569; }
  .dark .ql-table-cell.ql-table-cell-hover { background: #1e3a5f; border-color: #3b82f6; }
  .ql-table-picker-label { text-align: center; font-size: 11px; color: #64748b; margin-top: 6px; }
  .dark .ql-table-picker-label { color: #94a3b8; }
  /* ─ Tables in editor ─ */
  .ql-editor table { border-collapse: collapse; width: 100%; margin: 12px 0; }
  .ql-editor table td, .ql-editor table th { border: 1px solid #cbd5e1; padding: 6px 10px; min-width: 40px; vertical-align: top; }
  .dark .ql-editor table td, .dark .ql-editor table th { border-color: #475569; }
</style>
@endsection

@section('content')

@php
  $isEdit   = ! is_null($berita);
  $action   = $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store');
  $oldSts   = old('status', $berita?->status ?? 'draft');
  $oldKat   = old('kategori_id', $berita?->kategori_id);
  $siteUrl  = rtrim(config('app.url'), '/');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="beritaForm">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- ── Kolom Kiri: Konten utama ──────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- Judul & Slug --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1.5">
            Judul Berita <span class="text-rose-500">*</span>
          </label>
          <input type="text" name="judul" id="f_judul" required autocomplete="off"
            class="w-full px-3 h-10 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('judul') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
            value="{{ old('judul', $berita?->judul) }}"
            placeholder="Tulis judul yang menarik…">
          @error('judul')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Slug URL</label>
          <div class="flex items-center rounded-lg border {{ $errors->has('slug') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} overflow-hidden focus-within:ring-2 focus-within:ring-brand-500">
            <span class="px-3 h-10 flex items-center text-slate-400 text-xs border-r border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 whitespace-nowrap">/berita/</span>
            <input type="text" name="slug" id="f_slug"
              class="flex-1 min-w-0 px-3 h-10 bg-white dark:bg-slate-800 text-sm outline-none border-0 focus:ring-0 font-mono"
              value="{{ old('slug', $berita?->slug) }}"
              placeholder="judul-berita-anda">
          </div>
          <p class="text-xs text-slate-400 mt-1">Diisi otomatis dari judul. Hanya huruf kecil, angka, dan tanda hubung.</p>
          @error('slug')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Quill Editor --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border {{ $errors->has('konten') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-800' }} overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
          <label class="text-sm font-medium">Konten <span class="text-rose-500">*</span></label>
          <span id="wordCount" class="text-xs text-slate-400">0 kata</span>
        </div>
        {{-- Quill mount point --}}
        <div id="quillEditor"></div>
        {{-- Hidden field yang dikirim ke server --}}
        <textarea name="konten" id="f_konten" class="sr-only" aria-hidden="true">{{ old('konten', $berita?->konten) }}</textarea>
        @error('konten')<p class="text-xs text-rose-600 px-5 pb-3">{{ $message }}</p>@enderror
      </div>

      {{-- SEO --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center gap-2">
          <i class="ti ti-search text-slate-400 text-base"></i>
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">SEO & Meta</p>
        </div>
        <div class="p-5 space-y-5">

          {{-- Preview Google --}}
          <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 space-y-1 bg-slate-50/60 dark:bg-slate-800/40">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Pratinjau di Google</p>
            <p id="prev_url" class="text-xs text-emerald-700 dark:text-emerald-500 truncate">
              {{ $siteUrl }}/berita/<span id="prev_slug">{{ $berita?->slug ?? 'slug-berita' }}</span>
            </p>
            <p id="prev_title" class="text-base font-semibold text-blue-700 dark:text-blue-400 leading-snug line-clamp-2">
              {{ $berita?->meta_title ?: ($berita?->judul ?: 'Judul Berita') }}
            </p>
            <p id="prev_desc" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">
              {{ $berita?->meta_deskripsi ?: 'Deskripsi berita akan muncul di sini. Tulis ringkasan menarik agar pengunjung tertarik mengklik.' }}
            </p>
          </div>

          {{-- Meta Title --}}
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="text-sm font-medium">Meta Title</label>
              <span id="metaTitleCount" class="text-xs text-slate-400">0 / 60</span>
            </div>
            <input type="text" name="meta_title" id="f_meta_title" maxlength="255"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('meta_title', $berita?->meta_title) }}"
              placeholder="Judul halaman di hasil pencarian (50–60 karakter ideal)">
            <p class="text-xs text-slate-400 mt-1">Kosongkan = menggunakan Judul Berita secara otomatis.</p>
          </div>

          {{-- Meta Deskripsi --}}
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="text-sm font-medium">Meta Deskripsi</label>
              <span id="metaDescCount" class="text-xs text-slate-400">0 / 160</span>
            </div>
            <textarea name="meta_deskripsi" id="f_meta_deskripsi" rows="3" maxlength="500"
              class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none"
              placeholder="Ringkasan singkat yang tampil di hasil pencarian (150–160 karakter ideal)">{{ old('meta_deskripsi', $berita?->meta_deskripsi) }}</textarea>
          </div>

          {{-- Meta Keywords --}}
          <div>
            <label class="block text-sm font-medium mb-1.5">Meta Keywords</label>
            <input type="text" name="meta_keywords" id="f_meta_keywords"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('meta_keywords', $berita?->meta_keywords) }}"
              placeholder="desa, berita, informasi (pisahkan dengan koma)">
            <p class="text-xs text-slate-400 mt-1">Pengaruh terbatas — opsional.</p>
          </div>

        </div>
      </div>

    </div>

    {{-- ── Kolom Kanan: Metadata ────────────────────────── --}}
    <div class="space-y-4 lg:sticky lg:top-20">

      {{-- Publikasi --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Publikasi</p>
        </div>
        <div class="p-5 space-y-4">

          <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <div class="flex gap-2">
              <label id="lbl_draft" class="flex-1 flex items-center gap-2.5 px-3 py-2.5 rounded-lg border-2 cursor-pointer transition-all
                {{ $oldSts === 'draft' ? 'border-amber-400 bg-amber-50 dark:bg-amber-500/10' : 'border-slate-200 dark:border-slate-700' }}">
                <input type="radio" name="status" value="draft" {{ $oldSts === 'draft' ? 'checked' : '' }}
                       class="sr-only" onchange="onStatusChange(this)">
                <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                <span class="text-sm font-medium">Draft</span>
              </label>
              <label id="lbl_terbit" class="flex-1 flex items-center gap-2.5 px-3 py-2.5 rounded-lg border-2 cursor-pointer transition-all
                {{ $oldSts === 'diterbitkan' ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-500/10' : 'border-slate-200 dark:border-slate-700' }}">
                <input type="radio" name="status" value="diterbitkan" {{ $oldSts === 'diterbitkan' ? 'checked' : '' }}
                       class="sr-only" onchange="onStatusChange(this)">
                <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                <span class="text-sm font-medium">Terbitkan</span>
              </label>
            </div>
          </div>

          <div id="pubDateWrap" style="{{ $oldSts === 'diterbitkan' ? '' : 'display:none' }}">
            <label class="block text-sm font-medium mb-1.5">Tanggal Terbit</label>
            <input type="datetime-local" name="published_at" id="f_published_at"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('published_at', $berita?->published_at?->format('Y-m-d\TH:i')) }}">
            <p class="text-xs text-slate-400 mt-1">Kosongkan = waktu sekarang.</p>
          </div>

          <div class="flex gap-2 pt-1">
            <a href="{{ route('admin.berita.index') }}"
               class="flex-none flex items-center px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-arrow-left text-sm"></i>
            </a>
            <button type="submit"
              class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
              <i class="ti ti-device-floppy text-base"></i>
              {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
          </div>
        </div>
      </div>

      {{-- Gambar utama --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Gambar Utama</p>
        </div>
        <div class="p-5 space-y-3">
          <div class="relative rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700"
               id="gambarPreviewWrap" style="{{ ($isEdit && $berita->gambar_url) ? '' : 'display:none' }}">
            <img id="gambarPreview"
              src="{{ $isEdit ? ($berita->gambar_url ?? '') : '' }}"
              alt="" class="w-full h-40 object-cover">
            <button type="button" onclick="hapusGambar()"
              class="absolute top-2 right-2 w-7 h-7 rounded-lg bg-slate-900/60 hover:bg-rose-600 text-white grid place-items-center transition-colors">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
          <input type="hidden" name="hapus_gambar" id="hapusGambarInput" value="0">

          <label for="gambarInput"
            class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 cursor-pointer
                   hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50/40 dark:hover:bg-brand-500/5 transition-all"
            id="gambarDropzone">
            <i class="ti ti-photo-plus text-slate-400 text-2xl"></i>
            <span class="text-xs text-slate-400 text-center leading-relaxed">
              Klik untuk pilih gambar<br>
              <span class="text-[10px]">JPG, PNG, WEBP — maks. 3 MB</span>
            </span>
          </label>
          <input type="file" id="gambarInput" name="gambar" accept="image/*" class="sr-only"
                 onchange="previewGambar(this)">
        </div>
      </div>

      {{-- Kategori --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kategori</p>
        </div>
        <div class="p-5">
          @if($kategoris->isEmpty())
            <p class="text-xs text-slate-400">
              Belum ada kategori.
              <a href="{{ route('admin.master.kategori.index') }}" class="text-brand-600 dark:text-brand-100 hover:underline">Buat kategori →</a>
            </p>
          @else
            <div class="space-y-1">
              <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <input type="radio" name="kategori_id" value="" {{ ! $oldKat ? 'checked' : '' }}
                       class="text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-slate-500">Tanpa Kategori</span>
              </label>
              @foreach($kategoris as $kat)
                <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <input type="radio" name="kategori_id" value="{{ $kat->id }}"
                         {{ $oldKat == $kat->id ? 'checked' : '' }}
                         class="text-brand-600 focus:ring-brand-500">
                  <span class="flex items-center gap-2 text-sm flex-1 min-w-0">
                    @if($kat->warna)
                      <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $kat->warna }}"></span>
                    @endif
                    {{ $kat->nama }}
                  </span>
                </label>
              @endforeach
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
/* ── Custom Quill icons ── */
(function() {
  const Icons = Quill.import('ui/icons');
  Icons['table']  = '<svg viewBox="0 0 18 18"><rect class="ql-stroke" x="1.5" y="1.5" width="15" height="15" rx="1"/><line class="ql-stroke" x1="1.5" y1="6.5" x2="16.5" y2="6.5"/><line class="ql-stroke" x1="1.5" y1="11.5" x2="16.5" y2="11.5"/><line class="ql-stroke" x1="6.5" y1="1.5" x2="6.5" y2="16.5"/><line class="ql-stroke" x1="11.5" y1="1.5" x2="11.5" y2="16.5"/></svg>';
  Icons['source'] = '<svg viewBox="0 0 18 18"><polyline class="ql-stroke" points="5,4 1,9 5,14"/><polyline class="ql-stroke" points="13,4 17,9 13,14"/><line class="ql-stroke" x1="10.5" y1="3" x2="7.5" y2="15"/></svg>';
})();

/* ── Quill init ── */
const quill = new Quill('#quillEditor', {
  theme: 'snow',
  placeholder: 'Tulis konten berita di sini…',
  modules: {
    toolbar: [
      [{ header: [2, 3, 4, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      ['blockquote'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ indent: '-1' }, { indent: '+1' }],
      ['link', 'image'],
      ['table', 'source'],
      ['clean'],
    ],
  },
});

/* Isi konten awal dari hidden textarea */
const hiddenKonten = document.getElementById('f_konten');
if (hiddenKonten.value.trim()) {
  quill.clipboard.dangerouslyPasteHTML(hiddenKonten.value);
}

/* Word count */
const wordCountEl = document.getElementById('wordCount');
function updateWordCount() {
  const text  = quill.getText().trim();
  const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
  wordCountEl.textContent = words.toLocaleString('id') + ' kata';
}
quill.on('text-change', updateWordCount);
updateWordCount();

/* ── Source code mode ── */
const _srcEl = document.createElement('textarea');
_srcEl.id = 'sourceEditor';
document.getElementById('quillEditor').parentNode.appendChild(_srcEl);
let _srcMode = false;

function toggleSource() {
  _srcMode = !_srcMode;
  const edEl = document.getElementById('quillEditor');
  const btn  = document.querySelector('.ql-source');
  if (_srcMode) {
    _srcEl.value = quill.root.innerHTML;
    edEl.style.display = 'none';
    _srcEl.style.display = 'block';
    if (btn) btn.classList.add('ql-active');
  } else {
    quill.clipboard.dangerouslyPasteHTML(_srcEl.value);
    edEl.style.display = '';
    _srcEl.style.display = 'none';
    if (btn) btn.classList.remove('ql-active');
  }
}

/* ── Table picker ── */
let _tblPicker = null;

function showTablePicker() {
  if (_tblPicker) { _tblPicker.remove(); _tblPicker = null; return; }
  const btn = document.querySelector('.ql-table');
  if (!btn) return;
  _tblPicker = document.createElement('div');
  _tblPicker.className = 'ql-table-picker';
  const grid = document.createElement('div');
  grid.className = 'ql-table-grid';
  const lbl = document.createElement('div');
  lbl.className = 'ql-table-picker-label';
  lbl.textContent = 'Pilih ukuran tabel';
  const cells = [];
  for (let r = 1; r <= 8; r++) {
    for (let c = 1; c <= 8; c++) {
      const cell = document.createElement('div');
      cell.className = 'ql-table-cell';
      cell.dataset.r = r; cell.dataset.c = c;
      cell.addEventListener('mouseenter', function() {
        const tr = +this.dataset.r, tc = +this.dataset.c;
        cells.forEach(cl => cl.classList.toggle('ql-table-cell-hover', +cl.dataset.r <= tr && +cl.dataset.c <= tc));
        lbl.textContent = tr + ' × ' + tc;
      });
      cell.addEventListener('click', function() { insertTable(+this.dataset.r, +this.dataset.c); });
      cells.push(cell);
      grid.appendChild(cell);
    }
  }
  _tblPicker.appendChild(grid);
  _tblPicker.appendChild(lbl);
  document.body.appendChild(_tblPicker);
  const rect = btn.getBoundingClientRect();
  _tblPicker.style.left = rect.left + 'px';
  _tblPicker.style.top  = (rect.bottom + 4) + 'px';
  setTimeout(() => {
    document.addEventListener('click', function _close(e) {
      if (_tblPicker && !_tblPicker.contains(e.target) && e.target !== btn) {
        _tblPicker.remove(); _tblPicker = null;
        document.removeEventListener('click', _close);
      }
    });
  }, 0);
}

function insertTable(rows, cols) {
  if (_tblPicker) { _tblPicker.remove(); _tblPicker = null; }
  let html = '<table><tbody>';
  for (let r = 0; r < rows; r++) {
    html += '<tr>';
    for (let c = 0; c < cols; c++) html += '<td><p><br></p></td>';
    html += '</tr>';
  }
  html += '</tbody></table><p><br></p>';
  if (_srcMode) {
    const s = _srcEl.selectionStart;
    _srcEl.value = _srcEl.value.substring(0, s) + html + _srcEl.value.substring(_srcEl.selectionEnd);
    _srcEl.selectionStart = _srcEl.selectionEnd = s + html.length;
    _srcEl.focus();
  } else {
    const range = quill.getSelection(true);
    quill.clipboard.dangerouslyPasteHTML(range ? range.index : quill.getLength(), html);
  }
}

quill.getModule('toolbar').addHandler('table',  showTablePicker);
quill.getModule('toolbar').addHandler('source', toggleSource);

/* Sync ke hidden field saat submit */
document.getElementById('beritaForm').addEventListener('submit', function () {
  hiddenKonten.value = _srcMode ? _srcEl.value : quill.root.innerHTML;
});

/* ── Slug auto-generate ── */
const judulInput = document.getElementById('f_judul');
const slugInput  = document.getElementById('f_slug');

function toSlug(str) {
  return str.toLowerCase()
    .normalize('NFD').replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9\s-]/g, '')
    .trim().replace(/\s+/g, '-')
    .replace(/-+/g, '-');
}

judulInput.addEventListener('input', function () {
  if (slugInput.dataset.manual === '1') return;
  slugInput.value = toSlug(this.value);
  updatePrevSlug();
  updatePrevTitle();
});
slugInput.addEventListener('input', function () {
  this.dataset.manual = this.value ? '1' : '';
  updatePrevSlug();
});

/* ── SEO preview live ── */
const prevSlug  = document.getElementById('prev_slug');
const prevTitle = document.getElementById('prev_title');
const prevDesc  = document.getElementById('prev_desc');

function updatePrevSlug() {
  prevSlug.textContent = slugInput.value || 'slug-berita';
}
function updatePrevTitle() {
  const mt = document.getElementById('f_meta_title').value;
  prevTitle.textContent = mt || judulInput.value || 'Judul Berita';
}
function updatePrevDesc() {
  const md = document.getElementById('f_meta_deskripsi').value;
  prevDesc.textContent = md ||
    'Deskripsi berita akan muncul di sini. Tulis ringkasan menarik agar pengunjung tertarik mengklik.';
}

/* Meta Title counter + preview */
const metaTitleInput = document.getElementById('f_meta_title');
const metaTitleCount = document.getElementById('metaTitleCount');
metaTitleInput.addEventListener('input', function () {
  const len = this.value.length;
  metaTitleCount.textContent = len + ' / 60';
  metaTitleCount.className = 'text-xs ' +
    (len === 0 ? 'text-slate-400' : len <= 60 ? 'seo-ok' : 'seo-over');
  updatePrevTitle();
});

/* Meta Desc counter + preview */
const metaDescInput = document.getElementById('f_meta_deskripsi');
const metaDescCount = document.getElementById('metaDescCount');
metaDescInput.addEventListener('input', function () {
  const len = this.value.length;
  metaDescCount.textContent = len + ' / 160';
  metaDescCount.className = 'text-xs ' +
    (len === 0 ? 'text-slate-400' : len <= 160 ? 'seo-ok' : 'seo-over');
  updatePrevDesc();
});

/* Init counter values on edit */
(function () {
  const tl = metaTitleInput.value.length;
  if (tl) {
    metaTitleCount.textContent = tl + ' / 60';
    metaTitleCount.className = 'text-xs ' + (tl <= 60 ? 'seo-ok' : 'seo-over');
  }
  const dl = metaDescInput.value.length;
  if (dl) {
    metaDescCount.textContent = dl + ' / 160';
    metaDescCount.className = 'text-xs ' + (dl <= 160 ? 'seo-ok' : 'seo-over');
  }
})();

/* ── Status radio styling ── */
function onStatusChange(radio) {
  const lblDraft  = document.getElementById('lbl_draft');
  const lblTerbit = document.getElementById('lbl_terbit');
  lblDraft.className  = lblDraft.className.replace(/border-amber-400|bg-amber-50|dark:bg-amber-500\/10/g, '').trim();
  lblTerbit.className = lblTerbit.className.replace(/border-emerald-400|bg-emerald-50|dark:bg-emerald-500\/10/g, '').trim();
  lblDraft.classList.add('border-slate-200','dark:border-slate-700');
  lblTerbit.classList.add('border-slate-200','dark:border-slate-700');

  const active = radio.value === 'draft' ? lblDraft : lblTerbit;
  active.classList.remove('border-slate-200','dark:border-slate-700');
  if (radio.value === 'draft') {
    active.classList.add('border-amber-400','bg-amber-50','dark:bg-amber-500/10');
  } else {
    active.classList.add('border-emerald-400','bg-emerald-50','dark:bg-emerald-500/10');
  }
  document.getElementById('pubDateWrap').style.display =
    radio.value === 'diterbitkan' ? '' : 'none';
}

/* ── Gambar preview ── */
function previewGambar(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('gambarPreview').src = e.target.result;
    document.getElementById('gambarPreviewWrap').style.display = '';
    document.getElementById('gambarDropzone').style.display = 'none';
    document.getElementById('hapusGambarInput').value = '0';
  };
  reader.readAsDataURL(file);
}

function hapusGambar() {
  document.getElementById('gambarInput').value = '';
  document.getElementById('gambarPreview').src = '';
  document.getElementById('gambarPreviewWrap').style.display = 'none';
  document.getElementById('gambarDropzone').style.display = '';
  document.getElementById('hapusGambarInput').value = '1';
}
</script>
@endsection
