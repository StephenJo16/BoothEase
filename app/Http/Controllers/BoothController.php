<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Event;

class BoothController extends Controller
{
    /**
     * Count available booths for an event or a query builder instance
     * 
     * @param \Illuminate\Database\Eloquent\Builder|\App\Models\Event|int $eventOrQuery
     * @return int
     */
    public static function countAvailableBooths($eventOrQuery): int
    {
        if ($eventOrQuery instanceof Event) {
            return $eventOrQuery->booths()->where('status', 'available')->count();
        }

        if (is_numeric($eventOrQuery)) {
            return Booth::where('event_id', $eventOrQuery)
                ->where('status', 'available')
                ->count();
        }

        // If it's already a query builder for booths relationship
        return $eventOrQuery->where('status', 'available')->count();
    }

    /**
     * Get paginated booths for an event
     * 
     * @param \App\Models\Event $event
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getBooths(Event $event, int $perPage = 5)
    {
        return $event->booths()
            ->orderBy('floor_number')
            ->orderByRaw('LENGTH(name), name')
            ->paginate($perPage);
    }

    /**
     * Create booths from layout objects
     * 
     * @param int $eventId
     * @param int $floorNumber
     * @param array $boothObjects
     * @param bool $replaceExisting
     * @return int Number of booths created
     */
    public static function createBooths(int $eventId, int $floorNumber, array $boothObjects, bool $replaceExisting = true): int
    {
        if ($replaceExisting) {
            // Only delete booths for this specific floor
            Booth::where('event_id', $eventId)
                ->where('floor_number', $floorNumber)
                ->delete();
        }

        $payload = [];
        $now = now();

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
                'floor_number' => $floorNumber,
                'name' => $label,
                'size' => $size,
                'type' => $object['boothType'] ?? 'Standard',
                'price' => (int) round($object['boothPrice'] ?? 0),
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($payload)) {
            Booth::insert($payload);
        }

        return count($payload);
    }
}
