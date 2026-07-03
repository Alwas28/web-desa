@extends('mobile.layouts.app')

@section('title', 'Prediksi Stunting')

@section('content')

<div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 48px;color:#fff">
  <p style="font-size:11px;opacity:.7;margin-bottom:4px">KESEHATAN ANAK</p>
  <h2 style="font-size:20px;font-weight:800">Prediksi Stunting</h2>
  <p style="font-size:12px;opacity:.8;margin-top:4px">Analisis tumbuh kembang balita berbasis AI</p>
</div>

<div style="margin:-28px 16px 0;position:relative;z-index:10">

  {{-- ── Form Card ── --}}
  <div id="viewForm" style="background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.12)">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
      <p style="font-size:13px;font-weight:700;color:#111;margin:0">Data Balita</p>
      @if($riwayat->isNotEmpty())
      <button onclick="lihatRiwayat()" style="border:none;background:#f5f3ff;border-radius:99px;padding:5px 12px;font-size:11px;font-weight:700;color:#7c3aed;cursor:pointer">
        Riwayat ({{ $riwayat->count() }})
      </button>
      @endif
    </div>

    <div style="display:flex;flex-direction:column;gap:14px">

      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">NAMA BALITA</label>
        <input id="inpNama" type="text" placeholder="Masukkan nama lengkap"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">USIA (BULAN)</label>
          <input id="inpUmur" type="number" min="0" max="60" placeholder="0–60"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box">
        </div>
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">JENIS KELAMIN</label>
          <select id="inpJk" style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box;background:#fff">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">BERAT BADAN (KG)</label>
          <input id="inpBB" type="number" step="0.1" min="0.1" max="50" placeholder="Contoh: 8.5"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box">
        </div>
        <div>
          <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">TINGGI BADAN (CM)</label>
          <input id="inpTB" type="number" step="0.1" min="1" max="150" placeholder="Contoh: 75.0"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box">
        </div>
      </div>

      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">LINGKAR KEPALA (CM) — <em>opsional</em></label>
        <input id="inpLK" type="number" step="0.1" min="1" max="60" placeholder="Opsional"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box">
      </div>

      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">CATATAN TAMBAHAN — <em>opsional</em></label>
        <textarea id="inpCatatan" rows="2" placeholder="Riwayat penyakit, kondisi khusus, dll."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;outline:none;resize:none;box-sizing:border-box"></textarea>
      </div>

      <div id="errMsg" style="display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:10px 12px;font-size:13px;color:#dc2626"></div>

      <button onclick="generate()" id="btnAnalisis"
        style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;border:none;border-radius:14px;
               padding:14px;font-size:15px;font-weight:700;width:100%;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/><path d="m16 12-4-4-4 4"/><path d="M12 16V8"/>
        </svg>
        Analisis dengan AI
      </button>
    </div>
  </div>

  {{-- ── Result Card ── --}}
  <div id="viewResult" style="display:none;margin-top:16px">
    <div id="resultHeader" style="border-radius:16px 16px 0 0;padding:18px 20px;text-align:center">
      <div id="resultIcon" style="font-size:32px;margin-bottom:6px"></div>
      <div id="resultStatus" style="font-size:18px;font-weight:800;color:#fff"></div>
      <div id="resultNama" style="font-size:12px;color:rgba(255,255,255,.8);margin-top:4px"></div>
    </div>

    <div style="background:#fff;border-radius:0 0 16px 16px;padding:16px;box-shadow:0 4px 16px rgba(0,0,0,.1)">

      {{-- Z-Score Cards --}}
      <div id="zscoreRow" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:16px"></div>

      {{-- Kesimpulan --}}
      <div style="background:#f8fafc;border-radius:12px;padding:14px;margin-bottom:12px">
        <p style="font-size:11px;font-weight:700;color:#7c3aed;margin-bottom:6px">KESIMPULAN</p>
        <p id="resultKesimpulan" style="font-size:13px;color:#374151;line-height:1.6;margin:0"></p>
      </div>

      {{-- Rujukan Warning --}}
      <div id="rujukanWarning" style="display:none;background:#fff7ed;border:1.5px solid #fed7aa;border-radius:12px;padding:12px;margin-bottom:12px;display:flex;align-items:flex-start;gap:10px">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
          <p style="font-size:12px;font-weight:700;color:#ea580c;margin:0 0 2px">Perlu Rujukan Medis</p>
          <p style="font-size:11px;color:#c2410c;margin:0">Segera konsultasikan ke puskesmas atau dokter spesialis anak.</p>
        </div>
      </div>

      {{-- Faktor Risiko --}}
      <div id="risikoSection" style="margin-bottom:12px">
        <p style="font-size:11px;font-weight:700;color:#6b7280;margin-bottom:8px">FAKTOR RISIKO</p>
        <div id="risikoList"></div>
      </div>

      {{-- Rencana Tindakan --}}
      <div style="margin-bottom:12px">
        <p style="font-size:11px;font-weight:700;color:#6b7280;margin-bottom:8px">RENCANA TINDAKAN</p>
        <div id="rekomendasiList"></div>
      </div>

      {{-- Rekomendasi Makanan --}}
      <div id="makananSection" style="margin-bottom:12px">
        <p style="font-size:11px;font-weight:700;color:#059669;margin-bottom:8px">🥗 REKOMENDASI MAKANAN</p>
        <div id="makananList" style="display:flex;flex-direction:column;gap:8px"></div>
      </div>

      {{-- Pola Makan --}}
      <div id="polaMakanSection" style="background:#f0fdf4;border-radius:12px;padding:12px;margin-bottom:12px">
        <p style="font-size:11px;font-weight:700;color:#15803d;margin-bottom:6px">POLA MAKAN IDEAL</p>
        <p id="polaMakanText" style="font-size:13px;color:#166534;line-height:1.6;margin:0"></p>
      </div>

      {{-- Suplemen --}}
      <div id="suplemenSection" style="margin-bottom:16px;display:none">
        <p style="font-size:11px;font-weight:700;color:#6b7280;margin-bottom:8px">SUPLEMEN GIZI</p>
        <div id="suplemenList"></div>
      </div>

      <div style="display:flex;gap:10px">
        <button onclick="showForm()"
          style="flex:1;border:1.5px solid #e5e7eb;background:#fff;border-radius:12px;padding:12px;font-size:13px;font-weight:600;color:#374151;cursor:pointer">
          Analisis Baru
        </button>
        <button onclick="lihatRiwayat()"
          style="flex:1;border:none;background:#f5f3ff;border-radius:12px;padding:12px;font-size:13px;font-weight:600;color:#7c3aed;cursor:pointer">
          Lihat Riwayat
        </button>
      </div>
    </div>
  </div>

  {{-- ── Riwayat Section ── --}}
  <div id="viewRiwayat" style="display:none;margin-top:16px">
    <div style="background:#fff;border-radius:16px;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.08)">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
        <p style="font-size:14px;font-weight:700;color:#111;margin:0">Riwayat Analisis</p>
        <button onclick="showForm()" style="border:none;background:transparent;font-size:12px;color:#7c3aed;font-weight:600;cursor:pointer">+ Baru</button>
      </div>
      <div id="riwayatList"></div>
    </div>
  </div>

  <div style="height:20px"></div>
