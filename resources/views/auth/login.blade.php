<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0C7C46">
<title>Masuk — {{ $desaNama ?? 'Portal Desa' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#F4FAF7;--surface:#fff;--surface-2:#EDF6F1;
  --ink:#0F241B;--muted:#54685E;--line:rgba(15,36,27,.1);
  --green:#0C7C46;--green-deep:#075F36;--green-soft:#DDF1E6;
  --blue:#0E63A8;--blue-deep:#0A4E86;
  --shadow:0 10px 30px -12px rgba(12,60,40,.18);
  --shadow-lg:0 24px 60px -20px rgba(12,60,40,.25);
  --radius:18px;
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

/* ── Split layout ── */
.auth-wrap{display:flex;min-height:100vh;width:100%}

/* ── Left panel ── */
.auth-left{
  display:none;
  flex:1;
  background:linear-gradient(145deg,#075F36 0%,#0C7C46 45%,#0A4E86 100%);
  padding:48px;
  flex-direction:column;
  justify-content:space-between;
  position:relative;
  overflow:hidden;
}
@media(min-width:900px){.auth-left{display:flex}}
.auth-left::before{
  content:'';position:absolute;right:-80px;top:-80px;
  width:360px;height:360px;border-radius:50%;
  background:rgba(255,255,255,.06);
}
.auth-left::after{
  content:'';position:absolute;left:-60px;bottom:-60px;
  width:280px;height:280px;border-radius:50%;
  background:rgba(255,255,255,.04);
}
.left-brand{display:flex;align-items:center;gap:14px;position:relative;z-index:1}
.left-logo{
  width:52px;height:52px;border-radius:15px;
  background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);
  display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;
}
.left-logo img{width:100%;height:100%;object-fit:contain}
.left-brand-name{font-size:1.1rem;font-weight:800;color:#fff;line-height:1.2}
.left-brand-sub{font-size:.75rem;color:rgba(255,255,255,.7);font-weight:600;letter-spacing:.06em;margin-top:2px}
.left-hero{position:relative;z-index:1}
.left-hero h2{font-size:2rem;font-weight:800;color:#fff;line-height:1.2;letter-spacing:-.02em;margin-bottom:16px}
.left-hero p{font-size:.95rem;color:rgba(255,255,255,.75);line-height:1.7;max-width:320px}
.left-features{display:flex;flex-direction:column;gap:14px;margin-top:32px}
.left-feat{display:flex;align-items:center;gap:12px}
.feat-ic{width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.feat-text{font-size:.85rem;color:rgba(255,255,255,.85);font-weight:500}
.left-footer{font-size:.78rem;color:rgba(255,255,255,.5);position:relative;z-index:1}

/* ── Right panel (form) ── */
.auth-right{
  width:100%;max-width:520px;
  display:flex;flex-direction:column;justify-content:center;
  padding:32px 24px;
  background:var(--surface);
  position:relative;
}
@media(min-width:900px){.auth-right{padding:48px}}

/* ── Dark mode toggle ── */
.theme-btn{
  position:absolute;top:20px;right:20px;
  width:38px;height:38px;border-radius:10px;
  border:1px solid var(--line);background:var(--surface-2);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;color:var(--muted);transition:.2s;
}
.theme-btn:hover{color:var(--green);border-color:var(--green)}

/* ── Back to home ── */
.back-home{
  display:inline-flex;align-items:center;gap:6px;
  font-size:.8rem;font-weight:600;color:var(--muted);
  margin-bottom:36px;transition:.15s;
}
.back-home:hover{color:var(--green)}

/* ── Auth card header ── */
.auth-header{margin-bottom:32px}
.auth-icon{
  width:52px;height:52px;border-radius:15px;
  background:linear-gradient(135deg,var(--green),var(--blue));
  display:flex;align-items:center;justify-content:center;
  margin-bottom:18px;box-shadow:0 8px 20px -8px rgba(12,124,70,.45);
}
.auth-header h1{font-size:1.5rem;font-weight:800;letter-spacing:-.02em;line-height:1.2}
.auth-header p{color:var(--muted);font-size:.9rem;margin-top:6px}

/* ── Form ── */
.field{margin-bottom:20px}
.field label{display:block;font-size:.8rem;font-weight:700;color:var(--ink);margin-bottom:7px;letter-spacing:.01em}
.inp{
  width:100%;padding:13px 16px;
  border:1.5px solid var(--line);border-radius:13px;
  font-size:.95rem;font-family:inherit;color:var(--ink);
  background:var(--surface-2);outline:none;transition:.2s;
  -webkit-appearance:none;appearance:none;
}
.inp:focus{border-color:var(--green);background:var(--surface);box-shadow:0 0 0 3px var(--green-soft)}
.inp.is-error{border-color:#ef4444;background:#fef2f2}
.inp::placeholder{color:var(--muted)}
.field-err{font-size:.78rem;color:#ef4444;margin-top:5px;font-weight:600}

/* Password wrapper */
.inp-wrap{position:relative}
.inp-wrap .inp{padding-right:48px}
.pw-toggle{
  position:absolute;right:14px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;color:var(--muted);
  display:flex;align-items:center;padding:4px;transition:.15s;
}
.pw-toggle:hover{color:var(--ink)}

/* ── Remember & forgot ── */
.auth-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.check-label{display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--muted);cursor:pointer}
.check-label input[type=checkbox]{width:16px;height:16px;accent-color:var(--green);cursor:pointer}
.forgot-link{font-size:.83rem;font-weight:600;color:var(--green);transition:.15s}
.forgot-link:hover{color:var(--green-deep)}

/* ── Submit button ── */
.btn-auth{
  width:100%;padding:14px;border-radius:14px;border:none;cursor:pointer;
  background:linear-gradient(135deg,var(--green),var(--green-deep));
  color:#fff;font-size:1rem;font-weight:700;font-family:inherit;
  display:flex;align-items:center;justify-content:center;gap:8px;
  box-shadow:0 8px 20px -8px rgba(12,124,70,.55);
  transition:.2s;
}
.btn-auth:hover{transform:translateY(-2px);box-shadow:0 14px 28px -10px rgba(12,124,70,.6)}
.btn-auth:active{transform:none}
.btn-auth:disabled{opacity:.6;cursor:not-allowed;transform:none}

/* ── Divider ── */
.divider{display:flex;align-items:center;gap:12px;margin:24px 0;color:var(--muted);font-size:.78rem}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--line)}

/* ── Register link ── */
.auth-alt{text-align:center;font-size:.88rem;color:var(--muted)}
.auth-alt a{color:var(--green);font-weight:700;transition:.15s}
.auth-alt a:hover{color:var(--green-deep)}

/* ── Alert session status ── */
.alert-status{background:var(--green-soft);border:1.5px solid var(--green);color:var(--green-deep);
  border-radius:12px;padding:11px 14px;font-size:.85rem;font-weight:600;margin-bottom:20px}
</style>
</head>
<body>

<script>
(function(){
  const t=localStorage.getItem('theme')||( window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');
  document.documentElement.setAttribute('data-theme',t);
})();
</script>

<div class="auth-wrap">

  {{-- ── Panel Kiri ── --}}
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
      <h2>Selamat Datang<br>di Portal Warga</h2>
      <p>Akses layanan administrasi desa, informasi publik, dan laporan secara digital kapan saja dan di mana saja.</p>
      <div class="left-features">
        <div class="left-feat">
          <div class="feat-ic">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13h6M9 17h6"/></svg>
          </div>
          <span class="feat-text">Pengajuan surat online tanpa antri</span>
        </div>
        <div class="left-feat">
          <div class="feat-ic">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          <span class="feat-text">Laporan warga langsung ke desa</span>
        </div>
        <div class="left-feat">
          <div class="feat-ic">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </div>
          <span class="feat-text">Notifikasi status layanan real-time</span>
        </div>
      </div>
    </div>

    <div class="left-footer">© {{ date('Y') }} {{ $desaNama ?? 'Portal Desa' }}. Hak cipta dilindungi.</div>
  </div>

  {{-- ── Panel Kanan (Form) ── --}}
  <div class="auth-right">

    {{-- Dark mode toggle --}}
    <button class="theme-btn" id="themeBtn" aria-label="Ganti tema">
      <svg id="iconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
      <svg id="iconSun" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
    </button>

    {{-- Back to home --}}
    <a href="{{ route('home') }}" class="back-home">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali ke Beranda
    </a>

    {{-- Header --}}
    <div class="auth-header">
      <div class="auth-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
      </div>
      <h1>Masuk ke Akun</h1>
      <p>Gunakan email dan password akun portal warga Anda</p>
    </div>

    {{-- Session status --}}
    @if(session('status'))
      <div class="alert-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
      @csrf

      <div class="field">
        <label for="email">Alamat Email</label>
        <input class="inp {{ $errors->has('email') ? 'is-error' : '' }}"
               type="email" id="email" name="email"
               value="{{ old('email') }}" placeholder="nama@email.com"
               required autofocus autocomplete="username">
        @error('email')
          <p class="field-err">{{ $message }}</p>
        @enderror
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="inp-wrap">
          <input class="inp {{ $errors->has('password') ? 'is-error' : '' }}"
                 type="password" id="password" name="password"
                 placeholder="Masukkan password" required autocomplete="current-password">
          <button type="button" class="pw-toggle" onclick="togglePw()" tabindex="-1">
            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('password')
          <p class="field-err">{{ $message }}</p>
        @enderror
      </div>

      <div class="auth-row">
        <label class="check-label">
          <input type="checkbox" name="remember" id="remember_me">
          Ingat saya
        </label>
        @if(Route::has('password.request'))
          <a class="forgot-link" href="{{ route('password.request') }}">Lupa password?</a>
        @endif
      </div>

      <button type="submit" class="btn-auth" id="btnLogin">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
        Masuk
      </button>
    </form>

    <div class="divider">atau</div>

    <div class="auth-alt">
      Belum punya akun?
      <a href="{{ route('warga.register') }}">Daftar sebagai Warga</a>
    </div>

  </div>
</div>

<script>
// Dark mode init
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

// Password toggle
function togglePw(){
  const inp=document.getElementById('password');
  const isHidden=inp.type==='password';
  inp.type=isHidden?'text':'password';
  document.getElementById('eyeIcon').innerHTML=isHidden
    ?'<path d="M17.9 17.9A10.5 10.5 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.2-6.9M9.9 4.2A9 9 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.4M1 1l22 22"/><circle cx="12" cy="12" r="3"/>'
    :'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}

// Disable button on submit
document.getElementById('loginForm')?.addEventListener('submit',function(){
  const btn=document.getElementById('btnLogin');
  btn.disabled=true;
  btn.innerHTML='<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Memproses...';
});
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>
