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
        if (!Schema::hasTable('kunjungans')) {
            Schema::create('kunjungans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('jenis_kunjungan')->nullable();
                $table->string('nama_instansi')->nullable();
                $table->string('nama_lengkap')->nullable();
                $table->string('no_whatsapp')->nullable();
                $table->integer('jumlah_rombongan')->nullable();
                $table->text('rencana_kunjungan')->nullable();
                $table->string('surat_permohonan')->nullable();
                $table->string('ktp')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
