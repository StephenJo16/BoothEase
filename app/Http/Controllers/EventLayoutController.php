<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoothRequest;
use App\Http\Controllers\BoothController;
use App\Models\Booth;
use App\Models\Event;
use App\Models\EventLayout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventLayoutController extends Controller
{
    public function saveLayout(StoreBoothRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $layoutData = json_decode($validated['layout_json'], true);

        if (! is_array($layoutData)) {
            throw ValidationException::withMessages([
                'layout_json' => 'The layout data could not be read.',
            ]);
        }

        $objects = $layoutData['objects'] ?? [];
        $boothObjects = array_values(array_filter($objects, static function (array $object): bool {
            return ($object['elementType'] ?? null) === 'booth';
        }));

        if (empty($boothObjects)) {
            throw ValidationException::withMessages([
                'layout_json' => 'At least one booth is required. Please add at least one booth to your layout before saving.',
            ]);
        }

        $eventId = $validated['event_id'];
        $floorNumber = $request->input('floor_number', 1);
        $floorName = $request->input('floor_name', 'Floor ' . $floorNumber);

        // Ensure the user owns the event before allowing booth creation
        $event = Event::ownedBy($request->user())->findOrFail($eventId);

        $replaceExisting = $request->boolean('replace_existing', true);
        $boothCount = count($boothObjects);
        $now = now();

        DB::transaction(static function () use ($eventId, $floorNumber, $floorName, $replaceExisting, $boothObjects, $layoutData, $boothCount): void {
            // Create booths using BoothController
            BoothController::createBooths($eventId, $floorNumber, $boothObjects, $replaceExisting);

            EventLayout::updateOrCreate(
                [
                    'event_id' => $eventId,
                    'floor_number' => $floorNumber,
                ],
                [
                    'floor_name' => $floorName,
                    'layout_json' => $layoutData,
                    'booth_count' => $boothCount,
                ]
            );

            // Update event status to finalized when booths are added
            Event::where('id', $eventId)->update(['status' => Event::STATUS_FINALIZED]);
        });

        return response()->json([
            'message' => 'Booths and layout saved successfully for ' . $floorName . '. Event status updated to finalized.',
            'saved' => $boothCount,
            'floor_number' => $floorNumber,
            'floor_name' => $floorName,
        ], 201);
    }

    public function viewLayout(int $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Check authorization: Owner or Published
        $user = request()->user();
        $isOwner = $user && $user->id === $event->user_id;
        // Allow access if event is published OR user is owner
        // Note: 'ongoing' and 'completed' events were once published, so they should be visible too if needed.
        // Assuming public should see layout for published/ongoing/completed events.
        $publicStatuses = [Event::STATUS_PUBLISHED, Event::STATUS_ONGOING, Event::STATUS_COMPLETED];

        if (!in_array($event->status, $publicStatuses) && !$isOwner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $floorNumber = request()->input('floor_number', 1);

        $layout = EventLayout::where('event_id', $eventId)
            ->where('floor_number', $floorNumber)
            ->first();

        if (! $layout) {
            return response()->json([
                'message' => 'No saved layout found for this floor.',
            ], 404);
        }

        $booths = Booth::where('event_id', $eventId)
            ->where('floor_number', $floorNumber)
            ->orderBy('name')
            ->get(['id', 'event_id', 'floor_number', 'name', 'size', 'type', 'price', 'status']);

        // Get all floors for this event
        $allFloors = EventLayout::where('event_id', $eventId)
            ->orderBy('floor_number')
            ->get(['floor_number', 'floor_name', 'booth_count']);

        return response()->json([
            'event' => $event,
            'layout' => $layout->layout_json,
            'booth_count' => $layout->booth_count,
            'booths' => $booths,
            'current_floor' => [
                'floor_number' => $layout->floor_number,
                'floor_name' => $layout->floor_name,
            ],
            'all_floors' => $allFloors,
        ]);
    }

    /**
     * Get all floors for an event
     */
    public function getFloors(int $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Check authorization: Owner or Published
        $user = request()->user();
        $isOwner = $user && $user->id === $event->user_id;
        $publicStatuses = [Event::STATUS_PUBLISHED, Event::STATUS_ONGOING, Event::STATUS_COMPLETED];

        if (!in_array($event->status, $publicStatuses) && !$isOwner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $floors = EventLayout::where('event_id', $eventId)
            ->orderBy('floor_number')
            ->get(['id', 'floor_number', 'floor_name', 'booth_count', 'created_at', 'updated_at']);

        return response()->json([
            'floors' => $floors,
            'total' => $floors->count(),
        ]);
    }

    /**
     * Delete a specific floor
     */
    public function deleteFloor(int $eventId, int $floorNumber): JsonResponse
    {
        $event = Event::findOrFail($eventId);

        // Ensure ownership
        if ($event->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $layout = EventLayout::where('event_id', $eventId)
            ->where('floor_number', $floorNumber)
            ->first();

        if (!$layout) {
            return response()->json([
                'message' => 'Floor not found.',
            ], 404);
        }

        // Delete associated booths
        Booth::where('event_id', $eventId)
            ->where('floor_number', $floorNumber)
            ->delete();

        // Delete the layout
        $layout->delete();

        return response()->json([
            'message' => 'Floor deleted successfully.',
        ]);
    }
}
