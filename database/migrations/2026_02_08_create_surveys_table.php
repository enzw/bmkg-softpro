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
        // Create surveys table if not exists
        if (!Schema::hasTable('surveys')) {
            Schema::create('surveys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('nama_lengkap');
                $table->string('no_whatsapp');
                $table->string('email');
                $table->string('lokasi_survey')->nullable();
                $table->integer('durasi_survey_hari')->nullable();
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
        Schema::dropIfExists('surveys');
    }
};
