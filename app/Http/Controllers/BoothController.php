<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoothRequest;
use App\Models\Booth;
use App\Models\Event;
use App\Models\EventLayout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BoothController extends Controller
{
    public function store(StoreBoothRequest $request): JsonResponse
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

        // Ensure the user owns the event before allowing booth creation
        $event = Event::ownedBy($request->user())->findOrFail($eventId);

        $replaceExisting = $request->boolean('replace_existing', true);
        $boothCount = count($boothObjects);
        $now = now();

        DB::transaction(static function () use ($eventId, $replaceExisting, $boothObjects, $now, $layoutData, $boothCount): void {
            if ($replaceExisting) {
                Booth::where('event_id', $eventId)->delete();
            }

            $payload = [];

            foreach ($boothObjects as $index => $object) {
                $label = $object['elementLabel'] ?? 'Booth ' . ($index + 1);
                $width = $object['originalWidth'] ?? $object['width'] ?? null;
                $height = $object['originalHeight'] ?? $object['height'] ?? null;

                if ($width !== null && isset($object['scaleX'])) {
                    $width = round((float) $width * (float) $object['scaleX']);
                }

                if ($height !== null && isset($object['scaleY'])) {
                    $height = round((float) $height * (float) $object['scaleY']);
                }

                $size = ($width === null || $height === null)
                    ? 'unspecified'
                    : sprintf('%dx%d', (int) $width, (int) $height);

                $payload[] = [
                    'event_id' => $eventId,
                    'number' => $label,
                    'size' => $size,
                    'type' => $object['boothType'] ?? 'Standard',
                    'price' => (int) round($object['boothPrice'] ?? 0),
                    'status' => 'available',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($payload !== []) {
                Booth::insert($payload);
            }

            EventLayout::updateOrCreate(
                ['event_id' => $eventId],
                [
                    'layout_json' => $layoutData,
                    'booth_count' => $boothCount,
                ]
            );

            // Update event status to finalized when booths are added
            Event::where('id', $eventId)->update(['status' => Event::STATUS_FINALIZED]);
        });

        return response()->json([
            'message' => 'Booths and layout saved successfully. Event status updated to finalized.',
            'saved' => $boothCount,
        ], 201);
    }

    public function show(int $eventId): JsonResponse
    {
        $layout = EventLayout::where('event_id', $eventId)->first();

        if (! $layout) {
            return response()->json([
                'message' => 'No saved layout found for this event.',
            ], 404);
        }

        $event = Event::find($eventId);
        $booths = Booth::where('event_id', $eventId)
            ->orderBy('number')
            ->get(['id', 'event_id', 'number', 'size', 'type', 'price', 'status']);

        return response()->json([
            'event' => $event,
            'layout' => $layout->layout_json,
            'booth_count' => $layout->booth_count,
            'booths' => $booths,
        ]);
    }
}
