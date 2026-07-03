@extends('layouts.admin')
@section('title', 'Panduan Penggunaan')
@section('page-title', 'Panduan Penggunaan')
@section('page-sub', 'Dokumentasi lengkap cara menggunakan sistem administrasi desa')

@section('content')

{{-- ── Sidebar Navigasi Fitur (Fixed) ── --}}
<div id="panduan-sidebar"
  style="position:fixed;top:72px;right:20px;width:216px;z-index:30;max-height:calc(100vh - 88px);overflow-y:auto">
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
      <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Navigasi Fitur</p>
    </div>
    <nav class="p-2 flex flex-col gap-0.5" id="featureNav">

      <a href="#penduduk" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-users text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Kependudukan</p><p class="text-[10px] text-slate-400 leading-tight truncate">Data Penduduk &amp; KK</p></div>
      </a>
      <a href="#publik" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-world text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Publik</p><p class="text-[10px] text-slate-400 leading-tight truncate">Berita, Page, Arsip</p></div>
      </a>
      <a href="#kesehatan" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-heart-plus text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Kesehatan</p><p class="text-[10px] text-slate-400 leading-tight truncate">Posyandu, Balita, Bumil</p></div>
      </a>
      <a href="#ai" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-brain text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Kecerdasan Buatan</p><p class="text-[10px] text-slate-400 leading-tight truncate">AI &amp; Prediksi</p></div>
      </a>
      <a href="#statistik" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-chart-bar text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Statistik Desa</p><p class="text-[10px] text-slate-400 leading-tight truncate">IDM &amp; APBDes</p></div>
      </a>
      <a href="#peta" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-map text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Peta Desa</p><p class="text-[10px] text-slate-400 leading-tight truncate">GIS &amp; Lokasi</p></div>
      </a>
      <a href="#laporan" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-message-report text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Laporan Warga</p><p class="text-[10px] text-slate-400 leading-tight truncate">Pengaduan Masyarakat</p></div>
      </a>
      <a href="#bansos" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-heart-handshake text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Bansos</p><p class="text-[10px] text-slate-400 leading-tight truncate">Bantuan Sosial</p></div>
      </a>
      <a href="#surat" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-mail text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Persuratan</p><p class="text-[10px] text-slate-400 leading-tight truncate">Pengajuan Surat</p></div>
      </a>
      <a href="#pasar" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-shopping-bag text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Pasar Desa</p><p class="text-[10px] text-slate-400 leading-tight truncate">Penjual &amp; Produk</p></div>
      </a>
      <a href="#master" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-database text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Master Data</p><p class="text-[10px] text-slate-400 leading-tight truncate">Data Referensi</p></div>
      </a>
      <a href="#pengguna" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-user-circle text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Pengguna</p><p class="text-[10px] text-slate-400 leading-tight truncate">Role &amp; Akses</p></div>
      </a>
      <a href="#pengaturan" onclick="setActive(this)" class="nav-feat-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
        <i class="ti ti-settings text-sm flex-shrink-0 text-slate-400 group-hover:text-brand-500"></i>
        <div class="min-w-0"><p class="text-xs font-semibold leading-tight truncate">Pengaturan</p><p class="text-[10px] text-slate-400 leading-tight truncate">Konfigurasi Sistem</p></div>
      </a>

    </nav>
  </div>
</div>

