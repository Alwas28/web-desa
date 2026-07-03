<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('surat_domisilis');
    }
};
