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
        Schema::table('sewa_alats', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('user_id');
            $table->string('no_whatsapp')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sewa_alats', function (Blueprint $table) {
            $table->dropColumn(['nama', 'no_whatsapp']);
        });
    }
};
