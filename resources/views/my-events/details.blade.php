<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Details</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


@php
$location = is_array($event->location) ? $event->location : [];
$booths = $event->booth_configuration;
$status = $event->status;
$statusStyles = [
'published' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
'finalized' => ['label' => 'Finalized', 'class' => 'bg-blue-100 text-blue-800'],
'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
'ongoing' => ['label' => 'Ongoing', 'class' => 'bg-purple-100 text-purple-800'],
'completed' => ['label' => 'Completed', 'class' => 'bg-gray-100 text-gray-800'],
];
$badge = $statusStyles[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
$start = $event->start_time ? $event->start_time->format('d M Y, H:i') : null;
$end = $event->end_time ? $event->end_time->format('d M Y, H:i') : null;
$deadlineFormatted = $event->registration_deadline ? $event->registration_deadline->format('d M Y') : null;

// Define table headers
$headers = [
['title' => 'Booth Number', 'class' => 'text-left'],
['title' => 'Type', 'class' => 'text-left'],
['title' => 'Price', 'class' => 'text-left'],
['title' => 'Size', 'class' => 'text-left'],
['title' => 'Status', 'class' => 'text-left'],
];

// Transform booths data into rows format
$rows = [];
foreach($event->booths as $booth) {
$isAvailable = $booth->status === 'available';
$isBooked = $booth->status === 'booked';
$isPending = $booth->status === 'pending';
$statusColor = $isAvailable ? 'bg-green-100 text-green-800' : ($isBooked ? 'bg-red-100 text-red-800' : ($isPending ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'));
$statusText = ucfirst($booth->status ?? 'Available');

$rows[] = [
'rowClass' => 'hover:bg-gray-50',
'cells' => [
[
'content' => $booth->number ?? '—',
'class' => 'font-medium text-gray-900'
],
[
'content' => ucfirst($booth->type ?? '—'),
'class' => 'text-gray-700'
],
[
'content' => formatRupiah($booth->price ?? 0),
'class' => 'text-gray-700'
],
[
'content' => $booth->size ? $booth->size : '—',
'class' => 'text-gray-700'
],
[
'content' => '<span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ' . $statusColor . '">' . $statusText . '</span>',
'class' => ''
],
]
];
}

$boothCount = count($rows);
$boothCountText = $boothCount === 0 ? 'No booths configured' : ($boothCount === 1 ? '1 booth configured' : "$boothCount booths configured");

// Paid Bookings Data (including ongoing and completed)
$paidBookings = $event->booths->flatMap->bookings->whereIn('status', ['paid', 'ongoing', 'completed']);
$paidBookingsCount = $paidBookings->count();
$totalRevenue = $paidBookings->sum('total_price');

// Define paid bookings table headers
$paidBookingHeaders = [
['title' => 'Booking ID', 'class' => 'text-left'],
['title' => 'Tenant', 'class' => 'text-left'],
['title' => 'Booth Number', 'class' => 'text-left'],
['title' => 'Booking Date', 'class' => 'text-left'],
['title' => 'Payment Method', 'class' => 'text-left'],
['title' => 'Actions', 'class' => 'text-center'],
];

// Transform paid bookings data into rows format
$paidBookingRows = [];
foreach($paidBookings as $booking) {

$paidBookingRows[] = [
'rowClass' => 'hover:bg-gray-50',
'cells' => [
[
'content' => '#' . $booking->id,
'class' => 'font-medium text-gray-900'
],
[
'content' => $booking->user->name ?? 'Unknown',
'class' => 'text-gray-700'
],
[
'content' => $booking->booth->number ?? '—',
'class' => 'text-gray-700'
],
[
'content' => $booking->booking_date ? $booking->booking_date->format('d M Y') : '—',
'class' => 'text-gray-700'
],
[
'content' => $booking->payment ? $booking->payment->formatted_payment_method : 'N/A',
'class' => 'text-gray-700'
],
[
'content' => '<div class="flex items-center justify-center gap-2">' .
    '<a href="' . route('attendant.details', ['event' => $event->id, 'booking' => $booking->id]) . '" class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-[#ff7700] rounded hover:bg-[#e66600] transition-colors">' .
        '<i class="fas fa-eye mr-1"></i> View Details' .
        '</a>' .
    '</div>',
'class' => 'text-center'
],
]
];
}
@endphp


<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="min-h-screen py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['text' => 'Back to My Events', 'url' => route('my-events.index')])

            <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                    <p class="mt-2 text-sm text-gray-500">Created {{ $event->created_at?->format('d M Y H:i') }} - Last updated {{ $event->updated_at?->format('d M Y H:i') }}</p>
                </div>
                <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $badge['class'] }}">
                    {{ $badge['label'] }}
                </span>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-[2fr_1fr]">
                <section class="space-y-6 rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                    <header>
                        <h2 class="text-lg font-semibold text-gray-900">Overview</h2>
                        <p class="text-sm text-gray-500">Key information about the event.</p>
                    </header>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Category</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ optional($event->category)->name ?: 'Uncategorised' }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Capacity</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $event->capacity ?: 'Not specified' }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Starts</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $start ?: 'Schedule to be announced' }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Ends</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $end ?: 'Schedule to be announced' }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Registration deadline</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $deadlineFormatted ?: 'Not set' }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-gray-500">Location</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $event->display_location ?: 'To be confirmed' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Description</h3>
                        <div class="prose mt-3 max-w-none text-sm text-gray-700">
                            @if($event->description)
                            {!! nl2br(e($event->description)) !!}
                            @else
                            <p>No description has been provided for this event yet.</p>
                            @endif
                        </div>
                    </div>
                </section>

                <aside class="space-y-6">
                    @if(in_array($event->status, ['published', 'ongoing', 'completed']))
                    <section class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Booking Statistics</h2>

                        @php
                        $totalBookings = $event->booths->flatMap->bookings->count();
                        $pendingBookings = $event->booths->flatMap->bookings->where('status', 'pending')->count();
                        $confirmedBookings = $event->booths->flatMap->bookings->where('status', 'confirmed')->count();
                        $paidBookings = $event->booths->flatMap->bookings->whereIn('status', ['paid', 'ongoing', 'completed'])->count();
                        $cancelledBookings = $event->booths->flatMap->bookings->where('status', 'cancelled')->count();
                        @endphp

                        <!-- Total Revenue -->
                        <div class="mt-4 flex items-center justify-between rounded-lg">
                            <p class="text-sm font-medium text-green-700">Revenue</p>
                            <p class="text-2xl font-bold text-green-600">{{ formatRupiah($totalRevenue) }}</p>
                        </div>

                        <!-- Total Bookings Header -->
                        <div class="mt-4 border-b border-gray-200 pb-3">
                            <p class="text-sm text-gray-500">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>

                        <!-- Status Cards -->
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-lg border border-yellow-100 bg-yellow-50 px-3 py-3 text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $pendingBookings }}</p>
                                <p class="text-xs text-yellow-600 mt-1">Pending</p>
                            </div>
                            <div class="rounded-lg border border-green-100 bg-green-50 px-3 py-3 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $confirmedBookings }}</p>
                                <p class="text-xs text-green-600 mt-1">Confirmed</p>
                            </div>
                            <div class="rounded-lg border border-blue-100 bg-blue-50 px-3 py-3 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $paidBookings }}</p>
                                <p class="text-xs text-blue-600 mt-1">Paid</p>
                            </div>
                            <div class="rounded-lg border border-red-100 bg-red-50 px-3 py-3 text-center">
                                <p class="text-2xl font-bold text-red-600">{{ $cancelledBookings }}</p>
                                <p class="text-xs text-red-600 mt-1">Cancelled</p>
                            </div>
                        </div>
                    </section>
                    @endif

                    <section class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                        <div class="mt-4 space-y-3">
                            @if($event->status === 'draft')
                            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-info-circle text-blue-500 mr-2"></i>
                                    <p class="text-sm text-blue-700 font-medium">Complete booth configuration to finalize this event</p>
                                </div>
                                <p class="text-xs text-blue-600 mt-1">Configure your booth layout and pricing before publishing.</p>
                            </div>
                            @elseif($event->status === 'finalized')
                            @php
                            $boothCount = $event->booths()->count();
                            $capacity = $event->capacity;
                            $boothsMismatch = $capacity && $boothCount !== $capacity;
                            @endphp

                            @if($boothsMismatch)
                            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 mb-3">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <p class="text-sm text-red-700 font-medium">Cannot publish: Booth count mismatch</p>
                                </div>
                                <p class="text-xs text-red-600 mt-1">You have {{ $boothCount }} booth(s) but capacity is set to {{ $capacity }}. Please adjust your booth layout or capacity.</p>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('my-events.publish', $event) }}" onsubmit="return confirm('Publish this event? Once published, it will be visible to attendees.');">
                                @csrf
                                <button type="submit" class="hover:cursor-pointer flex w-full items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700 {{ $boothsMismatch ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $boothsMismatch ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-rocket mr-2"></i>
                                    Publish Event
                                </button>
                            </form>
                            @elseif($event->status === 'published')
                            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>
                                    <p class="text-sm text-green-700 font-medium">Event is live and accepting bookings</p>
                                </div>
                            </div>
                            @elseif($event->status === 'ongoing')
                            <div class="rounded-lg border border-purple-200 bg-purple-50 px-4 py-3">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-circle-play text-purple-500 mr-2"></i>
                                    <p class="text-sm text-purple-700 font-medium">Event is currently ongoing</p>
                                </div>
                                <p class="text-xs text-purple-600 mt-1">You can no longer view booking requests.</p>
                            </div>
                            @elseif($event->status === 'completed')
                            <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-circle-check text-gray-500 mr-2"></i>
                                    <p class="text-sm text-gray-700 font-medium">Event has been completed</p>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">This event is archived and cannot be edited or deleted.</p>
                            </div>
                            @endif

                            @if(in_array($event->status, ['published']))
                            <a href="{{ route('booking-requests', ['event' => $event->id]) }}" class="flex items-center justify-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                                <i class="fa-solid fa-clipboard-list mr-2"></i>
                                View Booking Requests
                            </a>
                            @endif

                            @if(!in_array($event->status, ['published', 'ongoing', 'completed']))
                            <a href="{{ route('my-events.edit', $event) }}" class="flex items-center justify-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                                <i class="fa-regular fa-pen-to-square mr-2"></i>
                                Edit event
                            </a>
                            @endif

                            @if(!in_array($event->status, ['published', 'ongoing', 'completed']))
                            <form method="POST" action="{{ route('my-events.destroy', $event) }}" onsubmit="return confirm('Delete this event? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hover:cursor-pointer flex w-full items-center justify-center rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                    <i class="fa-regular fa-trash-can mr-2"></i>
                                    Delete event
                                </button>
                            </form>
                            <p class="text-xs text-gray-400">Deleting an event permanently removes it and associated booths.</p>
                            @endif
                        </div>
                    </section>
                </aside>
            </div>

            <section class="mt-8 rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Booth Layout</h2>
                        <p class="text-sm text-gray-500">Details for each booth in the event layout.</p>
                    </div>
                    <a href="{{ route('booth-layout.view', ['event_id' => $event->id]) }}" class="inline-flex items-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                        View Booths
                    </a>
                </div>
                <div class="mt-6">
                    @if($boothCount > 0)
                    @include('components.table', [
                    'headers' => $headers,
                    'rows' => $rows,
                    'tableClass' => 'min-w-full text-sm',
                    'containerClass' => 'overflow-x-auto'
                    ])
                    @else
                    <div class="px-4 py-6 text-center text-gray-500">
                        No booth layout has been configured for this event yet.
                    </div>
                    @endif
                </div>
            </section>

            @if(in_array($event->status, ['published', 'ongoing', 'completed']))
            <section class="mt-8 rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Attendants</h2>
                        <p class="text-sm text-gray-500">All confirmed bookings that have been paid for this event.</p>
                    </div>
                </div>

                <div class="mt-6">
                    @if($paidBookingsCount > 0)
                    @include('components.table', [
                    'headers' => $paidBookingHeaders,
                    'rows' => $paidBookingRows,
                    'tableClass' => 'min-w-full text-sm',
                    'containerClass' => 'overflow-x-auto'
                    ])
                    @else
                    <div class="px-4 py-6 text-center">
                        <i class="fa-solid fa-wallet text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500 font-medium">No paid bookings yet</p>
                        <p class="text-sm text-gray-400 mt-1">Paid bookings will appear here once tenants complete their payments.</p>
                    </div>
                    @endif
                </div>
            </section>
            @endif
        </div>
    </div>
</body>

</html>