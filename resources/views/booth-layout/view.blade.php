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
                    <button type="button" onclick="handleLayoutAction()" id="layoutActionButton"
                        class="px-6 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center gap-2">
                        <i id="layoutActionIcon" class="fa-regular fa-pen-to-square"></i>
                        <span id="layoutActionText">Edit Layout</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
            <!-- Canvas Section -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <!-- Zoom Controls -->
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800">Layout View</h2>
                    <div class="flex gap-2">
                        <button onclick="zoomIn()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-search-plus"></i>
                            Zoom In
                        </button>
                        <button onclick="zoomOut()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-search-minus"></i>
                            Zoom Out
                        </button>
                        <button onclick="resetZoom()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-compress"></i>
                            Reset
                        </button>
                    </div>
                </div>
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

        // Variables for panning
        let isPanning = false;
        let lastPosX = 0;
        let lastPosY = 0;

        const loadEndpointTemplate = "{{ route('booth-layout.data', ['event' => '__EVENT__']) }}";
        const actionButton = document.getElementById('layoutActionButton');
        const actionIcon = document.getElementById('layoutActionIcon');
        const actionText = document.getElementById('layoutActionText');
        let hasLayout = false;

        async function loadLayout() {
            const rawEventId = "{{ $eventId ?? '' }}".toString().trim();

            if (!rawEventId) {
                console.error('No event ID provided.');
                updateActionButton(false);
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
                    updateActionButton(false);
                    console.error('No layout data found for this event.');
                    return;
                }

                updateActionButton(true);

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
                updateActionButton(false);
            }
        }

        function updateActionButton(layoutExists) {
            hasLayout = !!layoutExists;

            if (!actionButton || !actionIcon || !actionText) {
                return;
            }

            if (hasLayout) {
                actionIcon.className = 'fa-regular fa-pen-to-square';
                actionText.textContent = 'Edit Layout';
            } else {
                actionIcon.className = 'fa-solid fa-plus';
                actionText.textContent = 'Create Layout';
            }
        }

        function handleLayoutAction() {
            const rawEventId = "{{ $eventId ?? '' }}";
            if (rawEventId) {
                const targetBase = hasLayout ? "{{ route('booth-layout.edit') }}" : "{{ route('booth-layout') }}";
                const targetUrl = targetBase + "?event_id=" + encodeURIComponent(rawEventId);
                window.location.href = targetUrl;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadLayout();
        });

        // Zoom functions
        function zoomIn() {
            let zoom = canvas.getZoom();
            zoom += 0.1;
            if (zoom > 3) zoom = 3;
            const center = new fabric.Point(canvas.width / 2, canvas.height / 2);
            canvas.zoomToPoint(center, zoom);
            canvas.renderAll();
        }

        function zoomOut() {
            let zoom = canvas.getZoom();
            zoom -= 0.1;
            if (zoom < 0.3) zoom = 0.3;
            const center = new fabric.Point(canvas.width / 2, canvas.height / 2);
            canvas.zoomToPoint(center, zoom);
            canvas.renderAll();
        }

        function resetZoom() {
            canvas.setZoom(1);
            canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);
            canvas.renderAll();
        }

        // Mouse wheel zoom
        canvas.on('mouse:wheel', function(opt) {
            const delta = opt.e.deltaY;
            let zoom = canvas.getZoom();
            zoom *= 0.999 ** delta;
            if (zoom > 3) zoom = 3;
            if (zoom < 0.3) zoom = 0.3;
            const point = new fabric.Point(opt.e.offsetX, opt.e.offsetY);
            canvas.zoomToPoint(point, zoom);
            opt.e.preventDefault();
            opt.e.stopPropagation();
            canvas.renderAll();
        });

        // Panning functionality
        canvas.on('mouse:down', function(opt) {
            const evt = opt.e;
            if (!opt.target && evt.button === 0) {
                isPanning = true;
                canvas.selection = false;
                lastPosX = evt.clientX;
                lastPosY = evt.clientY;
                canvas.defaultCursor = 'grab';
                canvas.renderAll();
            }
        });

        canvas.on('mouse:move', function(opt) {
            if (isPanning) {
                const evt = opt.e;
                const vpt = canvas.viewportTransform;
                vpt[4] += evt.clientX - lastPosX;
                vpt[5] += evt.clientY - lastPosY;
                canvas.requestRenderAll();
                lastPosX = evt.clientX;
                lastPosY = evt.clientY;
                canvas.defaultCursor = 'grabbing';
            }
        });

        canvas.on('mouse:up', function(opt) {
            if (isPanning) {
                canvas.setViewportTransform(canvas.viewportTransform);
                isPanning = false;
                canvas.selection = false;
                canvas.defaultCursor = 'default';
                canvas.renderAll();
            }
        });
    </script>
</body>

</html>