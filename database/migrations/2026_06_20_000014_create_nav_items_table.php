<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->foreignId('parent_id')->nullable()->constrained('nav_items')->nullOnDelete();
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->enum('tipe_link', ['custom', 'page', 'arsip'])->default('custom');
            $table->string('link_url', 500)->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
