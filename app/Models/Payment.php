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
        if (!$this->payment_type) {
            return ucfirst($this->payment_method);
        }

        $paymentTypeMap = [
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'credit_card' => 'Credit Card',
            'cimb_clicks' => 'CIMB Clicks',
            'bca_klikpay' => 'BCA KlikPay',
            'bca_klikbca' => 'BCA KlikBCA',
            'mandiri_clickpay' => 'Mandiri Clickpay',
            'bri_epay' => 'BRI e-Pay',
            'echannel' => 'Mandiri Bill Payment',
            'permata_va' => 'Permata Virtual Account',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'other_va' => 'Bank Transfer',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
            'akulaku' => 'Akulaku',
        ];

        // For bank_transfer, check if we have payment_channel info
        if ($this->payment_type === 'bank_transfer' && $this->payment_channel) {
            $bankMap = [
                'permata' => 'Permata Virtual Account',
                'bca' => 'BCA Virtual Account',
                'bni' => 'BNI Virtual Account',
                'bri' => 'BRI Virtual Account',
                'mandiri' => 'Mandiri Virtual Account',
                'cimb' => 'CIMB Niaga Virtual Account',
            ];
            return $bankMap[strtolower($this->payment_channel)] ?? ucfirst($this->payment_channel) . ' Virtual Account';
        }

        return $paymentTypeMap[$this->payment_type] ?? ucfirst(str_replace('_', ' ', $this->payment_type));
    }
}
