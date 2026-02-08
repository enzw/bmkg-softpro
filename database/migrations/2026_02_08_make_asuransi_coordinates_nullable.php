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
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                // Make latitude and longitude nullable since they're optional in the form
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable()->change();
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable()->change();
                }
                
                // Ensure lokasi column exists
                if (!Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->string('lokasi')->nullable();
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
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable(false)->change();
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable(false)->change();
                }
            });
        }
    }
};
