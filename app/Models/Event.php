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
        'user_id',
        'image_path',
        'capacity',
        'status',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_FINALIZED = 'finalized';
    const STATUS_PUBLISHED = 'published';

    protected $casts = [
        'location' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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

    public function scopeOwnedBy($query, $user)
    {
        $userId = $user instanceof \Illuminate\Contracts\Auth\Authenticatable ? $user->getAuthIdentifier() : $user;

        return $query->where('user_id', $userId);
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

    public function getRegistrationDeadlineAttribute(): ?string
    {
        return $this->location['registration_deadline'] ?? null;
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

    // Method to check if event can be finalized (has booths set up)
    public function canBeFinalized(): bool
    {
        return $this->booths()->exists() &&
            !empty($this->title) &&
            !empty($this->start_time) &&
            !empty($this->end_time);
    }
}
