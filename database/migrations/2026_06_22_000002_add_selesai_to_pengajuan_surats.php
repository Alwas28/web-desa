<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('diajukan','diproses','menunggu_ttd','disetujui','selesai','ditolak') NOT NULL DEFAULT 'diajukan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('diajukan','diproses','menunggu_ttd','disetujui','ditolak') NOT NULL DEFAULT 'diajukan'");
    }
};
