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
        Schema::table('idm_data', function (Blueprint $table) {
            // Hapus kolom sub-dimensi lama yang tidak sesuai format CSV
            $table->dropColumn([
                'iks_kesehatan', 'iks_pendidikan', 'iks_modal_sosial', 'iks_permukiman',
                'ike_keragaman_produksi', 'ike_pusat_perdagangan', 'ike_akses_distribusi',
                'ike_lembaga_keuangan', 'ike_lembaga_ekonomi', 'ike_keterbukaan_wilayah',
                'ikl_kualitas_lingkungan', 'ikl_rawan_bencana',
            ]);
            // Ganti dengan JSON yang menyimpan semua 50 indikator
            $table->json('indikators')->nullable()->after('status_idm');
        });
    }

    public function down(): void
    {
        Schema::table('idm_data', function (Blueprint $table) {
            $table->dropColumn('indikators');
            $table->decimal('iks_kesehatan',    6, 4)->nullable();
            $table->decimal('iks_pendidikan',   6, 4)->nullable();
            $table->decimal('iks_modal_sosial', 6, 4)->nullable();
            $table->decimal('iks_permukiman',   6, 4)->nullable();
            $table->decimal('ike_keragaman_produksi',  6, 4)->nullable();
            $table->decimal('ike_pusat_perdagangan',   6, 4)->nullable();
            $table->decimal('ike_akses_distribusi',    6, 4)->nullable();
            $table->decimal('ike_lembaga_keuangan',    6, 4)->nullable();
            $table->decimal('ike_lembaga_ekonomi',     6, 4)->nullable();
            $table->decimal('ike_keterbukaan_wilayah', 6, 4)->nullable();
            $table->decimal('ikl_kualitas_lingkungan', 6, 4)->nullable();
            $table->decimal('ikl_rawan_bencana',       6, 4)->nullable();
        });
    }
};
