<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add ktp to surveys if it doesn't exist
        if (Schema::hasTable('surveys') && !Schema::hasColumn('surveys', 'ktp')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->string('ktp')->nullable();
            });
        }

        // Add ktp to jasa_konsultasis if it doesn't exist
        if (Schema::hasTable('jasa_konsultasis') && !Schema::hasColumn('jasa_konsultasis', 'ktp')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                $table->string('ktp')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('surveys') && Schema::hasColumn('surveys', 'ktp')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropColumn('ktp');
            });
        }

        if (Schema::hasTable('jasa_konsultasis') && Schema::hasColumn('jasa_konsultasis', 'ktp')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                $table->dropColumn('ktp');
            });
        }
    }
};
