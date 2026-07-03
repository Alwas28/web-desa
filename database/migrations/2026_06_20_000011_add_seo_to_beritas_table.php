<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->after('published_at');
            $table->text('meta_deskripsi')->nullable()->after('meta_title');
            $table->string('meta_keywords', 500)->nullable()->after('meta_deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_deskripsi', 'meta_keywords']);
        });
    }
};
