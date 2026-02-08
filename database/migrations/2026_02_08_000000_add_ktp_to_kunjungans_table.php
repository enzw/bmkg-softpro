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
        if (!Schema::hasColumn('kunjungans', 'ktp')) {
            Schema::table('kunjungans', function (Blueprint $table) {
                $table->string('ktp')->nullable()->after('surat_permohonan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn('ktp');
        });
    }
};
