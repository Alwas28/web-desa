<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('file', 500);
            $table->string('nama_file', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('ukuran')->default(0);
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
