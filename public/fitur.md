# Daftar Fitur Sistem Web Desa

> Platform manajemen desa berbasis Laravel 11  
> Tiga area: **Publik** · **Admin Panel** · **Portal Warga**

---

## AREA PUBLIK

| Halaman | URL | Keterangan |
|---|---|---|
| Beranda | `/` | Hero slider, sambutan kades, statistik, berita, pengumuman, layanan |
| Berita | `/berita` | Listing + detail, filter kategori, pencarian |
| Pengumuman | `/pengumuman` | Listing + detail |
| Arsip Dokumen | `/arsip` | Filter kategori, unduh file |
| Statistik Desa | `/statistik` | Grafik demografi, APBDes, layanan surat, konten |
| IDM | `/idm` | Indeks Desa Membangun, tren chart, 50 indikator IKS/IKE/IKL |
| Pasar Desa | `/pasar-desa` | Produk warga, pencarian, tombol WA penjual |
| Profil Desa | `/profil-desa` | Sambutan, visi misi, struktur organisasi |
| Halaman Statis | `/{slug}` | Halaman CMS custom |
| Verifikasi Surat | `/verifikasi/{token}` | Verifikasi keaslian surat via QR/token |

---

## ADMIN PANEL (`/portal/admin/`)

### Kependudukan
- Data Penduduk — CRUD + detail per warga
- Kartu Keluarga — CRUD KK + relasi anggota

### Layanan Surat
- Pengajuan Surat — Lihat, proses, setujui, cetak PDF, nomor otomatis
- Jenis Surat — Template dengan variabel dinamis
- Pengaturan Surat — Format nomor, header, footer

### Konten
- Berita — CRUD + Quill editor (tabel, source code, SEO)
- Pengumuman — CRUD + Quill editor
- Halaman (Page) — CMS + Quill editor + SEO meta
- Arsip Dokumen — CRUD + upload file

### Keuangan & Data Desa
- APBDes — Anggaran & realisasi per tahun (pendapatan, belanja, pembiayaan)
- IDM — Input tahunan, 50 indikator, status IDM
- Bantuan Sosial — Jenis bansos, penerima, penyaluran per periode
- Laporan Warga — Pengaduan masyarakat + tindak lanjut

### Kesehatan (Posyandu)
- Posyandu — Master data posyandu
- Balita — Data balita + riwayat timbang (BB, TB, status gizi)
- Ibu Hamil — Data bumil + kunjungan ANC
- Kegiatan Posyandu — Jadwal & rekam kegiatan

### Peta & GIS
- Peta Desa — Titik lokasi (marker), wilayah (polygon), kategori; Leaflet.js

### Pasar Desa
- Penjual — Persetujuan/tolak pendaftaran penjual
- Produk — Kelola produk warga yang aktif

### Master Data
- Jabatan, Periode, Pejabat — Struktur organisasi
- Kategori Berita, Kategori Arsip

### Sistem & Akses
- Pengguna — CRUD akun + assign role
- Role & Permission — Kelola role, permission granular per role
- Notifikasi — Notifikasi real-time (pengajuan surat, penjual baru)

### Pengaturan Sistem (7 tab)
- Tampilan — Tema & warna brand
- API — Kunci API provider AI (Anthropic, OpenAI, OpenRouter, Gemini)
- Info Desa — Nama, kontak, logo
- Navbar — Menu navigasi publik
- Header — Slide hero beranda
- Profil Desa — Sambutan, visi, misi
- Fitur — Toggle grafik statistik publik

### AI Desa
- Penyusun Perdes — Draft peraturan desa
- Penyusun Perkades — Draft peraturan kepala desa
- Generator Sambutan — Sambutan/pidato kepala desa
- Asisten Surat — Bantu tulis surat dinas
- Prediksi Stunting — Analisis AI risiko stunting balita

---

## PORTAL WARGA (`/portal/`)

| Fitur | Keterangan |
|---|---|
| Registrasi | 2 step: verifikasi NIK/KK → buat akun |
| Beranda | Info desa, layanan, berita, riwayat surat |
| Ajukan Surat | Form online berbagai jenis surat |
| Unduh PDF | Surat yang sudah disetujui |
| Pasar Desa | Kelola produk sendiri, ajukan sebagai penjual |
| Prediksi Stunting | Analisis AI risiko stunting untuk orang tua |
| Laporan/Pengaduan | Submit laporan ke desa |
| Profil Akun | Edit nama, email, password |
| Notifikasi | Status surat & produk |

---

## TEKNIS

- **Framework**: Laravel 11, Blade, Tailwind CSS
- **Auth**: Role-based permission (5 role: administrator, kepala_desa, operator, editor, viewer)
- **Editor**: Quill 2.0.3 (tabel picker, source code HTML mode)
- **PDF**: DomPDF — template blade per jenis surat
- **AI**: Multi-provider (Anthropic, OpenAI, OpenRouter, Gemini)
- **Storage**: `php artisan storage:link`
- **Deploy**: `php artisan migrate --seed` → admin `admin@gmail.com` / `password`
