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
        Schema::table('magangs', function (Blueprint $table) {
            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('surat_permohonan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magangs', function (Blueprint $table) {
            $table->dropColumn(['universitas', 'fakultas', 'prodi', 'tanggal_mulai', 'tanggal_selesai', 'surat_permohonan']);
        });
    }
};
