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
        if (Schema::hasTable('sewa_alats')) {
            Schema::table('sewa_alats', function (Blueprint $table) {
                if (!Schema::hasColumn('sewa_alats', 'nama')) {
                    $table->string('nama')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('sewa_alats', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->nullable()->after('nama');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sewa_alats')) {
            Schema::table('sewa_alats', function (Blueprint $table) {
                $columnsToDrop = [];
                foreach (['nama', 'no_whatsapp'] as $column) {
                    if (Schema::hasColumn('sewa_alats', $column)) {
                        $columnsToDrop[] = $column;
                    }
                }
                
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
