<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'asuransis',
            'magangs',
            'layanan_data',
            'surveys',
            'jasa_konsultasis',
            'kunjungans',
            'sewa_alats'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::beginTransaction();
                    
                    // Add uuid column
                    if (!Schema::hasColumn($table, 'uuid')) {
                        DB::statement("ALTER TABLE {$table} ADD COLUMN uuid UUID DEFAULT gen_random_uuid()");
                    }
                    
                    // Drop foreign key constraints referencing id from other tables
                    DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$table}_pkey");
                    
                    // Rename old id to old_id
                    if (Schema::hasColumn($table, 'id')) {
                        DB::statement("ALTER TABLE {$table} RENAME COLUMN id TO old_id");
                    }
                    
                    // Rename uuid to id
                    DB::statement("ALTER TABLE {$table} RENAME COLUMN uuid TO id");
                    
                    // Set id as primary key
                    DB::statement("ALTER TABLE {$table} ADD PRIMARY KEY (id)");
                    
                    // Drop old_id column
                    if (Schema::hasColumn($table, 'old_id')) {
                        DB::statement("ALTER TABLE {$table} DROP COLUMN old_id");
                    }
                    
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Failed to convert {$table} to UUID: " . $e->getMessage());
                    throw $e;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is irreversible as it changes primary key type
        // To rollback, restore from backup
    }
};
