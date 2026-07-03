<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nav_items', function (Blueprint $table) {
            $table->enum('arsip_filter', ['semua', 'kategori'])->default('semua')->after('page_id');
            $table->foreignId('kategori_arsip_id')->nullable()->after('arsip_filter')
                  ->constrained('kategori_arsips')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('nav_items', function (Blueprint $table) {
            $table->dropForeign(['kategori_arsip_id']);
            $table->dropColumn(['arsip_filter', 'kategori_arsip_id']);
        });
    }
};