</div>

{{-- ── Loading Modal ── --}}
<div id="loadingModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;backdrop-filter:blur(4px);flex-direction:column;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:24px;padding:32px 28px;text-align:center;width:260px">
    <div style="width:60px;height:60px;background:linear-gradient(135deg,#7c3aed,#5b21b6);border-radius:18px;
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/>
        <path d="M12 8v4l3 3"/>
        <path d="M9.5 2.5C7 4 5 6.5 5 9.5"/>
      </svg>
    </div>
    <p style="font-size:15px;font-weight:700;color:#111;margin:0 0 6px">Menganalisis Data</p>
    <p style="font-size:12px;color:#6b7280;margin:0 0 16px">AI sedang memproses data balita...</p>
    <div style="display:flex;justify-content:center;gap:6px">
      <div class="dot-bounce" style="width:8px;height:8px;background:#7c3aed;border-radius:50%;animation:bounce 1.2s infinite"></div>
      <div class="dot-bounce" style="width:8px;height:8px;background:#7c3aed;border-radius:50%;animation:bounce 1.2s .2s infinite"></div>
      <div class="dot-bounce" style="width:8px;height:8px;background:#7c3aed;border-radius:50%;animation:bounce 1.2s .4s infinite"></div>
    </div>
  </div>
</div>

