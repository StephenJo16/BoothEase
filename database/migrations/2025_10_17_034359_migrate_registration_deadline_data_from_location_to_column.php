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
        // Migrate registration_deadline from location JSON to dedicated column
        $events = DB::table('events')->whereNotNull('location')->get();

        foreach ($events as $event) {
            $location = json_decode($event->location, true);

            if (isset($location['registration_deadline']) && !empty($location['registration_deadline'])) {
                // Update the registration_deadline column
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['registration_deadline' => $location['registration_deadline']]);

                // Remove registration_deadline from location JSON
                unset($location['registration_deadline']);
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['location' => json_encode($location)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move registration_deadline back to location JSON
        $events = DB::table('events')->whereNotNull('registration_deadline')->get();

        foreach ($events as $event) {
            $location = json_decode($event->location, true) ?: [];
            $location['registration_deadline'] = $event->registration_deadline;

            DB::table('events')
                ->where('id', $event->id)
                ->update([
                    'location' => json_encode($location),
                    'registration_deadline' => null
                ]);
        }
    }
};
