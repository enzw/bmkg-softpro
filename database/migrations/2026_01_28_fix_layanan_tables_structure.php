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
        // Add user_id to jasa_konsultasis if missing
        if (Schema::hasTable('jasa_konsultasis') && !Schema::hasColumn('jasa_konsultasis', 'user_id')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            });
        }

        // Create pemetaans table if not exists
        if (!Schema::hasTable('pemetaans')) {
            Schema::create('pemetaans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('nama_lengkap');
                $table->string('no_whatsapp');
                $table->string('email');
                $table->text('keterangan')->nullable();
                $table->string('status')->default('Menunggu');
                $table->string('surat_permohonan')->nullable();
                $table->timestamps();
            });
        }

        // Create layanan_datas table if not exists
        if (!Schema::hasTable('layanan_datas')) {
            Schema::create('layanan_datas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('nama_lengkap');
                $table->string('no_whatsapp');
                $table->string('email');
                $table->text('keterangan')->nullable();
                $table->string('status')->default('Menunggu');
                $table->string('surat_permohonan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop tables on rollback
    }
};
