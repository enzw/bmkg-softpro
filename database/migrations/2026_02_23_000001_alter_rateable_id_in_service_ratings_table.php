<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter rateable_id from UUID to VARCHAR to support both UUIDs and BIGINTs
        DB::statement('ALTER TABLE service_ratings ALTER COLUMN rateable_id TYPE VARCHAR(36)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If reversing, this might fail if string cannot be cast to uuid implicitly
        DB::statement('ALTER TABLE service_ratings ALTER COLUMN rateable_id TYPE UUID USING rateable_id::uuid');
    }
};
