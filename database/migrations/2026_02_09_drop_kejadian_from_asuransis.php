<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drop the kejadian column from asuransis table
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                if (Schema::hasColumn('asuransis', 'kejadian')) {
                    $table->dropColumn('kejadian');
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
                if (!Schema::hasColumn('asuransis', 'kejadian')) {
                    $table->string('kejadian')->nullable();
                }
            });
        }
    }
};
