<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200)->unique();
            $table->string('kode', 20)->nullable();
            $table->boolean('dapat_dibuat_masyarakat')->default(false);
            $table->text('template_isi')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        $seed = [
            ['Surat Keterangan Tidak Mampu',   'SKTM',  1],
            ['Surat Keterangan Usaha',          'SKU',   2],
            ['Surat Keterangan Kelahiran',      'SKL',   3],
            ['Surat Keterangan Kematian',       'SKKM',  4],
            ['Surat Keterangan Pindah',         'SKP',   5],
            ['Surat Pengantar',                 'SP',    6],
            ['Surat Keterangan Menikah',        'SKMN',  7],
            ['Surat Keterangan Belum Menikah',  'SKBM',  8],
            ['Surat Pengantar SKCK',            'SKCK',  9],
            ['Surat Keterangan Lainnya',        'SKET', 10],
        ];

        $now = now();
        foreach ($seed as [$nama, $kode, $urutan]) {
            DB::table('jenis_surats')->insert([
                'nama'       => $nama,
                'kode'       => $kode,
                'urutan'     => $urutan,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surats');
    }
};
