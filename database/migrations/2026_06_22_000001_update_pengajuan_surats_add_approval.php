<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->string('token', 64)->unique()->nullable()->after('id');
            $table->foreignId('disetujui_oleh')->nullable()->nullOnDelete()->constrained('users')->after('diproses_oleh');
        });

        DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('diajukan','diproses','menunggu_ttd','disetujui','ditolak') NOT NULL DEFAULT 'diajukan'");
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropColumn(['token', 'disetujui_oleh']);
        });
        DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('diajukan','diproses','selesai','ditolak') NOT NULL DEFAULT 'diajukan'");
    }
};
