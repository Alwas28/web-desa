<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_arsips', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 100)->unique();
            $table->string('warna', 7)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_arsips');
    }
};
