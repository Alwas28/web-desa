<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apbdes_pos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apbdes_id')->constrained('apbdes')->cascadeOnDelete();
            $table->enum('jenis', ['pendapatan', 'belanja', 'pembiayaan']);
            $table->string('kelompok', 150);
            $table->string('uraian', 255);
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes_pos');
    }
};
