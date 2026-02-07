<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Drop the old nomor_whatsapp column if it exists
                if (Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                    DB::statement('ALTER TABLE asuransis DROP COLUMN nomor_whatsapp');
                }
                
                // Drop no_whatsapp if it exists (it shouldn't be NOT NULL)
                if (Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    DB::statement('ALTER TABLE asuransis DROP COLUMN no_whatsapp');
                }
                
                // Add no_whatsapp as nullable
                $table->string('no_whatsapp')->nullable()->after('nama_user');
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
                if (Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->dropColumn('no_whatsapp');
                }
            });
        }
    }
};
