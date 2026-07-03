{{-- ── Modal Lihat Riwayat — di luar semua panel agar tidak terpengaruh display:none ── --}}
<div id="modalRiwayat" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.5); align-items:center; justify-content:center; padding:1rem;">
  <div class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col"
       style="max-height:90vh">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
      <div class="flex-1 min-w-0">
        <h3 id="modalRiwayatTitle" class="font-bold text-slate-900 dark:text-slate-100 text-sm truncate"></h3>
        <p id="modalRiwayatTime" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <div class="flex items-center gap-2 flex-shrink-0 ml-4">
        <button id="btnCopyRiwayat" onclick="copyRiwayat()"
          class="flex items-center gap-1 px-3 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-copy text-xs"></i> Salin
        </button>
        <input type="hidden" id="_riwayatId">
        <button onclick="gunakanLagi(parseInt(document.getElementById('_riwayatId').value))"
          class="flex items-center gap-1 px-3 h-7 rounded-lg bg-brand-600 hover:bg-brand-700 text-xs font-semibold text-white transition-colors">
          <i class="ti ti-refresh text-xs"></i> Gunakan Lagi
        </button>
        <button onclick="closeModalRiwayat()"
          class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
          <i class="ti ti-x text-sm"></i>
        </button>
      </div>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
      <pre id="modalRiwayatBody"
        class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed font-sans"
        style="white-space: pre-wrap; word-break: break-word;"></pre>
    </div>
  </div>
</div>

@php
$_riwayatJs = $riwayat->map(fn($r) => [
    'id'         => $r->id,
    'created_at' => $r->created_at->toISOString(),
    'parameter'  => $r->parameter,
    'konten'     => $r->konten,
])->values()->all();

$_pdfMeta = [
    'nama'       => $desaNama       ?? '',
    'kecamatan'  => $desaKecamatan  ?? '',
    'kabupaten'  => $desaKabupaten  ?? '',
    'provinsi'   => $desaProvinsi   ?? '',
    'kepala'     => $desaKepala     ?? '',
    'sekretaris' => $desaSekretaris ?? '',
    'logoUrl'    => $desaLogoUrl    ?? null,
    'tipe'       => $tipe           ?? 'sambutan',
];
@endphp
<script>
const GENERATE_URL  = '{{ route("admin.ai.generate") }}';
const DESTROY_BASE  = '{{ url("admin/ai/riwayat") }}/';
const CSRF          = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const PDF_META      = @json($_pdfMeta);

/* ── Riwayat data (mutable) ── */
let RIWAYAT = @json($_riwayatJs);

/* ── Tab switching ── */
function switchTab(tab) {
  ['generate','riwayat'].forEach(t => {
    document.getElementById('panel-' + t).style.display  = t === tab ? '' : 'none';
    document.getElementById('tab-btn-' + t).dataset.active = t === tab ? '1' : '0';
    const el = document.getElementById('tab-btn-' + t);
    if (t === tab) {
      el.classList.add('bg-white','dark:bg-slate-900','text-slate-900','dark:text-slate-100','shadow-sm');
      el.classList.remove('text-slate-500','dark:text-slate-400');
    } else {
      el.classList.remove('bg-white','dark:bg-slate-900','text-slate-900','dark:text-slate-100','shadow-sm');
      el.classList.add('text-slate-500','dark:text-slate-400');
    }
  });
  if (tab === 'riwayat') renderRiwayat();
}

/* ── Output states ── */
function showState(state) {
  ['Placeholder','Loading','Error','Content'].forEach(s =>
    document.getElementById('state' + s).style.display = s === state ? '' : 'none'
  );
  const on = state === 'Content';
  document.getElementById('btnEdit').style.display  = on ? '' : 'none';
  document.getElementById('btnCopy').style.display  = on ? '' : 'none';
  document.getElementById('btnPdf').style.display   = on ? '' : 'none';
  document.getElementById('wordCount').classList.toggle('hidden', !on);
  document.getElementById('regenHint').style.display = on ? '' : 'none';
}

