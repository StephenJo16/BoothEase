<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update event statuses based on current date and time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $updatedCount = 0;

        // Update events to 'ongoing' status
        $ongoingCount = Event::where('status', '!=', Event::STATUS_COMPLETED)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->update(['status' => Event::STATUS_ONGOING]);

        // Update events to 'completed' status
        $completedCount = Event::where('status', '!=', Event::STATUS_COMPLETED)
            ->where('end_time', '<', $now)
            ->update(['status' => Event::STATUS_COMPLETED]);

        $updatedCount = $ongoingCount + $completedCount;

        $this->info("Updated {$updatedCount} event(s):");
        $this->info("- {$ongoingCount} event(s) set to 'ongoing'");
        $this->info("- {$completedCount} event(s) set to 'completed'");

        return Command::SUCCESS;
    }
}
