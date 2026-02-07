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
                // Change latitude and longitude to use proper precision for coordinates
                if (Schema::hasColumn('asuransis', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->change();
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->change();
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
                    $table->decimal('latitude', 11, 8)->change();
                }
                if (Schema::hasColumn('asuransis', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->change();
                }
            });
        }
    }
};
