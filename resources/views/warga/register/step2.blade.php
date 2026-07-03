<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#15803d">
<title>Buat Akun — Daftar Warga</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;font-family:'Inter',system-ui,sans-serif;-webkit-font-smoothing:antialiased;background:#f0fdf4}
.wrap{width:100%;max-width:430px;min-height:100vh;margin:0 auto;background:#f5f6f8;display:flex;flex-direction:column}
@media(min-width:480px){
  body{display:flex;align-items:flex-start;justify-content:center;padding:24px 0}
  .wrap{min-height:calc(100vh - 48px);border-radius:36px;overflow:hidden;box-shadow:0 0 40px rgba(0,0,0,.18)}
}
.header{background:linear-gradient(135deg,#15803d,#166534);padding:calc(env(safe-area-inset-top,0) + 14px) 20px 20px;color:#fff}
.back-btn{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:rgba(255,255,255,.8);
  padding:8px 0;border:none;background:none;cursor:pointer;margin-bottom:16px}
.back-btn:active{opacity:.7}
.header-ic{width:56px;height:56px;border-radius:18px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);
  display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.header h1{font-size:22px;font-weight:800;line-height:1.2}
.header p{font-size:13px;opacity:.8;margin-top:6px;line-height:1.5}
.steps{display:flex;align-items:center;gap:0;padding:20px 20px 0}
.step{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1}
.step-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700}
.step-circle.active{background:#15803d;color:#fff;box-shadow:0 2px 8px rgba(21,128,61,.35)}
.step-circle.done{background:#dcfce7;color:#15803d;border:2px solid #15803d}
.step-circle.pending{background:#f3f4f6;color:#9ca3af;border:2px solid #e5e7eb}
.step-label{font-size:10px;font-weight:600;color:#6b7280}
.step-label.active{color:#15803d}
.step-label.done{color:#15803d}
.step-line{flex:1;height:2px;margin-bottom:16px}
.step-line.done{background:#15803d}
.step-line.pending{background:#e5e7eb}

/* Verified identity card */
.verified-card{background:#fff;border-radius:20px;margin:16px 16px 0;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.07)}
.verified-header{display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #f3f4f6}
.verified-badge{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:#15803d;
  background:#dcfce7;padding:5px 10px;border-radius:99px}
.verified-avatar{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#15803d,#166534);
  display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px;flex-shrink:0}
.verified-name{font-size:15px;font-weight:800;color:#111827}
.verified-nik{font-size:12px;color:#6b7280;margin-top:2px}
.detail-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0}
.detail-row .lbl{font-size:12px;color:#9ca3af;font-weight:500}
.detail-row .val{font-size:12px;color:#374151;font-weight:600}

/* Form card */
.form-card{background:#fff;border-radius:20px;margin:12px 16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.07)}
.form-card h2{font-size:16px;font-weight:700;color:#111827;margin-bottom:4px}
.form-card .sub{font-size:13px;color:#6b7280;margin-bottom:20px;line-height:1.5}
.field{margin-bottom:16px;position:relative}
.field label{display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px}
.field label span{color:#dc2626}
.field-hint{font-size:11px;color:#9ca3af;margin-top:4px}
.inp{width:100%;padding:13px 14px;border:1.5px solid #e5e7eb;border-radius:12px;
  font-size:15px;font-family:inherit;color:#111827;background:#fafafa;outline:none;transition:.15s;
  -webkit-appearance:none;appearance:none}
.inp:focus{border-color:#15803d;background:#fff;box-shadow:0 0 0 3px rgba(21,128,61,.1)}
.inp.error{border-color:#dc2626;background:#fef2f2}
.inp::placeholder{color:#9ca3af}
.inp-icon{position:relative}
.inp-icon .inp{padding-right:46px}
.toggle-pw{position:absolute;right:14px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;display:flex}
.toggle-pw:hover{color:#374151}
.error-box{background:#fef2f2;border:1.5px solid #fecaca;border-radius:12px;padding:12px 14px;
  display:flex;gap:9px;align-items:flex-start;margin-bottom:16px}
.error-box p{font-size:13px;color:#dc2626;font-weight:600}

/* Password strength */
.pw-strength{margin-top:6px;display:flex;gap:4px;align-items:center}
.pw-bar{height:3px;flex:1;border-radius:99px;background:#e5e7eb;transition:.3s}
.pw-bar.weak{background:#dc2626}
.pw-bar.fair{background:#f59e0b}
.pw-bar.good{background:#3b82f6}
.pw-bar.strong{background:#15803d}
.pw-lbl{font-size:10px;font-weight:600;margin-left:4px;min-width:36px;color:#9ca3af}

.btn-submit{width:100%;padding:15px;border-radius:14px;border:none;cursor:pointer;
  background:linear-gradient(135deg,#15803d,#166534);color:#fff;
  font-size:15px;font-weight:700;font-family:inherit;
  display:flex;align-items:center;justify-content:center;gap:8px;
  box-shadow:0 4px 14px rgba(21,128,61,.4);transition:.15s;margin-top:4px}
.btn-submit:active{transform:scale(.98);opacity:.9}
.btn-submit:disabled{opacity:.6;cursor:not-allowed}
.security-note{display:flex;align-items:center;gap:8px;padding:0 20px 24px;font-size:11px;color:#9ca3af;font-weight:500}
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <a href="{{ route('warga.register') }}" class="back-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali
    </a>
    <div class="header-ic">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>
    <h1>Buat Kata Sandi</h1>
    <p>Identitas terverifikasi. Sekarang buat akun login Anda.</p>
  </div>

  {{-- Steps --}}
  <div class="steps">
    <div class="step">
      <div class="step-circle done">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
      </div>
      <div class="step-label done">Verifikasi</div>
    </div>
    <div class="step-line done" style="margin-bottom:16px"></div>
    <div class="step">
      <div class="step-circle active">2</div>
      <div class="step-label active">Buat Akun</div>
    </div>
  </div>

  {{-- Verified identity summary --}}
  <div class="verified-card">
    <div class="verified-header">
      <div class="verified-badge">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
        Identitas Terverifikasi
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
      <div class="verified-avatar">
        {{ strtoupper(substr($penduduk->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strstr($penduduk->nama_lengkap, ' ') ?: ' ', 1, 1)) }}
      </div>
      <div style="flex:1;min-width:0">
        <div class="verified-name">{{ $penduduk->nama_lengkap }}</div>
        <div class="verified-nik">NIK: {{ substr($penduduk->nik, 0, 4) . '••••••••••' . substr($penduduk->nik, -2) }}</div>
      </div>
    </div>
    <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f3f4f6;display:grid;gap:2px">
      <div class="detail-row">
        <span class="lbl">Tanggal Lahir</span>
        <span class="val">{{ $penduduk->tanggal_lahir->locale('id')->translatedFormat('d M Y') }}</span>
      </div>
      <div class="detail-row">
        <span class="lbl">Jenis Kelamin</span>
        <span class="val">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
      </div>
    </div>
  </div>

  {{-- Errors --}}
  @if($errors->any())
  <div style="padding:12px 16px 0">
    <div class="error-box">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p>{{ $errors->first() }}</p>
    </div>
  </div>
  @endif

  <div class="form-card">
    <h2>Informasi Akun</h2>
    <p class="sub">Email dan password akan digunakan untuk login ke portal warga.</p>

    <form method="POST" action="{{ route('warga.register.simpan') }}" id="form" novalidate>
      @csrf

      <div class="field">
        <label for="email">Alamat Email <span>*</span></label>
        <input class="inp {{ $errors->has('email') ? 'error' : '' }}" type="email" id="email" name="email"
          value="{{ old('email') }}" placeholder="contoh@email.com"
          autocomplete="email" inputmode="email" required>
        @error('email')<p class="field-hint" style="color:#dc2626">{{ $message }}</p>@enderror
        <p class="field-hint">Email ini akan digunakan sebagai username login</p>
      </div>

      <div class="field">
        <label for="password">Password <span>*</span></label>
        <div class="inp-icon">
          <input class="inp {{ $errors->has('password') ? 'error' : '' }}" type="password" id="password" name="password"
            placeholder="Minimal 8 karakter" autocomplete="new-password" required oninput="checkStrength(this.value)">
          <button type="button" class="toggle-pw" onclick="togglePw('password','icon1')" tabindex="-1">
            <svg id="icon1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div class="pw-strength" id="pwStrength" style="display:none">
          <div class="pw-bar" id="bar1"></div>
          <div class="pw-bar" id="bar2"></div>
          <div class="pw-bar" id="bar3"></div>
          <div class="pw-bar" id="bar4"></div>
          <span class="pw-lbl" id="pwLbl"></span>
        </div>
        @error('password')<p class="field-hint" style="color:#dc2626">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label for="password_confirmation">Konfirmasi Password <span>*</span></label>
        <div class="inp-icon">
          <input class="inp" type="password" id="password_confirmation" name="password_confirmation"
            placeholder="Ulangi password" autocomplete="new-password" required oninput="checkMatch()">
          <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation','icon2')" tabindex="-1">
            <svg id="icon2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <p class="field-hint" id="matchHint" style="display:none"></p>
      </div>

      <button type="submit" class="btn-submit" id="btnSubmit">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Buat Akun
      </button>
    </form>
  </div>

  <div class="security-note">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    Akun terhubung langsung dengan data kependudukan Anda
  </div>

</div>
<script>
function togglePw(inputId, iconId) {
  const inp = document.getElementById(inputId);
  const icon = document.getElementById(iconId);
  const isHidden = inp.type === 'password';
  inp.type = isHidden ? 'text' : 'password';
  icon.innerHTML = isHidden
    ? '<path d="M17.9 17.9A10.5 10.5 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.2-6.9M9.9 4.2A9 9 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.4M1 1l22 22"/><circle cx="12" cy="12" r="3"/>'
    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}

function checkStrength(val) {
  const wrap = document.getElementById('pwStrength');
  if (!val) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'flex';
  let score = 0;
  if (val.length >= 8)  score++;
  if (/[A-Z]/.test(val) || /[0-9]/.test(val)) score++;
  if (/[^a-zA-Z0-9]/.test(val)) score++;
  if (val.length >= 12) score++;
  const levels = [{cls:'weak',lbl:'Lemah'},{cls:'fair',lbl:'Cukup'},{cls:'good',lbl:'Baik'},{cls:'strong',lbl:'Kuat'}];
  const lvl = levels[Math.max(0, score - 1)];
  for(let i=1;i<=4;i++){
    const bar = document.getElementById('bar'+i);
    bar.className = 'pw-bar' + (i <= score ? ' ' + lvl.cls : '');
  }
  document.getElementById('pwLbl').textContent = lvl.lbl;
  document.getElementById('pwLbl').style.color = {weak:'#dc2626',fair:'#f59e0b',good:'#3b82f6',strong:'#15803d'}[lvl.cls];
}

function checkMatch() {
  const pw = document.getElementById('password').value;
  const cf = document.getElementById('password_confirmation').value;
  const hint = document.getElementById('matchHint');
  if (!cf) { hint.style.display = 'none'; return; }
  hint.style.display = 'block';
  if (pw === cf) {
    hint.textContent = '✓ Password cocok';
    hint.style.color = '#15803d';
  } else {
    hint.textContent = '✗ Password tidak cocok';
    hint.style.color = '#dc2626';
  }
}

document.getElementById('form').addEventListener('submit', function(e) {
  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Membuat Akun...';
});
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>
