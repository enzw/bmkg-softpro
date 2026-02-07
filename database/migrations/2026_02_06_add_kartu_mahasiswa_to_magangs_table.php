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
        if (Schema::hasTable('magangs')) {
            Schema::table('magangs', function (Blueprint $table) {
                if (!Schema::hasColumn('magangs', 'kartu_mahasiswa')) {
                    $table->string('kartu_mahasiswa')->nullable()->after('surat_ijin_magang')->comment('File path for student ID card');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('magangs')) {
            Schema::table('magangs', function (Blueprint $table) {
                if (Schema::hasColumn('magangs', 'kartu_mahasiswa')) {
                    $table->dropColumn('kartu_mahasiswa');
                }
            });
        }
    }
};
