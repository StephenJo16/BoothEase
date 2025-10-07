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
    ];

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

    public function getStatusAttribute(): string
    {
        return $this->image_path === 'published' ? 'published' : 'draft';
    }

    public function setStatusAttribute(string $status): void
    {
        $this->image_path = $status;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