/* ── Quill lazy loader ── */
let _quill     = null;
let _editMode  = false;
let _quillReady = false;

function loadQuill() {
  return new Promise(resolve => {
    if (window.Quill) { resolve(); return; }
    const link  = document.createElement('link');
    link.rel    = 'stylesheet';
    link.href   = 'https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css';
    document.head.appendChild(link);
    const script  = document.createElement('script');
    script.src    = 'https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js';
    script.onload = resolve;
    document.head.appendChild(script);
  });
}

/* ── Convert plain text → HTML (untuk Quill input) ── */
function textToHtml(text) {
  return text.split('\n').map(line => {
    if (!line.trim()) return '<p><br></p>';
    return `<p>${escHtml(line)}</p>`;
  }).join('');
}

function quillToPlainText() {
  if (!_quill) return '';
  return _quill.getText().replace(/\n$/, '');
}

/* ── Ambil konten aktif (view atau edit mode) ── */
function getOutputContent() {
  if (_editMode && _quill) return quillToPlainText();
  return document.getElementById('outputText').textContent;
}

function getOutputHtml() {
  if (_editMode && _quill) return _quill.root.innerHTML;
  const text = document.getElementById('outputText').textContent;
  return textToHtml(text);
}

/* ── Toggle mode edit ── */
async function toggleEdit() {
  const div     = document.getElementById('outputText');
  const wrapper = document.getElementById('quillWrapper');
  const icon    = document.getElementById('btnEditIcon');
  const label   = document.getElementById('btnEditLabel');
  const badge   = document.getElementById('editBadge');
  const btn     = document.getElementById('btnEdit');

  if (!_editMode) {
    /* ── Masuk edit mode ── */
    btn.disabled = true;
    label.textContent = 'Memuat…';

    await loadQuill();

    /* Inisialisasi Quill */
    const isDark = document.documentElement.classList.contains('dark');
    _quill = new Quill('#quillEditor', {
      theme: 'snow',
      placeholder: 'Edit konten di sini…',
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline'],
          [{ align: ['', 'center', 'right', 'justify'] }],
          [{ list: 'ordered' }, { list: 'bullet' }],
          [{ indent: '-1' }, { indent: '+1' }],
          ['clean'],
        ],
      },
    });

    /* Isi konten ke Quill — gunakan dangerouslyPasteHTML agar delta model sinkron */
    const initHtml = (PDF_META.tipe === 'perdes' || PDF_META.tipe === 'perkades')
      ? perdesPlainToHtml(div.textContent)
      : textToHtml(div.textContent);
    _quill.clipboard.dangerouslyPasteHTML(initHtml);

    /* Dark mode: warna teks editor */
    if (isDark) {
      _quill.root.style.color      = '#e2e8f0';
      _quill.root.style.background = 'transparent';
    }

    /* Tinggi editor */
    _quill.root.style.minHeight  = '380px';
    _quill.root.style.maxHeight  = '420px';
    _quill.root.style.overflowY  = 'auto';
    _quill.root.style.fontSize   = '14px';
    _quill.root.style.lineHeight = '1.7';
    _quill.root.style.fontFamily = 'inherit';

    div.style.display     = 'none';
    wrapper.style.display = '';
    _editMode = true;

    icon.className    = 'ti ti-check text-xs';
    label.textContent = 'Selesai';
    btn.disabled      = false;
    btn.classList.add('text-amber-600','border-amber-300');
    badge.style.display = '';
    _quill.focus();

  } else {
    /* ── Keluar edit mode: simpan konten ── */
    const text    = _quill ? _quill.getText().replace(/\n$/, '') : '';
    const htmlSrc = _quill ? _quill.root.innerHTML : '';

    /* Simpan plain text ke div (untuk display) dan HTML ke data attr (untuk PDF) */
    div.textContent         = text;
    div.dataset.quillHtml   = htmlSrc;

    /* Destroy Quill */
    wrapper.style.display = 'none';
    document.getElementById('quillEditor').innerHTML = '';
    _quill    = null;
    _editMode = false;

    div.style.display = '';
    icon.className    = 'ti ti-pencil text-xs';
    label.textContent = 'Edit';
    btn.classList.remove('text-amber-600','border-amber-300');
    badge.style.display = 'none';

    const words = text.trim().split(/\s+/).filter(Boolean).length;
    document.getElementById('wordCount').textContent = words + ' kata';
  }
}

