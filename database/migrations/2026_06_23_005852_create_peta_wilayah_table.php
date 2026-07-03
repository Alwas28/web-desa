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
        Schema::create('peta_wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->enum('tipe', ['desa','dusun','rt_rw','sawah','hutan','lainnya'])->default('lainnya');
            $table->longText('geojson');
            $table->string('warna', 20)->default('#3B82F6');
            $table->decimal('opacity', 4, 2)->default(0.25);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peta_wilayah');
    }
};
