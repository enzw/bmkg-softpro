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
        // Update jasa_konsultasis table if needed
        if (Schema::hasTable('jasa_konsultasis')) {
            Schema::table('jasa_konsultasis', function (Blueprint $table) {
                if (!Schema::hasColumn('jasa_konsultasis', 'nama_lengkap')) {
                    $table->string('nama_lengkap')->after('user_id');
                }
                if (!Schema::hasColumn('jasa_konsultasis', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->after('nama_lengkap');
                }
                if (!Schema::hasColumn('jasa_konsultasis', 'email')) {
                    $table->string('email')->after('no_whatsapp');
                }
                if (!Schema::hasColumn('jasa_konsultasis', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('email');
                }
                if (!Schema::hasColumn('jasa_konsultasis', 'status')) {
                    $table->string('status')->default('Menunggu')->after('keterangan');
                }
                if (!Schema::hasColumn('jasa_konsultasis', 'surat_permohonan')) {
                    $table->string('surat_permohonan')->nullable()->after('status');
                }
            });
        }

        // Update pemetaans table if needed
        if (Schema::hasTable('pemetaans')) {
            Schema::table('pemetaans', function (Blueprint $table) {
                if (!Schema::hasColumn('pemetaans', 'nama_lengkap')) {
                    $table->string('nama_lengkap')->after('user_id');
                }
                if (!Schema::hasColumn('pemetaans', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->after('nama_lengkap');
                }
                if (!Schema::hasColumn('pemetaans', 'email')) {
                    $table->string('email')->after('no_whatsapp');
                }
                if (!Schema::hasColumn('pemetaans', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('email');
                }
                if (!Schema::hasColumn('pemetaans', 'status')) {
                    $table->string('status')->default('Menunggu')->after('keterangan');
                }
                if (!Schema::hasColumn('pemetaans', 'surat_permohonan')) {
                    $table->string('surat_permohonan')->nullable()->after('status');
                }
            });
        }

        // Update layanan_datas table if needed
        if (Schema::hasTable('layanan_datas')) {
            Schema::table('layanan_datas', function (Blueprint $table) {
                if (!Schema::hasColumn('layanan_datas', 'nama_lengkap')) {
                    $table->string('nama_lengkap')->after('user_id');
                }
                if (!Schema::hasColumn('layanan_datas', 'no_whatsapp')) {
                    $table->string('no_whatsapp')->after('nama_lengkap');
                }
                if (!Schema::hasColumn('layanan_datas', 'email')) {
                    $table->string('email')->after('no_whatsapp');
                }
                if (!Schema::hasColumn('layanan_datas', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('email');
                }
                if (!Schema::hasColumn('layanan_datas', 'status')) {
                    $table->string('status')->default('Menunggu')->after('keterangan');
                }
                if (!Schema::hasColumn('layanan_datas', 'surat_permohonan')) {
                    $table->string('surat_permohonan')->nullable()->after('status');
                }
            });
        }

        // Remove jenis_layanan from magangs table if exists
        if (Schema::hasTable('magangs')) {
            Schema::table('magangs', function (Blueprint $table) {
                if (Schema::hasColumn('magangs', 'jenis_layanan')) {
                    $table->dropColumn('jenis_layanan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse these changes
    }
};
