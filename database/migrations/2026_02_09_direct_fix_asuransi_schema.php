<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Direct fix for the asuransis table schema
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Drop the bad column if it exists
                if (Schema::hasColumn('asuransis', 'jumlah_rombongan')) {
                    $table->dropColumn('jumlah_rombongan');
                }
                if (Schema::hasColumn('asuransis', 'nama_lengkap')) {
                    $table->dropColumn('nama_lengkap');
                }
                if (Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                    $table->dropColumn('nomor_whatsapp');
                }
            });
            
            // Now add the correct columns if they don't exist
            Schema::table('asuransis', function (Blueprint $table) {
                if (!Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->string('lokasi')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'latitude')) {
                    $table->string('latitude')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'longitude')) {
                    $table->string('longitude')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing - this is a critical fix
    }
};
