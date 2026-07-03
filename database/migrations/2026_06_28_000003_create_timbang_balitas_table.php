<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timbang_balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained('balitas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('lingkar_kepala', 4, 1)->nullable();
            $table->enum('status_gizi', ['normal', 'kurang', 'buruk', 'lebih', 'stunting'])->default('normal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timbang_balitas');
    }
};
