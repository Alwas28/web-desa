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
        Schema::create('penyaluran_bansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_bansos_id')->constrained('penerima_bansos')->cascadeOnDelete();
            $table->string('periode', 60);
            $table->date('tanggal_penyaluran')->nullable();
            $table->decimal('nilai_disalurkan', 15, 2)->nullable();
            $table->enum('status', ['terjadwal', 'disalurkan', 'tidak_hadir', 'ditolak'])->default('terjadwal');
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyaluran_bansos');
    }
};
