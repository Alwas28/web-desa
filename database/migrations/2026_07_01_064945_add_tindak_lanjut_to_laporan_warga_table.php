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
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->text('rencana_tindak_lanjut')->nullable()->after('balasan');
            $table->text('laporan_penanganan')->nullable()->after('rencana_tindak_lanjut');
            $table->timestamp('ditangani_at')->nullable()->after('dibalas_at');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropColumn(['rencana_tindak_lanjut', 'laporan_penanganan', 'ditangani_at']);
        });
    }
};
