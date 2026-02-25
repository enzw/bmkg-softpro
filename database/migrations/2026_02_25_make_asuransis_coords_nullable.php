<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            // Make latitude and longitude nullable (they existed as NOT NULL from original migration)
            DB::statement('ALTER TABLE asuransis ALTER COLUMN latitude DROP NOT NULL');
            DB::statement('ALTER TABLE asuransis ALTER COLUMN longitude DROP NOT NULL');

            // Also ensure nama_user is nullable in case it exists as NOT NULL
            if (Schema::hasColumn('asuransis', 'nama_user')) {
                DB::statement('ALTER TABLE asuransis ALTER COLUMN nama_user DROP NOT NULL');
            }
            if (Schema::hasColumn('asuransis', 'lokasi')) {
                DB::statement('ALTER TABLE asuransis ALTER COLUMN lokasi DROP NOT NULL');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not re-add NOT NULL constraints as data may already have nulls
    }
};
