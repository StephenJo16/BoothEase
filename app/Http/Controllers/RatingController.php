<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Booking;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * HTTP endpoint to store a rating for an organizer.
     */
    public function rateOrganizerEndpoint(Request $request, Booking $booking)
    {
        // Validate that the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Validate that the booking is completed
        if ($booking->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate completed bookings.'
            ], 400);
        }

        // Check if user has already rated this booking
        $existingRating = Rating::where('event_id', $booking->booth->event_id)
            ->where('rater_id', Auth::id())
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this event.'
            ], 400);
        }

        // Validate the request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Call the core business logic method
        $rating = $this->rateOrganizer(
            Auth::id(),
            $booking->booth->event->user_id,
            $booking->booth->event_id,
            $validated['rating'],
            $validated['feedback'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!',
            'rating' => $rating
        ]);
    }

    /**
     * Core business logic: Create a rating for an organizer.
     */
    public function rateOrganizer(int $raterId, int $rateeId, int $eventId, int $rating, ?string $feedback)
    {
        return Rating::create([
            'event_id' => $eventId,
            'rater_id' => $raterId,
            'ratee_id' => $rateeId,
            'rating' => $rating,
            'feedback' => $feedback,
        ]);
    }

    /**
     * Check if user has already rated a booking
     */
    public function checkRating(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $rating = Rating::where('event_id', $booking->booth->event_id)
            ->where('rater_id', Auth::id())
            ->first();

        return response()->json([
            'has_rated' => $rating !== null,
            'rating' => $rating
        ]);
    }

    /**
     * HTTP endpoint to store a rating from organizer to tenant.
     */
    public function rateTenantEndpoint(Request $request, $eventId, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $event = $booking->booth->event;

        // Validate that the event belongs to the authenticated user (organizer)
        if ($event->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Validate that the booking is completed
        if ($booking->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate completed bookings.'
            ], 400);
        }

        // Check if organizer has already rated this tenant for this event
        $existingRating = Rating::where('event_id', $event->id)
            ->where('rater_id', Auth::id())
            ->where('ratee_id', $booking->user_id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this tenant for this event.'
            ], 400);
        }

        // Validate the request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Call the core business logic method
        $rating = $this->rateTenant(
            Auth::id(),
            $booking->user_id,
            $event->id,
            $validated['rating'],
            $validated['feedback'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!',
            'rating' => $rating
        ]);
    }

    /**
     * Core business logic: Create a rating from organizer to tenant.
     */
    public function rateTenant(int $raterId, int $rateeId, int $eventId, int $rating, ?string $feedback)
    {
        return Rating::create([
            'event_id' => $eventId,
            'rater_id' => $raterId,
            'ratee_id' => $rateeId,
            'rating' => $rating,
            'feedback' => $feedback,
        ]);
    }

    /**
     * Check if organizer has already rated a tenant for a specific booking
     */
    public function checkOrganizerRating($eventId, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $event = $booking->booth->event;

        // Validate that the event belongs to the authenticated user (organizer)
        if ($event->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $rating = Rating::where('event_id', $event->id)
            ->where('rater_id', Auth::id())
            ->where('ratee_id', $booking->user_id)
            ->first();

        return response()->json([
            'has_rated' => $rating !== null,
            'rating' => $rating
        ]);
    }
}
