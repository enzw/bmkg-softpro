<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            // Use raw SQL for PostgreSQL to properly cast columns
            DB::statement('ALTER TABLE asuransis ALTER COLUMN latitude TYPE numeric(10,8) USING latitude::numeric(10,8)');
            DB::statement('ALTER TABLE asuransis ALTER COLUMN longitude TYPE numeric(11,8) USING longitude::numeric(11,8)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('asuransis')) {
            // Revert to string type
            DB::statement('ALTER TABLE asuransis ALTER COLUMN latitude TYPE varchar(255)');
            DB::statement('ALTER TABLE asuransis ALTER COLUMN longitude TYPE varchar(255)');
        }
    }
};
