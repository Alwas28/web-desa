<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_posyandu_id')->constrained('kegiatan_posyandus')->cascadeOnDelete();
            $table->enum('kategori', ['balita', 'bumil', 'lansia', 'umum'])->default('balita');
            $table->string('nama', 150);
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();  // kg
            $table->decimal('tinggi_badan', 5, 2)->nullable(); // cm
            $table->decimal('lingkar_kepala', 4, 1)->nullable(); // cm
            $table->string('tekanan_darah', 20)->nullable();   // bumil/lansia
            $table->enum('status_gizi', ['normal', 'kurang', 'buruk', 'lebih', 'stunting'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_kegiatans');
    }
};
