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
                // Add all missing columns that the Asuransi model expects
                if (!Schema::hasColumn('asuransis', 'nama_user')) {
                    $table->string('nama_user')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('asuransis', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->nullable()->after('kejadian');
                }
                if (!Schema::hasColumn('asuransis', 'ktp')) {
                    $table->string('ktp')->nullable()->after('surat_permohonan');
                }
                if (!Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->string('lokasi')->nullable()->after('tanggal');
                }
                if (!Schema::hasColumn('asuransis', 'latitude')) {
                    $table->string('latitude')->nullable()->after('lokasi');
                }
                if (!Schema::hasColumn('asuransis', 'longitude')) {
                    $table->string('longitude')->nullable()->after('latitude');
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
                $columns = ['nama_user', 'no_whatsapp', 'ktp', 'lokasi', 'latitude', 'longitude'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('asuransis', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
