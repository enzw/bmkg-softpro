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
        Schema::table('pemetaans', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['nama_lengkap', 'no_whatsapp', 'email', 'keterangan']);
            
            // Add new columns
            $table->string('perusahaan')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('lokasi')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('kejadian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemetaans', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['perusahaan', 'tanggal', 'lokasi', 'latitude', 'longitude', 'kejadian']);
            
            // Add back old columns
            $table->string('nama_lengkap')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('keterangan')->nullable();
        });
    }
};
