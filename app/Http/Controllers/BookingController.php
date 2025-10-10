<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boothId = request('booth_id');
        
        if (!$boothId) {
            return redirect()->route('events.index')->with('error', 'No booth selected');
        }
        
        $booth = \App\Models\Booth::with(['event.category', 'event.user'])
            ->findOrFail($boothId);
        
        // Check if booth is available
        if ($booth->status !== 'available') {
            return redirect()->back()->with('error', 'This booth is not available for booking');
        }
        
        $event = $booth->event;
        
        return view('book-booth.index', compact('booth', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
