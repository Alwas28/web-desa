<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#15803d">
<title>Daftar Akun Warga</title>
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

/* Header */
.header{background:linear-gradient(135deg,#15803d,#166534);padding:calc(env(safe-area-inset-top,0) + 14px) 20px 20px;color:#fff}
.back-btn{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:rgba(255,255,255,.8);
  padding:8px 0;border:none;background:none;cursor:pointer;margin-bottom:16px}
.back-btn:active{opacity:.7}
.header-ic{width:56px;height:56px;border-radius:18px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);
  display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.header h1{font-size:22px;font-weight:800;line-height:1.2}
.header p{font-size:13px;opacity:.8;margin-top:6px;line-height:1.5}

/* Steps indicator */
.steps{display:flex;align-items:center;gap:0;padding:20px 20px 0}
.step{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1}
.step-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:700;transition:.2s}
.step-circle.active{background:#15803d;color:#fff;box-shadow:0 2px 8px rgba(21,128,61,.35)}
.step-circle.done{background:#dcfce7;color:#15803d;border:2px solid #15803d}
.step-circle.pending{background:#f3f4f6;color:#9ca3af;border:2px solid #e5e7eb}
.step-label{font-size:10px;font-weight:600;color:#6b7280}
.step-label.active{color:#15803d}
.step-line{flex:1;height:2px;margin-bottom:16px;transition:.2s}
.step-line.done{background:#15803d}
.step-line.pending{background:#e5e7eb}

/* Form card */
.form-card{background:#fff;border-radius:20px;margin:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.07)}
.form-card h2{font-size:16px;font-weight:700;color:#111827;margin-bottom:4px}
.form-card .sub{font-size:13px;color:#6b7280;margin-bottom:20px;line-height:1.5}

/* Info box */
.info-box{background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:13px 14px;
  display:flex;gap:10px;align-items:flex-start;margin-bottom:20px}
.info-box p{font-size:12px;color:#15803d;line-height:1.5;font-weight:500}

/* Error box */
.error-box{background:#fef2f2;border:1.5px solid #fecaca;border-radius:14px;padding:13px 14px;
  display:flex;gap:10px;align-items:flex-start;margin-bottom:20px}
.error-box p{font-size:13px;color:#dc2626;line-height:1.5;font-weight:600}

/* Form fields */
.field{margin-bottom:16px}
.field label{display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;letter-spacing:.01em}
.field label span{color:#dc2626}
.field-hint{font-size:11px;color:#9ca3af;margin-top:4px;font-weight:400}
.inp{width:100%;padding:13px 14px;border:1.5px solid #e5e7eb;border-radius:12px;
  font-size:15px;font-family:inherit;color:#111827;background:#fafafa;outline:none;transition:.15s;
  -webkit-appearance:none;appearance:none}
.inp:focus{border-color:#15803d;background:#fff;box-shadow:0 0 0 3px rgba(21,128,61,.1)}
.inp.error{border-color:#dc2626;background:#fef2f2}
.inp::placeholder{color:#9ca3af}

/* Date input styling */
.inp[type="date"]{color:#111827}
.inp[type="date"]::-webkit-calendar-picker-indicator{opacity:.5;cursor:pointer}

/* Submit button */
.btn-submit{width:100%;padding:15px;border-radius:14px;border:none;cursor:pointer;
  background:linear-gradient(135deg,#15803d,#166534);color:#fff;
  font-size:15px;font-weight:700;font-family:inherit;
  display:flex;align-items:center;justify-content:center;gap:8px;
  box-shadow:0 4px 14px rgba(21,128,61,.4);transition:.15s;margin-top:4px}
.btn-submit:active{transform:scale(.98);opacity:.9}
.btn-submit:disabled{opacity:.6;cursor:not-allowed}

/* Login link */
.login-link{text-align:center;padding:16px 20px;font-size:13px;color:#6b7280}
.login-link a{color:#15803d;font-weight:700;text-decoration:none}

/* Security note */
.security-note{display:flex;align-items:center;gap:8px;padding:0 20px 24px;font-size:11px;color:#9ca3af;font-weight:500}
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <button class="back-btn" onclick="history.back()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali
    </button>
    <div class="header-ic">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <h1>Daftar Akun Warga</h1>
    <p>Verifikasi data diri sesuai dokumen kependudukan Anda</p>
  </div>

  {{-- Steps --}}
  <div class="steps">
    <div class="step">
      <div class="step-circle active">1</div>
      <div class="step-label active">Verifikasi</div>
    </div>
    <div class="step-line pending" style="margin-bottom:16px"></div>
    <div class="step">
      <div class="step-circle pending">2</div>
      <div class="step-label">Buat Akun</div>
    </div>
  </div>

  {{-- Error --}}
  @if($errors->has('identitas'))
  <div style="padding:16px 16px 0">
    <div class="error-box">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p>{{ $errors->first('identitas') }}</p>
    </div>
  </div>
  @endif

  <div class="form-card">
    <h2>Verifikasi Identitas</h2>
    <p class="sub">Masukkan data sesuai KTP dan Kartu Keluarga Anda. Data harus cocok dengan yang terdaftar di desa.</p>

    <div class="info-box">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.2" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
      <p>Hanya warga yang sudah terdaftar dalam sistem data kependudukan desa yang dapat mendaftar akun.</p>
    </div>

    <form method="POST" action="{{ route('warga.register.verifikasi') }}" id="form" novalidate>
      @csrf

      <div class="field">
        <label for="nik">Nomor Induk Kependudukan (NIK) <span>*</span></label>
        <input class="inp {{ $errors->has('nik') ? 'error' : '' }}" type="text" id="nik" name="nik"
          value="{{ old('nik') }}" maxlength="16" placeholder="16 digit angka"
          inputmode="numeric" pattern="[0-9]{16}" autocomplete="off" required>
        @error('nik')<p class="field-hint" style="color:#dc2626">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label for="nomor_kk">Nomor Kartu Keluarga (No. KK) <span>*</span></label>
        <input class="inp {{ $errors->has('nomor_kk') ? 'error' : '' }}" type="text" id="nomor_kk" name="nomor_kk"
          value="{{ old('nomor_kk') }}" maxlength="16" placeholder="16 digit angka"
          inputmode="numeric" pattern="[0-9]{16}" autocomplete="off" required>
        @error('nomor_kk')<p class="field-hint" style="color:#dc2626">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label for="tanggal_lahir">Tanggal Lahir (sesuai KTP) <span>*</span></label>
        <input class="inp {{ $errors->has('tanggal_lahir') ? 'error' : '' }}" type="date" id="tanggal_lahir" name="tanggal_lahir"
          value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
        @error('tanggal_lahir')<p class="field-hint" style="color:#dc2626">{{ $message }}</p>@enderror
      </div>

      <button type="submit" class="btn-submit" id="btnSubmit">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Verifikasi Data
      </button>
    </form>
  </div>

  <div class="login-link">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
  </div>

  <div class="security-note">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    Data Anda diproteksi dan tidak dibagikan ke pihak ketiga
  </div>

</div>
<script>
// Hanya izinkan angka pada NIK dan nomor KK
['nik','nomor_kk'].forEach(id => {
  document.getElementById(id).addEventListener('input', function() {
    this.value = this.value.replace(/\D/g,'');
  });
});

// Disable button saat submit untuk mencegah double submit
document.getElementById('form').addEventListener('submit', function() {
  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Memverifikasi...';
});
</script>
<style>
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</body>
</html>
