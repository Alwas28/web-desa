@extends('mobile.layouts.app')

@section('title', 'Edit Data Diri')

@section('content')

<div style="background:linear-gradient(135deg,#15803d,#166534);padding:20px 16px 48px;color:#fff;position:relative">
  <a href="{{ route('portal.akun') }}"
    style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.8);
           font-size:13px;font-weight:600;text-decoration:none;margin-bottom:14px">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali
  </a>
  <p style="font-size:11px;opacity:.7;margin-bottom:4px">PENGATURAN AKUN</p>
  <h2 style="font-size:20px;font-weight:800">Edit Data Diri</h2>
  <p style="font-size:12px;opacity:.8;margin-top:4px">Perbarui informasi profil Anda</p>
</div>

<div style="margin:-28px 16px 0;position:relative;z-index:10">

  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:14px 16px;margin-bottom:14px">
    <p style="font-size:12px;font-weight:700;color:#dc2626;margin:0 0 6px">Terdapat kesalahan:</p>
    @foreach($errors->all() as $err)
    <p style="font-size:12px;color:#dc2626;margin:2px 0">• {{ $err }}</p>
    @endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('portal.akun.profil') }}">
    @csrf
    @method('PATCH')

    {{-- Informasi Akun --}}
    <div style="background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:14px">
      <p style="font-size:11px;font-weight:700;color:#15803d;margin:0 0 16px;letter-spacing:.04em">INFORMASI AKUN</p>

      <div style="margin-bottom:14px">
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">NAMA TAMPILAN</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
      </div>

      <div style="background:#f9fafb;border-radius:10px;padding:10px 12px">
        <p style="font-size:11px;color:#6b7280;margin:0">Email: <strong style="color:#374151">{{ $user->email }}</strong></p>
        <p style="font-size:10px;color:#9ca3af;margin:3px 0 0">Email tidak dapat diubah sendiri. Hubungi admin desa jika perlu perubahan.</p>
      </div>
    </div>

    @if($penduduk)
    {{-- Data Sosial --}}
    <div style="background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:14px">
      <p style="font-size:11px;font-weight:700;color:#15803d;margin:0 0 16px;letter-spacing:.04em">DATA SOSIAL</p>

      <div style="margin-bottom:14px">
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">PEKERJAAN</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}" maxlength="100"
          placeholder="Contoh: Petani, Wiraswasta, PNS..."
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">PENDIDIKAN TERAKHIR</label>
        <select name="pendidikan"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box;background:#fff"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
          <option value="">-- Pilih --</option>
          @foreach(['tidak_sekolah'=>'Tidak Sekolah','sd'=>'SD/Sederajat','smp'=>'SMP/Sederajat','sma'=>'SMA/Sederajat','diploma'=>'Diploma (D1-D3)','s1'=>'Sarjana (S1)','s2'=>'Magister (S2)','s3'=>'Doktor (S3)'] as $val => $lbl)
          <option value="{{ $val }}" {{ old('pendidikan', $penduduk->pendidikan) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
          @endforeach
        </select>
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">STATUS PERKAWINAN</label>
        <select name="status_perkawinan"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box;background:#fff"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
          <option value="">-- Pilih --</option>
          @foreach(['belum_kawin'=>'Belum Kawin','kawin'=>'Kawin','cerai_hidup'=>'Cerai Hidup','cerai_mati'=>'Cerai Mati'] as $val => $lbl)
          <option value="{{ $val }}" {{ old('status_perkawinan', $penduduk->status_perkawinan) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
          @endforeach
        </select>
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">AGAMA</label>
        <select name="agama"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box;background:#fff"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
          <option value="">-- Pilih --</option>
          @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
          <option value="{{ $ag }}" {{ old('agama', $penduduk->agama) === $ag ? 'selected' : '' }}>{{ $ag }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:5px">GOLONGAN DARAH</label>
        <select name="golongan_darah"
          style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;
                 font-size:14px;outline:none;box-sizing:border-box;background:#fff"
          onfocus="this.style.borderColor='#15803d'" onblur="this.style.borderColor='#e5e7eb'">
          <option value="">-- Pilih --</option>
          @foreach(['A'=>'A','B'=>'B','AB'=>'AB','O'=>'O','tidak_tahu'=>'Tidak Tahu'] as $val => $lbl)
          <option value="{{ $val }}" {{ old('golongan_darah', $penduduk->golongan_darah) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
          @endforeach
        </select>
      </div>
    </div>
    @endif

    {{-- Data yang tidak bisa diubah --}}
    <div style="background:#f9fafb;border-radius:16px;padding:16px;margin-bottom:16px;border:1px solid #f3f4f6">
      <p style="font-size:11px;font-weight:700;color:#9ca3af;margin:0 0 10px;letter-spacing:.04em">DATA RESMI (TIDAK DAPAT DIUBAH SENDIRI)</p>
      @if($penduduk)
      <div style="display:flex;flex-direction:column;gap:7px">
        @foreach(['NIK' => substr($penduduk->nik,0,4).' •••• •••• '.substr($penduduk->nik,-4), 'Nama Lengkap (KTP)' => $penduduk->nama_lengkap, 'Tempat Lahir' => $penduduk->tempat_lahir, 'Tanggal Lahir' => $penduduk->tanggal_lahir->locale('id')->translatedFormat('d F Y'), 'Jenis Kelamin' => $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'] as $lbl => $val)
        <div style="display:flex;align-items:center;justify-content:space-between">
          <span style="font-size:11px;color:#9ca3af">{{ $lbl }}</span>
          <span style="font-size:12px;color:#6b7280;font-weight:500">{{ $val }}</span>
        </div>
        @endforeach
      </div>
      @endif
    </div>

    <button type="submit"
      style="width:100%;background:linear-gradient(135deg,#15803d,#166534);color:#fff;border:none;
             border-radius:16px;padding:15px;font-size:16px;font-weight:700;cursor:pointer;
             display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:24px;box-shadow:0 4px 16px rgba(21,128,61,.3)">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Simpan Perubahan
    </button>
  </form>

</div>

@endsection
