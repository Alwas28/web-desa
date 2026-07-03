<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibu_hamils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->nullOnDelete();
            $table->string('nama', 150);
            $table->string('nik', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nama_suami', 150)->nullable();
            $table->string('alamat', 300)->nullable();
            $table->date('hpht');
            $table->date('hpl')->nullable();
            $table->enum('status_risiko', ['normal', 'risiko_rendah', 'risiko_tinggi'])->default('normal');
            $table->enum('status', ['hamil', 'selesai', 'keguguran'])->default('hamil');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibu_hamils');
    }
};
