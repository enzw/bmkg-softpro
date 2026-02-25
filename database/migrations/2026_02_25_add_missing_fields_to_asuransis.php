<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('asuransis')) {
            Schema::table('asuransis', function (Blueprint $table) {
                if (!Schema::hasColumn('asuransis', 'nama_user')) {
                    $table->string('nama_user')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('asuransis', 'lokasi')) {
                    $table->string('lokasi')->nullable()->after('perusahaan');
                }
                if (!Schema::hasColumn('asuransis', 'latitude')) {
                    $table->decimal('latitude', 10, 7)->nullable()->after('lokasi');
                }
                if (!Schema::hasColumn('asuransis', 'longitude')) {
                    $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
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
                $cols = ['nama_user', 'lokasi', 'latitude', 'longitude'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('asuransis', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
