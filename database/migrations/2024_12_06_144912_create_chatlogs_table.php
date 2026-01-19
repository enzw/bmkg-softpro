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
        Schema::create('chatlogs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('answer');
            $table->timestamps();
            $table->string('session_id');
            $table->string('intent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatlogs');
    }
};
