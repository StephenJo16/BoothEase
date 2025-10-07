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

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    @php
    $location = is_array($event->location) ? $event->location : [];
    $booths = $event->booth_configuration;
    $status = $event->status;
    $statusStyles = [
    'published' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
    'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
    ];
    $badge = $statusStyles[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
    $start = $event->start_time ? $event->start_time->format('d M Y, H:i') : null;
    $end = $event->end_time ? $event->end_time->format('d M Y, H:i') : null;
    $deadline = $location['registration_deadline'] ?? null;
    $deadlineFormatted = $deadline ? \Carbon\Carbon::parse($deadline)->format('d M Y') : null;
    @endphp

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
                    <section class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                        <div class="mt-4 space-y-3">
                            <a href="{{ route('my-events.edit', $event) }}" class="flex items-center justify-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                                <i class="fa-regular fa-pen-to-square mr-2"></i>
                                Edit event
                            </a>
                            <form method="POST" action="{{ route('my-events.destroy', $event) }}" onsubmit="return confirm('Delete this event? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex w-full items-center justify-center rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                    <i class="fa-regular fa-trash-can mr-2"></i>
                                    Delete event
                                </button>
                            </form>
                            <p class="text-xs text-gray-400">Deleting an event permanently removes it and associated booths.</p>
                        </div>
                    </section>
                    <section class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Owner</h2>
                        <p class="mt-3 text-sm text-gray-600">Managed by {{ optional($event->user)->name ?: 'Unknown user' }}</p>
                        <p class="mt-1 text-xs text-gray-400">User ID: {{ $event->user_id }}</p>
                    </section>
                </aside>
            </div>

            <section class="mt-8 rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Booth Layout</h2>
                        <p class="text-sm text-gray-500">Details for each booth in the event layout.</p>
                    </div>
                    <span id="boothCountBadge" class="text-xs uppercase tracking-wide text-gray-400">Loading...</span>
                </div>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Booth Number</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Type</th>
                                <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-600">Price</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Size</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody id="boothTableBody" class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Loading booth data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script>
        const loadEndpointTemplate = "{{ route('testing-layout.data', ['event' => '__EVENT__']) }}";
        const eventId = "{{ $event->id }}";

        function populateBoothTable(booths) {
            const body = document.getElementById('boothTableBody');
            const badge = document.getElementById('boothCountBadge');

            if (!Array.isArray(booths) || booths.length === 0) {
                body.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No booth layout has been configured for this event yet.</td></tr>';
                badge.textContent = 'No booths configured';
                return;
            }

            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            body.innerHTML = booths.map(booth => {
                const price = formatter.format(booth.price ?? 0);
                const size = booth.size ? `${booth.size}` : '—';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">${booth.number ?? '—'}</td>
                        <td class="px-4 py-3 text-gray-700 capitalize">${booth.type ?? '—'}</td>
                        <td class="px-4 py-3 text-right text-gray-700">${price}</td>
                        <td class="px-4 py-3 text-gray-700">${size}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${booth.status === 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${booth.status ?? 'Available'}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');

            badge.textContent = booths.length === 1 ? '1 booth configured' : `${booths.length} booths configured`;
        }

        async function loadBoothData() {
            if (!eventId) {
                document.getElementById('boothTableBody').innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Event ID not available.</td></tr>';
                document.getElementById('boothCountBadge').textContent = 'Error loading';
                return;
            }

            const endpoint = loadEndpointTemplate.replace('__EVENT__', encodeURIComponent(eventId));

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load booth data');
                }

                const data = await response.json();
                populateBoothTable(data.booths ?? []);
            } catch (error) {
                console.error('Error loading booth data:', error);
                document.getElementById('boothTableBody').innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Failed to load booth data. The booth layout may not have been configured yet.</td></tr>';
                document.getElementById('boothCountBadge').textContent = 'Failed to load';
            }
        }

        // Load booth data when the page loads
        document.addEventListener('DOMContentLoaded', loadBoothData);
    </script>
</body>

</html>