@extends('mobile.layouts.app')

@section('title', 'Akun Saya')

@section('content')

@php
  $namaUser     = $penduduk->nama_lengkap ?? $user->name;
  $inisial      = collect(explode(' ', $namaUser))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->implode('');
  $desaLogoPath = $desa['desa.logo'] ?? null;
  $desaLogoUrl  = $desaLogoPath ? \Illuminate\Support\Facades\Storage::url($desaLogoPath) : null;
@endphp

<div class="profil-header">
  @if($desaLogoUrl)
  <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;background:#fff;border:3px solid rgba(255,255,255,.4);
              display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
    <img src="{{ $desaLogoUrl }}" alt="Logo Desa" style="width:100%;height:100%;object-fit:contain">
  </div>
  @else
  <div class="profil-logo" style="font-size:22px;font-weight:800;color:#fff">{{ $inisial }}</div>
  @endif
  <h2>{{ $namaUser }}</h2>
  <p>{{ $user->email }}</p>
  @if($penduduk)
  <div style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;background:rgba(255,255,255,.15);
              border:1px solid rgba(255,255,255,.25);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600">
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
    Warga Terverifikasi
  </div>
  @endif
</div>

{{-- Data Diri --}}
@if($penduduk)
<div class="info-card" style="margin-top:16px">
  <div class="info-card-header" style="justify-content:space-between">
    <div style="display:flex;align-items:center;gap:10px">
      <div class="info-ic" style="background:#dcfce7">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2.5"/><path d="M5.5 16c.7-2 1.8-3 3.5-3s2.8 1 3.5 3M15 9h4M15 13h4"/></svg>
      </div>
      <h3 style="margin:0">Data Diri</h3>
    </div>
    <a href="{{ route('portal.akun.edit') }}"
      style="border:none;background:#f0fdf4;border-radius:99px;padding:5px 12px;
             font-size:11px;font-weight:700;color:#15803d;text-decoration:none;display:flex;align-items:center;gap:5px">
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Edit
    </a>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Nama Lengkap</span>
    <span class="info-row-val">{{ $penduduk->nama_lengkap }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Email Akun</span>
    <span class="info-row-val">{{ $user->email }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">NIK</span>
    <span class="info-row-val" style="font-family:monospace;letter-spacing:.05em">{{ substr($penduduk->nik,0,4) . ' •••• •••• ' . substr($penduduk->nik,-4) }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Tempat Lahir</span>
    <span class="info-row-val">{{ $penduduk->tempat_lahir }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Tanggal Lahir</span>
    <span class="info-row-val">{{ $penduduk->tanggal_lahir->locale('id')->translatedFormat('d F Y') }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Jenis Kelamin</span>
    <span class="info-row-val">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">Status</span>
    <span class="info-row-val">{{ $penduduk->status_perkawinan_label }}</span>
  </div>
  @if($penduduk->pekerjaan)
  <div class="info-row">
    <span class="info-row-lbl">Pekerjaan</span>
    <span class="info-row-val">{{ $penduduk->pekerjaan }}</span>
  </div>
  @endif
</div>

{{-- Data KK --}}
@if($penduduk->kartuKeluarga)
<div class="info-card">
  <div class="info-card-header">
    <div class="info-ic" style="background:#dbeafe">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round"><path d="M3 11l9-8 9 8v10H3V11z"/><path d="M9 21V12h6v9"/></svg>
    </div>
    <h3>Kartu Keluarga</h3>
  </div>
  <div class="info-row">
    <span class="info-row-lbl">No. KK</span>
    <span class="info-row-val" style="font-family:monospace;letter-spacing:.05em">{{ substr($penduduk->kartuKeluarga->nomor_kk,0,4) . ' •••• •••• ' . substr($penduduk->kartuKeluarga->nomor_kk,-4) }}</span>
  </div>
  @if($penduduk->kartuKeluarga->alamat)
  <div class="info-row">
    <span class="info-row-lbl">Alamat</span>
    <span class="info-row-val">{{ $penduduk->kartuKeluarga->alamat }}{{ $penduduk->kartuKeluarga->rt ? ', RT ' . $penduduk->kartuKeluarga->rt . '/RW ' . $penduduk->kartuKeluarga->rw : '' }}</span>
  </div>
  @endif
  <div class="info-row">
    <span class="info-row-lbl">Status dalam KK</span>
    <span class="info-row-val">{{ $penduduk->hubungan_label }}</span>
  </div>
</div>
@endif
@endif

{{-- Pengaturan Akun --}}
<div class="info-card" style="margin-bottom:12px">
  <div class="info-card-header">
    <div class="info-ic" style="background:#f3f4f6">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </div>
    <h3>Pengaturan Akun</h3>
  </div>

  {{-- Ubah Password --}}
  <div class="info-row" style="cursor:pointer" onclick="bukaUbahPassword()">
    <span class="info-row-lbl" style="width:auto;display:flex;align-items:center;gap:8px;color:#374151">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Ubah Password
    </span>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.2" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
  </div>

  {{-- Divider --}}
  <div style="height:1px;background:#f3f4f6;margin:0 0"></div>

  {{-- Keluar --}}
  <div class="info-row" style="cursor:pointer" onclick="konfirmasiLogout()">
    <span class="info-row-lbl" style="width:auto;color:#dc2626;display:flex;align-items:center;gap:8px">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      Keluar dari Akun
    </span>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
  </div>
</div>

<div style="height:24px"></div>

{{-- ══════════════════════════════════════════
     MODAL: Konfirmasi Logout
     ══════════════════════════════════════════ --}}
<div id="modalLogout"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;
         backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:24px">
  <div style="background:#fff;border-radius:24px;padding:28px 24px;width:100%;max-width:360px;
              box-shadow:0 20px 60px rgba(0,0,0,.2);text-align:center">
    <div style="width:60px;height:60px;background:#fef2f2;border-radius:18px;
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
      </svg>
    </div>
    <h3 style="font-size:18px;font-weight:800;color:#111;margin:0 0 8px">Keluar dari Akun?</h3>
    <p style="font-size:13px;color:#6b7280;line-height:1.6;margin:0 0 24px">
      Anda akan keluar dari sesi ini. Pastikan data Anda sudah tersimpan sebelum melanjutkan.
    </p>
    <div style="display:flex;gap:10px">
      <button onclick="tutupModalLogout()"
        style="flex:1;border:1.5px solid #e5e7eb;background:#fff;border-radius:14px;padding:13px;
               font-size:14px;font-weight:700;color:#374151;cursor:pointer">
        Batal
      </button>
      <button onclick="document.getElementById('formLogout').submit()"
        style="flex:1;border:none;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;
               border-radius:14px;padding:13px;font-size:14px;font-weight:700;cursor:pointer">
        Ya, Keluar
      </button>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     BOTTOM SHEET: Ubah Password
     ══════════════════════════════════════════ --}}
<div id="overlayUbahPassword" onclick="tutupUbahPassword(event)"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9000;
         backdrop-filter:blur(3px);align-items:flex-end;justify-content:center">
  <div id="panelUbahPassword" onclick="event.stopPropagation()"
    style="background:#fff;border-radius:24px 24px 0 0;width:100%;max-width:480px;padding:20px 20px 36px;
           box-shadow:0 -8px 40px rgba(0,0,0,.15);
           transform:translateY(100%);transition:transform .3s cubic-bezier(.32,.72,0,1)">

    <div style="width:36px;height:4px;background:#e5e7eb;border-radius:99px;margin:0 auto 18px"></div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
      <div style="width:34px;height:34px;background:#eff6ff;border-radius:10px;
                  display:flex;align-items:center;justify-content:center">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <div>
        <p style="font-size:15px;font-weight:800;color:#111;margin:0">Ubah Password</p>
        <p style="font-size:11px;color:#6b7280;margin:0">Minimal 8 karakter</p>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px">
      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">PASSWORD SAAT INI</label>
        <div style="position:relative">
          <input id="pwdCurrent" type="password" placeholder="Masukkan password saat ini"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 44px 11px 14px;
                   font-size:14px;outline:none;box-sizing:border-box">
          <button type="button" onclick="togglePwd('pwdCurrent',this)"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;padding:0;color:#9ca3af">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>
      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">PASSWORD BARU</label>
        <div style="position:relative">
          <input id="pwdNew" type="password" placeholder="Minimal 8 karakter"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 44px 11px 14px;
                   font-size:14px;outline:none;box-sizing:border-box">
          <button type="button" onclick="togglePwd('pwdNew',this)"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;padding:0;color:#9ca3af">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>
      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">KONFIRMASI PASSWORD BARU</label>
        <div style="position:relative">
          <input id="pwdConfirm" type="password" placeholder="Ulangi password baru"
            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 44px 11px 14px;
                   font-size:14px;outline:none;box-sizing:border-box">
          <button type="button" onclick="togglePwd('pwdConfirm',this)"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;padding:0;color:#9ca3af">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>
      <div id="pwdErr" style="display:none;background:#fef2f2;border:1px solid #fecaca;
                               border-radius:10px;padding:10px 12px;font-size:13px;color:#dc2626"></div>
      <div id="pwdOk" style="display:none;background:#f0fdf4;border:1px solid #bbf7d0;
                              border-radius:10px;padding:10px 12px;font-size:13px;color:#15803d"></div>
      <button id="pwdBtn" onclick="simpanPassword()"
        style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;
               border-radius:14px;padding:14px;font-size:15px;font-weight:700;width:100%;cursor:pointer">
        Ubah Password
      </button>
    </div>
  </div>
</div>

{{-- Toast container --}}
<div id="toast"
  style="position:fixed;top:20px;left:50%;transform:translateX(-50%) translateY(-90px);z-index:99999;
         transition:transform .35s cubic-bezier(.32,.72,0,1);pointer-events:none;min-width:260px;max-width:320px">
  <div id="toastInner"
    style="display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;
           box-shadow:0 8px 30px rgba(0,0,0,.18);backdrop-filter:blur(8px)">
    <div id="toastIcon" style="flex-shrink:0;width:28px;height:28px;border-radius:8px;
                                display:flex;align-items:center;justify-content:center"></div>
    <p id="toastMsg" style="font-size:13px;font-weight:600;color:#fff;margin:0;line-height:1.4"></p>
  </div>
</div>

<script>
const PASSWORD_URL = '{{ route("portal.akun.password") }}';
const CSRF = '{{ csrf_token() }}';

/* ── Toast ── */
let toastTimer;
function showToast(msg, type = 'success') {
  clearTimeout(toastTimer);
  const colors = { success: { bg:'#15803d', icon:'#bbf7d0' }, error: { bg:'#dc2626', icon:'#fecaca' }, info: { bg:'#2563eb', icon:'#bfdbfe' } };
  const icons  = {
    success: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>',
    error:   '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    info:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
  };
  const c = colors[type] || colors.success;
  document.getElementById('toastInner').style.background = c.bg;
  document.getElementById('toastIcon').style.background  = c.icon;
  document.getElementById('toastIcon').style.color       = c.bg;
  document.getElementById('toastIcon').innerHTML = icons[type] || icons.success;
  document.getElementById('toastMsg').textContent = msg;
  document.getElementById('toast').style.transform = 'translateX(-50%) translateY(0)';
  toastTimer = setTimeout(() => {
    document.getElementById('toast').style.transform = 'translateX(-50%) translateY(-90px)';
  }, 3500);
}

/* Tampilkan toast dari session flash */
@if(session('toast_success'))
document.addEventListener('DOMContentLoaded', () => showToast('{{ session("toast_success") }}', 'success'));
@endif
@if(session('toast_error'))
document.addEventListener('DOMContentLoaded', () => showToast('{{ session("toast_error") }}', 'error'));
@endif

/* ── Logout modal ── */
function konfirmasiLogout() {
  document.getElementById('modalLogout').style.display = 'flex';
}
function tutupModalLogout() {
  document.getElementById('modalLogout').style.display = 'none';
}

/* ── Bottom sheet helpers ── */
function bukaSheet(overlayId, panelId) {
  const ov = document.getElementById(overlayId);
  const p  = document.getElementById(panelId);
  ov.style.display = 'flex';
  requestAnimationFrame(() => { p.style.transform = 'translateY(0)'; });
}
function tutupSheet(overlayId, panelId) {
  const p = document.getElementById(panelId);
  p.style.transform = 'translateY(100%)';
  setTimeout(() => { document.getElementById(overlayId).style.display = 'none'; }, 300);
}

/* ── Ubah Password ── */
function bukaUbahPassword() {
  ['pwdCurrent','pwdNew','pwdConfirm'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('pwdErr').style.display = 'none';
  bukaSheet('overlayUbahPassword', 'panelUbahPassword');
}
function tutupUbahPassword(e) {
  if (e && e.target !== document.getElementById('overlayUbahPassword')) return;
  tutupSheet('overlayUbahPassword', 'panelUbahPassword');
}
async function simpanPassword() {
  const current = document.getElementById('pwdCurrent').value;
  const pwd     = document.getElementById('pwdNew').value;
  const confirm = document.getElementById('pwdConfirm').value;
  const errEl   = document.getElementById('pwdErr');
  const btn     = document.getElementById('pwdBtn');

  errEl.style.display = 'none';
  if (!current)        { errEl.textContent = 'Masukkan password saat ini.'; errEl.style.display = 'block'; return; }
  if (pwd.length < 8)  { errEl.textContent = 'Password baru minimal 8 karakter.'; errEl.style.display = 'block'; return; }
  if (pwd !== confirm) { errEl.textContent = 'Konfirmasi password tidak cocok.'; errEl.style.display = 'block'; return; }

  btn.disabled = true; btn.textContent = 'Mengubah...';
  try {
    const res = await fetch(PASSWORD_URL, {
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ current_password: current, password: pwd, password_confirmation: confirm }).toString(),
    });
    const json = await res.json();
    if (!res.ok) {
      const msg = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Gagal mengubah password.');
      throw new Error(msg);
    }
    tutupSheet('overlayUbahPassword', 'panelUbahPassword');
    setTimeout(() => showToast(json.message || 'Password berhasil diubah.', 'success'), 350);
  } catch (e) {
    errEl.textContent = e.message; errEl.style.display = 'block';
  } finally {
    btn.disabled = false; btn.textContent = 'Ubah Password';
  }
}

/* ── Toggle show/hide password ── */
function togglePwd(inputId, btn) {
  const inp = document.getElementById(inputId);
  const isText = inp.type === 'text';
  inp.type = isText ? 'password' : 'text';
  btn.style.color = isText ? '#9ca3af' : '#374151';
}
</script>

@endsection
