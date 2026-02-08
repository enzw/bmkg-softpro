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
        foreach (['magangs', 'asuransis', 'sewa_alats', 'kunjungans', 'layanan_datas', 'surveys', 'jasa_konsultasis'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'ktp')) {
                        $table->string('ktp')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['magangs', 'asuransis', 'sewa_alats', 'kunjungans', 'layanan_datas', 'surveys', 'jasa_konsultasis'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'ktp')) {
                        $table->dropColumn('ktp');
                    }
                });
            }
        }
    }
};
