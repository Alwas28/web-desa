<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kk_id')->nullable()->constrained('kartu_keluargas')->nullOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 150);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 50);
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati']);
            $table->string('pekerjaan', 100)->nullable();
            $table->enum('pendidikan', ['tidak_sekolah', 'sd', 'smp', 'sma', 'diploma', 's1', 's2', 's3']);
            $table->string('golongan_darah', 3)->nullable();
            $table->string('hubungan_keluarga', 20)->default('kepala_keluarga');
            $table->enum('status_penduduk', ['tetap', 'sementara'])->default('tetap');
            $table->string('kewarganegaraan', 10)->default('WNI');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
