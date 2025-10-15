@php
// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Fetch event and booth data
$event = null;
$booths = [];
$totalBooths = 0;
$availableBooths = 0;

if ($eventId) {
$event = \App\Models\Event::with(['user', 'category'])->find($eventId);
if ($event) {
$booths = \App\Models\Booth::where('event_id', $eventId)
->orderBy('number')
->get();
$totalBooths = $booths->count();
$availableBooths = $booths->where('status', 'available')->count();
}
}

// Define table headers
$headers = [
['title' => 'Booth', 'class' => 'w-20'],
['title' => 'Type', 'class' => 'w-24'],
['title' => 'Price', 'class' => 'w-24'],
['title' => 'Size', 'class' => 'w-16'],
['title' => 'Status', 'class' => 'w-20'],
];

// Transform booths data into rows format
$rows = [];
foreach($booths as $booth) {
$isAvailable = strtolower($booth->status) === 'available';
$statusColor = $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
$statusText = ucfirst($booth->status);

$rows[] = [
'rowClass' => 'h-14',
'cells' => [
[
'content' => $booth->number ?? '-',
'class' => 'font-semibold text-slate-800'
],
[
'content' => ucfirst($booth->type ?? 'Standard'),
'class' => 'text-slate-600'
],
[
'content' => formatRupiah($booth->price ?? 0),
'class' => 'font-semibold text-slate-800'
],
[
'content' => $booth->size ?? '-',
'class' => 'text-slate-600'
],
[
'content' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ' . $statusColor . '">' . $statusText . '</span>',
'class' => ''
],
]
];
}
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booth Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-white min-h-screen">
    @include('components.navbar')


    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', [
        'text' => 'Back to Event Details',
        'url' => $eventId ? route('my-events.show', ['event' => $eventId]) : route('my-events.index')
        ])

        <!-- Event Header -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $event->title ?? 'Event Layout' }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                            <span>{{ $event && $event->start_time ? $event->start_time->format('d M Y') : 'TBA' }} - {{ $event && $event->end_time ? $event->end_time->format('d M Y') : 'TBA' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                            <span>{{ $event->venue ?? 'Venue TBA' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                            <span>{{ $availableBooths }} / {{ $totalBooths }} Booths Available</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if($event && $event->status !== 'published')
                    <button type="button" onclick="editLayout()" id="editButton"
                        class="px-6 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center gap-2">
                        <i class="fa-regular fa-pen-to-square"></i>
                        Edit Layout
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
            <!-- Canvas Section -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4">
                    <canvas id="layoutCanvas" width="900" height="600"></canvas>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">


                <!-- Booth Details Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-box me-2"></i>
                        Booth Details
                    </h2>
                    <div class="max-h-56 overflow-auto border border-slate-200 rounded-lg">
                        @if($booths->count() > 0)
                        @include('components.table', [
                        'headers' => $headers,
                        'rows' => $rows,
                        'tableClass' => 'w-full min-w-[640px] text-xs',
                        'containerClass' => 'min-w-full',
                        ])
                        @else
                        <div class="px-3 py-6 text-center text-slate-500">No booths are stored for this event.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: false
        });

        const loadEndpointTemplate = "{{ route('booth-layout.data', ['event' => '__EVENT__']) }}";

        async function loadLayout() {
            const rawEventId = "{{ $eventId ?? '' }}".toString().trim();

            if (!rawEventId) {
                console.error('No event ID provided.');
                return;
            }

            const endpoint = loadEndpointTemplate.replace('__EVENT__', encodeURIComponent(rawEventId));

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    console.error('Unable to load layout.');
                    return;
                }

                const data = await response.json();

                if (!data.layout) {
                    console.error('No layout data found for this event.');
                    return;
                }

                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise((resolve) => {
                    canvas.loadFromJSON(data.layout, () => {
                        // Lock all objects - this is a view-only page
                        canvas.getObjects().forEach(obj => {
                            obj.set({
                                selectable: false,
                                evented: false,
                                hasControls: false,
                                hasBorders: false,
                                lockMovementX: true,
                                lockMovementY: true,
                                lockRotation: true,
                                lockScalingX: true,
                                lockScalingY: true,
                                hoverCursor: 'default'
                            });
                        });

                        canvas.renderAll();
                        resolve();
                    });
                });

            } catch (error) {
                console.error('Load layout error:', error);
            }
        }

        function editLayout() {
            const rawEventId = "{{ $eventId ?? '' }}";
            if (rawEventId) {
                const editUrl = "{{ route('booth-layout.edit') }}" + "?event_id=" + encodeURIComponent(rawEventId);
                window.location.href = editUrl;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadLayout();
        });
    </script>
</body>

</html>
