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
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Rename columns to match form labels
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->renameColumn('latitude', 'nama_lengkap');
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->renameColumn('longitude', 'nomor_whatsapp');
                }
                if (Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->renameColumn('lokasi', 'jumlah_rombongan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Revert the renames
                if (Schema::hasColumn('asuransis', 'nama_lengkap')) {
                    $table->renameColumn('nama_lengkap', 'latitude');
                }
                if (Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                    $table->renameColumn('nomor_whatsapp', 'longitude');
                }
                if (Schema::hasColumn('asuransis', 'jumlah_rombongan')) {
                    $table->renameColumn('jumlah_rombongan', 'lokasi');
                }
            });
        }
    }
};
