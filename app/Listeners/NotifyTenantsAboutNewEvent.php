<?php

namespace App\Listeners;

use App\Events\EventPublished;
use App\Models\User;
use App\Notifications\NewEventPublished;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class NotifyTenantsAboutNewEvent
{
    /**
     * Handle the event.
     */
    public function handle(EventPublished $event): void
    {
        try {
            // Get the event with its category
            $publishedEvent = $event->event->load('category');

            // If event has no category, skip notification
            if (!$publishedEvent->category_id) {
                Log::info("Event {$publishedEvent->id} has no category, skipping tenant notifications");
                return;
            }

            // Find all tenants with matching category_id
            // Assuming role_id 2 is for tenants - adjust if different
            $matchingTenants = User::where('category_id', $publishedEvent->category_id)
                ->where('role_id', 2) // Tenant role
                ->whereNotNull('email')
                ->get();

            if ($matchingTenants->isEmpty()) {
                Log::info("No matching tenants found for category_id: {$publishedEvent->category_id}");
                return;
            }

            // Send notification to all matching tenants (queued automatically)
            Notification::send($matchingTenants, new NewEventPublished($publishedEvent));

            Log::info("Queued event notifications for {$matchingTenants->count()} tenants for event: {$publishedEvent->title}");
        } catch (\Exception $e) {
            // Log error but don't fail the event publishing
            Log::error('Failed to notify tenants about new event: ' . $e->getMessage());
        }
    }
}
