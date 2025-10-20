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
        Schema::table('event_layouts', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['event_id']);

            // Drop the unique constraint on event_id
            $table->dropUnique(['event_id']);
        });

        Schema::table('event_layouts', function (Blueprint $table) {
            // Add floor_number and floor_name columns
            $table->unsignedInteger('floor_number')->default(1)->after('event_id');
            $table->string('floor_name', 100)->default('Floor 1')->after('floor_number');

            // Add composite unique constraint for event_id and floor_number
            $table->unique(['event_id', 'floor_number'], 'event_layouts_event_floor_unique');

            // Re-add the foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_layouts', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['event_id']);

            // Drop the composite unique constraint
            $table->dropUnique('event_layouts_event_floor_unique');

            // Drop the floor columns
            $table->dropColumn(['floor_number', 'floor_name']);
        });

        Schema::table('event_layouts', function (Blueprint $table) {
            // Restore the original unique constraint on event_id
            $table->unique('event_id');

            // Re-add the foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });
    }
};
