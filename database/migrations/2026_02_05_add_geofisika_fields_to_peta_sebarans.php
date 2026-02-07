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
        Schema::table('peta_sebarans', function (Blueprint $table) {
            // Add geofisika fields if they don't exist
            if (!Schema::hasColumn('peta_sebarans', 'perusahaan')) {
                $table->string('perusahaan')->nullable()->after('identitas');
            }
            if (!Schema::hasColumn('peta_sebarans', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('perusahaan');
            }
            if (!Schema::hasColumn('peta_sebarans', 'latitude')) {
                $table->decimal('latitude', 10, 6)->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('peta_sebarans', 'longitude')) {
                $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('peta_sebarans', 'kejadian')) {
                $table->text('kejadian')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('peta_sebarans', 'data_type')) {
                $table->enum('data_type', ['peta_sebaran', 'geofisika'])->default('peta_sebaran')->after('kejadian');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peta_sebarans', function (Blueprint $table) {
            if (Schema::hasColumn('peta_sebarans', 'perusahaan')) {
                $table->dropColumn('perusahaan');
            }
            if (Schema::hasColumn('peta_sebarans', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('peta_sebarans', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('peta_sebarans', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('peta_sebarans', 'kejadian')) {
                $table->dropColumn('kejadian');
            }
            if (Schema::hasColumn('peta_sebarans', 'data_type')) {
                $table->dropColumn('data_type');
            }
        });
    }
};