<style>
@keyframes bounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-10px)}}
input:focus,select:focus,textarea:focus{border-color:#7c3aed!important;box-shadow:0 0 0 3px rgba(124,58,237,.1)}
</style>

<script>
const GENERATE_URL = '{{ route("portal.prediksi-stunting.generate") }}';
const CSRF         = '{{ csrf_token() }}';

const riwayatData = {!! json_encode($riwayat->map(fn($r) => [
  'id'         => $r->id,
  'created_at' => $r->created_at->toISOString(),
  'parameter'  => $r->parameter,
  'konten'     => $r->konten,
])->values()) !!};

function escHtml(s) {
  return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function showForm() {
  document.getElementById('viewForm').style.display   = 'block';
  document.getElementById('viewResult').style.display = 'none';
  document.getElementById('viewRiwayat').style.display = 'none';
  document.getElementById('errMsg').style.display = 'none';
}

function lihatRiwayat() {
  renderRiwayat();
  document.getElementById('viewForm').style.display    = 'none';
  document.getElementById('viewResult').style.display  = 'none';
  document.getElementById('viewRiwayat').style.display = 'block';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showLoading() { document.getElementById('loadingModal').style.display = 'flex'; }
function hideLoading() { document.getElementById('loadingModal').style.display = 'none'; }

function showErr(msg) {
  const el = document.getElementById('errMsg');
  el.textContent = msg;
  el.style.display = 'block';
}

async function generate() {
  const nama  = document.getElementById('inpNama').value.trim();
  const umur  = document.getElementById('inpUmur').value.trim();
  const jk    = document.getElementById('inpJk').value;
  const bb    = document.getElementById('inpBB').value.trim();
  const tb    = document.getElementById('inpTB').value.trim();
  const lk    = document.getElementById('inpLK').value.trim();
  const catat = document.getElementById('inpCatatan').value.trim();

  if (!nama || !umur || !bb || !tb) {
    showErr('Harap lengkapi data: nama, usia, berat badan, dan tinggi badan.');
    return;
  }
  document.getElementById('errMsg').style.display = 'none';

  const body = new URLSearchParams({ nama, umur_bulan: umur, jenis_kelamin: jk, berat_badan: bb, tinggi_badan: tb });
  if (lk)    body.append('lingkar_kepala', lk);
  if (catat) body.append('catatan', catat);

  showLoading();
  try {
    const res = await fetch(GENERATE_URL, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN':     CSRF,
        'Accept':           'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type':     'application/x-www-form-urlencoded',
      },
      body: body.toString(),
    });

    const rawText = await res.text();
    let json;
    try { json = JSON.parse(rawText); }
    catch { throw new Error('Server error (' + res.status + '). Coba muat ulang halaman.'); }

    if (!res.ok) throw new Error(json.error || json.message || 'Analisis gagal.');

    renderResult(json.parsed, nama);

    if (json.riwayat) {
      riwayatData.unshift({ ...json.riwayat, konten: json.content });
    }
  } catch (e) {
    showErr('Analisis Gagal — ' + e.message);
  } finally {
    hideLoading();
  }
}

const STATUS_META = {
  normal:            { bg: 'linear-gradient(135deg,#059669,#065f46)', icon: '✅', label: 'Normal' },
  berisiko_stunting: { bg: 'linear-gradient(135deg,#d97706,#92400e)', icon: '⚠️', label: 'Berisiko Stunting' },
  stunting_sedang:   { bg: 'linear-gradient(135deg,#dc2626,#7f1d1d)', icon: '🔴', label: 'Stunting Sedang' },
  stunting_berat:    { bg: 'linear-gradient(135deg,#7f1d1d,#450a0a)', icon: '🚨', label: 'Stunting Berat' },
};

function renderResult(d, nama) {
  const meta = STATUS_META[d.status] || STATUS_META['normal'];

  document.getElementById('resultHeader').style.background = meta.bg;
  document.getElementById('resultIcon').textContent   = meta.icon;
  document.getElementById('resultStatus').textContent = meta.label;
  document.getElementById('resultNama').textContent   = nama || d.nama || '';
  document.getElementById('resultKesimpulan').textContent = d.kesimpulan || '';

  // Z-Score cards
  const zRow = document.getElementById('zscoreRow');
  const zscore = (label, val, sub) => `
    <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center">
      <div style="font-size:18px;font-weight:800;color:#7c3aed">${(+val).toFixed(1)}</div>
      <div style="font-size:10px;font-weight:700;color:#374151;margin:2px 0">${escHtml(label)}</div>
      <div style="font-size:9px;color:#6b7280">${escHtml(sub)}</div>
    </div>`;
  zRow.innerHTML =
    zscore('TB/U', d.tb_u_zscore, 'Tinggi/Usia') +
    zscore('BB/U', d.bb_u_zscore, 'Berat/Usia') +
    zscore('BB/TB', d.bb_tb_zscore, 'Berat/Tinggi');

  // Rujukan
  const rujEl = document.getElementById('rujukanWarning');
  rujEl.style.display = d.perlu_rujukan ? 'flex' : 'none';

  // Faktor Risiko
  const risikoList = document.getElementById('risikoList');
  if (d.faktor_risiko && d.faktor_risiko.length) {
    risikoList.innerHTML = d.faktor_risiko.map(f =>
      `<div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:6px">
        <span style="color:#f97316;font-size:16px;line-height:1.4">•</span>
        <span style="font-size:13px;color:#374151">${escHtml(f)}</span>
      </div>`
    ).join('');
    document.getElementById('risikoSection').style.display = 'block';
  } else {
    document.getElementById('risikoSection').style.display = 'none';
  }

  // Rekomendasi
  const rekList = document.getElementById('rekomendasiList');
  rekList.innerHTML = (d.rekomendasi || []).map((r, i) =>
    `<div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px">
      <div style="min-width:22px;height:22px;background:#7c3aed;border-radius:50%;display:flex;align-items:center;justify-content:center;
                  font-size:11px;font-weight:700;color:#fff;flex-shrink:0;margin-top:1px">${i+1}</div>
      <span style="font-size:13px;color:#374151;line-height:1.5">${escHtml(r)}</span>
    </div>`
  ).join('');

  // Makanan
  const makList = document.getElementById('makananList');
  if (d.rekomendasi_makanan && d.rekomendasi_makanan.length) {
    makList.innerHTML = d.rekomendasi_makanan.map(m =>
      `<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
          <span style="font-size:16px">🥦</span>
          <span style="font-size:13px;font-weight:700;color:#065f46">${escHtml(m.nama)}</span>
        </div>
        <p style="font-size:12px;color:#047857;margin:2px 0"><strong>Manfaat:</strong> ${escHtml(m.manfaat)}</p>
        <p style="font-size:12px;color:#047857;margin:2px 0"><strong>Menu:</strong> ${escHtml(m.contoh_menu)}</p>
      </div>`
    ).join('');
    document.getElementById('makananSection').style.display = 'block';
  } else {
    document.getElementById('makananSection').style.display = 'none';
  }

  // Pola Makan
  document.getElementById('polaMakanText').textContent = d.pola_makan || '';

  // Suplemen
  const suplList = document.getElementById('suplemenList');
  if (d.suplemen_gizi && d.suplemen_gizi.length) {
    suplList.innerHTML = d.suplemen_gizi.map(s =>
      `<div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:6px">
        <span style="color:#7c3aed;font-size:16px;line-height:1.4">◆</span>
        <span style="font-size:13px;color:#374151">${escHtml(s)}</span>
      </div>`
    ).join('');
    document.getElementById('suplemenSection').style.display = 'block';
  } else {
    document.getElementById('suplemenSection').style.display = 'none';
  }

  document.getElementById('viewForm').style.display    = 'none';
  document.getElementById('viewRiwayat').style.display = 'none';
  document.getElementById('viewResult').style.display  = 'block';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function tryParseJson(konten) {
  try {
    const d = JSON.parse(konten);
    if (d && typeof d === 'object') return d;
  } catch {}
  const m = konten.match(/\{[\s\S]*\}/);
  if (m) { try { return JSON.parse(m[0]); } catch {} }
  return null;
}

function renderRiwayat() {
  const el = document.getElementById('riwayatList');
  if (!riwayatData.length) {
    el.innerHTML = '<p style="text-align:center;font-size:13px;color:#9ca3af;padding:20px 0">Belum ada riwayat analisis.</p>';
    return;
  }

  el.innerHTML = riwayatData.map(r => {
    const d    = tryParseJson(r.konten);
    const p    = r.parameter || {};
    const meta = STATUS_META[d?.status] || STATUS_META['normal'];
    const tgl  = new Date(r.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });

    return `
      <div onclick="bukaRiwayat(${r.id})"
        style="border:1.5px solid #f3f4f6;border-radius:14px;padding:14px;margin-bottom:10px;cursor:pointer;transition:.15s"
        onmouseover="this.style.borderColor='#7c3aed'" onmouseout="this.style.borderColor='#f3f4f6'">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
          <div style="display:flex;align-items:center;gap:8px">
            <span style="font-size:18px">${meta.icon}</span>
            <div>
              <p style="font-size:14px;font-weight:700;color:#111;margin:0">${escHtml(p.nama || '-')}</p>
              <p style="font-size:11px;color:#6b7280;margin:0">${p.umur_bulan || '-'} bulan • ${tgl}</p>
            </div>
          </div>
          <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;color:#fff;background:${meta.bg.includes('linear') ? meta.bg : meta.bg}">${meta.label}</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
          <div style="background:#f8fafc;border-radius:8px;padding:7px;text-align:center">
            <div style="font-size:14px;font-weight:800;color:#7c3aed">${d ? (+d.tb_u_zscore).toFixed(1) : '–'}</div>
            <div style="font-size:9px;color:#6b7280">TB/U</div>
          </div>
          <div style="background:#f8fafc;border-radius:8px;padding:7px;text-align:center">
            <div style="font-size:14px;font-weight:800;color:#7c3aed">${d ? (+d.bb_u_zscore).toFixed(1) : '–'}</div>
            <div style="font-size:9px;color:#6b7280">BB/U</div>
          </div>
          <div style="background:#f8fafc;border-radius:8px;padding:7px;text-align:center">
            <div style="font-size:14px;font-weight:800;color:#7c3aed">${d ? (+d.bb_tb_zscore).toFixed(1) : '–'}</div>
            <div style="font-size:9px;color:#6b7280">BB/TB</div>
          </div>
        </div>
      </div>`;
  }).join('');
}

function bukaRiwayat(id) {
  const r = riwayatData.find(x => x.id === id);
  if (!r) return;
  const d = tryParseJson(r.konten);
  if (!d) { alert('Data riwayat tidak dapat dibaca.'); return; }
  renderResult(d, r.parameter?.nama || '');
}

// Pre-render riwayat list so it's ready when opened
if (riwayatData.length) renderRiwayat();
</script>

@endsection
