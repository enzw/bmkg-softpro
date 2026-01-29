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
        Schema::table('magangs', function (Blueprint $table) {
            // Drop jenis_layanan column jika ada
            if (Schema::hasColumn('magangs', 'jenis_layanan')) {
                $table->dropColumn('jenis_layanan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magangs', function (Blueprint $table) {
            $table->string('jenis_layanan')->after('user_id')->nullable();
        });
    }
};
