<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'registration_deadline',
        'user_id',
        'image_path',
        'capacity',
        'status',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_FINALIZED = 'finalized';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    protected $casts = [
        'location' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'registration_deadline' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booths(): HasMany
    {
        return $this->hasMany(Booth::class);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Booth::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function scopeOwnedBy($query, $user)
    {
        $userId = $user instanceof \Illuminate\Contracts\Auth\Authenticatable ? $user->getAuthIdentifier() : $user;

        return $query->where('user_id', $userId);
    }

    public function scopeOngoing($query)
    {
        $now = now();
        return $query->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now);
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_time', '<', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    public function getVenueAttribute(): ?string
    {
        return $this->location['venue'] ?? null;
    }

    public function getCityAttribute(): ?string
    {
        return $this->location['city'] ?? null;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->location['address'] ?? null;
    }

    public function getBoothConfigurationAttribute(): array
    {
        return $this->location['booths'] ?? [];
    }

    public function getDisplayLocationAttribute(): ?string
    {
        $venue = $this->venue;
        $city = $this->city;

        if ($venue && $city) {
            return $venue . ', ' . $city;
        }

        return $venue ?: $city;
    }

    // Status helper methods
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isFinalized(): bool
    {
        return $this->status === self::STATUS_FINALIZED;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Method to get the current status based on dates
    public function getCurrentStatus(): string
    {
        $now = now();

        // If event has started and hasn't ended yet
        if ($this->start_time <= $now && $this->end_time >= $now) {
            return self::STATUS_ONGOING;
        }

        // If event has ended
        if ($this->end_time < $now) {
            return self::STATUS_COMPLETED;
        }

        // Otherwise, return the current status
        return $this->status;
    }

    // Method to update status based on current time
    public function updateStatusBasedOnTime(): bool
    {
        $currentStatus = $this->getCurrentStatus();

        if ($this->status !== $currentStatus && in_array($currentStatus, [self::STATUS_ONGOING, self::STATUS_COMPLETED])) {
            $this->status = $currentStatus;
            return $this->save();
        }

        return false;
    }

    // Method to check if event can be finalized (has booths set up)
    public function canBeFinalized(): bool
    {
        return $this->booths()->exists() &&
            !empty($this->title) &&
            !empty($this->start_time) &&
            !empty($this->end_time);
    }
}
