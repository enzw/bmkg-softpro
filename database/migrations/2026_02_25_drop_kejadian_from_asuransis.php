<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('asuransis') && Schema::hasColumn('asuransis', 'kejadian')) {
            Schema::table('asuransis', function (Blueprint $table) {
                $table->dropColumn('kejadian');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('asuransis') && !Schema::hasColumn('asuransis', 'kejadian')) {
            Schema::table('asuransis', function (Blueprint $table) {
                $table->string('kejadian')->nullable();
            });
        }
    }
};
