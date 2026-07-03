<?php

use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\KartuKeluargaController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\LayananSuratController;
use App\Http\Controllers\Admin\SuratPengaturanController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KategoriArsipController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PejabatController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\PenjualController;
use App\Http\Controllers\Admin\ProdukAdminController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\IdmController;
use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\AiController;
use App\Http\Controllers\Admin\PosyanduController;
use App\Http\Controllers\Admin\BalitaController;
use App\Http\Controllers\Admin\IbuHamilController;
use App\Http\Controllers\Admin\JenisBansosController;
use App\Http\Controllers\Admin\PenerimaBansosController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PetaController;
use App\Http\Controllers\Warga\PortalController;
use App\Http\Controllers\Warga\ProdukController as WargaProdukController;
use App\Http\Controllers\Warga\RegisterController as WargaRegisterController;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/pengumuman', [PublicController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pengumuman/{id}', [PublicController::class, 'pengumumanShow'])->name('pengumuman.show')->whereNumber('id');

Route::get('/berita', [PublicController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/{slug}', [PublicController::class, 'beritaShow'])->name('berita.show');

Route::get('/arsip', [PublicController::class, 'arsip'])->name('arsip');
Route::get('/statistik', [PublicController::class, 'statistik'])->name('statistik');
Route::get('/idm', [PublicController::class, 'idm'])->name('idm');
Route::get('/pasar-desa', [PublicController::class, 'pasarDesa'])->name('pasar-desa');
Route::get('/profil-desa', [PublicController::class, 'profilDesa'])->name('profil-desa');

// ── Notifikasi (admin & warga) ────────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get  ('/',          [NotificationController::class, 'index'])      ->name('index');
    Route::get  ('/count',     [NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::get  ('/dropdown',  [NotificationController::class, 'dropdown'])   ->name('dropdown');
    Route::post ('/{id}/read', [NotificationController::class, 'markRead'])   ->name('read');
    Route::post ('/read-all',  [NotificationController::class, 'readAll'])    ->name('read-all');
});

Route::middleware(['auth'])->prefix('portal')->name('portal')->group(function () {
    Route::get ('/',        [PortalController::class, 'beranda']) ->name('');           // route('portal')
    Route::get ('/layanan', [PortalController::class, 'layanan']) ->name('.layanan');
    Route::get ('/berita',  [PortalController::class, 'berita'])  ->name('.berita');
    Route::get ('/akun',    [PortalController::class, 'akun'])    ->name('.akun');
    Route::get ('/produk',  [PortalController::class, 'produk'])  ->name('.produk');

    Route::get ('/prediksi-stunting',          [PortalController::class, 'prediksiStunting'])         ->name('.prediksi-stunting');
    Route::post('/prediksi-stunting/generate', [PortalController::class, 'prediksiStuntingGenerate']) ->name('.prediksi-stunting.generate');

    Route::post('/laporan',             [PortalController::class, 'laporanStore'])    ->name('.laporan.store');
    Route::delete('/laporan/{laporan}', [PortalController::class, 'laporanDestroy']) ->name('.laporan.destroy');

    Route::get  ('/akun/edit',     [PortalController::class, 'akunEdit'])       ->name('.akun.edit');
    Route::patch('/akun/profil',   [PortalController::class, 'updateProfil'])   ->name('.akun.profil');
    Route::patch('/akun/password', [PortalController::class, 'updatePassword']) ->name('.akun.password');

    Route::get ('/notifikasi',     [PortalController::class, 'notifikasi'])     ->name('.notifikasi');
    Route::post('/surat',          [WargaSuratController::class, 'store'])       ->name('.surat.store');
    Route::get ('/surat/{id}/pdf', [WargaSuratController::class, 'downloadPdf']) ->name('.surat.pdf');
    Route::post  ('/produk/ajukan',       [WargaProdukController::class, 'ajukan'])  ->name('.produk.ajukan');
    Route::post  ('/produk',              [WargaProdukController::class, 'store'])   ->name('.produk.store');
    Route::put   ('/produk/{produk}',        [WargaProdukController::class, 'update'])  ->name('.produk.update');
    Route::patch ('/produk/{produk}/toggle', [WargaProdukController::class, 'toggle'])  ->name('.produk.toggle');
    Route::delete('/produk/{produk}',        [WargaProdukController::class, 'destroy']) ->name('.produk.destroy');
});

Route::get('/verifikasi/{token}', [LayananSuratController::class, 'verifikasi'])->name('surat.verifikasi');

// ── Pendaftaran Warga (publik, hanya untuk guest) ─────────────────────────────
Route::middleware('guest')->prefix('daftar')->name('warga.register')->group(function () {
    Route::get ('/',        [WargaRegisterController::class, 'step1'])               ->name('');
    Route::post('/',        [WargaRegisterController::class, 'verifikasiIdentitas']) ->name('.verifikasi')  ->middleware('throttle:5,1');
    Route::get ('/akun',    [WargaRegisterController::class, 'step2'])               ->name('.akun');
    Route::post('/akun',    [WargaRegisterController::class, 'simpanAkun'])          ->name('.simpan')      ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    // Dashboard — nama tetap 'dashboard' agar redirect login tidak berubah
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Panduan Penggunaan
    Route::get('/panduan', fn() => view('admin.panduan'))->name('admin.panduan');

    // Sub-route dengan prefix nama 'admin.'
    Route::name('admin.')->group(function () {

        // Pengguna
        Route::get('/pengguna',             [UserController::class, 'index'])       ->middleware('perm:lihat.pengguna')  ->name('users.index');
        Route::post('/pengguna',            [UserController::class, 'store'])       ->middleware('perm:tambah.pengguna') ->name('users.store');
        Route::delete('/pengguna/{user}',   [UserController::class, 'destroy'])     ->middleware('perm:hapus.pengguna')  ->name('users.destroy');
        Route::get('/pengguna/{user}/role', [UserController::class, 'editRoles'])   ->middleware('perm:edit.pengguna')   ->name('users.roles');
        Route::put('/pengguna/{user}/role', [UserController::class, 'updateRoles']) ->middleware('perm:edit.pengguna')   ->name('users.roles.update');

        // Role & Akses
        Route::get('/role',                 [RoleController::class, 'index'])             ->middleware('perm:lihat.role')  ->name('roles.index');
        Route::post('/role',                [RoleController::class, 'store'])             ->middleware('perm:tambah.role') ->name('roles.store');
        Route::delete('/role/{role}',       [RoleController::class, 'destroy'])           ->middleware('perm:hapus.role')  ->name('roles.destroy');
        Route::get('/role/{role}/akses',    [RoleController::class, 'editPermissions'])   ->middleware('perm:edit.role')   ->name('roles.permissions');
        Route::put('/role/{role}/akses',    [RoleController::class, 'updatePermissions']) ->middleware('perm:edit.role')   ->name('roles.permissions.update');

        // Page
        Route::get   ('/page',               [PageController::class, 'index'])  ->middleware('perm:lihat.page')   ->name('page.index');
        Route::get   ('/page/buat',          [PageController::class, 'create']) ->middleware('perm:tambah.page')  ->name('page.create');
        Route::post  ('/page',               [PageController::class, 'store'])  ->middleware('perm:tambah.page')  ->name('page.store');
        Route::get   ('/page/{page}/edit',   [PageController::class, 'edit'])   ->middleware('perm:edit.page')    ->name('page.edit');
        Route::put   ('/page/{page}',        [PageController::class, 'update']) ->middleware('perm:edit.page')    ->name('page.update');
        Route::delete('/page/{page}',        [PageController::class, 'destroy'])->middleware('perm:hapus.page')   ->name('page.destroy');

        // Kependudukan
        Route::get   ('/penduduk',                      [PendudukController::class, 'index'])  ->middleware('perm:lihat.penduduk')   ->name('penduduk.index');
        Route::post  ('/penduduk',                      [PendudukController::class, 'store'])  ->middleware('perm:tambah.penduduk')  ->name('penduduk.store');
        Route::get   ('/penduduk/cari',                 [PendudukController::class, 'cari'])   ->middleware('perm:lihat.penduduk')   ->name('penduduk.cari');
        Route::get   ('/penduduk/{penduduk}',           [PendudukController::class, 'show'])   ->middleware('perm:lihat.penduduk')   ->name('penduduk.show');
        Route::put   ('/penduduk/{penduduk}',           [PendudukController::class, 'update']) ->middleware('perm:edit.penduduk')    ->name('penduduk.update');
        Route::delete('/penduduk/{penduduk}',           [PendudukController::class, 'destroy'])->middleware('perm:hapus.penduduk')   ->name('penduduk.destroy');
        Route::get   ('/kartu-keluarga',                [KartuKeluargaController::class, 'index'])  ->middleware('perm:lihat.kartu-keluarga')   ->name('kk.index');
        Route::post  ('/kartu-keluarga',                [KartuKeluargaController::class, 'store'])  ->middleware('perm:tambah.kartu-keluarga')  ->name('kk.store');
        Route::get   ('/kartu-keluarga/{kartuKeluarga}',[KartuKeluargaController::class, 'show'])   ->middleware('perm:lihat.kartu-keluarga')   ->name('kk.show');
        Route::put   ('/kartu-keluarga/{kartuKeluarga}',[KartuKeluargaController::class, 'update']) ->middleware('perm:edit.kartu-keluarga')    ->name('kk.update');
        Route::delete('/kartu-keluarga/{kartuKeluarga}',[KartuKeluargaController::class, 'destroy'])->middleware('perm:hapus.kartu-keluarga')   ->name('kk.destroy');

        // Layanan Surat
        Route::get   ('/layanan-surat',                                [LayananSuratController::class, 'index'])        ->middleware('perm:lihat.layanan-surat')   ->name('layanan-surat.index');
        Route::post  ('/layanan-surat',                                [LayananSuratController::class, 'store'])        ->middleware('perm:buat.layanan-surat')    ->name('layanan-surat.store');
        Route::get   ('/layanan-surat/{layananSurat}/nomor-otomatis',  [LayananSuratController::class, 'nomorOtomatis'])->middleware('perm:lihat.layanan-surat')   ->name('layanan-surat.nomor-otomatis');
        Route::get   ('/layanan-surat/{layananSurat}/pdf',             [LayananSuratController::class, 'downloadPdf'])  ->middleware('perm:lihat.layanan-surat')   ->name('layanan-surat.pdf');
        Route::put   ('/layanan-surat/{layananSurat}',                 [LayananSuratController::class, 'update'])       ->middleware('perm:proses.layanan-surat')  ->name('layanan-surat.update');
        Route::delete('/layanan-surat/{layananSurat}',                 [LayananSuratController::class, 'destroy'])      ->middleware('perm:hapus.layanan-surat')   ->name('layanan-surat.destroy');

        // Pengaturan Surat
        Route::get ('/surat/pengaturan',              [SuratPengaturanController::class, 'index'])        ->middleware('perm:lihat.surat-pengaturan') ->name('surat.pengaturan.index');
        Route::put ('/surat/pengaturan',              [SuratPengaturanController::class, 'update'])       ->middleware('perm:edit.surat-pengaturan')  ->name('surat.pengaturan.update');
        Route::post('/surat/pengaturan/preview-nomor',[SuratPengaturanController::class, 'previewNomor']) ->middleware('perm:lihat.surat-pengaturan') ->name('surat.pengaturan.preview-nomor');

        // Jenis Surat
        Route::get ('/surat/jenis',                     [JenisSuratController::class, 'index'])  ->middleware('perm:lihat.surat-jenis')   ->name('surat.jenis.index');
        Route::post('/surat/jenis',                     [JenisSuratController::class, 'store'])  ->middleware('perm:tambah.surat-jenis')  ->name('surat.jenis.store');
        Route::put ('/surat/jenis/{jenisSurat}',        [JenisSuratController::class, 'update']) ->middleware('perm:edit.surat-jenis')    ->name('surat.jenis.update');
        Route::post('/surat/jenis/{jenisSurat}/toggle', [JenisSuratController::class, 'toggle']) ->middleware('perm:edit.surat-jenis')    ->name('surat.jenis.toggle');

        // Arsip
        Route::get   ('/arsip',               [ArsipController::class, 'index'])  ->middleware('perm:lihat.arsip') ->name('arsip.index');
        Route::post  ('/arsip',               [ArsipController::class, 'store'])  ->middleware('perm:tambah.arsip')->name('arsip.store');
        Route::put   ('/arsip/{arsip}',       [ArsipController::class, 'update']) ->middleware('perm:edit.arsip')  ->name('arsip.update');
        Route::delete('/arsip/{arsip}',       [ArsipController::class, 'destroy'])->middleware('perm:hapus.arsip') ->name('arsip.destroy');

        // Pasar Desa — Penjual
        Route::get   ('/pasar-desa/penjual',                          [PenjualController::class, 'index'])  ->middleware('perm:lihat.pasar-desa')    ->name('penjual.index');
        Route::post  ('/pasar-desa/penjual/{pengajuan}/setujui',      [PenjualController::class, 'setujui'])->middleware('perm:setujui.pasar-desa')  ->name('penjual.setujui');
        Route::post  ('/pasar-desa/penjual/{pengajuan}/tolak',        [PenjualController::class, 'tolak'])  ->middleware('perm:setujui.pasar-desa')  ->name('penjual.tolak');

        // Pasar Desa — Produk
        Route::get   ('/pasar-desa/produk',                           [ProdukAdminController::class, 'index'])->middleware('perm:lihat.pasar-desa')->name('produk.index');

        // IDM
        Route::get   ('/idm',              [IdmController::class, 'index'])  ->middleware('perm:lihat.idm')   ->name('idm.index');
        Route::post  ('/idm',              [IdmController::class, 'store'])  ->middleware('perm:tambah.idm')  ->name('idm.store');
        Route::post  ('/idm/import',       [IdmController::class, 'import']) ->middleware('perm:tambah.idm')  ->name('idm.import');
        Route::put   ('/idm/{idm}',        [IdmController::class, 'update']) ->middleware('perm:edit.idm')    ->name('idm.update');
        Route::delete('/idm/{idm}',        [IdmController::class, 'destroy'])->middleware('perm:hapus.idm')   ->name('idm.destroy');

        // Pengumuman
        Route::get   ('/pengumuman',                     [AdminPengumumanController::class, 'index'])  ->middleware('perm:lihat.pengumuman')   ->name('pengumuman.index');
        Route::post  ('/pengumuman',                     [AdminPengumumanController::class, 'store'])  ->middleware('perm:tambah.pengumuman')  ->name('pengumuman.store');
        Route::put   ('/pengumuman/{pengumuman}',        [AdminPengumumanController::class, 'update']) ->middleware('perm:edit.pengumuman')    ->name('pengumuman.update');
        Route::delete('/pengumuman/{pengumuman}',        [AdminPengumumanController::class, 'destroy'])->middleware('perm:hapus.pengumuman')   ->name('pengumuman.destroy');

        // Berita
        Route::get   ('/berita',               [BeritaController::class, 'index'])  ->middleware('perm:lihat.berita')   ->name('berita.index');
        Route::get   ('/berita/buat',          [BeritaController::class, 'create']) ->middleware('perm:tambah.berita')  ->name('berita.create');
        Route::post  ('/berita',               [BeritaController::class, 'store'])  ->middleware('perm:tambah.berita')  ->name('berita.store');
        Route::get   ('/berita/{berita}/edit', [BeritaController::class, 'edit'])   ->middleware('perm:edit.berita')    ->name('berita.edit');
        Route::put   ('/berita/{berita}',      [BeritaController::class, 'update']) ->middleware('perm:edit.berita')    ->name('berita.update');
        Route::delete('/berita/{berita}',      [BeritaController::class, 'destroy'])->middleware('perm:hapus.berita')   ->name('berita.destroy');

        // Master Data
        Route::prefix('master')->name('master.')->group(function () {
            // Kategori Arsip
            Route::get   ('/kategori-arsip',                    [KategoriArsipController::class, 'index'])  ->middleware('perm:lihat.kategori-arsip')   ->name('kategoriArsip.index');
            Route::post  ('/kategori-arsip',                    [KategoriArsipController::class, 'store'])  ->middleware('perm:tambah.kategori-arsip')  ->name('kategoriArsip.store');
            Route::put   ('/kategori-arsip/{kategoriArsip}',    [KategoriArsipController::class, 'update']) ->middleware('perm:edit.kategori-arsip')    ->name('kategoriArsip.update');
            Route::delete('/kategori-arsip/{kategoriArsip}',    [KategoriArsipController::class, 'destroy'])->middleware('perm:hapus.kategori-arsip')   ->name('kategoriArsip.destroy');

            // Kategori
            Route::get   ('/kategori',            [KategoriController::class, 'index'])  ->middleware('perm:lihat.kategori')  ->name('kategori.index');
            Route::post  ('/kategori',            [KategoriController::class, 'store'])  ->middleware('perm:tambah.kategori') ->name('kategori.store');
            Route::put   ('/kategori/{kategori}', [KategoriController::class, 'update']) ->middleware('perm:edit.kategori')   ->name('kategori.update');
            Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->middleware('perm:hapus.kategori')  ->name('kategori.destroy');

            // Jabatan
            Route::get   ('/jabatan',           [JabatanController::class, 'index'])  ->middleware('perm:lihat.jabatan')  ->name('jabatan.index');
            Route::post  ('/jabatan',           [JabatanController::class, 'store'])  ->middleware('perm:tambah.jabatan') ->name('jabatan.store');
            Route::put   ('/jabatan/{jabatan}', [JabatanController::class, 'update']) ->middleware('perm:edit.jabatan')   ->name('jabatan.update');
            Route::delete('/jabatan/{jabatan}', [JabatanController::class, 'destroy'])->middleware('perm:hapus.jabatan')  ->name('jabatan.destroy');

            // Periode
            Route::get   ('/periode',           [PeriodeController::class, 'index'])  ->middleware('perm:lihat.periode')  ->name('periode.index');
            Route::post  ('/periode',           [PeriodeController::class, 'store'])  ->middleware('perm:tambah.periode') ->name('periode.store');
            Route::put   ('/periode/{periode}', [PeriodeController::class, 'update']) ->middleware('perm:edit.periode')   ->name('periode.update');
            Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->middleware('perm:hapus.periode')  ->name('periode.destroy');

            // Pejabat Desa
            Route::get   ('/pejabat',           [PejabatController::class, 'index'])  ->middleware('perm:lihat.pejabat')  ->name('pejabat.index');
            Route::post  ('/pejabat',           [PejabatController::class, 'store'])  ->middleware('perm:tambah.pejabat') ->name('pejabat.store');
            Route::put   ('/pejabat/{pejabat}', [PejabatController::class, 'update']) ->middleware('perm:edit.pejabat')   ->name('pejabat.update');
            Route::delete('/pejabat/{pejabat}', [PejabatController::class, 'destroy'])->middleware('perm:hapus.pejabat')  ->name('pejabat.destroy');
        });

        // Kesehatan
        Route::prefix('kesehatan')->name('kesehatan.')->group(function () {
            // Posyandu
            Route::get   ('/posyandu',              [PosyanduController::class, 'index'])         ->middleware('perm:lihat.posyandu')  ->name('posyandu.index');
            Route::get   ('/kegiatan',              [PosyanduController::class, 'indexKegiatan']) ->middleware('perm:lihat.posyandu')  ->name('kegiatan.index');
            Route::post  ('/posyandu',              [PosyanduController::class, 'store'])  ->middleware('perm:tambah.posyandu') ->name('posyandu.store');
            Route::put   ('/posyandu/{posyandu}',   [PosyanduController::class, 'update']) ->middleware('perm:edit.posyandu')   ->name('posyandu.update');
            Route::delete('/posyandu/{posyandu}',                      [PosyanduController::class, 'destroy'])        ->middleware('perm:hapus.posyandu')  ->name('posyandu.destroy');
            Route::post  ('/posyandu/{posyandu}/kegiatan',             [PosyanduController::class, 'storeKegiatan'])   ->middleware('perm:tambah.posyandu') ->name('posyandu.kegiatan.store');
            Route::delete('/posyandu-kegiatan/{kegiatan}',             [PosyanduController::class, 'destroyKegiatan']) ->middleware('perm:hapus.posyandu')  ->name('posyandu.kegiatan.destroy');
            Route::get   ('/posyandu/{posyandu}/kegiatan/{kegiatan}',  [PosyanduController::class, 'showKegiatan'])    ->middleware('perm:lihat.posyandu')  ->name('posyandu.kegiatan.show');
            Route::post  ('/posyandu-kegiatan/{kegiatan}/rekam',       [PosyanduController::class, 'storeRekam'])      ->middleware('perm:tambah.posyandu') ->name('posyandu.kegiatan.rekam.store');
            Route::delete('/posyandu-rekam/{rekam}',                   [PosyanduController::class, 'destroyRekam'])    ->middleware('perm:hapus.posyandu')  ->name('posyandu.kegiatan.rekam.destroy');

            // Balita
            Route::get   ('/balita',                          [BalitaController::class, 'index'])       ->middleware('perm:lihat.balita')  ->name('balita.index');
            Route::post  ('/balita',                          [BalitaController::class, 'store'])       ->middleware('perm:tambah.balita') ->name('balita.store');
            Route::put   ('/balita/{balita}',                 [BalitaController::class, 'update'])      ->middleware('perm:edit.balita')   ->name('balita.update');
            Route::delete('/balita/{balita}',                 [BalitaController::class, 'destroy'])     ->middleware('perm:hapus.balita')  ->name('balita.destroy');
            Route::post  ('/balita/{balita}/rekam',           [BalitaController::class, 'storeRekam'])  ->middleware('perm:tambah.balita') ->name('balita.rekam.store');
            Route::delete('/timbang/{timbang}',               [BalitaController::class, 'destroyRekam'])->middleware('perm:hapus.balita')  ->name('balita.rekam.destroy');

            // Ibu Hamil
            Route::get   ('/ibu-hamil',                       [IbuHamilController::class, 'index'])            ->middleware('perm:lihat.ibu-hamil')  ->name('ibuHamil.index');
            Route::post  ('/ibu-hamil',                       [IbuHamilController::class, 'store'])            ->middleware('perm:tambah.ibu-hamil') ->name('ibuHamil.store');
            Route::put   ('/ibu-hamil/{ibuHamil}',            [IbuHamilController::class, 'update'])           ->middleware('perm:edit.ibu-hamil')   ->name('ibuHamil.update');
            Route::delete('/ibu-hamil/{ibuHamil}',            [IbuHamilController::class, 'destroy'])          ->middleware('perm:hapus.ibu-hamil')  ->name('ibuHamil.destroy');
            Route::post  ('/ibu-hamil/{ibuHamil}/kunjungan',  [IbuHamilController::class, 'storeKunjungan'])   ->middleware('perm:tambah.ibu-hamil') ->name('ibuHamil.kunjungan.store');
            Route::delete('/kunjungan/{kunjungan}',           [IbuHamilController::class, 'destroyKunjungan']) ->middleware('perm:hapus.ibu-hamil')  ->name('ibuHamil.kunjungan.destroy');
        });

        // AI
        Route::get ('/ai/sambutan',              [AiController::class, 'sambutan'])        ->middleware('perm:akses.ai')->name('ai.sambutan');
        Route::get ('/ai/perdes',                [AiController::class, 'perdes'])          ->middleware('perm:akses.ai')->name('ai.perdes');
        Route::get ('/ai/surat',                 [AiController::class, 'surat'])           ->middleware('perm:akses.ai')->name('ai.surat');
        Route::get ('/ai/perkades',              [AiController::class, 'perkades'])        ->middleware('perm:akses.ai')->name('ai.perkades');
        Route::get ('/ai/prediksi-stunting',     [AiController::class, 'prediksiStunting'])->middleware('perm:akses.ai')->name('ai.prediksi-stunting');
        Route::post  ('/ai/generate',            [AiController::class, 'generate'])        ->middleware('perm:akses.ai')->name('ai.generate');
        Route::delete('/ai/riwayat/{aiRiwayat}', [AiController::class, 'destroyRiwayat']) ->middleware('perm:akses.ai')->name('ai.riwayat.destroy');

        // APBDes
        Route::get    ('/apbdes',                     [ApbdesController::class, 'index'])     ->middleware('perm:lihat.apbdes')   ->name('apbdes.index');
        Route::post   ('/apbdes',                     [ApbdesController::class, 'store'])     ->middleware('perm:tambah.apbdes')  ->name('apbdes.store');
        Route::get    ('/apbdes/{apbdes}',            [ApbdesController::class, 'show'])      ->middleware('perm:lihat.apbdes')   ->name('apbdes.show');
        Route::put    ('/apbdes/{apbdes}',            [ApbdesController::class, 'update'])    ->middleware('perm:edit.apbdes')    ->name('apbdes.update');
        Route::delete ('/apbdes/{apbdes}',            [ApbdesController::class, 'destroy'])   ->middleware('perm:hapus.apbdes')   ->name('apbdes.destroy');
        Route::post   ('/apbdes/{apbdes}/pos',        [ApbdesController::class, 'storePos'])  ->middleware('perm:tambah.apbdes')  ->name('apbdes.pos.store');
        Route::put    ('/apbdes/{apbdes}/pos/{pos}',  [ApbdesController::class, 'updatePos']) ->middleware('perm:edit.apbdes')    ->name('apbdes.pos.update');
        Route::delete ('/apbdes/{apbdes}/pos/{pos}',  [ApbdesController::class, 'destroyPos'])->middleware('perm:hapus.apbdes')   ->name('apbdes.pos.destroy');

        // Peta Desa (GIS)
        Route::get   ('/peta',                              [PetaController::class, 'index'])          ->middleware('perm:lihat.peta')  ->name('peta.index');
        Route::get   ('/peta/lokasi',                       [PetaController::class, 'lokasiJson'])     ->middleware('perm:lihat.peta')  ->name('peta.lokasi');
        Route::post  ('/peta/lokasi',                       [PetaController::class, 'storeLokasi'])    ->middleware('perm:tambah.peta') ->name('peta.lokasi.store');
        Route::put   ('/peta/lokasi/{petaLokasi}',          [PetaController::class, 'updateLokasi'])   ->middleware('perm:edit.peta')   ->name('peta.lokasi.update');
        Route::delete('/peta/lokasi/{petaLokasi}',          [PetaController::class, 'destroyLokasi'])  ->middleware('perm:hapus.peta')  ->name('peta.lokasi.destroy');
        Route::get   ('/peta/wilayah',                      [PetaController::class, 'wilayahJson'])    ->middleware('perm:lihat.peta')  ->name('peta.wilayah');
        Route::post  ('/peta/wilayah',                      [PetaController::class, 'storeWilayah'])   ->middleware('perm:tambah.peta') ->name('peta.wilayah.store');
        Route::put   ('/peta/wilayah/{petaWilayah}',        [PetaController::class, 'updateWilayah'])  ->middleware('perm:edit.peta')   ->name('peta.wilayah.update');
        Route::delete('/peta/wilayah/{petaWilayah}',        [PetaController::class, 'destroyWilayah']) ->middleware('perm:hapus.peta')  ->name('peta.wilayah.destroy');
        Route::get   ('/peta/kategori',                     [PetaController::class, 'kategoriJson'])   ->middleware('perm:lihat.peta')  ->name('peta.kategori');
        Route::post  ('/peta/kategori',                     [PetaController::class, 'storeKategori'])  ->middleware('perm:tambah.peta') ->name('peta.kategori.store');
        Route::put   ('/peta/kategori/{petaKategori}',      [PetaController::class, 'updateKategori']) ->middleware('perm:edit.peta')   ->name('peta.kategori.update');
        Route::delete('/peta/kategori/{petaKategori}',      [PetaController::class, 'destroyKategori'])->middleware('perm:hapus.peta')  ->name('peta.kategori.destroy');

        // Bantuan Sosial — Jenis
        Route::get   ('/bansos/jenis',                   [JenisBansosController::class, 'index'])  ->middleware('perm:lihat.bansos')   ->name('bansos.jenis.index');
        Route::post  ('/bansos/jenis',                   [JenisBansosController::class, 'store'])  ->middleware('perm:tambah.bansos')  ->name('bansos.jenis.store');
        Route::put   ('/bansos/jenis/{jenisBanso}',      [JenisBansosController::class, 'update']) ->middleware('perm:edit.bansos')    ->name('bansos.jenis.update');
        Route::delete('/bansos/jenis/{jenisBanso}',      [JenisBansosController::class, 'destroy'])->middleware('perm:hapus.bansos')   ->name('bansos.jenis.destroy');
        Route::post  ('/bansos/jenis/{jenisBanso}/toggle',[JenisBansosController::class,'toggle']) ->middleware('perm:edit.bansos')    ->name('bansos.jenis.toggle');

        // Bantuan Sosial — Penerima
        Route::get   ('/bansos',                         [PenerimaBansosController::class, 'index'])  ->middleware('perm:lihat.bansos')   ->name('bansos.index');
        Route::post  ('/bansos',                         [PenerimaBansosController::class, 'store'])  ->middleware('perm:tambah.bansos')  ->name('bansos.store');
        Route::get   ('/bansos/cari-penduduk',           [PenerimaBansosController::class, 'cariPenduduk'])->middleware('perm:lihat.bansos')->name('bansos.cari-penduduk');
        Route::get   ('/bansos/{penerimaBanso}',         [PenerimaBansosController::class, 'show'])   ->middleware('perm:lihat.bansos')   ->name('bansos.show');
        Route::put   ('/bansos/{penerimaBanso}',         [PenerimaBansosController::class, 'update']) ->middleware('perm:edit.bansos')    ->name('bansos.update');
        Route::delete('/bansos/{penerimaBanso}',         [PenerimaBansosController::class, 'destroy'])->middleware('perm:hapus.bansos')   ->name('bansos.destroy');

        // Bantuan Sosial — Penyaluran
        Route::post  ('/bansos/{penerimaBanso}/penyaluran',                       [PenerimaBansosController::class, 'salurkan'])         ->middleware('perm:edit.bansos')->name('bansos.salurkan');
        Route::put   ('/bansos/{penerimaBanso}/penyaluran/{penyaluran}',          [PenerimaBansosController::class, 'updatePenyaluran']) ->middleware('perm:edit.bansos')->name('bansos.penyaluran.update');
        Route::delete('/bansos/{penerimaBanso}/penyaluran/{penyaluran}',          [PenerimaBansosController::class, 'destroyPenyaluran'])->middleware('perm:edit.bansos')->name('bansos.penyaluran.destroy');

        // Laporan Warga
        Route::get   ('/laporan',               [LaporanController::class, 'index'])   ->name('laporan.index');
        Route::get   ('/laporan/{laporan}',      [LaporanController::class, 'show'])    ->name('laporan.show');
        Route::put   ('/laporan/{laporan}',      [LaporanController::class, 'update'])  ->name('laporan.update');
        Route::delete('/laporan/{laporan}',      [LaporanController::class, 'destroy']) ->name('laporan.destroy');

        // Pengaturan
        Route::get   ('/pengaturan',              [SettingController::class, 'index'])      ->middleware('perm:lihat.pengaturan')->name('settings.index');
        Route::post  ('/pengaturan/desa',         [SettingController::class, 'updateDesa']) ->middleware('perm:edit.pengaturan') ->name('settings.desa');
        Route::post  ('/pengaturan/api',          [SettingController::class, 'updateApi'])  ->middleware('perm:edit.pengaturan') ->name('settings.api');
        Route::post  ('/pengaturan/fitur',        [SettingController::class, 'updateFitur'])  ->middleware('perm:edit.pengaturan') ->name('settings.fitur');
        Route::post  ('/pengaturan/header',       [SettingController::class, 'updateHeader']) ->middleware('perm:edit.pengaturan') ->name('settings.header');
        Route::post  ('/pengaturan/profil',       [SettingController::class, 'updateProfil'])->middleware('perm:edit.pengaturan') ->name('settings.profil');

        // Navbar
        Route::post  ('/pengaturan/navbar',          [NavItemController::class, 'store'])  ->middleware('perm:edit.pengaturan')->name('navbar.store');
        Route::put   ('/pengaturan/navbar/{navItem}', [NavItemController::class, 'update']) ->middleware('perm:edit.pengaturan')->name('navbar.update');
        Route::delete('/pengaturan/navbar/{navItem}', [NavItemController::class, 'destroy'])->middleware('perm:edit.pengaturan')->name('navbar.destroy');
    });
});

require __DIR__.'/auth.php';

// Halaman statis — harus paling akhir agar tidak shadow route spesifik (termasuk /login, /register, dll)
Route::get('/{slug}', [PublicController::class, 'page'])->name('page.show');
