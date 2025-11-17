<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Event;

class UpdateBookingStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update booking statuses based on associated event date and time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // Cancel unpaid bookings that have been confirmed for more than 3 hours
        $cancelledCount = Booking::where('status', 'confirmed')
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '<=', $now->copy()->subHours(3))
            ->whereDoesntHave('payment', function ($query) {
                $query->where('payment_status', 'completed');
            })
            ->update(['status' => 'cancelled']);

        // Update bookings to 'ongoing' status when event has started
        $ongoingCount = Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now);
        })
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->update(['status' => 'ongoing']);

        // Update bookings to 'completed' status when event has ended
        $completedCount = Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('end_time', '<', $now);
        })
            ->whereIn('status', ['pending', 'confirmed', 'paid', 'ongoing'])
            ->update(['status' => 'completed']);

        $total = $ongoingCount + $completedCount + $cancelledCount;

        $this->info("Updated {$total} booking(s):");
        $this->info("- {$cancelledCount} unpaid booking(s) cancelled after 3 hours");
        $this->info("- {$ongoingCount} booking(s) set to 'ongoing'");
        $this->info("- {$completedCount} booking(s) set to 'completed'");

        return Command::SUCCESS;
    }
}