/* ── Helper: format tanggal Indonesia ── */
function tanggalIndonesia(date) {
  const bln = ['Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
  return `${date.getDate()} ${bln[date.getMonth()]} ${date.getFullYear()}`;
}

/* ── Helper: escape HTML ── */
function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ── Smart formatter untuk konten Perdes (plain text → HTML) ── */
function perdesPlainToHtml(text) {
  const lines = text.split('\n');
  let html = '';
  for (let i = 0; i < lines.length; i++) {
    const raw     = lines[i];
    const trimmed = raw.trim();

    if (!trimmed) { html += '<p style="margin:6px 0">&nbsp;</p>'; continue; }

    /* BAB heading */
    if (/^BAB\s+[IVXLC0-9]+\s*$/.test(trimmed)) {
      html += `<p style="text-align:center;font-weight:bold;margin:18px 0 2px;">${escHtml(trimmed)}</p>`;
      continue;
    }

    /* Judul setelah BAB (all caps, no numbers at start) */
    const prevNonEmpty = [...lines].slice(0,i).reverse().find(l => l.trim());
    const afterBab     = prevNonEmpty && /^BAB\s+[IVXLC0-9]+/.test(prevNonEmpty.trim());
    if (afterBab && trimmed === trimmed.toUpperCase() && !/^\d/.test(trimmed) && trimmed.length > 2) {
      html += `<p style="text-align:center;font-weight:bold;margin:2px 0 12px;">${escHtml(trimmed)}</p>`;
      continue;
    }

    /* Pasal heading */
    if (/^Pasal\s+\d+/.test(trimmed)) {
      html += `<p style="text-align:center;font-weight:bold;margin:14px 0 4px;">${escHtml(trimmed)}</p>`;
      continue;
    }

    /* All caps title (KETENTUAN UMUM, LARANGAN, dll) */
    if (trimmed === trimmed.toUpperCase() && trimmed.length > 4 && !/^\d/.test(trimmed) && !/^[A-Z]\./.test(trimmed)) {
      html += `<p style="text-align:center;font-weight:bold;margin:12px 0 4px;">${escHtml(trimmed)}</p>`;
      continue;
    }

    /* Menimbang / Mengingat / Menetapkan */
    const konsideran = trimmed.match(/^(Menimbang|Mengingat|Menetapkan)\s*:/i);
    if (konsideran) {
      const lbl  = konsideran[1];
      const rest = trimmed.slice(lbl.length + 1).trim();
      html += `<table style="width:100%;margin:6px 0"><tr>
        <td style="width:90px;vertical-align:top;font-weight:bold">${escHtml(lbl)}</td>
        <td style="width:12px;vertical-align:top;font-weight:bold">:</td>
        <td style="vertical-align:top;text-align:justify">${escHtml(rest)}</td>
      </tr></table>`;
      continue;
    }

    /* Numbered sub-items di bawah Menimbang/Mengingat (1. / 2. / dst) */
    const numItem = trimmed.match(/^(\d+)\.\s+(.*)/);
    if (numItem) {
      html += `<table style="width:100%;margin:2px 0"><tr>
        <td style="width:102px"></td>
        <td style="width:20px;vertical-align:top">${numItem[1]}.</td>
        <td style="vertical-align:top;text-align:justify">${escHtml(numItem[2])}</td>
      </tr></table>`;
      continue;
    }

    /* Huruf sub-items (a. / b. / dst) */
    const alphaItem = trimmed.match(/^([a-z])\.\s+(.*)/);
    if (alphaItem) {
      html += `<table style="width:100%;margin:2px 0"><tr>
        <td style="width:16px;vertical-align:top">${alphaItem[1]}.</td>
        <td style="vertical-align:top;text-align:justify">${escHtml(alphaItem[2])}</td>
      </tr></table>`;
      continue;
    }

    /* Tanda tangan / Ditetapkan / Diundangkan */
    if (/^(Ditetapkan|Diundangkan)\s+di/i.test(trimmed)) {
      html += `<p style="margin:4px 0">${escHtml(trimmed)}</p>`;
      continue;
    }

    /* Default: justified paragraph */
    html += `<p style="text-align:justify;margin:4px 0">${escHtml(trimmed)}</p>`;
  }
  return html;
}

/* ── Buat konten body untuk PDF ── */
function buildPdfBody(tipe, isQuill) {
  const div = document.getElementById('outputText');

  /* 1. Sedang dalam edit mode → ambil langsung dari Quill */
  if (isQuill && _quill) {
    return `<div style="font-size:12pt;line-height:1.8">${_quill.root.innerHTML}</div>`;
  }

  /* 2. Sudah pernah diedit & disimpan → gunakan HTML Quill yang tersimpan */
  if (div.dataset.quillHtml) {
    return `<div style="font-size:12pt;line-height:1.8">${div.dataset.quillHtml}</div>`;
  }

  /* 3. Belum diedit → konversi plain text ke HTML */
  const text = div.textContent;
  if (tipe === 'perdes' || tipe === 'perkades') {
    return perdesPlainToHtml(text);
  }
  /* sambutan & surat: pre-wrap */
  return `<pre style="white-space:pre-wrap;word-break:break-word;font-family:'Times New Roman',Times,serif;font-size:12pt;line-height:1.8">${escHtml(text)}</pre>`;
}

/* ── Buat kop surat HTML ── */
function buildKopSurat(meta) {
  const logoHtml = meta.logoUrl
    ? `<div style="text-align:center;margin-bottom:8px"><img src="${meta.logoUrl}" style="height:90px;width:auto"></div>`
    : '';
  const kec = meta.kecamatan ? `<div style="text-align:center;font-size:11pt">KECAMATAN ${meta.kecamatan.toUpperCase()}</div>` : '';
  const kab = meta.kabupaten ? `<div style="text-align:center;font-size:11pt">KABUPATEN ${meta.kabupaten.toUpperCase()}</div>` : '';

  return `
  <div style="display:flex;align-items:center;gap:20px;padding-bottom:8px">
    ${meta.logoUrl ? `<div><img src="${meta.logoUrl}" style="height:90px;width:auto;display:block"></div>` : ''}
    <div style="flex:1">
      <div style="text-align:center;font-size:15pt;font-weight:bold;letter-spacing:.5px">KEPALA DESA ${escHtml(meta.nama.toUpperCase())}</div>
      ${kec}${kab}
    </div>
  </div>
  <hr style="border:none;border-top:3px double #000;margin:4px 0 16px">`;
}

/* ── Generate PDF ── */
function downloadPdf() {
  const meta    = PDF_META;
  const tipe    = meta.tipe;
  const hari    = tanggalIndonesia(new Date());
  const tahun   = new Date().getFullYear();
  const isQuill = _editMode && !!_quill;

  const bodyHtml = buildPdfBody(tipe, isQuill);
  const kopHtml  = buildKopSurat(meta);

  const win = window.open('', '_blank', 'width=900,height=750');
  if (!win) { alert('Aktifkan popup browser untuk membuka PDF.'); return; }

  win.document.write(`<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>${escHtml(meta.nama)} — ${tipe === 'perdes' ? 'Peraturan Desa' : tipe === 'perkades' ? 'Peraturan Kepala Desa' : tipe === 'sambutan' ? 'Kata Sambutan' : 'Surat Desa'}</title>
  <style>
    *   { box-sizing:border-box; margin:0; padding:0; }
    body {
      font-family:'Times New Roman',Times,serif;
      font-size:12pt; line-height:1.8; color:#000;
      padding:2cm 2.5cm;
    }
    table { border-collapse:collapse; width:100%; }
    p { margin-bottom:.3em; }
    /* Quill overrides */
    .ql-editor {
      padding:0 !important; border:none !important;
      font-family:inherit !important; font-size:inherit !important;
      line-height:inherit !important;
    }
    h1,h2,h3 { font-weight:bold; margin:.4em 0 .2em; }
    ol,ul { padding-left:1.8em; margin:.2em 0; }
    .toolbar {
      position:fixed;top:0;left:0;right:0;
      background:#1e293b;color:#fff;
      padding:10px 20px;display:flex;align-items:center;gap:10px;
      z-index:9999;font-family:sans-serif;font-size:13px;
    }
    .toolbar button {
      padding:5px 16px;border-radius:6px;border:none;
      cursor:pointer;font-size:13px;font-weight:600;
    }
    .btn-print { background:#dc2626;color:#fff; }
    .btn-close { background:#475569;color:#fff; }
    .content   { margin-top:56px; }
    @media print {
      .toolbar   { display:none !important; }
      .content   { margin-top:0; }
      @page { size:A4; margin:2cm 2.5cm; }
      body  { padding:0; }
    }
  </style>
</head>
<body>
  <div class="toolbar">
    <span style="flex:1;font-weight:600">📄 ${escHtml(meta.nama)} — Preview PDF</span>
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
  </div>
  <div class="content">
    ${kopHtml}
    <div style="margin-top:8px">
      ${bodyHtml}
    </div>
  </div>
</body>
</html>`);
  win.document.close();
}

/* ── Generate ── */
async function generate(formId, type) {
  const form = document.getElementById(formId);
  if (!form.reportValidity()) return;

  const fd   = new FormData(form);
  const data = { type };
  fd.forEach((v, k) => { data[k] = v; });

  /* Reset edit mode sebelum generate baru */
  if (_editMode) await toggleEdit();

  showState('Loading');
  const btn = form.querySelector('[data-generate]');
  if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-base"></i> Generating…'; }

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
    catch { throw new Error('Server error (' + res.status + '). Coba muat ulang halaman.'); }

    if (json.error) {
      document.getElementById('errorMsg').textContent = json.error;
      showState('Error');
    } else {
      const outDiv = document.getElementById('outputText');
      outDiv.textContent       = json.content ?? '';
      delete outDiv.dataset.quillHtml;            // hapus HTML edit lama
      const words = (json.content ?? '').trim().split(/\s+/).filter(Boolean).length;
      document.getElementById('wordCount').textContent = words + ' kata';
      showState('Content');

      /* Prepend ke riwayat */
      if (json.riwayat) {
        RIWAYAT.unshift({ ...json.riwayat, konten: json.content });
        document.getElementById('riwayatCount').textContent = RIWAYAT.length;
      }
    }
  } catch (e) {
    document.getElementById('errorMsg').textContent = 'Koneksi gagal: ' + e.message;
    showState('Error');
  } finally {
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ti ti-sparkles text-base"></i> Generate'; }
  }
}

