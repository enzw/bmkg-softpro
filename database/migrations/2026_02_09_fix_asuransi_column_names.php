<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fix the bad column renames from 2026_01_30_fix_asuransi_field_names
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Revert bad renames from the Jan 30 migration
                if (Schema::hasColumn('asuransis', 'nama_lengkap')) {
                    $table->renameColumn('nama_lengkap', 'latitude');
                }
                if (Schema::hasColumn('asuransis', 'nomor_whatsapp') && !Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->renameColumn('nomor_whatsapp', 'longitude');
                }
                if (Schema::hasColumn('asuransis', 'jumlah_rombongan')) {
                    $table->renameColumn('jumlah_rombongan', 'lokasi');
                }
            });
            
            // Now make sure columns are properly nullable
            Schema::table('asuransis', function (Blueprint $table) {
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->string('latitude')->nullable()->change();
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->string('longitude')->nullable()->change();
                }
                if (Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->string('lokasi')->nullable()->change();
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
                // Revert back to bad names (just in case)
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->renameColumn('latitude', 'nama_lengkap');
                }
                if (Schema::hasColumn('asuransis', 'longitude') && !Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                    $table->renameColumn('longitude', 'nomor_whatsapp');
                }
                if (Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->renameColumn('lokasi', 'jumlah_rombongan');
                }
            });
        }
    }
};
