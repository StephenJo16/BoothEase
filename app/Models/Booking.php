<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PAID = 'paid';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'booth_id',
        'status',
        'booking_date',
        'total_price',
        'notes',
        'confirmed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function refundRequest(): HasOne
    {
        return $this->hasOne(RefundRequest::class);
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Method to get the current status based on associated event dates
    public function getCurrentStatus(): string
    {
        // If booking is cancelled, keep it cancelled
        if ($this->isCancelled()) {
            return self::STATUS_CANCELLED;
        }

        // Get the associated event through booth
        $event = $this->booth->event;

        if (!$event) {
            return $this->status;
        }

        $now = now();

        // If event has started and hasn't ended yet
        if ($event->start_time <= $now && $event->end_time >= $now) {
            return self::STATUS_ONGOING;
        }

        // If event has ended
        if ($event->end_time < $now) {
            return self::STATUS_COMPLETED;
        }

        // Otherwise, return the current status
        return $this->status;
    }

    // Method to update status based on associated event time
    public function updateStatusBasedOnEventTime(): bool
    {
        $currentStatus = $this->getCurrentStatus();

        if ($this->status !== $currentStatus && in_array($currentStatus, [self::STATUS_ONGOING, self::STATUS_COMPLETED])) {
            $this->status = $currentStatus;
            return $this->save();
        }

        return false;
    }
}
