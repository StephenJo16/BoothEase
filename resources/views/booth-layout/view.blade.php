@php
// Fetch event and booth data
$event = null;
$booths = [];
$totalBooths = 0;
$availableBooths = 0;
$allFloors = [];

if ($eventId) {
$event = \App\Models\Event::with(['user', 'category'])->find($eventId);
if ($event) {
// Get all floors for this event
$allFloors = \App\Models\EventLayout::where('event_id', $eventId)
->orderBy('floor_number')
->get(['floor_number', 'floor_name', 'booth_count']);

$booths = \App\Models\Booth::where('event_id', $eventId)
->orderBy('floor_number')
->orderBy('name')
->get();
$totalBooths = $booths->count();
$availableBooths = $booths->where('status', 'available')->count();
}
}

// Define table headers
$headers = [
['title' => 'Floor', 'class' => 'w-16'],
['title' => 'Booth', 'class' => 'w-20'],
['title' => 'Type', 'class' => 'w-24'],
['title' => 'Price', 'class' => 'w-24'],
['title' => 'Size', 'class' => 'w-16'],
['title' => 'Status', 'class' => 'w-20'],
];

// Transform booths data into rows format
$rows = [];
foreach($booths as $booth) {
$boothStatus = getBoothStatusDisplay($booth->status);

// Get floor name
$floorName = 'Floor ' . ($booth->floor_number ?? 1);
$floor = $allFloors->firstWhere('floor_number', $booth->floor_number);
if ($floor) {
$floorName = $floor->floor_name;
}

$rows[] = [
'rowClass' => 'h-14',
'cells' => [
[
'content' => $floorName,
'class' => 'text-slate-600 text-xs'
],
[
'content' => $booth->name ?? '-',
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
'content' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ' . $boothStatus['class'] . '">' . $boothStatus['label'] . '</span>',
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
    <style>
        .floor-item {
            position: relative;
            overflow: hidden;
        }

        .floor-item:not(.active) {
            background: linear-gradient(to right, #e2e8f0, #cbd5e1);
            color: #475569;
        }

        .floor-item.active {
            background: #ff7700;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(255, 119, 0, 0.3), 0 2px 4px -1px rgba(255, 119, 0, 0.2);
        }

        .floor-item:not(.active):hover {
            background: linear-gradient(to right, #cbd5e1, #94a3b8);
            transform: translateX(4px);
        }

        .floor-item.active::before {
            content: '';
            position: absolute;
            left: -2px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: white;
            border-radius: 0 2px 2px 0;
        }
    </style>
</head>

<body class="bg-white min-h-screen">
    @include('components.navbar')

    <!-- Full Page Loader -->
    <div id="pageLoader">
        <x-loader overlay="true" message="Loading booth layout..." size="lg" />
    </div>

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
        @if($allFloors->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="mainContentGrid" data-floors='@json($allFloors)' data-booths='@json($booths)'>
            <!-- Canvas Section -->
            <div class="lg:col-span-2 xl:col-span-3 bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <!-- Floor Pills -->
                @if($allFloors->count() > 1)
                <div id="floorPills" class="mb-4 flex flex-wrap gap-2">
                    @foreach($allFloors as $floor)
                    <button class="floor-item px-4 py-2 rounded-full font-medium transition-all shadow-md hover:shadow-lg flex items-center gap-2 {{ $loop->first ? 'active' : '' }}"
                        data-floor="{{ $floor->floor_number }}">
                        <span class="font-semibold text-sm">{{ $floor->floor_name }}</span>
                        <span class="text-xs {{ $loop->first ? 'bg-white/30' : 'bg-slate-300' }} px-2 py-0.5 rounded-full">{{ $floor->booth_count }}</span>
                    </button>
                    @endforeach
                </div>
                @endif

                <!-- Zoom Controls -->
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-slate-800">Layout View</h2>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="zoomIn()" class="px-3 py-2 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-search-plus"></i>
                            <span class="hidden sm:inline">Zoom In</span>
                        </button>
                        <button onclick="zoomOut()" class="px-3 py-2 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-search-minus"></i>
                            <span class="hidden sm:inline">Zoom Out</span>
                        </button>
                        <button onclick="resetZoom()" class="px-3 py-2 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-compress"></i>
                            <span class="hidden sm:inline">Reset</span>
                        </button>
                    </div>
                </div>
                <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4 overflow-hidden">
                    <div class="w-full overflow-x-auto">
                        <canvas id="layoutCanvas" width="900" height="600"></canvas>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 xl:col-span-1">
                <!-- Booth Details Card -->
                <div id="boothDetailsCard" class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-info-circle me-2 text-[#ff7700]"></i>
                        Booth Details
                    </h2>
                    <div id="boothDetailsContent" class="overflow-hidden">
                        <!-- Default state when no booth is selected/hovered -->
                        <div id="noBoothSelected" class="text-center py-12">
                            <i class="fas fa-mouse-pointer text-6xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500 text-sm">Hover over or click a booth on the map to view details</p>
                        </div>

                        <!-- Booth details will be populated here -->
                        <div id="boothInfo" class="space-y-4 hidden">
                            <!-- Booth Name and Floor -->
                            <div class="p-4 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg border border-slate-200">
                                <div class="flex items-end justify-between">
                                    <div>
                                        <div class="text-xs text-slate-600 mb-1">Booth Name</div>
                                        <div id="boothName" class="text-2xl font-bold text-slate-900">—</div>
                                    </div>
                                    <div class="text-right" id="boothFloorContainer">
                                        <div class="text-xs text-slate-600 mb-1">Floor</div>
                                        <div id="boothFloor" class="text-sm font-semibold text-slate-700">—</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Type and Size -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-slate-600 mb-1">Type</div>
                                    <div id="boothType" class="font-semibold text-slate-900 text-sm break-words">—</div>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-slate-600 mb-1">Size</div>
                                    <div id="boothSize" class="font-semibold text-slate-900 text-sm break-words">—</div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="p-4 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg border border-orange-200">
                                <div class="text-xs text-slate-600 mb-1">Price per Event</div>
                                <div id="boothPrice" class="text-2xl font-bold text-[#ff7700] break-words">—</div>
                            </div>

                            <!-- Status -->
                            <div class="p-3 rounded-lg">
                                <div class="text-xs text-slate-600 mb-1">Status</div>
                                <div id="boothStatus">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-200 text-slate-600">
                                        Unknown
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- No Layout Message -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-12">
            <div class="text-center max-w-2xl mx-auto">
                <div class="mb-6">
                    <i class="fas fa-map-marked-alt text-8xl text-slate-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-3">No Layout Created Yet</h2>
                <p class="text-slate-600 mb-6">
                    You haven't set up a booth layout for this event yet. Create a layout to organize your event space and manage booth bookings.
                </p>
            </div>
        </div>
        @endif

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
        let currentFloorNumber = 1;
        let selectedBooth = null;
        let hoveredBooth = null;

        // Get floors and booths data from data attributes
        const mainGrid = document.getElementById('mainContentGrid');
        const allFloorsData = mainGrid ? JSON.parse(mainGrid.dataset.floors || '[]') : [];
        const boothsData = mainGrid ? JSON.parse(mainGrid.dataset.booths || '[]') : [];

        // Helper function to format Rupiah
        function formatRupiah(value) {
            const digits = String(value ?? 0).replace(/\D/g, '');
            const num = digits === '' ? 0 : parseInt(digits);
            return 'Rp' + num.toLocaleString('id-ID');
        }

        // Helper function to get booth color based on status
        function getBoothColor(status) {
            const colors = {
                'available': '#22c55e', // green
                'booked': '#ef4444', // red
                'reserved': '#eab308', // yellow
                'selected': '#3b82f6' // blue
            };
            return colors[status] || colors['available'];
        }

        // Helper function to get booth border color
        function getBoothBorderColor(status) {
            const colors = {
                'available': '#15803d', // green
                'booked': '#b91c1c', // red
                'reserved': '#a16207', // yellow
                'selected': '#1e40af' // blue
            };
            return colors[status] || colors['available'];
        }

        function lockCanvasObject(obj, overrides = {}) {
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
                hoverCursor: 'default',
                ...overrides
            });

            if (typeof obj.editable !== 'undefined') {
                obj.editable = false;
            }
        }

        function showBoothDetails(booth) {
            const noBoothSelected = document.getElementById('noBoothSelected');
            const boothInfo = document.getElementById('boothInfo');

            if (!booth) {
                noBoothSelected.classList.remove('hidden');
                boothInfo.classList.add('hidden');
                return;
            }

            noBoothSelected.classList.add('hidden');
            boothInfo.classList.remove('hidden');

            // Get floor name
            let floorName = 'Floor ' + (booth.floor_number ?? 1);
            const floor = allFloorsData.find(f => f.floor_number === booth.floor_number);
            if (floor) {
                floorName = floor.floor_name;
            }

            // Populate booth details
            document.getElementById('boothName').textContent = booth.name ?? 'N/A';
            document.getElementById('boothFloor').textContent = floorName;
            document.getElementById('boothType').textContent = booth.type ?? 'Standard';
            document.getElementById('boothSize').textContent = booth.size ?? 'N/A';
            document.getElementById('boothPrice').textContent = formatRupiah(booth.price ?? 0);

            // Set status badge
            const statusColors = {
                'available': 'bg-green-100 text-green-800',
                'booked': 'bg-red-100 text-red-800',
                'reserved': 'bg-yellow-100 text-yellow-800',
                'selected': 'bg-blue-100 text-blue-800'
            };
            const statusColor = statusColors[booth.status] || 'bg-slate-200 text-slate-600';
            const statusText = booth.status ? booth.status.charAt(0).toUpperCase() + booth.status.slice(1) : 'Unknown';

            document.getElementById('boothStatus').innerHTML = `
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusColor}">
                    ${statusText}
                </span>
            `;
        }

        function selectBooth(booth) {
            // Deselect previous booth
            if (selectedBooth) {
                canvas.getObjects().forEach(obj => {
                    if (obj.boothData && obj.boothData.id === selectedBooth.id) {
                        obj.set({
                            fill: getBoothColor(selectedBooth.status),
                            stroke: getBoothBorderColor(selectedBooth.status),
                            strokeWidth: 2
                        });
                    }
                });
            }

            // Select new booth
            selectedBooth = booth;

            // Highlight on canvas
            canvas.getObjects().forEach(obj => {
                if (obj.boothData && obj.boothData.id === booth.id) {
                    obj.set({
                        fill: getBoothColor('selected'),
                        stroke: getBoothBorderColor('selected'),
                        strokeWidth: 3
                    });
                }
            });

            canvas.renderAll();
            showBoothDetails(booth);
        }

        async function loadLayout(floorNumber = 1) {
            const rawEventId = "{{ $eventId ?? '' }}".toString().trim();

            if (!rawEventId) {
                console.error('No event ID provided.');
                updateActionButton(false);
                return;
            }

            const endpoint = loadEndpointTemplate.replace('__EVENT__', encodeURIComponent(rawEventId)) + '?floor_number=' + floorNumber;

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    console.error('Unable to load layout for floor', floorNumber);
                    canvas.clear();
                    canvas.backgroundColor = '#ffffff';
                    canvas.renderAll();
                    return;
                }

                const data = await response.json();

                if (!data.layout) {
                    updateActionButton(false);
                    console.error('No layout data found for floor', floorNumber);
                    canvas.clear();
                    canvas.backgroundColor = '#ffffff';
                    canvas.renderAll();
                    return;
                }

                updateActionButton(true);

                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise((resolve) => {
                    canvas.loadFromJSON(data.layout, () => {
                        // Add booth data to canvas objects and make them interactive
                        canvas.getObjects().forEach((obj) => {
                            // Check if this is a booth element
                            if (obj.elementType === 'booth' && obj.elementLabel) {
                                // Find booth data by matching the booth name from the label
                                const booth = boothsData.find(b => b.name === obj.elementLabel && b.floor_number === floorNumber);
                                if (booth) {
                                    obj.boothData = booth;
                                    lockCanvasObject(obj, {
                                        evented: true,
                                        hoverCursor: 'pointer'
                                    });

                                    // Set colors based on status
                                    obj.set({
                                        fill: getBoothColor(booth.status),
                                        stroke: getBoothBorderColor(booth.status),
                                        strokeWidth: 2
                                    });

                                    obj.on('mousedown', function() {
                                        selectBooth(booth);
                                    });

                                    obj.on('mouseover', function() {
                                        hoveredBooth = booth;
                                        // Only show hover details if no booth is selected
                                        if (!selectedBooth) {
                                            showBoothDetails(booth);
                                        }

                                        if (!selectedBooth || selectedBooth.id !== booth.id) {
                                            this.set({
                                                strokeWidth: 3,
                                                opacity: 0.8
                                            });
                                            canvas.renderAll();
                                        }
                                    });

                                    obj.on('mouseout', function() {
                                        hoveredBooth = null;
                                        // Clear details if no booth is selected
                                        if (!selectedBooth) {
                                            showBoothDetails(null);
                                        }

                                        if (!selectedBooth || selectedBooth.id !== booth.id) {
                                            this.set({
                                                strokeWidth: 2,
                                                opacity: 1
                                            });
                                            canvas.renderAll();
                                        }
                                    });
                                }
                            } else {
                                lockCanvasObject(obj);
                            }
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

        function switchFloor(floorNumber) {
            if (typeof floorNumber === 'undefined') {
                return;
            }

            if (floorNumber === currentFloorNumber) {
                return;
            }

            currentFloorNumber = floorNumber;
            selectedBooth = null;
            hoveredBooth = null;
            showBoothDetails(null);

            // Update UI
            document.querySelectorAll('.floor-item').forEach(btn => {
                const btnFloor = parseInt(btn.dataset.floor);
                if (btnFloor === floorNumber) {
                    btn.classList.add('active');
                    // Update badge style
                    const badge = btn.querySelector('span:last-child');
                    if (badge) {
                        badge.className = 'text-xs bg-white/30 px-2 py-0.5 rounded-full';
                    }
                } else {
                    btn.classList.remove('active');
                    // Update badge style
                    const badge = btn.querySelector('span:last-child');
                    if (badge) {
                        badge.className = 'text-xs bg-slate-300 px-2 py-0.5 rounded-full';
                    }
                }
            });

            // Load the floor layout
            loadLayout(floorNumber);
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
            // Show loader
            const pageLoader = document.getElementById('pageLoader');
            if (pageLoader) pageLoader.classList.remove('hidden');

            // Add click handlers to floor buttons
            document.querySelectorAll('.floor-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const floorNum = parseInt(this.dataset.floor);
                    switchFloor(floorNum);
                });
            });

            // Load the first floor by default only if floors exist
            if (allFloorsData.length > 0) {
                loadLayout(currentFloorNumber).finally(() => {
                    // Hide loader after loading
                    if (pageLoader) pageLoader.classList.add('hidden');
                });
            } else {
                // No layout exists, update button accordingly
                updateActionButton(false);
                // Hide loader
                if (pageLoader) pageLoader.classList.add('hidden');
            }
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