<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan_bumils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamils')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('tekanan_darah', 20)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->unsignedTinyInteger('usia_kehamilan')->nullable();
            $table->unsignedTinyInteger('kunjungan_ke')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan_bumils');
    }
};
