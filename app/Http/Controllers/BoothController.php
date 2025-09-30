<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoothRequest;
use App\Models\Booth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BoothController extends Controller
{
    public function store(StoreBoothRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $layout = json_decode($validated['layout_json'], true);
        $objects = $layout['objects'] ?? [];
        $boothObjects = array_values(array_filter($objects, static function (array $object): bool {
            return ($object['elementType'] ?? null) === 'booth';
        }));

        if (empty($boothObjects)) {
            throw ValidationException::withMessages([
                'layout_json' => 'No booth objects were found in the provided layout data.',
            ]);
        }

        $eventId = $validated['event_id'];
        $replaceExisting = $request->boolean('replace_existing', true);
        $now = now();

        DB::transaction(static function () use ($eventId, $replaceExisting, $boothObjects, $now): void {
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
                    : sprintf('%d×%d', (int) $width, (int) $height);

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

            Booth::insert($payload);
        });

        return response()->json([
            'message' => 'Booths saved successfully.',
            'saved' => count($boothObjects),
        ], 201);
    }
}
