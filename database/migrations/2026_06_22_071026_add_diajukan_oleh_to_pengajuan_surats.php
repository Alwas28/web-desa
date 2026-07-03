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
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            // Siapa yang mengajukan (KK head bisa ajukan untuk anggota keluarga lain)
            $table->unsignedBigInteger('diajukan_oleh_penduduk_id')
                  ->nullable()
                  ->after('penduduk_id');

            $table->foreign('diajukan_oleh_penduduk_id')
                  ->references('id')->on('penduduks')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropForeign(['diajukan_oleh_penduduk_id']);
            $table->dropColumn('diajukan_oleh_penduduk_id');
        });
    }
};
