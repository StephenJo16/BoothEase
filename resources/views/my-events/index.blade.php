<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Events</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="min-h-screen">
        @include('components.header', ['title' => 'My Events', 'subtitle' => 'Manage your events'])
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                {{ session('status') }}
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-600">View, edit, and publish the events you manage.</p>
                </div>
                <a href="{{ route('my-events.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[#e66600]">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create New Event
                </a>
            </div>

            @if($events->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500">
                <i class="fa-solid fa-calendar-plus mb-4 text-4xl text-[#ff7700]"></i>
                <p class="text-lg font-semibold text-gray-700">You have not created any events yet.</p>
                <p class="mt-2 text-sm">Start by creating your first event to manage booths and bookings.</p>
            </div>
            @else
            @php
                $statusStyles = [
                    'published' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
                    'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
                ];
            @endphp
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($events as $event)
                @php
                    $status = $event->status;
                    $badge = $statusStyles[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
                    $booths = $event->booth_configuration;
                    $boothTypes = is_array($booths) ? count($booths) : 0;
                    $boothTotal = is_array($booths) ? collect($booths)->sum('qty') : 0;
                    $start = $event->start_time ? $event->start_time->format('d M Y, H:i') : 'Schedule to be announced';
                    $end = $event->end_time ? $event->end_time->format('d M Y, H:i') : null;
                    $location = $event->display_location ?: 'Location to be confirmed';
                    $category = optional($event->category)->name ?: 'Uncategorised';
                @endphp
                <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">{{ $event->title }}</h3>
                                <p class="text-xs uppercase tracking-wide text-gray-500">{{ $category }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between px-5 py-4">
                        <div class="space-y-4">
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-location-dot mr-2 mt-0.5 text-[#ff7700]"></i>
                                <span>{{ $location }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-calendar mr-2 text-[#ff7700]"></i>
                                    <span>{{ $start }}</span>
                                </div>
                                @if($end)
                                <div class="mt-1 flex items-center">
                                    <i class="fa-regular fa-clock mr-2 text-[#ff7700]"></i>
                                    <span>Ends {{ $end }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-700">
                                <div class="flex items-center justify-between">
                                    <span>Booth types</span>
                                    <span class="font-semibold">{{ $boothTypes }}</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <span>Total booths</span>
                                    <span class="font-semibold">{{ $boothTotal }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="{{ route('my-events.show', $event) }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900">
                                <i class="fa-regular fa-eye mr-2"></i>
                                View details
                            </a>
                            <a href="{{ route('my-events.edit', $event) }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900">
                                <i class="fa-regular fa-pen-to-square mr-2"></i>
                                Edit
                            </a>
                            <form action="{{ route('my-events.destroy', $event) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-lg border border-transparent px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50" onclick="return confirm('Delete this event? This action cannot be undone.')">
                                    <i class="fa-regular fa-trash-can mr-2"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
