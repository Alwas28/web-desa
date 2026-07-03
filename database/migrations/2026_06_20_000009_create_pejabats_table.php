<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pejabats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->string('foto', 255)->nullable();
            $table->string('nip', 30)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pejabats');
    }
};
