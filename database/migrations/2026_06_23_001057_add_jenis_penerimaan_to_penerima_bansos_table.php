<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penerima_bansos', function (Blueprint $table) {
            $table->enum('jenis_penerimaan', ['tunai', 'non_tunai'])->default('tunai')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('penerima_bansos', function (Blueprint $table) {
            $table->dropColumn('jenis_penerimaan');
        });
    }
};
