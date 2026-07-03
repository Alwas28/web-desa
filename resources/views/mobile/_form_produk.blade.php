@php $px = $prefix ?? ''; @endphp

@php
  $inputStyle = "width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:13px;font-family:inherit;color:#111827;outline:none;transition:border-color .2s;box-sizing:border-box;background:#fff";
  $labelStyle = "display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px";
@endphp

{{-- Foto Produk --}}
<div style="margin-bottom:14px">
  <label style="{{ $labelStyle }}">Foto Produk <span style="color:#9ca3af;font-weight:400">(opsional, maks. 2MB)</span></label>

  <div id="{{ $px }}foto_preview_wrap" style="display:none;margin-bottom:8px;position:relative">
    <img id="{{ $px }}foto_preview"
      style="width:100%;height:180px;object-fit:cover;border-radius:12px;border:1.5px solid #e5e7eb">
    <button type="button" onclick="hapusFoto('{{ $px }}')"
      style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;
             background:rgba(0,0,0,.55);border:none;color:#fff;cursor:pointer;font-size:14px;
             display:flex;align-items:center;justify-content:center;line-height:1">✕</button>
  </div>

  <label id="{{ $px }}foto_dropzone" for="{{ $px }}foto_input"
    style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
           height:120px;border:2px dashed #e5e7eb;border-radius:12px;cursor:pointer;
           background:#fafafa;transition:border-color .2s;text-align:center;padding:12px">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round">
      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
      <polyline points="21 15 16 10 5 21"/>
    </svg>
    <div>
      <p style="font-size:13px;font-weight:600;color:#374151;margin:0">Tap untuk pilih foto</p>
      <p style="font-size:11px;color:#9ca3af;margin:2px 0 0">JPG, PNG, WebP</p>
    </div>
  </label>
  <input type="file" name="foto" id="{{ $px }}foto_input" accept="image/jpeg,image/png,image/webp"
    style="display:none" onchange="previewFoto(this, '{{ $px }}')">
</div>

{{-- Nama --}}
<div style="margin-bottom:12px">
  <label style="{{ $labelStyle }}">Nama Produk <span style="color:#dc2626">*</span></label>
  <input type="text" name="nama" id="{{ $px }}nama" required maxlength="150"
    placeholder="Contoh: Sayur Bayam, Keripik Singkong..."
    style="{{ $inputStyle }}"
    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'">
</div>

{{-- Harga + Satuan --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
  <div>
    <label style="{{ $labelStyle }}">Harga <span style="color:#dc2626">*</span></label>
    {{-- Input tampilan (format Rp) --}}
    <div style="position:relative">
      <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;color:#6b7280;pointer-events:none">Rp</span>
      <input type="text" id="{{ $px }}harga_display" inputmode="numeric"
        required placeholder="0"
        style="{{ $inputStyle }};padding-left:36px"
        oninput="formatHargaInput(this,'{{ $px }}')"
        onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'">
    </div>
    {{-- Hidden input yang dikirim ke server --}}
    <input type="hidden" name="harga" id="{{ $px }}harga">
  </div>
  <div>
    <label style="{{ $labelStyle }}">Satuan <span style="color:#dc2626">*</span></label>
    <input type="text" name="satuan" id="{{ $px }}satuan" required maxlength="50"
      placeholder="kg / pcs / ikat..."
      style="{{ $inputStyle }}"
      onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'">
  </div>
</div>

{{-- Nomor WhatsApp --}}
<div style="margin-bottom:12px">
  <label style="{{ $labelStyle }}">Nomor WhatsApp <span style="color:#dc2626">*</span></label>
  <div style="position:relative">
    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:13px;color:#6b7280;font-weight:600">+62</span>
    <input type="tel" name="nomor_wa" id="{{ $px }}nomor_wa" required maxlength="20"
      placeholder="812xxxxxxxx"
      style="{{ $inputStyle }};padding-left:44px"
      onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'">
  </div>
  <p style="font-size:11px;color:#9ca3af;margin-top:4px">Calon pembeli akan menghubungi Anda via WhatsApp</p>
</div>

{{-- Deskripsi --}}
<div style="margin-bottom:16px">
  <label style="{{ $labelStyle }}">Deskripsi <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
  <textarea name="deskripsi" id="{{ $px }}deskripsi" rows="2" maxlength="500"
    placeholder="Info tambahan tentang produk: kondisi, cara pesan, dll..."
    style="{{ $inputStyle }};resize:none"
    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
</div>
