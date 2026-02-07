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
        if (Schema::hasTable('kunjungans')) {
            Schema::table('kunjungans', function (Blueprint $table) {
                // Drop old geographic/location-based columns
                $columnsToDelete = [];
                foreach (['nama_user', 'tanggal', 'lokasi', 'latitude', 'longitude', 'kartu_identitas'] as $column) {
                    if (Schema::hasColumn('kunjungans', $column)) {
                        $columnsToDelete[] = $column;
                    }
                }
                
                if (!empty($columnsToDelete)) {
                    $table->dropColumn($columnsToDelete);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kunjungans')) {
            Schema::table('kunjungans', function (Blueprint $table) {
                // Restore old columns if needed
                if (!Schema::hasColumn('kunjungans', 'nama_user')) {
                    $table->string('nama_user')->nullable();
                }
                if (!Schema::hasColumn('kunjungans', 'tanggal')) {
                    $table->date('tanggal')->nullable();
                }
                if (!Schema::hasColumn('kunjungans', 'lokasi')) {
                    $table->string('lokasi')->nullable();
                }
                if (!Schema::hasColumn('kunjungans', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable();
                }
                if (!Schema::hasColumn('kunjungans', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable();
                }
                if (!Schema::hasColumn('kunjungans', 'kartu_identitas')) {
                    $table->string('kartu_identitas')->nullable();
                }
            });
        }
    }
};
