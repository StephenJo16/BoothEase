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
        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',
        'venue',
        'address',
        'start_time',
        'end_time',
        'registration_deadline',
        'user_id',
        'image_path',
        'capacity',
        'booth_configuration',
        'status',
        'refundable',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_FINALIZED = 'finalized';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    protected $casts = [
        'booth_configuration' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'registration_deadline' => 'date',
        'refundable' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
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

    public function getDisplayLocationAttribute(): ?string
    {
        $parts = array_filter([
            $this->venue,
            $this->city?->name,
            $this->province?->name,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
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

    public function isRefundable(): bool
    {
        return $this->refundable === true;
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
