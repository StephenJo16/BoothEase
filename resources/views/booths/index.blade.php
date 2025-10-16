<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title ?? 'Event' }} - Booth Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', [
        'text' => 'Back to Event Details',
        'url' => route('events.show', ['event' => $event->id])
        ])

        <!-- Event Header -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $event->title }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                            <span>{{ $event->start_time ? $event->start_time->format('d M Y') : 'TBA' }} - {{ $event->end_time ? $event->end_time->format('d M Y') : 'TBA' }}</span>
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
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8">
            <!-- Canvas Section -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fa-solid fa-map me-2 text-[#ff7700]"></i>
                            Booth Layout
                        </h2>
                        <p class="text-sm text-slate-600 mt-1">Click on a booth to view details and book</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="zoomIn()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-1 text-sm">
                            <i class="fas fa-search-plus"></i>
                            <span class="hidden sm:inline">Zoom In</span>
                        </button>
                        <button onclick="zoomOut()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-1 text-sm">
                            <i class="fas fa-search-minus"></i>
                            <span class="hidden sm:inline">Zoom Out</span>
                        </button>
                        <button onclick="resetZoom()" class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium transition-all shadow-md flex items-center gap-1 text-sm">
                            <i class="fas fa-compress"></i>
                            <span class="hidden sm:inline">Reset</span>
                        </button>
                    </div>
                </div>
                <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4 overflow-x-auto">
                    <canvas id="layoutCanvas" width="800" height="600"></canvas>
                </div>
                <div id="canvasMessage" class="mt-4 text-sm text-slate-600 text-center min-h-[20px]"></div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Booth Details Card -->
                <div id="boothDetailsCard" class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-info-circle me-2 text-[#ff7700]"></i>
                        Booth Details
                    </h2>
                    <div id="boothDetailsContent">
                        <!-- Default state when no booth is selected/hovered -->
                        <div id="noBoothSelected" class="text-center py-12">
                            <i class="fas fa-mouse-pointer text-6xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500 text-sm">Hover over or click a booth on the map to view details</p>
                        </div>

                        <!-- Booth details will be populated here -->
                        <div id="boothInfo" class="space-y-4 hidden">
                            <!-- Booth Number -->
                            <div class="p-4 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg border border-slate-200">
                                <div class="text-xs text-slate-600 mb-1">Booth Number</div>
                                <div id="boothNumber" class="text-2xl font-bold text-slate-900">—</div>
                            </div>

                            <!-- Type and Size -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-slate-600 mb-1">Type</div>
                                    <div id="boothType" class="font-semibold text-slate-900">—</div>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-slate-600 mb-1">Size</div>
                                    <div id="boothSize" class="font-semibold text-slate-900">—</div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="p-4 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg border border-orange-200">
                                <div class="text-xs text-slate-600 mb-1">Price per Event</div>
                                <div id="boothPrice" class="text-2xl font-bold text-[#ff7700]">—</div>
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

                            <!-- Description (if available) -->
                            <div id="boothDescriptionContainer" class="hidden">
                                <div class="p-3 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-slate-600 mb-1">Description</div>
                                    <div id="boothDescription" class="text-sm text-slate-700">—</div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div id="boothActionButton">
                                <!-- Will be populated based on status -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script>
        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: false,
            interactive: true,
            // Disable all modifications - view only
            allowTouchScrolling: true
        });

        // Variables for panning
        let isPanning = false;
        let lastPosX = 0;
        let lastPosY = 0;

        const loadEndpoint = "{{ route('booth-layout.data', ['event' => $event->id]) }}";
        let selectedBooth = null;
        let hoveredBooth = null;
        let boothsData = [];

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

        function setCanvasMessage(message, tone = 'info') {
            const msg = document.getElementById('canvasMessage');
            msg.textContent = message;

            const tones = {
                info: 'text-slate-600',
                success: 'text-emerald-600',
                error: 'text-red-600'
            };

            msg.className = `mt-4 text-sm text-center min-h-[20px] ${tones[tone] ?? tones.info}`;
        }

        function showBoothDetails(booth, isHovered = false) {
            const noBoothSelected = document.getElementById('noBoothSelected');
            const boothInfo = document.getElementById('boothInfo');
            const boothActionButton = document.getElementById('boothActionButton');

            if (!booth) {
                noBoothSelected.classList.remove('hidden');
                boothInfo.classList.add('hidden');
                return;
            }

            noBoothSelected.classList.add('hidden');
            boothInfo.classList.remove('hidden');

            // Populate booth details
            document.getElementById('boothNumber').textContent = booth.number ?? 'N/A';
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

            // Show description if available
            const descContainer = document.getElementById('boothDescriptionContainer');
            if (booth.description) {
                document.getElementById('boothDescription').textContent = booth.description;
                descContainer.classList.remove('hidden');
            } else {
                descContainer.classList.add('hidden');
            }

            // Show appropriate action button
            if (booth.status === 'available') {
                boothActionButton.innerHTML = `
                    <button onclick="bookBooth('${booth.id}')" class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-shopping-cart"></i>
                        Book This Booth
                    </button>
                `;
            } else {
                boothActionButton.innerHTML = `
                    <div class="w-full bg-slate-300 text-slate-600 font-semibold py-3 px-4 rounded-lg text-center">
                        <i class="fas fa-lock mr-2"></i>
                        Not Available
                    </div>
                `;
            }
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
            showBoothDetails(booth, false);
        }

        function bookBooth(boothId) {
            // Redirect to booth details/booking page with booth ID
            window.location.href = `/booths/${boothId}/details`;
        }

        async function loadLayout() {
            try {
                const response = await fetch(loadEndpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    setCanvasMessage('Unable to load booth layout. Please try again later.', 'error');
                    return;
                }

                const data = await response.json();
                boothsData = data.booths ?? [];

                if (!data.layout) {
                    setCanvasMessage('No booth layout available for this event yet.', 'info');
                    return;
                }

                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise((resolve) => {
                    canvas.loadFromJSON(data.layout, () => {
                        // Add booth data to canvas objects and make them interactive
                        canvas.getObjects().forEach((obj) => {
                            // Check if this is a booth element
                            if (obj.elementType === 'booth' && obj.elementLabel) {
                                // Find booth data by matching the booth number from the label
                                const booth = boothsData.find(b => b.number === obj.elementLabel);
                                if (booth) {
                                    obj.boothData = booth;

                                    // Set colors based on status
                                    obj.set({
                                        fill: getBoothColor(booth.status),
                                        stroke: getBoothBorderColor(booth.status),
                                        strokeWidth: 2,
                                        // Lock all modifications - view only
                                        selectable: false,
                                        evented: true,
                                        hasControls: false,
                                        hasBorders: false,
                                        lockMovementX: true,
                                        lockMovementY: true,
                                        lockRotation: true,
                                        lockScalingX: true,
                                        lockScalingY: true
                                    });

                                    // Make clickable only if available
                                    if (booth.status === 'available') {
                                        obj.set({
                                            hoverCursor: 'pointer'
                                        });

                                        obj.on('mousedown', function() {
                                            selectBooth(booth);
                                        });

                                        obj.on('mouseover', function() {
                                            hoveredBooth = booth;
                                            // Only show hover details if no booth is selected
                                            if (!selectedBooth) {
                                                showBoothDetails(booth, true);
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
                                    } else {
                                        obj.set({
                                            hoverCursor: 'not-allowed'
                                        });

                                        // Still show details on hover for non-available booths
                                        obj.on('mouseover', function() {
                                            hoveredBooth = booth;
                                            if (!selectedBooth) {
                                                showBoothDetails(booth, true);
                                            }
                                        });

                                        obj.on('mouseout', function() {
                                            hoveredBooth = null;
                                            if (!selectedBooth) {
                                                showBoothDetails(null);
                                            }
                                        });
                                    }
                                }
                            } else {
                                // Lock all non-booth elements (parking, entrance, exit, toilet, etc.)
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
                            }
                        });

                        canvas.renderAll();
                        resolve();
                    });
                });

            } catch (error) {
                console.error('Load layout error:', error);
                setCanvasMessage('An error occurred while loading the layout.', 'error');
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

        // Panning functionality - only on empty canvas to not interfere with booth interactions
        canvas.on('mouse:down', function(opt) {
            const evt = opt.e;
            // Only allow panning on empty space (no booth interaction)
            if (!opt.target && evt.button === 0) {
                isPanning = true;
                lastPosX = evt.clientX;
                lastPosY = evt.clientY;
                canvas.defaultCursor = 'grab';
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
                canvas.defaultCursor = 'default';
                canvas.renderAll();
            }
        });

        // Make functions global for onclick handlers
        window.bookBooth = bookBooth;
    </script>
</body>

</html>