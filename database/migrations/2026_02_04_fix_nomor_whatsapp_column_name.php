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
                // Rename nomor_whatsapp to no_whatsapp if it exists
                if (Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                    DB::statement('ALTER TABLE asuransis RENAME COLUMN nomor_whatsapp TO no_whatsapp');
                }
            });
            
            // Make sure no_whatsapp is nullable
            if (Schema::hasColumn('asuransis', 'no_whatsapp')) {
                DB::statement('ALTER TABLE asuransis ALTER COLUMN no_whatsapp DROP NOT NULL');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('asuransis')) {
            if (Schema::hasColumn('asuransis', 'no_whatsapp')) {
                DB::statement('ALTER TABLE asuransis RENAME COLUMN no_whatsapp TO nomor_whatsapp');
            }
            
            if (Schema::hasColumn('asuransis', 'nomor_whatsapp')) {
                DB::statement('ALTER TABLE asuransis ALTER COLUMN nomor_whatsapp SET NOT NULL');
            }
        }
    }
};
