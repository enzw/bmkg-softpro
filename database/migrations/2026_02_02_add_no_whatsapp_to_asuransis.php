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
        Schema::table('asuransis', function (Blueprint $table) {
            // Add no_whatsapp column
            $table->string('no_whatsapp')->nullable();
            
            // Make user_id nullable
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuransis', function (Blueprint $table) {
            $table->dropColumn('no_whatsapp');
            
            // Revert user_id to NOT NULL
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
