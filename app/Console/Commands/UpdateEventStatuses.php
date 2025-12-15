<?php

namespace App\Console\Commands;

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

        // Update events to 'ongoing' status (only for published events)
        $ongoingCount = \App\Models\Event::where('status', \App\Models\Event::STATUS_PUBLISHED)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->update(['status' => \App\Models\Event::STATUS_ONGOING]);

        // Update events to 'completed' status (only for ongoing events)
        $completedCount = \App\Models\Event::where('status', \App\Models\Event::STATUS_ONGOING)
            ->where('end_time', '<', $now)
            ->update(['status' => \App\Models\Event::STATUS_COMPLETED]);

        $total = $ongoingCount + $completedCount;

        $this->info("Updated {$total} event(s):");
        $this->info("- {$ongoingCount} event(s) set to 'ongoing'");
        $this->info("- {$completedCount} event(s) set to 'completed'");

        return Command::SUCCESS;
    }
}
