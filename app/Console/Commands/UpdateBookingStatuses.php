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

        $bookingsToCancel = Booking::where('status', 'confirmed')
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '<=', $now->copy()->subHours(3))
            ->whereDoesntHave('payment', function ($query) {
                $query->where('payment_status', 'completed');
            })
            ->get();

        // Cancel unpaid bookings that have been confirmed for more than 3 hours
        $cancelledCount = 0;
        foreach ($bookingsToCancel as $booking) {
            $booking->status = 'cancelled';
            $booking->save();

            if ($booking->booth) {
                $booking->booth->status = 'available';
                $booking->booth->save();
            }

            // Cancel associated payment if it exists and is not completed
            if ($booking->payment && $booking->payment->payment_status !== 'completed') {
                $booking->payment->payment_status = 'cancelled';
                $booking->payment->save();
            }

            $cancelledCount++;
        }

        // Auto-reject pending bookings when event starts and make booth available
        $bookingsToReject = Booking::where('status', 'pending')
            ->whereHas('booth.event', function ($query) use ($now) {
                $query->where('start_time', '<=', $now);
            })
            ->get();

        $rejectedCount = 0;
        foreach ($bookingsToReject as $booking) {
            $booking->status = 'rejected';
            $booking->rejection_reason = 'Your booking request was not accepted by the organizer before the event started.';
            $booking->save();

            if ($booking->booth) {
                $booking->booth->status = 'available';
                $booking->booth->save();
            }

            $rejectedCount++;
        }

        // Update bookings to 'ongoing' status when event has started (only paid bookings)
        $ongoingCount = Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now);
        })
            ->where('status', 'paid')
            ->update(['status' => 'ongoing']);

        // Update bookings to 'completed' status when event has ended (only ongoing bookings)
        $completedCount = Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('end_time', '<', $now);
        })
            ->where('status', 'ongoing')
            ->update(['status' => 'completed']);

        $total = $ongoingCount + $completedCount + $cancelledCount + $rejectedCount;

        $this->info("Updated {$total} booking(s):");
        $this->info("- {$cancelledCount} unpaid booking(s) cancelled after 3 hours");
        $this->info("- {$rejectedCount} pending booking(s) rejected when event started");
        $this->info("- {$ongoingCount} booking(s) set to 'ongoing'");
        $this->info("- {$completedCount} booking(s) set to 'completed'");

        return Command::SUCCESS;
    }
}
