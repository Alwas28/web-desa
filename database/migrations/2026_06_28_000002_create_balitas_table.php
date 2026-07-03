<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->nullOnDelete();
            $table->string('nama', 150);
            $table->string('nik', 20)->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nama_ibu', 150);
            $table->string('nama_ayah', 150)->nullable();
            $table->string('alamat', 300)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};
