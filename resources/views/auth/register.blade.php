<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0C7C46">
<title>Daftar — {{ $desaNama ?? 'Portal Desa' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#F4FAF7;--surface:#fff;--surface-2:#EDF6F1;
  --ink:#0F241B;--muted:#54685E;--line:rgba(15,36,27,.1);
  --green:#0C7C46;--green-deep:#075F36;--green-soft:#DDF1E6;
  --blue:#0E63A8;--blue-deep:#0A4E86;
}
[data-theme="dark"]{
  --bg:#0A1410;--surface:#101D17;--surface-2:#15251D;
  --ink:#E9F4EE;--muted:#9DB3A8;--line:rgba(233,244,238,.1);
  --green:#3BCD85;--green-deep:#2AA468;--green-soft:#143323;
  --blue:#5FAEE6;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;display:flex;-webkit-font-smoothing:antialiased;transition:background .3s,color .3s}
a{color:inherit;text-decoration:none}

.auth-wrap{display:flex;min-height:100vh;width:100%}

.auth-left{
  display:none;flex:1;
  background:linear-gradient(145deg,#0A4E86 0%,#0E63A8 40%,#0C7C46 100%);
  padding:48px;flex-direction:column;justify-content:space-between;
  position:relative;overflow:hidden;
}
@media(min-width:900px){.auth-left{display:flex}}
.auth-left::before{content:'';position:absolute;right:-80px;top:-80px;width:360px;height:360px;border-radius:50%;background:rgba(255,255,255,.06)}
.auth-left::after{content:'';position:absolute;left:-60px;bottom:-60px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.04)}
.left-brand{display:flex;align-items:center;gap:14px;position:relative;z-index:1}
.left-logo{width:52px;height:52px;border-radius:15px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
.left-logo img{width:100%;height:100%;object-fit:contain}
.left-brand-name{font-size:1.1rem;font-weight:800;color:#fff;line-height:1.2}
.left-brand-sub{font-size:.75rem;color:rgba(255,255,255,.7);font-weight:600;letter-spacing:.06em;margin-top:2px}
.left-hero{position:relative;z-index:1}
.left-hero h2{font-size:1.9rem;font-weight:800;color:#fff;line-height:1.2;letter-spacing:-.02em;margin-bottom:16px}
.left-hero p{font-size:.9rem;color:rgba(255,255,255,.75);line-height:1.75;max-width:320px}
.reg-steps{margin-top:32px;display:flex;flex-direction:column;gap:0}
.step-item{display:flex;gap:16px;position:relative}
.step-item:not(:last-child)::after{content:'';position:absolute;left:19px;top:40px;width:2px;height:calc(100% - 20px);background:rgba(255,255,255,.2)}
.step-num{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0;margin-bottom:20px}
.step-txt strong{display:block;color:#fff;font-weight:700;font-size:.9rem;margin-bottom:4px}
.step-txt span{font-size:.8rem;color:rgba(255,255,255,.65)}
.left-footer{font-size:.78rem;color:rgba(255,255,255,.5);position:relative;z-index:1}

.auth-right{
  width:100%;max-width:540px;
  display:flex;flex-direction:column;justify-content:center;
  padding:32px 24px;background:var(--surface);position:relative;
}
@media(min-width:900px){.auth-right{padding:48px}}

.theme-btn{position:absolute;top:20px;right:20px;width:38px;height:38px;border-radius:10px;border:1px solid var(--line);background:var(--surface-2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:.2s}
.theme-btn:hover{color:var(--green);border-color:var(--green)}
.back-home{display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;color:var(--muted);margin-bottom:32px;transition:.15s}
.back-home:hover{color:var(--green)}

.auth-header{margin-bottom:24px}
.auth-icon{width:52px;height:52px;border-radius:15px;background:linear-gradient(135deg,var(--blue),var(--green));display:flex;align-items:center;justify-content:center;margin-bottom:18px;box-shadow:0 8px 20px -8px rgba(14,99,168,.45)}
.auth-header h1{font-size:1.45rem;font-weight:800;letter-spacing:-.02em;line-height:1.2}
.auth-header p{color:var(--muted);font-size:.88rem;margin-top:6px}

.notice-card{background:var(--green-soft);border:1.5px solid var(--green);border-radius:16px;padding:20px;margin-bottom:20px}
.notice-card h3{font-size:.9rem;font-weight:800;color:var(--green-deep);margin-bottom:8px;display:flex;align-items:center;gap:8px}
.notice-card p{font-size:.82rem;color:var(--ink);line-height:1.65;margin-bottom:14px}
.btn-notice{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:12px;background:linear-gradient(135deg,var(--green),var(--green-deep));color:#fff;font-size:.88rem;font-weight:700;font-family:inherit;border:none;cursor:pointer;text-decoration:none;box-shadow:0 6px 16px -6px rgba(12,124,70,.5);transition:.2s}
.btn-notice:hover{transform:translateY(-2px);box-shadow:0 10px 22px -8px rgba(12,124,70,.55)}

.or-divider{display:flex;align-items:center;gap:12px;margin:20px 0;color:var(--muted);font-size:.78rem}
.or-divider::before,.or-divider::after{content:'';flex:1;height:1px;background:var(--line)}

.section-title{font-size:.7rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--line)}
.field{margin-bottom:16px}
.field label{display:block;font-size:.8rem;font-weight:700;color:var(--ink);margin-bottom:7px;letter-spacing:.01em}
.inp{width:100%;padding:12px 16px;border:1.5px solid var(--line);border-radius:13px;font-size:.9rem;font-family:inherit;color:var(--ink);background:var(--surface-2);outline:none;transition:.2s;-webkit-appearance:none;appearance:none}
.inp:focus{border-color:var(--green);background:var(--surface);box-shadow:0 0 0 3px var(--green-soft)}
.inp.is-error{border-color:#ef4444;background:#fef2f2}
.inp::placeholder{color:var(--muted)}
.field-err{font-size:.78rem;color:#ef4444;margin-top:5px;font-weight:600}
.inp-wrap{position:relative}
.inp-wrap .inp{padding-right:48px}
.pw-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);display:flex;align-items:center;padding:4px;transition:.15s}
.pw-toggle:hover{color:var(--ink)}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:480px){.row-2{grid-template-columns:1fr}}
.btn-auth{width:100%;padding:13px;border-radius:14px;border:none;cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--blue-deep));color:#fff;font-size:.97rem;font-weight:700;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 8px 20px -8px rgba(14,99,168,.5);transition:.2s;margin-top:20px}
.btn-auth:hover{transform:translateY(-2px);box-shadow:0 14px 28px -10px rgba(14,99,168,.55)}
.btn-auth:active{transform:none}
.btn-auth:disabled{opacity:.6;cursor:not-allowed;transform:none}
.auth-alt{text-align:center;font-size:.88rem;color:var(--muted);margin-top:18px}
.auth-alt a{color:var(--green);font-weight:700;transition:.15s}
.auth-alt a:hover{color:var(--green-deep)}
</style>
</head>
<body>

<script>
(function(){
  const t=localStorage.getItem('theme')||(window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');
  document.documentElement.setAttribute('data-theme',t);
})();
</script>

<div class="auth-wrap">

  <div class="auth-left">
    <div class="left-brand">
      <div class="left-logo">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="Logo {{ $desaNama }}">
        @else
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"/></svg>
        @endif
      </div>
      <div>
        <div class="left-brand-name">{{ $desaNama ?? 'Portal Desa' }}</div>
        <div class="left-brand-sub">SISTEM INFORMASI DESA</div>
      </div>
    </div>

    <div class="left-hero">
      <h2>Bergabung &<br>Nikmati Layanan<br>Digital Desa</h2>
      <p>Daftarkan diri sebagai warga untuk mengakses layanan administrasi desa secara online.</p>
      <div class="reg-steps">
        <div class="step-item">
          <div class="step-num">1</div>
          <div class="step-txt">
            <strong>Verifikasi Identitas</strong>
            <span>Masukkan NIK, No. KK, dan tanggal lahir</span>
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">2</div>
          <div class="step-txt">
            <strong>Buat Akun</strong>
            <span>Tentukan email dan password akun Anda</span>
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">3</div>
          <div class="step-txt">
            <strong>Akses Layanan</strong>
            <span>Ajukan surat, buat laporan, dan lainnya</span>
          </div>
        </div>
      </div>
    </div>

    <div class="left-footer">© {{ date('Y') }} {{ $desaNama ?? 'Portal Desa' }}. Hak cipta dilindungi.</div>
  </div>

  <div class="auth-right">

    <button class="theme-btn" id="themeBtn" aria-label="Ganti tema">
      <svg id="iconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
      <svg id="iconSun" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
    </button>

    <a href="{{ route('home') }}" class="back-home">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali ke Beranda
    </a>

    <div class="auth-header">
      <div class="auth-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
      </div>
      <h1>Buat Akun Warga</h1>
      <p>Pilih cara pendaftaran di bawah ini</p>
    </div>

    {{-- Cara utama: daftar warga lewat verifikasi kependudukan --}}
    <div class="notice-card">
      <h3>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
        Daftar sebagai Warga Desa
      </h3>
      <p>Pendaftaran warga membutuhkan verifikasi data kependudukan (NIK &amp; No. KK). Data Anda akan dicocokkan dengan database desa terlebih dahulu.</p>
      <a href="{{ route('warga.register') }}" class="btn-notice">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/><path d="M19 8v6M22 11h-6"/></svg>
        Mulai Daftar sebagai Warga
      </a>
    </div>

    <div class="or-divider">atau daftar akun biasa</div>

    <p class="section-title">Informasi Akun</p>

    <form method="POST" action="{{ route('register') }}" id="regForm">
      @csrf

      <div class="row-2">
        <div class="field">
          <label for="name">Nama Lengkap</label>
          <input class="inp {{ $errors->has('name') ? 'is-error' : '' }}"
                 type="text" id="name" name="name"
                 value="{{ old('name') }}" placeholder="Nama Anda"
                 required autofocus autocomplete="name">
          @error('name')
            <p class="field-err">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label for="email">Alamat Email</label>
          <input class="inp {{ $errors->has('email') ? 'is-error' : '' }}"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}" placeholder="email@domain.com"
                 required autocomplete="username">
          @error('email')
            <p class="field-err">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="row-2">
        <div class="field">
          <label for="password">Password</label>
          <div class="inp-wrap">
            <input class="inp {{ $errors->has('password') ? 'is-error' : '' }}"
                   type="password" id="password" name="password"
                   placeholder="Min. 8 karakter" required autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('password','eye1')" tabindex="-1">
              <svg id="eye1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password')
            <p class="field-err">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label for="password_confirmation">Konfirmasi Password</label>
          <div class="inp-wrap">
            <input class="inp"
                   type="password" id="password_confirmation"
                   name="password_confirmation"
                   placeholder="Ulangi password" required autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation','eye2')" tabindex="-1">
              <svg id="eye2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>
      </div>

      <button type="submit" class="btn-auth" id="btnReg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
        Buat Akun
      </button>
    </form>

    <div class="auth-alt">
      Sudah punya akun?
      <a href="{{ route('login') }}">Masuk di sini</a>
    </div>

  </div>
</div>

<script>
(function(){
  const t=document.documentElement.getAttribute('data-theme');
  const moon=document.getElementById('iconMoon');
  const sun=document.getElementById('iconSun');
  if(moon&&sun){moon.style.display=t==='dark'?'none':'block';sun.style.display=t==='dark'?'block':'none';}
})();
document.getElementById('themeBtn')?.addEventListener('click',function(){
  const curr=document.documentElement.getAttribute('data-theme');
  const next=curr==='dark'?'light':'dark';
  document.documentElement.setAttribute('data-theme',next);
  localStorage.setItem('theme',next);
  document.getElementById('iconMoon').style.display=next==='dark'?'none':'block';
  document.getElementById('iconSun').style.display=next==='dark'?'block':'none';
});

function togglePw(fieldId,iconId){
  const inp=document.getElementById(fieldId);
  const isHidden=inp.type==='password';
  inp.type=isHidden?'text':'password';
  document.getElementById(iconId).innerHTML=isHidden
    ?'<path d="M17.9 17.9A10.5 10.5 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.2-6.9M9.9 4.2A9 9 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.4M1 1l22 22"/><circle cx="12" cy="12" r="3"/>'
    :'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}

document.getElementById('regForm')?.addEventListener('submit',function(){
  const btn=document.getElementById('btnReg');
  btn.disabled=true;
  btn.innerHTML='<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Memproses...';
});
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>
