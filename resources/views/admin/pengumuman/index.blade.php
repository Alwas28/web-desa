@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('page-sub', 'Kelola pengumuman resmi yang tampil di website publik')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
<style>
#quillKonten {
  min-height: 260px;
  font-size: 0.9rem;
  line-height: 1.7;
  font-family: inherit;
}
.ql-toolbar.ql-snow {
  border: none;
  border-bottom: 1px solid #e2e8f0;
  padding: 6px 12px;
  background: #f8fafc;
  border-radius: 0;
}
.ql-container.ql-snow { border: none; }
.ql-editor { padding: 14px 16px; min-height: 220px; }
.ql-editor.ql-blank::before { font-style: normal; color: #94a3b8; left: 16px; }
.dark .ql-toolbar.ql-snow { background: #1e293b; border-bottom-color: #334155; }
.dark .ql-toolbar.ql-snow .ql-stroke { stroke: #94a3b8; }
.dark .ql-toolbar.ql-snow .ql-fill  { fill:   #94a3b8; }
.dark .ql-toolbar.ql-snow .ql-picker-label { color: #94a3b8; }
.dark .ql-toolbar.ql-snow .ql-picker-options { background: #0f172a; border-color: #334155; }
.dark .ql-toolbar.ql-snow button:hover .ql-stroke,
.dark .ql-toolbar.ql-snow .ql-active .ql-stroke { stroke: #f1f5f9; }
.dark .ql-toolbar.ql-snow button:hover .ql-fill,
.dark .ql-toolbar.ql-snow .ql-active .ql-fill   { fill:   #f1f5f9; }
.dark #quillKonten { background: #1e293b; color: #f1f5f9; }
/* ─ Source mode ─ */
#sourceEditor {
  display: none; width: 100%; min-height: 220px;
  padding: 14px 16px; font-family: 'Consolas','Monaco',monospace;
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

{{-- Stat cards --}}
<div class="grid grid-cols-4 gap-3 max-w-xl">
  @foreach([
    ['label'=>'Total',      'val'=>$total,    'icon'=>'ti-speakerphone', 'color'=>'blue'],
    ['label'=>'Terbit',     'val'=>$terbit,   'icon'=>'ti-circle-check', 'color'=>'emerald'],
    ['label'=>'Draft',      'val'=>$draft,    'icon'=>'ti-pencil',       'color'=>'amber'],
    ['label'=>'Nonaktif',   'val'=>$nonaktif, 'icon'=>'ti-circle-minus', 'color'=>'slate'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $s['val'] }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
<form method="GET" class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm flex-shrink-0"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>
  <select name="status" onchange="this.form.submit()"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Status</option>
    <option value="terbit"   {{ request('status')==='terbit'   ? 'selected':'' }}>Terbit</option>
    <option value="draft"    {{ request('status')==='draft'    ? 'selected':'' }}>Draft</option>
    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
  </select>
  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-search text-sm"></i>
  </button>
  @if(request()->hasAny(['q','status']))
  <a href="{{ route('admin.pengumuman.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
  <button type="button" onclick="openModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-semibold transition-colors">
    <i class="ti ti-plus"></i> Tambah Pengumuman
  </button>
</form>

{{-- Tabel --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($pengumumans->isEmpty())
  <div class="py-16 text-center text-slate-400">
    <i class="ti ti-speakerphone text-4xl block mb-3"></i>
    <p class="font-medium">Belum ada pengumuman</p>
    <p class="text-xs mt-1">Klik "Tambah Pengumuman" untuk membuat pengumuman baru</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60">
          <th class="px-4 py-3 w-10 text-center text-slate-500 text-xs font-semibold">#</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Judul</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Status</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Tanggal Terbit</th>
          <th class="text-left px-4 py-3 font-semibold text-slate-500 text-xs">Dibuat Oleh</th>
          <th class="px-4 py-3 w-24"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($pengumumans as $p)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
          <td class="px-4 py-3 text-center text-slate-400 text-xs">{{ $loop->iteration }}</td>
          <td class="px-4 py-3">
            <div class="font-semibold text-slate-900 dark:text-slate-100 max-w-sm">{{ $p->judul }}</div>
            <div class="text-xs text-slate-400 mt-0.5 truncate max-w-sm">{{ $p->ringkasan }}</div>
          </td>
          <td class="px-4 py-3">
            @if($p->status === 'terbit')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
              <i class="ti ti-circle-check"></i> Terbit
            </span>
            @elseif($p->status === 'nonaktif')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600">
              <i class="ti ti-circle-minus"></i> Nonaktif
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20">
              <i class="ti ti-pencil"></i> Draft
            </span>
            @endif
          </td>
          <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
            {{ $p->published_at ? $p->published_at->locale('id')->translatedFormat('d M Y') : '—' }}
          </td>
          <td class="px-4 py-3 text-xs text-slate-500">{{ $p->user->name ?? '—' }}</td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1 justify-end">
              <button onclick='openEdit(@json($p))'
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                title="Edit">
                <i class="ti ti-pencil text-sm"></i>
              </button>
              <form method="POST" action="{{ route('admin.pengumuman.destroy', $p) }}"
                    onsubmit="return confirm('Hapus pengumuman ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                  title="Hapus">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($pengumumans->hasPages())
  <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800">
    {{ $pengumumans->links() }}
  </div>
  @endif
  @endif
</div>

{{-- ── Modal Tambah / Edit ── --}}
<div id="modalPengumuman" style="display:none"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-start justify-center p-4 pt-10"
       onclick="if(event.target===this)closeModal()">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700">

      {{-- Header --}}
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-500/10 grid place-items-center">
            <i class="ti ti-speakerphone text-brand-600 dark:text-brand-400"></i>
          </div>
          <div>
            <h3 id="modalTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm">Tambah Pengumuman</h3>
            <p class="text-xs text-slate-400">Isi detail pengumuman</p>
          </div>
        </div>
        <button onclick="closeModal()"
          class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form id="formPengumuman" method="POST" enctype="multipart/form-data">
        @csrf
        <span id="methodField"></span>

        <div class="p-6 space-y-4">

          {{-- Judul --}}
          <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
              Judul <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" id="f_judul" required maxlength="255"
              placeholder="Masukkan judul pengumuman…"
              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
          </div>

          {{-- Konten — Quill Editor --}}
          <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
              Isi Pengumuman <span class="text-red-500">*</span>
            </label>
            <div class="rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
              <div id="quillKonten"></div>
            </div>
            {{-- hidden field dikirim ke server --}}
            <textarea name="konten" id="f_konten" class="sr-only" aria-hidden="true"></textarea>
          </div>

          {{-- Gambar + Status + Tanggal --}}
          <div class="grid grid-cols-2 gap-4">

            {{-- Gambar --}}
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                Gambar <span class="text-slate-400 font-normal">(opsional, maks. 3MB)</span>
              </label>
              <div id="gambarPreviewWrap" style="display:none" class="mb-2 relative">
                <img id="gambarPreview" src="" class="w-full h-28 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                <button type="button" onclick="hapusGambar()"
                  class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center hover:bg-red-600">
                  <i class="ti ti-x text-xs"></i>
                </button>
              </div>
              <input type="hidden" name="hapus_gambar" id="hapus_gambar" value="0">
              <label id="gambarDropzone"
                class="flex flex-col items-center justify-center gap-1.5 h-20 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:border-brand-400 transition-colors bg-slate-50 dark:bg-slate-800/50">
                <i class="ti ti-photo text-xl text-slate-300"></i>
                <span class="text-xs text-slate-400">Klik untuk pilih gambar</span>
              </label>
              <input type="file" name="gambar" id="f_gambar" accept="image/jpeg,image/png,image/webp"
                class="hidden" onchange="previewGambar(this)">
            </div>

            {{-- Status + Tanggal --}}
            <div class="space-y-3">
              <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                  Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="f_status" required
                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                  <option value="draft">Draft</option>
                  <option value="terbit">Terbit</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                  Tanggal Terbit
                </label>
                <input type="datetime-local" name="published_at" id="f_published_at"
                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
                <p class="text-xs text-slate-400 mt-1">Kosongkan = waktu sekarang saat diterbitkan.</p>
              </div>
            </div>
          </div>

        </div>

        {{-- Footer modal --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
          <button type="button" onclick="closeModal()"
            class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
          <button type="submit"
            class="px-5 py-2 rounded-lg text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white transition-colors flex items-center gap-2">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

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
const quill = new Quill('#quillKonten', {
  theme: 'snow',
  placeholder: 'Tulis isi pengumuman di sini…',
  modules: {
    toolbar: [
      [{ header: [2, 3, false] }],
      ['bold', 'italic', 'underline'],
      ['blockquote'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link'],
      ['table', 'source'],
      ['clean'],
    ],
  },
});

/* ── Source code mode ── */
const _srcEl = document.createElement('textarea');
_srcEl.id = 'sourceEditor';
document.getElementById('quillKonten').parentNode.appendChild(_srcEl);
let _srcMode = false;

function toggleSource() {
  _srcMode = !_srcMode;
  const edEl = document.getElementById('quillKonten');
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

const BASE = '{{ url('admin/pengumuman') }}/';

function openModal() {
  document.getElementById('modalTitle').textContent = 'Tambah Pengumuman';
  document.getElementById('formPengumuman').action  = BASE;
  document.getElementById('methodField').innerHTML  = '';
  document.getElementById('formPengumuman').reset();
  if (_srcMode) toggleSource();
  quill.setContents([]);
  resetGambar();
  document.getElementById('modalPengumuman').style.display = 'block';
  requestAnimationFrame(() => quill.update());
}

function openEdit(data) {
  document.getElementById('modalTitle').textContent = 'Edit Pengumuman';
  document.getElementById('formPengumuman').action  = BASE + data.id;
  document.getElementById('methodField').innerHTML  = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('f_judul').value    = data.judul  || '';
  document.getElementById('f_status').value   = data.status || 'draft';

  // Quill: isi konten HTML (reset ke visual mode dulu)
  if (_srcMode) toggleSource();
  quill.clipboard.dangerouslyPasteHTML(data.konten || '');

  // Tanggal
  const pa = data.published_at ? data.published_at.replace(' ', 'T').substring(0, 16) : '';
  document.getElementById('f_published_at').value = pa;

  resetGambar();
  if (data.gambar_url) {
    document.getElementById('gambarPreview').src               = data.gambar_url;
    document.getElementById('gambarPreviewWrap').style.display = 'block';
    document.getElementById('gambarDropzone').style.display    = 'none';
  }

  document.getElementById('modalPengumuman').style.display = 'block';
  requestAnimationFrame(() => quill.update());
}

function closeModal() {
  document.getElementById('modalPengumuman').style.display = 'none';
}

/* Sync Quill → hidden textarea sebelum submit */
document.getElementById('formPengumuman').addEventListener('submit', function () {
  document.getElementById('f_konten').value = _srcMode ? _srcEl.value : quill.root.innerHTML;
});

function resetGambar() {
  document.getElementById('f_gambar').value              = '';
  document.getElementById('hapus_gambar').value          = '0';
  document.getElementById('gambarPreviewWrap').style.display = 'none';
  document.getElementById('gambarDropzone').style.display    = 'flex';
}

function hapusGambar() {
  document.getElementById('hapus_gambar').value = '1';
  resetGambar();
}

function previewGambar(input) {
  if (!input.files?.[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('gambarPreview').src               = e.target.result;
    document.getElementById('gambarPreviewWrap').style.display = 'block';
    document.getElementById('gambarDropzone').style.display    = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}

document.getElementById('gambarDropzone').addEventListener('click', () => {
  document.getElementById('f_gambar').click();
});

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection
