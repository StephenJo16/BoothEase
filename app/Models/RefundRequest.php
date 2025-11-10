<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundRequest extends Model
{
    /** @use HasFactory<\Database\Factories\RefundRequestFactory> */
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'booking_id',
        'reason',
        'status',
        'account_holder_name',
        'bank_name',
        'account_number',
        'document',
        'refund_amount',
        'processing_fee',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'refund_amount' => 'integer',
        'processing_fee' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if refund can be requested for this booking
     */
    public static function canRequestRefund(Booking $booking): bool
    {
        // Get the event through the booth
        $event = $booking->booth->event;

        // Check if event allows refunds
        if (!$event->isRefundable()) {
            return false;
        }

        // Check if booking is paid
        if (!$booking->isPaid()) {
            return false;
        }

        // Check if there's already a pending or approved refund request
        $existingRequest = self::where('booking_id', $booking->id)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->exists();

        return !$existingRequest;
    }
}
