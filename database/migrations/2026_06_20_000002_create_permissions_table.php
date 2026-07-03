<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            // Pola: aksi.fitur — misal: edit.berita, lihat.surat, hapus.pengaduan
            $table->string('name')->unique();
            $table->string('label');    // Edit Berita
            $table->string('group');    // berita  (nama fitur/modul)
            $table->string('action');   // edit    (aksi: lihat|tambah|edit|hapus)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
