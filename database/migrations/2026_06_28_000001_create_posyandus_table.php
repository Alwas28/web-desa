<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posyandus', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('dusun', 100)->nullable();
            $table->string('lokasi', 300)->nullable();
            $table->string('kader', 200)->nullable();
            $table->string('jadwal', 200)->nullable();
            $table->boolean('aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posyandus');
    }
};
