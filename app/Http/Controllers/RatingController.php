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
     * Store a newly created rating for a booking.
     */
    public function store(Request $request, Booking $booking)
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

        // Create the rating
        $rating = Rating::create([
            'event_id' => $booking->booth->event_id,
            'rater_id' => Auth::id(),
            'ratee_id' => $booking->booth->event->user_id, // Event organizer
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!',
            'rating' => $rating
        ]);
    }

    /**
     * Check if user has already rated a booking
     */
    public function checkRating(Booking $booking)
    {
        $rating = Rating::where('event_id', $booking->booth->event_id)
            ->where('rater_id', Auth::id())
            ->first();

        return response()->json([
            'has_rated' => $rating !== null,
            'rating' => $rating
        ]);
    }

    /**
     * Store a rating from organizer to tenant
     */
    public function storeOrganizerRating(Request $request, $eventId, $bookingId)
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

        // Create the rating
        $rating = Rating::create([
            'event_id' => $event->id,
            'rater_id' => Auth::id(), // Event organizer
            'ratee_id' => $booking->user_id, // Tenant
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!',
            'rating' => $rating
        ]);
    }

    /**
     * Check if organizer has already rated a tenant for a specific booking
     */
    public function checkOrganizerRating($eventId, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $event = $booking->booth->event;

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
