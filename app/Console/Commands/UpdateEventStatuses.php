<?php

namespace App\Console\Commands;

use App\Http\Controllers\EventController;
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
        $controller = new EventController();
        $results = $controller->updateEventStatuses();

        $this->info("Updated {$results['total']} event(s):");
        $this->info("- {$results['ongoing']} event(s) set to 'ongoing'");
        $this->info("- {$results['completed']} event(s) set to 'completed'");

        return Command::SUCCESS;
    }
}
