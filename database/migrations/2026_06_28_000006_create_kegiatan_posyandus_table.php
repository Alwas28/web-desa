<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_posyandus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained('posyandus')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedSmallInteger('jumlah_balita')->nullable();
            $table->unsignedSmallInteger('jumlah_bumil')->nullable();
            $table->unsignedSmallInteger('jumlah_lansia')->nullable();
            $table->string('petugas', 200)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_posyandus');
    }
};
