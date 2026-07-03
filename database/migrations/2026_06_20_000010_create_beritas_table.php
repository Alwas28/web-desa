<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->longText('konten');
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['draft', 'diterbitkan'])->default('draft');
            $table->unsignedBigInteger('dilihat')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
