<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
/* Halaman 1: margin atas diatur via .page padding-top (kecil untuk KOP) */
/* Halaman 2+: margin atas 3cm via @page */
@page       { margin-top: 3cm;  margin-bottom: 3cm; margin-left: 0; margin-right: 0; }
@page :first { margin-top: 0;   margin-bottom: 0;   margin-left: 0; margin-right: 0; }
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: {{ $kopFont === 'Times New Roman' ? 'Times New Roman, serif' : ($kopFont === 'Georgia' ? 'Georgia, serif' : 'DejaVu Sans, sans-serif') }};
  font-size: 11pt;
  color: #000;
  line-height: 1.5;
  background: #fff;
}

/*
 * A4 = 21cm; konten = 21 - 3 (kiri) - 3 (kanan) = 15cm.
 * padding-top kecil untuk halaman 1 (KOP tidak perlu banyak spasi atas).
 * Halaman 2+ sudah ditangani @page margin-top: 3cm di atas.
 */
.page {
  margin-left: 3cm;
  width: 15cm;
  padding-top: 1.5cm;
  padding-bottom: 3cm;
}

/* ── KOP ── */
.kop-wrap {
  border-bottom: 3px double #000;
  padding-bottom: 6pt;
  margin-bottom: 10pt;
  text-align: {{ $kopAlign }};
}
.kop-line { display: block; line-height: 1.3; }
.kop-row  { display: table; width: 100%; }
.kop-logo-cell { display: table-cell; width: 70pt; vertical-align: middle; }
.kop-logo-cell img { width: 60pt; height: 60pt; object-fit: contain; }
.kop-text-cell { display: table-cell; vertical-align: middle; text-align: {{ $kopAlign }}; }

/* ── Judul & nomor ── */
.surat-title {
  text-align: center;
  font-size: 13pt;
  font-weight: bold;
  text-decoration: underline;
  margin-top: 14pt;
  margin-bottom: 0;
}
.surat-nomor {
  text-align: center;
  font-size: 10pt;
  margin-top: 2pt;
  margin-bottom: 12pt;
}

/* ── Isi surat ── */
.surat-body { font-size: 11pt; text-align: justify; }
.surat-body p { margin-bottom: 4pt; }

/* ── TTD: langsung setelah konten, tidak pakai display:table ── */
.ttd-outer {
  margin-top: 18pt;
  page-break-inside: avoid; /* cegah TTD terpisah ke halaman baru */
}
.ttd-block {
  /* Geser ke kanan: 55% dari lebar konten (15cm) = 8.25cm dari kiri */
  margin-left: 55%;
  text-align: center;
}
.ttd-place   { font-size: 10pt; margin-bottom: 1pt; }
.ttd-jabatan { font-size: 10pt; margin-bottom: 6pt; }
.ttd-qr img  { width: 76pt; height: 76pt; display: block; margin: 0 auto; }
.ttd-nama    { font-size: 11pt; font-weight: bold; text-decoration: underline; margin-top: 5pt; display: block; }
</style>
</head>
<body>
<div class="page">

  {{-- ── KOP SURAT ── --}}
  <div class="kop-wrap">
    @php $sizeKeys = ['l1','l2','l3','l4','l5']; @endphp

    @if($logoData && $logoPos === 'above')
      <img src="{{ $logoData }}" alt="Logo" style="height:58pt;margin-bottom:4pt;display:block;margin-left:auto;margin-right:auto">
      @foreach($kopLines as $idx => $line)
        @php $pt = $kopSizes[$sizeKeys[$idx] ?? 'l3'] ?? 11; @endphp
        <span class="kop-line" style="font-size:{{ $pt }}pt;{{ $idx === 1 ? 'font-weight:bold;' : '' }}">{{ $line }}</span>
      @endforeach

    @elseif($logoData && $logoPos === 'left')
      <div class="kop-row">
        <div class="kop-logo-cell"><img src="{{ $logoData }}" alt="Logo"></div>
        <div class="kop-text-cell">
          @foreach($kopLines as $idx => $line)
            @php $pt = $kopSizes[$sizeKeys[$idx] ?? 'l3'] ?? 11; @endphp
            <span class="kop-line" style="font-size:{{ $pt }}pt;{{ $idx === 1 ? 'font-weight:bold;' : '' }}">{{ $line }}</span>
          @endforeach
        </div>
      </div>

    @else
      @foreach($kopLines as $idx => $line)
        @php $pt = $kopSizes[$sizeKeys[$idx] ?? 'l3'] ?? 11; @endphp
        <span class="kop-line" style="font-size:{{ $pt }}pt;{{ $idx === 1 ? 'font-weight:bold;' : '' }}">{{ $line }}</span>
      @endforeach
    @endif
  </div>

  {{-- ── JUDUL SURAT ── --}}
  <div class="surat-title">{{ strtoupper($pengajuan->jenis_surat) }}</div>
  <div class="surat-nomor">Nomor: {{ $pengajuan->nomor_surat ?? '&nbsp;' }}</div>

  {{-- ── ISI SURAT ── --}}
  <div class="surat-body">{!! $isiSurat !!}</div>

  {{-- ── TANDA TANGAN ── --}}
  <div class="ttd-outer">
    <div class="ttd-block">
      <p class="ttd-place">{{ $desa['desa.nama'] ?? '' }}, {{ $tanggal }}</p>
      <p class="ttd-jabatan">{{ $jabatanKades }}</p>
      <div class="ttd-qr"><img src="{{ $qrData }}" alt="QR Verifikasi"></div>
      <span class="ttd-nama">{{ $namaKades }}</span>
    </div>
  </div>

</div>
</body>
</html>
