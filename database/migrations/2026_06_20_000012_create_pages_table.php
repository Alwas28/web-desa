<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->longText('konten');
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['draft', 'diterbitkan'])->default('draft');
            $table->unsignedBigInteger('dilihat')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_deskripsi')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
