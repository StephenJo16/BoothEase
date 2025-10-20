<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'floor_number',
        'floor_name',
        'layout_json',
        'booth_count',
    ];

    protected $casts = [
        'layout_json' => 'array',
    ];

    /**
     * Get the event that owns the layout.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all floors for a specific event.
     */
    public static function getFloorsByEvent(int $eventId)
    {
        return static::where('event_id', $eventId)
            ->orderBy('floor_number')
            ->get();
    }

    /**
     * Get a specific floor for an event.
     */
    public static function getFloor(int $eventId, int $floorNumber)
    {
        return static::where('event_id', $eventId)
            ->where('floor_number', $floorNumber)
            ->first();
    }
}
