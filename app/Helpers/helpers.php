<?php

// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
    function formatRupiah($value)
    {
        $digits = preg_replace('/\D/', '', (string) $value);
        $num = $digits === '' ? 0 : intval($digits);
        return 'Rp' . number_format($num, 0, ',', '.');
    }
}

// Helper function to get booking status display properties
if (!function_exists('getBookingStatusDisplay')) {
    function getBookingStatusDisplay($status)
    {
        $statusMap = [
            'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
            'confirmed' => ['label' => 'Confirmed', 'class' => 'bg-green-100 text-green-800'],
            'paid' => ['label' => 'Paid', 'class' => 'bg-blue-100 text-blue-800'],
            'ongoing' => ['label' => 'Ongoing', 'class' => 'bg-purple-100 text-purple-800'],
            'completed' => ['label' => 'Completed', 'class' => 'bg-green-800 text-green-100'],
            'rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
            'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
        ];

        return $statusMap[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
    }
}

// Helper function to get event status display properties
if (!function_exists('getEventStatusDisplay')) {
    function getEventStatusDisplay($status)
    {
        $statusMap = [
            'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
            'finalized' => ['label' => 'Finalized', 'class' => 'bg-blue-100 text-blue-800'],
            'published' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
            'ongoing' => ['label' => 'Ongoing', 'class' => 'bg-purple-100 text-purple-800'],
            'completed' => ['label' => 'Completed', 'class' => 'bg-green-800 text-green-100'],
        ];

        return $statusMap[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
    }
}

// Helper function to get booth status display properties
if (!function_exists('getBoothStatusDisplay')) {
    function getBoothStatusDisplay($status)
    {
        $statusMap = [
            'available' => ['label' => 'Available', 'class' => 'bg-green-100 text-green-800'],
            'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
            'booked' => ['label' => 'Booked', 'class' => 'bg-red-100 text-red-800'],
        ];

        return $statusMap[strtolower($status)] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
    }
}

// Helper function to format payment method display name
if (!function_exists('formatPaymentMethod')) {
    function formatPaymentMethod($paymentMethod, $paymentType = null, $paymentChannel = null)
    {
        if (!$paymentType) {
            return ucfirst($paymentMethod);
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
        if ($paymentType === 'bank_transfer' && $paymentChannel) {
            $bankMap = [
                'permata' => 'Permata Virtual Account',
                'bca' => 'BCA Virtual Account',
                'bni' => 'BNI Virtual Account',
                'bri' => 'BRI Virtual Account',
                'mandiri' => 'Mandiri Virtual Account',
                'cimb' => 'CIMB Niaga Virtual Account',
            ];
            return $bankMap[strtolower($paymentChannel)] ?? ucfirst($paymentChannel) . ' Virtual Account';
        }

        return $paymentTypeMap[$paymentType] ?? ucfirst(str_replace('_', ' ', $paymentType));
    }
}

// Helper function to format phone numbers with Indonesian country code and dashes every 4 digits
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($number)
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if ($digits === '') {
            return $number;
        }

        if (substr($digits, 0, 2) === '62') {
            $country = '+62';
            $rest = substr($digits, 2);
        } elseif (substr($digits, 0, 1) === '0') {
            $country = '+62';
            $rest = ltrim($digits, '0');
        } else {
            return $number;
        }

        if ($rest === '') {
            return $country;
        }

        if (strlen($rest) <= 3) {
            $formattedRest = $rest;
        } else {
            $firstBlock = substr($rest, 0, 3);
            $remaining = substr($rest, 3);
            $chunks = str_split($remaining, 4);
            $formattedRest = $firstBlock . ($chunks ? '-' . implode('-', $chunks) : '');
        }

        return trim($country . ' ' . $formattedRest);
    }
}

// Helper function to format event date display
if (!function_exists('formatEventDate')) {
    function formatEventDate($event)
    {
        if (!$event) {
            return 'Schedule to be announced';
        }

        if ($event->start_time && $event->end_time) {
            $startDate = $event->start_time->format('d M Y');
            $endDate = $event->end_time->format('d M Y');
            return $event->start_time->isSameDay($event->end_time) ? $startDate : "{$startDate} - {$endDate}";
        } elseif ($event->start_time) {
            return $event->start_time->format('d M Y');
        } elseif ($event->end_time) {
            return $event->end_time->format('d M Y');
        }

        return 'Schedule to be announced';
    }
}

// Helper function to format event time display
if (!function_exists('formatEventTime')) {
    function formatEventTime($event)
    {
        if (!$event) {
            return null;
        }

        if ($event->start_time && $event->end_time) {
            return $event->start_time->format('H:i') . ' - ' . $event->end_time->format('H:i');
        } elseif ($event->start_time) {
            return $event->start_time->format('H:i');
        } elseif ($event->end_time) {
            return $event->end_time->format('H:i');
        }

        return null;
    }
}

// Helper function to calculate event duration in days
if (!function_exists('getEventDuration')) {
    function getEventDuration($event)
    {
        if (!$event || !$event->start_time || !$event->end_time) {
            return 0;
        }

        return floor($event->start_time->diffInDays($event->end_time)) + 1;
    }
}

// Helper function to get category color scheme
if (!function_exists('getCategoryColors')) {
    function getCategoryColors($categoryId)
    {
        $schemes = [
            ['color' => 'text-blue-600', 'gradient' => ['from-blue-400', 'to-blue-600']],
            ['color' => 'text-green-600', 'gradient' => ['from-green-400', 'to-green-600']],
            ['color' => 'text-purple-600', 'gradient' => ['from-purple-400', 'to-purple-600']],
            ['color' => 'text-red-600', 'gradient' => ['from-red-400', 'to-red-600']],
            ['color' => 'text-orange-600', 'gradient' => ['from-yellow-400', 'to-orange-500']],
            ['color' => 'text-indigo-600', 'gradient' => ['from-indigo-400', 'to-indigo-600']],
            ['color' => 'text-pink-600', 'gradient' => ['from-pink-400', 'to-pink-600']],
            ['color' => 'text-teal-600', 'gradient' => ['from-teal-400', 'to-teal-600']],
            ['color' => 'text-cyan-600', 'gradient' => ['from-cyan-400', 'to-cyan-600']],
        ];

        $index = ($categoryId ?? 0) % count($schemes);
        return $schemes[$index];
    }
}
