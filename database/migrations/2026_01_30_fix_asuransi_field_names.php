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
        Schema::table('asuransis', function (Blueprint $table) {
            // Rename columns to match form labels
            $table->renameColumn('latitude', 'nama_lengkap');
            $table->renameColumn('longitude', 'nomor_whatsapp');
            $table->renameColumn('lokasi', 'jumlah_rombongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuransis', function (Blueprint $table) {
            // Revert the renames
            $table->renameColumn('nama_lengkap', 'latitude');
            $table->renameColumn('nomor_whatsapp', 'longitude');
            $table->renameColumn('jumlah_rombongan', 'lokasi');
        });
    }
};
