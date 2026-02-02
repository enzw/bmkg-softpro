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
        Schema::create('sewa_alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id');
            $table->foreignId('user_id');
            $table->string('sewa_mulai');
            $table->string('sewa_berakhir');
            $table->integer('banyak_unit')->default(1);
            $table->string('surat_permohonan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Menunggu');
            $table->string('expedisi')->nullable();
            $table->string('resi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewa_alats');
    }
};