{{-- ── Konten Utama ── --}}
<div style="padding-right:248px">

    {{-- ═══ KEPENDUDUKAN ═══ --}}
    <section id="penduduk" class="scroll-mt-4">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-users text-blue-600 dark:text-blue-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kependudukan</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Data Penduduk &amp; Kartu Keluarga</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-users text-slate-400"></i> Data Penduduk</h4>
          <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3">Modul ini digunakan untuk mengelola data kependudukan seluruh warga desa.</p>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Lihat daftar penduduk</strong> — klik menu <em>Kependudukan → Data Penduduk</em>. Gunakan kolom pencarian untuk menemukan warga berdasarkan nama atau NIK.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Tambah penduduk</strong> — klik tombol <em>"+ Tambah"</em>, isi formulir data diri lengkap (NIK, nama, tempat/tanggal lahir, jenis kelamin, dll.), lalu simpan.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Edit data</strong> — klik ikon pensil pada baris data yang ingin diubah, lakukan perubahan, lalu simpan.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Hapus data</strong> — klik ikon tempat sampah. Data yang sudah dihapus tidak dapat dikembalikan.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-home text-slate-400"></i> Kartu Keluarga</h4>
          <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3">Kelola data kartu keluarga (KK) dan anggota keluarga yang terdaftar.</p>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Buat KK baru</strong> — klik <em>"+ Tambah KK"</em>, masukkan nomor KK dan pilih kepala keluarga dari daftar penduduk.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Tambah anggota</strong> — buka detail KK, klik <em>"Tambah Anggota"</em>, pilih penduduk yang akan dimasukkan ke KK tersebut.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ PUBLIK ═══ --}}
    <section id="publik" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-world text-green-600 dark:text-green-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Publik</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Berita, Pengumuman, Page &amp; Arsip</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-news text-slate-400"></i> Berita</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Buat berita</strong> — klik <em>"+ Tulis Berita"</em>, isi judul, pilih kategori, upload gambar thumbnail, tulis konten menggunakan editor teks, lalu atur status <em>Draft</em> atau <em>Diterbitkan</em>.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Jadwalkan terbit</strong> — atur kolom <em>Tanggal Terbit</em> untuk menerbitkan berita secara otomatis di waktu yang ditentukan.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Kategori berita</strong> — tambah dan kelola kategori melalui <em>Master Data → Kategori Berita</em> sebelum membuat berita.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-speakerphone text-slate-400"></i> Pengumuman</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Buat pengumuman resmi desa yang akan tampil di portal warga. Isi judul, isi pengumuman, dan tanggal berlaku pengumuman tersebut.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-file-text text-slate-400"></i> Page (Halaman Statis)</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Buat halaman</strong> — digunakan untuk membuat konten halaman tetap seperti profil desa, visi misi, atau panduan layanan. Isi judul, konten HTML, dan slug URL yang unik.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>SEO</strong> — isi bagian Meta Title, Meta Deskripsi, dan Meta Keywords agar halaman mudah ditemukan di mesin pencari.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-archive text-slate-400"></i> Arsip</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Upload dan kelola dokumen arsip desa (SK, peraturan, laporan). Pilih kategori arsip, masukkan nomor dokumen, upload file, lalu simpan.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ KESEHATAN ═══ --}}
    <section id="kesehatan" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-heart-plus text-red-600 dark:text-red-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kesehatan</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Posyandu, Balita &amp; Ibu Hamil</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-building-hospital text-slate-400"></i> Posyandu</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Data Posyandu</strong> — daftarkan unit posyandu yang ada di desa beserta nama, lokasi, dan penanggung jawabnya.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Kegiatan Posyandu</strong> — catat setiap kegiatan pelayanan posyandu: tanggal, jenis kegiatan, jumlah peserta, dan catatan penting.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-baby-carriage text-slate-400"></i> Data Balita</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Kelola data tumbuh kembang balita: nama, tanggal lahir, berat badan, tinggi badan, dan lingkar kepala. Data ini digunakan untuk pemantauan gizi dan deteksi dini stunting.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-heart-plus text-slate-400"></i> Ibu Hamil</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Daftarkan dan pantau kondisi ibu hamil di desa: nama, usia kehamilan, riwayat kunjungan, dan kondisi kesehatan. Data ini membantu petugas kesehatan memberikan perhatian khusus.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ AI ═══ --}}
    <section id="ai" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-brain text-purple-600 dark:text-purple-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kecerdasan Buatan</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Fitur AI Terintegrasi</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">Seluruh fitur AI menggunakan model bahasa besar (LLM) untuk menghasilkan teks. Pastikan konfigurasi API sudah diatur di menu <em>Pengaturan → API</em> sebelum menggunakan fitur ini.</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
              <p class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1"><i class="ti ti-microphone mr-1"></i> Kata Sambutan</p>
              <p class="text-xs text-purple-700 dark:text-purple-400">Buat teks sambutan kepala desa secara otomatis. Isi konteks acara dan AI akan menghasilkan naskah yang siap digunakan.</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
              <p class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1"><i class="ti ti-file-certificate mr-1"></i> Peraturan Desa</p>
              <p class="text-xs text-purple-700 dark:text-purple-400">Buat draf peraturan desa (Perdes) berdasarkan topik dan pokok-pokok ketentuan yang diinputkan.</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
              <p class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1"><i class="ti ti-mail mr-1"></i> Konten Surat</p>
              <p class="text-xs text-purple-700 dark:text-purple-400">Buat konten surat resmi desa secara otomatis berdasarkan jenis dan tujuan surat yang dipilih.</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
              <p class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1"><i class="ti ti-file-check mr-1"></i> Peraturan Kades</p>
              <p class="text-xs text-purple-700 dark:text-purple-400">Buat draf Peraturan Kepala Desa (Perkades) dengan panduan AI berdasarkan bidang dan tujuan peraturan.</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 sm:col-span-2">
              <p class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1"><i class="ti ti-brain mr-1"></i> Prediksi Stunting</p>
              <p class="text-xs text-purple-700 dark:text-purple-400">Analisis risiko stunting balita menggunakan AI berdasarkan data antropometri (berat badan, tinggi badan, lingkar kepala, umur). Tersedia juga bagi warga melalui portal mobile.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ═══ STATISTIK ═══ --}}
    <section id="statistik" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-chart-bar text-amber-600 dark:text-amber-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Statistik Desa</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">IDM &amp; APBDes</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-chart-bar text-slate-400"></i> IDM (Indeks Desa Membangun)</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Input data IDM tahunan meliputi Indeks Ketahanan Sosial (IKS), Indeks Ketahanan Ekonomi (IKE), dan Indeks Ketahanan Lingkungan (IKL). Sistem akan menghitung nilai IDM dan menentukan status desa secara otomatis.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-cash text-slate-400"></i> APBDes</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Kelola Anggaran Pendapatan dan Belanja Desa per tahun anggaran. Input rincian pendapatan (Dana Desa, PAD, dll.) dan belanja (pembangunan, pemberdayaan, dll.) untuk ditampilkan secara publik di portal warga.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ PETA ═══ --}}
    <section id="peta" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-map text-teal-600 dark:text-teal-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Peta Desa</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Peta Desa (GIS)</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Tambah titik lokasi</strong> — klik pada peta di posisi yang diinginkan, isi nama lokasi, kategori (fasilitas umum, batas wilayah, dll.), dan deskripsi.</span></li>
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Upload GeoJSON</strong> — untuk batas wilayah desa atau area khusus, upload file GeoJSON yang sesuai format standar.</span></li>
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Peta akan tampil secara interaktif di portal warga sehingga masyarakat dapat melihat peta wilayah desa lengkap dengan fasilitas yang ada.</span></li>
        </ul>
      </div>
    </section>

    {{-- ═══ LAPORAN ═══ --}}
    <section id="laporan" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-message-report text-orange-600 dark:text-orange-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pelaporan</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Laporan Warga</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">Modul ini menampilkan seluruh laporan yang masuk dari warga melalui portal mobile. Admin dapat memproses, memberikan balasan, dan mengubah status laporan.</p>
        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Filter laporan</strong> — gunakan tab <em>Semua / Menunggu / Diproses / Selesai</em> untuk menyaring laporan berdasarkan status. Badge merah pada menu menunjukkan jumlah laporan yang belum ditangani.</span></li>
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Proses laporan</strong> — klik tombol <em>"Proses"</em> pada laporan yang dipilih untuk membuka halaman detail. Di sini admin dapat mengubah status, menulis balasan ke warga, membuat rencana tindak lanjut, dan mengisi laporan penanganan.</span></li>
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Status laporan:</strong> <em>Menunggu</em> → belum ditinjau, <em>Diproses</em> → sedang ditangani, <em>Selesai</em> → sudah selesai ditangani.</span></li>
          <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Laporan Penanganan</strong> wajib diisi saat status diubah menjadi <em>Selesai</em> sebagai bukti dan dokumentasi penanganan.</span></li>
        </ul>
      </div>
    </section>

    {{-- ═══ BANSOS ═══ --}}
    <section id="bansos" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-pink-100 dark:bg-pink-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-heart-handshake text-pink-600 dark:text-pink-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Bansos</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Bantuan Sosial</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-list text-slate-400"></i> Jenis Bansos</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Daftarkan jenis-jenis bantuan sosial yang ada (PKH, BLT, BPNT, dll.) beserta sumber dana, nilai bantuan, dan periode penyaluran.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-heart-handshake text-slate-400"></i> Penerima Bansos</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Daftarkan penerima</strong> — pilih warga dari data penduduk, pilih jenis bansos, dan tentukan periode penerimaan.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Rekam penyaluran</strong> — catat tanggal dan nominal setiap penyaluran bantuan kepada penerima yang terdaftar.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ PERSURATAN ═══ --}}
    <section id="surat" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-mail text-cyan-600 dark:text-cyan-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Layanan Persuratan</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Pengajuan &amp; Penerbitan Surat</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Alur Pengajuan Surat</h4>
          <div class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-400">
            <div class="flex flex-col items-center gap-1">
              <div class="w-7 h-7 rounded-full bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs flex-shrink-0">1</div>
              <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
              <div class="w-7 h-7 rounded-full bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs flex-shrink-0">2</div>
              <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
              <div class="w-7 h-7 rounded-full bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs flex-shrink-0">3</div>
              <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
              <div class="w-7 h-7 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-bold text-xs flex-shrink-0">4</div>
            </div>
            <div class="flex flex-col gap-5 pt-1">
              <div><strong>Warga mengajukan</strong> melalui portal mobile, memilih jenis surat dan mengisi data yang diperlukan.</div>
              <div><strong>Petugas memverifikasi</strong> — buka <em>Layanan Persuratan → Layanan Surat</em>, cari pengajuan berstatus <em>Diajukan</em>, periksa kelengkapan data, lalu ubah status menjadi <em>Diproses</em>.</div>
              <div><strong>Kepala Desa menyetujui</strong> — pengajuan yang sudah diproses akan menunggu tanda tangan Kades. Kades dapat menyetujui atau menolak dari menu yang sama.</div>
              <div><strong>Surat diterbitkan</strong> — setelah disetujui, surat dapat diunduh dalam format PDF oleh warga melalui portal mobile.</div>
            </div>
          </div>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-list-details text-slate-400"></i> Jenis Surat</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Tambah dan kelola jenis surat yang tersedia (SKCK, SKU, SKTM, dll.). Atur field formulir yang perlu diisi warga dan template isi surat yang akan dicetak.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ PASAR DESA ═══ --}}
    <section id="pasar" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-shopping-bag text-yellow-600 dark:text-yellow-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pasar Desa</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Penjual &amp; Produk</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-users-group text-slate-400"></i> Penjual</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Warga yang mengajukan diri sebagai penjual akan muncul di sini. Admin dapat <strong>menyetujui</strong> atau <strong>menolak</strong> pengajuan. Penjual yang disetujui dapat menambah produk ke pasar desa.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-shopping-bag text-slate-400"></i> Produk</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Lihat dan kelola semua produk yang ditawarkan penjual. Admin dapat mengaktifkan/menonaktifkan produk atau menghapus produk yang tidak sesuai.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ MASTER DATA ═══ --}}
    <section id="master" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-database text-slate-600 dark:text-slate-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Master Data</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Data Referensi Sistem</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3">Master data adalah data referensi yang digunakan oleh modul lain. Isi data ini terlebih dahulu sebelum menggunakan fitur terkait.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-sm">
            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="ti ti-folder mr-1 text-slate-400"></i> Kategori Arsip</p>
            <p class="text-xs text-slate-500">Kelompok dokumen arsip (SK, Perdes, Laporan, dll.)</p>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-sm">
            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="ti ti-tag mr-1 text-slate-400"></i> Kategori Berita</p>
            <p class="text-xs text-slate-500">Pengelompokan berita (Pembangunan, Sosial, Kesehatan, dll.)</p>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-sm">
            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="ti ti-id-badge mr-1 text-slate-400"></i> Jabatan</p>
            <p class="text-xs text-slate-500">Daftar jabatan perangkat desa untuk profil pejabat.</p>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-sm">
            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="ti ti-calendar-time mr-1 text-slate-400"></i> Periode</p>
            <p class="text-xs text-slate-500">Periode jabatan perangkat desa (tahun mulai–selesai).</p>
          </div>
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-sm sm:col-span-2">
            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="ti ti-user-star mr-1 text-slate-400"></i> Pejabat Desa</p>
            <p class="text-xs text-slate-500">Daftarkan nama pejabat, jabatan, dan periode bertugas. Data ini tampil di profil desa pada portal warga.</p>
          </div>
        </div>
      </div>
    </section>

    {{-- ═══ PENGGUNA ═══ --}}
    <section id="pengguna" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-user-circle text-indigo-600 dark:text-indigo-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Manajemen Pengguna</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Pengguna &amp; Role &amp; Akses</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-user-circle text-slate-400"></i> Pengguna</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Lihat semua pengguna</strong> — tampil daftar akun yang terdaftar di sistem, baik admin maupun warga.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Atur role</strong> — klik ikon pensil pada pengguna, pilih role yang sesuai (Administrator, Operator, Kades, dll.).</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Reset password</strong> — admin dapat mereset password pengguna yang lupa kata sandinya.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-2"><i class="ti ti-shield-lock text-slate-400"></i> Role &amp; Akses</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Buat role baru</strong> — klik <em>"+ Tambah Role"</em>, beri nama, lalu centang permission yang diizinkan untuk role tersebut.</span></li>
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span><strong>Permission</strong> menentukan menu dan fitur apa saja yang dapat diakses oleh pengguna dengan role tersebut. Role <em>Administrator</em> selalu memiliki akses penuh ke semua fitur.</span></li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ═══ PENGATURAN ═══ --}}
    <section id="pengaturan" class="scroll-mt-4" style="margin-top:56px">
      <div class="flex items-center gap-3" style="margin-bottom:12px">
        <div class="w-9 h-9 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ti ti-settings text-slate-600 dark:text-slate-400"></i>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Sistem</p>
          <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Pengaturan Sistem</h3>
        </div>
      </div>
      <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Pengaturan Desa</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Isi nama desa, kecamatan, kabupaten, provinsi, nomor telepon, email, dan visi misi desa. Upload logo desa yang akan tampil di portal warga dan akun warga.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Pengaturan API (Kecerdasan Buatan)</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Masukkan API Key dari layanan AI yang digunakan (Anthropic Claude atau OpenAI/OpenRouter). Tanpa konfigurasi ini, seluruh fitur AI tidak akan berfungsi.</span></li>
          </ul>
        </div>
        <div class="p-5">
          <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Pengaturan Fitur</h4>
          <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2">
            <li class="flex gap-2"><i class="ti ti-point-filled text-brand-500 mt-0.5 flex-shrink-0"></i><span>Aktifkan atau nonaktifkan fitur-fitur tertentu sesuai kebutuhan desa. Fitur yang dinonaktifkan tidak akan tampil di portal warga.</span></li>
          </ul>
        </div>
      </div>
    </section>

    <div style="height:32px"></div>

  </div>

@endsection

@push('scripts')
<script>
function setActive(el) {
  document.querySelectorAll('.nav-feat-item').forEach(function(a) {
    a.classList.remove('bg-brand-50','dark:bg-brand-500/10','text-brand-700','dark:text-brand-400','font-semibold');
  });
  el.classList.add('bg-brand-50','dark:bg-brand-500/10','text-brand-700','dark:text-brand-400','font-semibold');
}

// Highlight nav item berdasarkan section yang sedang terlihat
(function() {
  var sections = document.querySelectorAll('section[id]');
  var navLinks  = document.querySelectorAll('.nav-feat-item');
  if (!sections.length || !navLinks.length) return;

  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        navLinks.forEach(function(a) {
          a.classList.remove('bg-brand-50','text-brand-700','font-semibold');
        });
        var active = document.querySelector('.nav-feat-item[href="#' + entry.target.id + '"]');
        if (active) active.classList.add('bg-brand-50','text-brand-700','font-semibold');
      }
    });
  }, { rootMargin: '-20% 0px -70% 0px' });

  sections.forEach(function(s) { observer.observe(s); });
})();
</script>
@endpush
