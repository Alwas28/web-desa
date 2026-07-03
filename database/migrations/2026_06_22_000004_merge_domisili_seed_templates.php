<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /* ── Template isi surat per jenis ──────────────────────────────────── */
    private array $templates = [
        'Surat Keterangan Tidak Mampu' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami yang berdasarkan data yang ada tergolong dalam keluarga TIDAK MAMPU / kurang mampu secara ekonomi.

Surat keterangan ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Usaha' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami dan yang bersangkutan benar-benar menjalankan usaha di alamat tersebut di atas.

Jenis usaha / keterangan: {keterangan}

Surat keterangan ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Kelahiran' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa pada hari ini telah lahir seorang bayi:

Nama Anak        : {nama}
Jenis Kelamin    : {jenis_kelamin}
Hari / Tanggal   : {tanggal_lahir}
Tempat Lahir     : {tempat_lahir}

Keterangan orang tua / penolong persalinan:
{keterangan}

Alamat           : {alamat}

Surat keterangan kelahiran ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Kematian' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami yang telah meninggal dunia.

Keterangan waktu / tempat kematian:
{keterangan}

Surat keterangan kematian ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Pindah' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat Asal      : {alamat}

adalah benar warga desa kami yang akan pindah ke alamat tujuan:

{keterangan}

Surat keterangan pindah ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Pengantar' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami, bermaksud untuk: {keperluan}

{keterangan}

Demikian surat pengantar ini dibuat dan diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Menikah' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami dan yang bersangkutan telah melangsungkan pernikahan yang sah.

Keterangan pernikahan:
{keterangan}

Surat keterangan ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Belum Menikah' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami dan sepengetahuan kami yang bersangkutan BELUM PERNAH MENIKAH dan saat ini masih berstatus lajang / belum kawin.

Surat keterangan ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Pengantar SKCK' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami yang berkelakuan baik dan sepengetahuan kami tidak pernah terlibat dalam tindak pidana.

Surat pengantar ini diberikan kepada yang bersangkutan untuk dipergunakan sebagai syarat pengurusan Surat Keterangan Catatan Kepolisian (SKCK) di Kepolisian setempat.

Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Lainnya' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga desa kami.

{keterangan}

Surat keterangan ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",

        'Surat Keterangan Domisili' =>
"Yang bertanda tangan di bawah ini Kepala Desa menerangkan bahwa:

Nama             : {nama}
NIK              : {nik}
Tempat/Tgl. Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin    : {jenis_kelamin}
Alamat           : {alamat}

adalah benar warga yang berdomisili di wilayah desa kami pada alamat tersebut di atas dan tercatat sebagai penduduk desa kami.

{keterangan}

Surat keterangan domisili ini diberikan untuk keperluan: {keperluan}

Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.",
    ];

    public function up(): void
    {
        $now = now();

        /* 1. Tambah Surat Keterangan Domisili ke jenis_surats */
        if (! DB::table('jenis_surats')->where('nama', 'Surat Keterangan Domisili')->exists()) {
            DB::table('jenis_surats')->insert([
                'nama'       => 'Surat Keterangan Domisili',
                'kode'       => 'SKD',
                'urutan'     => 11,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /* 2. Seed template isi untuk semua jenis */
        foreach ($this->templates as $nama => $template) {
            DB::table('jenis_surats')
                ->where('nama', $nama)
                ->whereNull('template_isi')
                ->update(['template_isi' => $template, 'updated_at' => $now]);
        }

        /* 3. Migrasi data surat_domisilis → pengajuan_surats */
        if (Schema::hasTable('surat_domisilis')) {
            $domisilis = DB::table('surat_domisilis')->get();

            $statusMap = [
                'pending'  => 'diajukan',
                'diproses' => 'diproses',
                'selesai'  => 'selesai',
                'ditolak'  => 'ditolak',
            ];

            foreach ($domisilis as $d) {
                $pendudukId = DB::table('penduduks')->where('nik', $d->nik)->value('id');

                $keterangan = trim(implode("\n", array_filter([
                    $d->agama    ? 'Agama    : ' . $d->agama    : null,
                    $d->pekerjaan? 'Pekerjaan: ' . $d->pekerjaan: null,
                    ($d->rt || $d->rw) ? 'RT/RW    : ' . ($d->rt ?? '-') . '/' . ($d->rw ?? '-') : null,
                ])));

                DB::table('pengajuan_surats')->insert([
                    'penduduk_id'      => $pendudukId,
                    'jenis_surat'      => 'Surat Keterangan Domisili',
                    'keperluan'        => $d->keperluan,
                    'keterangan'       => $keterangan ?: null,
                    'status'           => $statusMap[$d->status] ?? 'diajukan',
                    'nomor_surat'      => $d->nomor_surat,
                    'catatan'          => $d->catatan,
                    'diproses_oleh'    => $d->diproses_oleh,
                    'tanggal_selesai'  => $d->tanggal_selesai,
                    'token'            => Str::random(48),
                    'created_at'       => $d->created_at,
                    'updated_at'       => $d->updated_at,
                ]);
            }

            /* 4. Drop tabel domisili setelah data dipindah */
            Schema::dropIfExists('surat_domisilis');
        }
    }

    public function down(): void
    {
        // Rollback: hapus Domisili dari jenis_surats & kosongkan templates
        DB::table('jenis_surats')->where('nama', 'Surat Keterangan Domisili')->delete();
        DB::table('jenis_surats')->update(['template_isi' => null]);

        // Recreate surat_domisilis (struktur saja, data tidak bisa dikembalikan)
        if (! Schema::hasTable('surat_domisilis')) {
            Schema::create('surat_domisilis', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pemohon', 150);
                $table->string('nik', 16);
                $table->string('tempat_lahir', 100);
                $table->date('tanggal_lahir');
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->string('agama', 50);
                $table->string('pekerjaan', 100);
                $table->text('alamat');
                $table->string('rt', 5)->nullable();
                $table->string('rw', 5)->nullable();
                $table->text('keperluan');
                $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
                $table->string('nomor_surat', 50)->nullable();
                $table->text('catatan')->nullable();
                $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('tanggal_selesai')->nullable();
                $table->timestamps();
            });
        }
    }
};
