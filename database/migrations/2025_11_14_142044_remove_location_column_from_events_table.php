<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add booth_configuration column to replace location->booths
            if (!Schema::hasColumn('events', 'booth_configuration')) {
                $table->json('booth_configuration')->nullable()->after('capacity');
            }
        });

        // Migrate booth configuration data from location to new column
        DB::statement("UPDATE events SET booth_configuration = JSON_EXTRACT(location, '$.booths') WHERE location IS NOT NULL AND JSON_EXTRACT(location, '$.booths') IS NOT NULL");

        Schema::table('events', function (Blueprint $table) {
            // Remove location column
            if (Schema::hasColumn('events', 'location')) {
                $table->dropColumn('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Re-add location column
            if (!Schema::hasColumn('events', 'location')) {
                $table->json('location')->nullable()->after('description');
            }
        });

        // Migrate booth configuration back to location
        DB::statement("UPDATE events SET location = JSON_OBJECT('booths', booth_configuration) WHERE booth_configuration IS NOT NULL");

        Schema::table('events', function (Blueprint $table) {
            // Remove booth_configuration column
            if (Schema::hasColumn('events', 'booth_configuration')) {
                $table->dropColumn('booth_configuration');
            }
        });
    }
};
