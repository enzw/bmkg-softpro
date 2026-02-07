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
                // Ensure Asuransi table has the right columns
                if (!Schema::hasColumn('asuransis', 'perusahaan')) {
                    $table->string('perusahaan')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'kejadian')) {
                    $table->string('kejadian')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuransis', function (Blueprint $table) {
            //
        });
    }
};
