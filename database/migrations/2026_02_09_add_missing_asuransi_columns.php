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
                // Add missing columns that the model expects
                if (!Schema::hasColumn('asuransis', 'nama_user')) {
                    $table->string('nama_user')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->nullable();
                }
                if (!Schema::hasColumn('asuransis', 'ktp')) {
                    $table->string('ktp')->nullable();
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
                if (Schema::hasColumn('asuransis', 'nama_user')) {
                    $table->dropColumn('nama_user');
                }
                if (Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->dropColumn('no_whatsapp');
                }
                if (Schema::hasColumn('asuransis', 'ktp')) {
                    $table->dropColumn('ktp');
                }
            });
        }
    }
};
