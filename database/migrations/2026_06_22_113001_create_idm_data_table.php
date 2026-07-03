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
        Schema::create('idm_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->year('tahun')->unique();

            // Skor tiga indeks utama
            $table->decimal('skor_iks', 6, 4)->default(0);
            $table->decimal('skor_ike', 6, 4)->default(0);
            $table->decimal('skor_ikl', 6, 4)->default(0);

            // IDM = (IKS + IKE + IKL) / 3 — disimpan agar bisa di-query & diurutkan
            $table->decimal('skor_idm', 6, 4)->default(0);
            $table->enum('status_idm', ['Sangat Tertinggal', 'Tertinggal', 'Berkembang', 'Maju', 'Mandiri']);

            // Dimensi IKS (Indeks Ketahanan Sosial)
            $table->decimal('iks_kesehatan',    6, 4)->nullable();
            $table->decimal('iks_pendidikan',   6, 4)->nullable();
            $table->decimal('iks_modal_sosial', 6, 4)->nullable();
            $table->decimal('iks_permukiman',   6, 4)->nullable();

            // Dimensi IKE (Indeks Ketahanan Ekonomi)
            $table->decimal('ike_keragaman_produksi',  6, 4)->nullable();
            $table->decimal('ike_pusat_perdagangan',   6, 4)->nullable();
            $table->decimal('ike_akses_distribusi',    6, 4)->nullable();
            $table->decimal('ike_lembaga_keuangan',    6, 4)->nullable();
            $table->decimal('ike_lembaga_ekonomi',     6, 4)->nullable();
            $table->decimal('ike_keterbukaan_wilayah', 6, 4)->nullable();

            // Dimensi IKL (Indeks Ketahanan Lingkungan/Ekologi)
            $table->decimal('ikl_kualitas_lingkungan', 6, 4)->nullable();
            $table->decimal('ikl_rawan_bencana',       6, 4)->nullable();

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idm_data');
    }
};
