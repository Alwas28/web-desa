<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penerima_bansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_bansos_id')->constrained('jenis_bansos')->cascadeOnDelete();
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduks')->nullOnDelete();
            $table->string('nama', 150);
            $table->string('nik', 16)->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->year('tahun_ditetapkan')->nullable();
            $table->enum('status', ['menunggu', 'aktif', 'nonaktif'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_bansos');
    }
};
