<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('layanan_datas')) {
            try {
                DB::beginTransaction();
                
                // Check if id column is still bigint
                $columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'layanan_datas' AND column_name = 'id'");
                
                if (!empty($columns) && $columns[0]->data_type === 'bigint') {
                    // Add uuid column
                    if (!Schema::hasColumn('layanan_datas', 'uuid')) {
                        DB::statement("ALTER TABLE layanan_datas ADD COLUMN uuid UUID DEFAULT gen_random_uuid()");
                    }
                    
                    // Drop primary key constraint
                    DB::statement("ALTER TABLE layanan_datas DROP CONSTRAINT IF EXISTS layanan_datas_pkey");
                    
                    // Rename old id to old_id
                    DB::statement("ALTER TABLE layanan_datas RENAME COLUMN id TO old_id");
                    
                    // Rename uuid to id
                    DB::statement("ALTER TABLE layanan_datas RENAME COLUMN uuid TO id");
                    
                    // Set id as primary key
                    DB::statement("ALTER TABLE layanan_datas ADD PRIMARY KEY (id)");
                    
                    // Drop old_id column
                    DB::statement("ALTER TABLE layanan_datas DROP COLUMN old_id");
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Failed to convert layanan_datas to UUID: " . $e->getMessage());
                throw $e;
            }
        }
    }

    public function down(): void
    {
        // Cannot safely reverse UUID conversion
    }
};
