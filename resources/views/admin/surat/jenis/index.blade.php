@extends('layouts.admin')

@section('title', 'Jenis Surat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-xl font-bold text-slate-800 dark:text-white">Jenis Surat</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kode, akses masyarakat, dan template isi surat</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.surat.pengaturan.index') }}"
         class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-settings-2 text-sm"></i> Pengaturan Surat
      </a>
      <button type="button" onclick="openCreate()"
        class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
        <i class="ti ti-plus text-sm"></i> Tambah Jenis
      </button>
    </div>
  </div>

  {{-- Info --}}
  <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-sm text-blue-700 dark:text-blue-300">
    <i class="ti ti-info-circle flex-shrink-0 mt-0.5"></i>
    <span>Kode surat digunakan untuk nomor otomatis (<code class="font-mono bg-blue-100 dark:bg-blue-500/20 px-1 rounded">{kode_jenis}</code>).
    Switch <strong>Masyarakat</strong> mengizinkan warga mengajukan langsung dari portal.</span>
  </div>

  {{-- Tabel --}}
  <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide w-8">#</th>
          <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Nama Surat</th>
          <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide w-28">Kode</th>
          <th class="px-4 py-3.5 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide w-36">Masyarakat</th>
          <th class="px-4 py-3.5 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide w-20">Template</th>
          <th class="px-4 py-3.5 w-16"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
        @foreach($jenisList as $jenis)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors" id="row-{{ $jenis->id }}">

          {{-- Urutan --}}
          <td class="px-6 py-4 text-slate-400 text-xs">{{ $jenis->urutan }}</td>

          {{-- Nama --}}
          <td class="px-4 py-4">
            <span class="font-medium text-slate-800 dark:text-white">{{ $jenis->nama }}</span>
            @if($jenis->template_isi)
            <span class="ml-2 inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
              <i class="ti ti-file-check text-[10px]"></i> Ada template
            </span>
            @endif
          </td>

          {{-- Kode --}}
          <td class="px-4 py-4">
            @if($jenis->kode)
            <code class="inline-block font-mono text-xs font-semibold px-2 py-1 rounded-md bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 border border-brand-200 dark:border-brand-500/20">
              {{ $jenis->kode }}
            </code>
            @else
            <span class="text-xs text-slate-400 italic">—</span>
            @endif
          </td>

          {{-- Switch masyarakat --}}
          <td class="px-4 py-4 text-center">
            <button type="button"
              onclick="toggleMasyarakat({{ $jenis->id }}, this)"
              data-aktif="{{ $jenis->dapat_dibuat_masyarakat ? '1' : '0' }}"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/40
                     {{ $jenis->dapat_dibuat_masyarakat ? 'bg-brand-600' : 'bg-slate-200 dark:bg-slate-600' }}"
              title="{{ $jenis->dapat_dibuat_masyarakat ? 'Masyarakat bisa mengajukan' : 'Hanya admin' }}">
              <span class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform
                           {{ $jenis->dapat_dibuat_masyarakat ? 'translate-x-6' : 'translate-x-1' }}"></span>
            </button>
            <div class="text-[10px] mt-1 {{ $jenis->dapat_dibuat_masyarakat ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }}">
              {{ $jenis->dapat_dibuat_masyarakat ? 'Masyarakat' : 'Admin saja' }}
            </div>
          </td>

          {{-- Template status --}}
          <td class="px-4 py-4 text-center">
            @if($jenis->template_isi)
            <i class="ti ti-circle-check text-emerald-500 text-lg" title="Ada template"></i>
            @else
            <i class="ti ti-circle-dashed text-slate-300 dark:text-slate-600 text-lg" title="Belum ada template"></i>
            @endif
          </td>

          {{-- Edit --}}
          <td class="px-4 py-4 text-right">
            <button type="button" onclick="openEdit({{ $jenis->id }})"
              class="inline-flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-600 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-brand-50 hover:border-brand-300 hover:text-brand-700 dark:hover:bg-brand-500/10 dark:hover:text-brand-400 transition-colors">
              <i class="ti ti-pencil text-sm"></i> Edit
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>

