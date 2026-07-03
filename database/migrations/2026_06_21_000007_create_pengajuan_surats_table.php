<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->nullable()->nullOnDelete()->constrained('penduduks');
            $table->string('jenis_surat', 100);
            $table->text('keperluan');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['diajukan', 'diproses', 'selesai', 'ditolak'])->default('diajukan');
            $table->string('nomor_surat', 80)->nullable();
            $table->string('nama_kades', 150)->nullable();
            $table->string('jabatan_kades', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->nullOnDelete()->constrained('users');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};
