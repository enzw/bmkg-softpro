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
        // Make user_id nullable in all tables for admin-created records
        if (Schema::hasTable('magangs')) {
            Schema::table('magangs', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }

        if (Schema::hasTable('layanan_datas')) {
            Schema::table('layanan_datas', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }

        if (Schema::hasTable('pemetaans')) {
            Schema::table('pemetaans', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }

        if (Schema::hasTable('surveys')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }

        if (Schema::hasTable('jasa_konsultasis')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert user_id to NOT NULL
        if (Schema::hasTable('magangs')) {
            Schema::table('magangs', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('layanan_datas')) {
            Schema::table('layanan_datas', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('pemetaans')) {
            Schema::table('pemetaans', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('surveys')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('jasa_konsultasis')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }
    }
};
