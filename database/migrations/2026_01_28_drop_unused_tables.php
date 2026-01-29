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
        // Drop old/unused konsultasis table - replaced by jasa_konsultasis
        if (Schema::hasTable('konsultasis')) {
            Schema::drop('konsultasis');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate if needed
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