{{-- ── Modal Tambah ───────────────────────────────────────────────────────── --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreate()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden max-h-[92vh] flex flex-col">

      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
        <div class="flex items-center gap-2">
          <i class="ti ti-plus text-brand-600 dark:text-brand-400"></i>
          <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Tambah Jenis Surat</h3>
        </div>
        <button type="button" onclick="closeCreate()"
          class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
          <i class="ti ti-x text-sm"></i>
        </button>
      </div>

      <div class="overflow-y-auto flex-1">
        <form id="fmCreate" method="POST" action="{{ route('admin.surat.jenis.store') }}" class="p-6 space-y-5">
          @csrf
          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Surat <span class="text-rose-500">*</span></label>
              <input type="text" name="nama" id="createNama" required
                placeholder="mis. Surat Keterangan Domisili"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kode Surat</label>
              <input type="text" name="kode" id="createKode"
                placeholder="mis. SKD"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-brand-500/40">
              <p class="text-[11px] text-slate-400 mt-1">Digunakan sebagai <code class="font-mono">{kode_jenis}</code> di nomor surat</p>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Template Isi Surat</label>
            <div class="mb-2 space-y-1.5">
              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="text-[9px] font-semibold uppercase tracking-wide text-slate-400 mr-0.5">Pemohon:</span>
                @foreach(['{nama}','{nik}','{jenis_kelamin}','{tempat_lahir}','{tanggal_lahir}','{alamat}','{keperluan}','{keterangan}','{nomor_surat}','{tanggal}','{kepala_desa}','{jabatan_kades}'] as $ph)
                <button type="button" onclick="insertCreatePlaceholder('{{ $ph }}')"
                  class="px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-[10px] font-mono text-brand-700 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-300 transition-colors">
                  {{ $ph }}
                </button>
                @endforeach
              </div>
              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="text-[9px] font-semibold uppercase tracking-wide text-slate-400 mr-0.5">Desa:</span>
                @foreach(['{nama_desa}','{kecamatan}','{kabupaten}','{provinsi}','{kepala_desa}'] as $ph)
                <button type="button" onclick="insertCreatePlaceholder('{{ $ph }}')"
                  class="px-2 py-0.5 rounded border border-teal-200 dark:border-teal-700 bg-teal-50 dark:bg-teal-500/10 text-[10px] font-mono text-teal-700 dark:text-teal-400 hover:bg-teal-100 dark:hover:bg-teal-500/20 transition-colors">
                  {{ $ph }}
                </button>
                @endforeach
              </div>
            </div>
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600">
              <textarea name="template_isi" id="createTemplate" rows="12"></textarea>
            </div>
          </div>
        </form>
      </div>

      <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex-shrink-0 bg-slate-50 dark:bg-slate-800/50">
        <button type="button" onclick="closeCreate()"
          class="px-5 h-9 rounded-xl border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
          Batal
        </button>
        <button type="button" onclick="document.getElementById('fmCreate').submit()"
          class="inline-flex items-center gap-2 px-6 h-9 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
          <i class="ti ti-plus"></i> Tambah
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Modal Edit ─────────────────────────────────────────────────────────── --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEdit()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden max-h-[92vh] flex flex-col">

      {{-- Modal Header --}}
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
        <div class="flex items-center gap-2">
          <i class="ti ti-pencil text-brand-600 dark:text-brand-400"></i>
          <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Edit Jenis Surat</h3>
          <span id="modalSubtitle" class="text-xs text-slate-400"></span>
        </div>
        <button type="button" onclick="closeEdit()"
          class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
          <i class="ti ti-x text-sm"></i>
        </button>
      </div>

      {{-- Modal Body --}}
      <div class="overflow-y-auto flex-1">
        <form id="fmEdit" method="POST" class="p-6 space-y-5">
          @csrf @method('PUT')

          <div class="grid grid-cols-2 gap-4">
            {{-- Nama --}}
            <div class="col-span-2">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Surat</label>
              <input type="text" name="nama" id="editNama" required
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
            </div>
            {{-- Kode --}}
            <div class="col-span-1">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kode Surat</label>
              <input type="text" name="kode" id="editKode"
                placeholder="mis. SKTM"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-brand-500/40">
              <p class="text-[11px] text-slate-400 mt-1">Digunakan sebagai <code class="font-mono">{kode_jenis}</code> di nomor surat</p>
            </div>
          </div>

          {{-- Template Isi --}}
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Template Isi Surat</label>
            <div class="mb-2 space-y-1.5">
              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="text-[9px] font-semibold uppercase tracking-wide text-slate-400 mr-0.5">Pemohon:</span>
                @foreach(['{nama}','{nik}','{jenis_kelamin}','{tempat_lahir}','{tanggal_lahir}','{alamat}','{keperluan}','{keterangan}','{nomor_surat}','{tanggal}','{kepala_desa}','{jabatan_kades}'] as $ph)
                <button type="button" onclick="insertTplPlaceholder('{{ $ph }}')"
                  class="px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-[10px] font-mono text-brand-700 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-300 transition-colors">
                  {{ $ph }}
                </button>
                @endforeach
              </div>
              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="text-[9px] font-semibold uppercase tracking-wide text-slate-400 mr-0.5">Desa:</span>
                @foreach(['{nama_desa}','{kecamatan}','{kabupaten}','{provinsi}','{kepala_desa}'] as $ph)
                <button type="button" onclick="insertTplPlaceholder('{{ $ph }}')"
                  class="px-2 py-0.5 rounded border border-teal-200 dark:border-teal-700 bg-teal-50 dark:bg-teal-500/10 text-[10px] font-mono text-teal-700 dark:text-teal-400 hover:bg-teal-100 dark:hover:bg-teal-500/20 transition-colors">
                  {{ $ph }}
                </button>
                @endforeach
              </div>
            </div>
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600">
              <textarea name="template_isi" id="editTemplate" rows="12"></textarea>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Gunakan toolbar di atas untuk format teks, insert tabel, atau klik Source untuk edit HTML langsung.</p>
          </div>
        </form>
      </div>

      {{-- Modal Footer --}}
      <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex-shrink-0 bg-slate-50 dark:bg-slate-800/50">
        <button type="button" onclick="closeEdit()"
          class="px-5 h-9 rounded-xl border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
          Batal
        </button>
        <button type="button" onclick="submitEdit()"
          class="inline-flex items-center gap-2 px-6 h-9 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
          <i class="ti ti-device-floppy"></i> Simpan
        </button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('scripts')
{{-- Jodit Editor — MIT License, 100% gratis, support tabel + source HTML --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.6/jodit.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.6/jodit.min.js"></script>
<script>
const ROUTES = {
  toggle : (id) => `/admin/surat/jenis/${id}/toggle`,
  update : (id) => `/admin/surat/jenis/${id}`,
};
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/* ── Data per baris ─────────────────────────────────────────────── */
const JENIS_DATA = @js($jenisList->keyBy('id')->map(fn($j) => [
  'id'           => $j->id,
  'nama'         => $j->nama,
  'kode'         => $j->kode ?? '',
  'template_isi' => $j->template_isi ?? '',
]));

/* ── Konfigurasi Jodit ───────────────────────────────────────────── */
const JODIT_CONFIG = {
  height: 340,
  language: 'en',
  toolbarAdaptive: false,
  showCharsCounter: false,
  showWordsCounter: false,
  showXPathInStatusbar: false,
  askBeforePasteHTML: false,
  askBeforePasteFromWord: false,
  defaultActionOnPaste: 'insert_only_text',
  buttons: [
    'source', '|',
    'bold', 'italic', 'underline', 'strikethrough', 'eraser', '|',
    'ul', 'ol', '|',
    'outdent', 'indent', '|',
    'left', 'center', 'right', 'justify', '|',
    'font', 'fontsize', 'paragraph', '|',
    'table', 'hr', '|',
    'undo', 'redo',
  ],
  style: {
    font: 'serif',
    fontSize: '12pt',
  },
  editorCssClass: 'jodit-surat',
  extraCss: `
    .jodit-surat { font-family: serif; font-size: 12pt; line-height: 1.6; }
    .jodit-surat table { border-collapse: collapse; width: 100%; }
    .jodit-surat td, .jodit-surat th { border: 1px solid #ccc; padding: 4px 8px; }
  `,
};

/* Instans aktif */
const _editors = {};

function initJodit(id, content) {
  if (_editors[id]) {
    _editors[id].destruct();
    delete _editors[id];
  }
  _editors[id] = Jodit.make('#' + id, JODIT_CONFIG);
  _editors[id].value = content || '';
  return _editors[id];
}

function destroyJodit(id) {
  if (_editors[id]) {
    _editors[id].destruct();
    delete _editors[id];
  }
}

function syncJodit(id) {
  if (_editors[id]) {
    document.getElementById(id).value = _editors[id].value;
  }
}

function insertPlaceholder(editorId, ph) {
  const ed = _editors[editorId];
  if (ed) {
    ed.selection.insertHTML(ph);
    ed.focus();
  }
}

/* ── Toggle masyarakat ───────────────────────────────────────────── */
async function toggleMasyarakat(id, btn) {
  btn.disabled = true;
  try {
    const res  = await fetch(ROUTES.toggle(id), {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    const data = await res.json();
    const aktif = data.aktif;

    btn.className = btn.className
      .replace('bg-brand-600', '').replace('bg-slate-200', '').replace('dark:bg-slate-600', '').trim()
      + (aktif ? ' bg-brand-600' : ' bg-slate-200 dark:bg-slate-600');

    const thumb = btn.querySelector('span');
    thumb.className = thumb.className
      .replace('translate-x-6', '').replace('translate-x-1', '').trim()
      + (aktif ? ' translate-x-6' : ' translate-x-1');

    const label = btn.nextElementSibling;
    if (label) {
      label.textContent = aktif ? 'Masyarakat' : 'Admin saja';
      label.className = 'text-[10px] mt-1 ' + (aktif ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400');
    }

    btn.dataset.aktif = aktif ? '1' : '0';
    if (typeof toast === 'function')
      toast(aktif ? 'Masyarakat dapat mengajukan surat ini.' : 'Hanya admin yang dapat membuat surat ini.', 'success');
  } catch (e) {
    if (typeof toast === 'function') toast('Gagal memperbarui. Coba lagi.', 'error');
  } finally {
    btn.disabled = false;
  }
}

/* ── Create modal ───────────────────────────────────────────────── */
function openCreate() {
  document.getElementById('createNama').value = '';
  document.getElementById('createKode').value = '';
  document.getElementById('modalCreate').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  setTimeout(() => {
    initJodit('createTemplate', '');
    document.getElementById('createNama').focus();
  }, 80);
}
function closeCreate() {
  destroyJodit('createTemplate');
  document.getElementById('modalCreate').classList.add('hidden');
  document.body.style.overflow = '';
}
function insertCreatePlaceholder(ph) {
  insertPlaceholder('createTemplate', ph);
}
document.getElementById('fmCreate').addEventListener('submit', function () {
  syncJodit('createTemplate');
});

/* ── Edit modal ──────────────────────────────────────────────────── */
let _editId = null;

function openEdit(id) {
  _editId = id;
  const data = JENIS_DATA[id];
  if (!data) return;

  document.getElementById('editNama').value = data.nama;
  document.getElementById('editKode').value = data.kode;
  document.getElementById('modalSubtitle').textContent = '— ' + data.nama;
  document.getElementById('fmEdit').action = ROUTES.update(id);

  document.getElementById('modalEdit').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  setTimeout(() => {
    initJodit('editTemplate', data.template_isi);
    document.getElementById('editNama').focus();
  }, 80);
}
function closeEdit() {
  destroyJodit('editTemplate');
  document.getElementById('modalEdit').classList.add('hidden');
  document.body.style.overflow = '';
  _editId = null;
}
function submitEdit() {
  syncJodit('editTemplate');
  document.getElementById('fmEdit').submit();
}
function insertTplPlaceholder(ph) {
  insertPlaceholder('editTemplate', ph);
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') { closeEdit(); closeCreate(); }
});
</script>
@endsection
