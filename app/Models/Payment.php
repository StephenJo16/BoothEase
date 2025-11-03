<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'payment_type',
        'payment_channel',
        'payment_status',
        'payment_date',
        'amount',
        'transaction_id',
        'snap_token',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get formatted payment method display name
     */
    public function getFormattedPaymentMethodAttribute(): string
    {
        return formatPaymentMethod($this->payment_method, $this->payment_type, $this->payment_channel);
    }
}
