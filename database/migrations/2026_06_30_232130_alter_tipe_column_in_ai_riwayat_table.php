<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM ke VARCHAR agar fleksibel untuk tipe AI baru di masa depan
        DB::statement("ALTER TABLE ai_riwayat MODIFY COLUMN tipe VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ai_riwayat MODIFY COLUMN tipe ENUM('sambutan','perdes','surat','perkades','prediksi-stunting') NOT NULL");
    }
};
