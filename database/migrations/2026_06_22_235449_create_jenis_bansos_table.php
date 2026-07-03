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
        Schema::create('jenis_bansos', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('sumber_dana', ['APBN', 'APBD', 'APBDes', 'Swasta', 'Lainnya'])->default('APBDes');
            $table->decimal('nilai_bantuan', 15, 2)->nullable();
            $table->string('satuan', 60)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_bansos');
    }
};