/* ── Copy output ── */
async function copyOutput() {
  const text = getOutputContent();
  try {
    await navigator.clipboard.writeText(text);
    const btn = document.getElementById('btnCopy');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-check text-xs"></i> Tersalin!';
    btn.classList.add('text-emerald-600','border-emerald-300');
    setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-emerald-600','border-emerald-300'); }, 2000);
  } catch { alert('Salin manual: Ctrl+A lalu Ctrl+C pada area teks.'); }
}

/* ── Riwayat render ── */
function timeAgo(iso) {
  const diff = Math.floor((Date.now() - new Date(iso)) / 1000);
  if (diff < 60)    return diff + ' detik lalu';
  if (diff < 3600)  return Math.floor(diff/60) + ' menit lalu';
  if (diff < 86400) return Math.floor(diff/3600) + ' jam lalu';
  return Math.floor(diff/86400) + ' hari lalu';
}

function renderRiwayat() {
  const el = document.getElementById('riwayatList');
  if (!RIWAYAT.length) {
    el.innerHTML = `
      <div class="py-16 text-center text-slate-400">
        <i class="ti ti-history text-4xl block mb-3 opacity-40"></i>
        <p class="font-medium text-sm">Belum ada riwayat generate</p>
        <p class="text-xs mt-1">Hasil generate akan tersimpan otomatis di sini</p>
      </div>`;
    return;
  }
  el.innerHTML = RIWAYAT.map(r => {
    const preview = r.konten.substring(0, 180).replace(/\n/g, ' ') + (r.konten.length > 180 ? '…' : '');
    const words   = r.konten.trim().split(/\s+/).filter(Boolean).length;
    return `
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 space-y-2" id="ritem-${r.id}">
      <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
          <div class="text-xs font-semibold text-slate-500 mb-1">${paramSummary(r.parameter)}</div>
          <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed line-clamp-2">${preview}</p>
        </div>
      </div>
      <div class="flex items-center justify-between gap-2 pt-1">
        <div class="flex items-center gap-2 text-xs text-slate-400">
          <i class="ti ti-clock text-xs"></i> ${timeAgo(r.created_at)}
          <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700 inline-block"></span>
          ${words} kata
        </div>
        <div class="flex items-center gap-1.5">
          <button onclick="lihatRiwayat(${r.id})"
            class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <i class="ti ti-eye text-xs"></i> Lihat
          </button>
          <button onclick="gunakanLagi(${r.id})"
            class="flex items-center gap-1 px-2.5 h-7 rounded-lg border border-brand-200 dark:border-brand-700 text-xs font-medium text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
            <i class="ti ti-refresh text-xs"></i> Gunakan Lagi
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

/* ── Lihat detail riwayat ── */
function lihatRiwayat(id) {
  const r = RIWAYAT.find(x => x.id === id);
  if (!r) return;
  document.getElementById('modalRiwayatTitle').textContent = paramSummary(r.parameter);
  document.getElementById('modalRiwayatBody').textContent  = r.konten;
  document.getElementById('modalRiwayatTime').textContent  = timeAgo(r.created_at);
  document.getElementById('_riwayatId').value = id;
  document.getElementById('modalRiwayat').style.display = 'flex';
}

function closeModalRiwayat() {
  document.getElementById('modalRiwayat').style.display = 'none';
}

function copyRiwayat() {
  const text = document.getElementById('modalRiwayatBody').textContent;
  navigator.clipboard.writeText(text).then(() => {
    const btn = document.getElementById('btnCopyRiwayat');
    btn.innerHTML = '<i class="ti ti-check text-xs"></i> Tersalin!';
    setTimeout(() => { btn.innerHTML = '<i class="ti ti-copy text-xs"></i> Salin'; }, 2000);
  });
}

/* ── Gunakan lagi ── */
async function gunakanLagi(id) {
  const r = RIWAYAT.find(x => x.id === id);
  if (!r) return;
  closeModalRiwayat();
  /* Reset edit mode dulu */
  if (_editMode) await toggleEdit();
  fillForm(r.parameter);
  switchTab('generate');
  document.getElementById('outputText').textContent = r.konten;
  const words = r.konten.trim().split(/\s+/).filter(Boolean).length;
  document.getElementById('wordCount').textContent = words + ' kata';
  showState('Content');
}

/* ── Hapus riwayat ── */
async function hapusRiwayat(id) {
  if (!confirm('Hapus riwayat ini?')) return;  // ganti dengan custom confirm jika ada
  try {
    const res = await fetch(DESTROY_BASE + id, {
      method:  'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (res.ok) {
      RIWAYAT = RIWAYAT.filter(r => r.id !== id);
      document.getElementById('riwayatCount').textContent = RIWAYAT.length;
      document.getElementById('ritem-' + id)?.remove();
      if (!RIWAYAT.length) renderRiwayat();
    }
  } catch(e) { console.error(e); }
}
</script>
