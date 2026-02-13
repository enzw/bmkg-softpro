<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'surveys',
            'jasa_konsultasis',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::beginTransaction();
                    
                    // Check if id column is still bigint
                    $columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ? AND column_name = 'id'", [$table]);
                    
                    if (!empty($columns) && $columns[0]->data_type === 'bigint') {
                        // Add uuid column
                        if (!Schema::hasColumn($table, 'uuid')) {
                            DB::statement("ALTER TABLE {$table} ADD COLUMN uuid UUID DEFAULT gen_random_uuid()");
                        }
                        
                        // Drop primary key constraint
                        DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$table}_pkey");
                        
                        // Rename old id to old_id
                        DB::statement("ALTER TABLE {$table} RENAME COLUMN id TO old_id");
                        
                        // Rename uuid to id
                        DB::statement("ALTER TABLE {$table} RENAME COLUMN uuid TO id");
                        
                        // Set id as primary key
                        DB::statement("ALTER TABLE {$table} ADD PRIMARY KEY (id)");
                        
                        // Drop old_id column
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

    public function down(): void
    {
        // Cannot safely reverse UUID conversion
    }
};
