<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configure Booths</title>
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

        .floor-item-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .floor-item:hover .floor-item-actions {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-white min-h-screen">
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', ['text' => 'Back to My Events', 'url' => route('my-events.index')])

        <!-- Floor Selector Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-6">
            <div class="flex items-start gap-6">
                <!-- Vertical Floor Selector -->
                <div class="flex-shrink-0">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center">
                        <i class="fas fa-layer-group mr-2 text-[#ff7700]"></i>
                        Floors
                    </h4>
                    <div id="floorList" class="flex flex-col gap-2 min-w-[140px]">
                        <!-- Floor items will be dynamically added here -->
                    </div>
                    <!-- Add Floor Button -->
                    <button onclick="addNewFloor()" class="mt-3 w-full px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-full font-medium transition-all border-2 border-dashed border-slate-300 hover:border-slate-400 flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span class="text-sm">Add Floor</span>
                    </button>
                </div>

                <!-- Floor Actions -->
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Floor Actions</h4>
                    <div class="flex flex-wrap gap-3">
                        <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-all hover:shadow-md flex items-center gap-2" onclick="renameCurrentFloor()">
                            <i class="fas fa-edit"></i>
                            Rename Floor
                        </button>
                        <button id="deleteFloorBtn" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all hover:shadow-md flex items-center gap-2" onclick="deleteCurrentFloor()" style="display: none;">
                            <i class="fas fa-trash"></i>
                            Delete Floor
                        </button>
                    </div>
                    <div class="mt-3 text-sm text-slate-600">
                        <i class="fas fa-info-circle mr-1 text-[#ff7700]"></i>
                        <span>Current: <strong id="currentFloorDisplay">Floor 1</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h4 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-[#ff7700]"></i>
                Instructions
            </h4>
            <ul class="list-disc pl-5 space-y-2 text-slate-600">
                <li>Click any element button to add it to the canvas</li>
                <li>Select a booth to edit its properties (size, price, type) in the right panel</li>
                <li>Double-click any element to quickly edit its text label</li>
                <li>Drag elements to position them, resize using corner handles (booths can only be resized via properties panel)</li>
                <li>Use Zoom In/Out buttons or mouse wheel to zoom the canvas</li>
                <li>Click and drag on empty canvas space to pan/move around the view</li>
                <li>Keyboard shortcuts: Ctrl+D to duplicate, Delete to remove</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
                <div class="flex flex-col gap-6">
                    <!-- Toolbar Card -->
                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <div class="col-span-full pb-2 border-b border-slate-300 text-sm font-semibold text-slate-700">Add Elements:</div>
                            <button class="px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('booth')">
                                <i class="fas fa-store"></i>
                                Add Booth
                            </button>
                            <button class="px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('parking')">
                                <i class="fas fa-parking"></i>
                                Parking
                            </button>
                            <button class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('entrance')">
                                <i class="fas fa-door-open"></i>
                                Entrance
                            </button>
                            <button class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('exit')">
                                <i class="fas fa-door-closed"></i>
                                Exit
                            </button>
                            <button class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('toilet')">
                                <i class="fas fa-restroom"></i>
                                Toilet
                            </button>

                            <div class="col-span-full pb-2 border-b border-slate-300 text-sm font-semibold text-slate-700 mt-2">Actions:</div>
                            <button class="px-4 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="zoomIn()">
                                <i class="fas fa-search-plus"></i>
                                Zoom In
                            </button>
                            <button class="px-4 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="zoomOut()">
                                <i class="fas fa-search-minus"></i>
                                Zoom Out
                            </button>
                            <button class="px-4 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="resetZoom()">
                                <i class="fas fa-compress"></i>
                                Reset Zoom
                            </button>
                            <button class="px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="clearCanvas()">
                                <i class="fas fa-trash-alt"></i>
                                Clear Canvas
                            </button>
                        </div>
                    </div>

                    <!-- Canvas Card -->
                    <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4">
                        <canvas id="layoutCanvas" width="900" height="600"></canvas>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 sticky top-5">
                        <h3 class="text-lg font-bold text-slate-800 mb-5 pb-3 border-b-2 border-slate-200 flex items-center">
                            <i class="fas fa-cog mr-2 text-[#ff7700]"></i>
                            Properties
                        </h3>
                        <div id="propertiesContent" class="text-slate-500 italic text-center py-10">
                            Select a booth to edit its properties
                        </div>

                        <div class="mt-8 pt-5 border-t border-slate-200">
                            <button type="button" id="saveLayoutBtn" class="w-full px-6 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center justify-center gap-2" onclick="saveLayout()">
                                <i class="fas fa-save"></i>
                                Save All Floors
                            </button>
                            <div id="saveStatus" class="mt-3 text-sm min-h-[18px] text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const saveEndpoint = "{{ route('booth-layout.save') }}";
        const eventId = "{{ $eventId ?? '' }}";
        const trackedProperties = ['elementType', 'elementLabel', 'originalWidth', 'originalHeight', 'boothType', 'boothPrice'];
        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: true
        });

        // Floor management variables
        let currentFloorNumber = 1;
        let currentFloorName = 'Floor 1';
        let allFloors = [{
            floor_number: 1,
            floor_name: 'Floor 1',
            booth_count: 0
        }];
        let floorLayouts = {}; // Store layouts for each floor

        // Variables for panning
        let isPanning = false;
        let lastPosX = 0;
        let lastPosY = 0;

        const elementTypes = {
            booth: {
                color: '#e3f2fd',
                strokeColor: '#1976d2',
                textColor: '#1976d2',
                defaultLabel: 'Booth',
                width: 120,
                height: 80
            },
            parking: {
                color: '#f5f5f5',
                strokeColor: '#616161',
                textColor: '#424242',
                defaultLabel: 'Parking',
                width: 140,
                height: 100
            },
            entrance: {
                color: '#e8f5e8',
                strokeColor: '#388e3c',
                textColor: '#2e7d32',
                defaultLabel: 'Entrance',
                width: 100,
                height: 60
            },
            exit: {
                color: '#ffebee',
                strokeColor: '#d32f2f',
                textColor: '#c62828',
                defaultLabel: 'Exit',
                width: 100,
                height: 60
            },
            toilet: {
                color: '#f3e5f5',
                strokeColor: '#7b1fa2',
                textColor: '#6a1b99',
                defaultLabel: 'Toilet',
                width: 80,
                height: 80
            }
        };

        const boothTypes = ['Standard', 'Premium', 'VIP', 'Corner', 'Island'];

        let elementCounters = {
            booth: 1,
            parking: 1,
            entrance: 1,
            exit: 1,
            toilet: 1
        };

        // Helper function to format Rupiah
        function formatRupiah(value) {
            const digits = String(value ?? 0).replace(/\D/g, '');
            const num = digits === '' ? 0 : parseInt(digits);
            return 'Rp' + num.toLocaleString('id-ID');
        }

        function createElement(type, left = 100, top = 100, customLabel = null, customProps = {}) {
            const config = elementTypes[type];
            const label = customLabel || `${config.defaultLabel} ${elementCounters[type]++}`;

            const width = customProps.width || config.width;
            const height = customProps.height || config.height;

            const rect = new fabric.Rect({
                left: 0,
                top: 0,
                width: width,
                height: height,
                fill: config.color,
                stroke: config.strokeColor,
                strokeWidth: 2,
                rx: 5,
                ry: 5
            });

            // Calculate vertical centering based on whether it's a booth (2 lines) or other element (1 line)
            let textTop;
            if (type === 'booth') {
                textTop = height / 2 - 10; // Position for first line when there are 2 lines
            } else {
                textTop = height / 2; // Center single line vertically
            }

            const text = new fabric.Text(label, {
                left: width / 2,
                top: textTop,
                fontSize: 14,
                fontFamily: 'Arial',
                fill: config.textColor,
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                fontWeight: 'bold'
            });

            const groupItems = [rect, text];

            if (type === 'booth') {
                const boothType = customProps.boothType || 'Standard';
                const price = customProps.price || 0;

                const infoText = new fabric.Text(
                    `${boothType} - ${formatRupiah(price)}`, {
                        left: width / 2,
                        top: height / 2 + 10, // Position for second line
                        fontSize: 11,
                        fontFamily: 'Arial',
                        fill: config.textColor,
                        textAlign: 'center',
                        originX: 'center',
                        originY: 'center'
                    }
                );
                groupItems.push(infoText);
            }

            const elementGroup = new fabric.Group(groupItems, {
                left: left,
                top: top,
                cornerColor: config.strokeColor,
                cornerSize: 8,
                transparentCorners: false,
                lockRotation: false,
                hasRotatingPoint: true,
                lockScalingX: type === 'booth', // Lock scaling for booths
                lockScalingY: type === 'booth' // Lock scaling for booths
            });

            elementGroup.set({
                elementType: type,
                elementLabel: label,
                originalWidth: width,
                originalHeight: height,
                boothType: customProps.boothType || 'Standard',
                boothPrice: customProps.price || 0
            });

            return elementGroup;
        }

        function addElement(type) {
            const element = createElement(
                type,
                Math.random() * (canvas.width - 200) + 50,
                Math.random() * (canvas.height - 150) + 50
            );

            canvas.add(element);
            canvas.setActiveObject(element);
            canvas.renderAll();
        }

        function updatePropertiesPanel(obj) {
            const content = document.getElementById('propertiesContent');

            if (!obj || obj.elementType !== 'booth') {
                content.innerHTML = '<div class="text-slate-500 italic text-center py-10">Select a booth to edit its properties</div>';
                return;
            }

            const width = Math.round(obj.originalWidth || obj.width);
            const height = Math.round(obj.originalHeight || obj.height);
            const type = obj.boothType || 'Standard';
            const price = obj.boothPrice || 0;
            const label = obj.elementLabel || 'Booth';

            content.innerHTML = `
                <div class="mb-5">
                    <label class="block mb-2 text-slate-700 font-medium text-sm">Booth Name:</label>
                    <input type="text" id="propLabel" value="${label}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-slate-700 font-medium text-sm">Booth Type:</label>
                    <select id="propType" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                        ${boothTypes.map(t => `<option value="${t}" ${t === type ? 'selected' : ''}>${t}</option>`).join('')}
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-slate-700 font-medium text-sm">Price:</label>
                    <input type="number" id="propPrice" value="${price}" min="0" step="100" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-slate-700 font-medium text-sm">Size:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" id="propWidth" value="${width}" min="50" placeholder="Width" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                        <input type="number" id="propHeight" value="${height}" min="50" placeholder="Height" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                    </div>
                </div>  

                <button class="w-full px-4 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center justify-center gap-2" onclick="applyProperties()">
                    <i class="fas fa-check"></i>
                    Apply Changes
                </button>
            `;
        }

        function applyProperties() {
            const obj = canvas.getActiveObject();
            if (!obj || obj.elementType !== 'booth') return;

            const newLabel = document.getElementById('propLabel').value.trim();
            const newType = document.getElementById('propType').value;
            const newPrice = parseFloat(document.getElementById('propPrice').value) || 0;
            const newWidth = parseInt(document.getElementById('propWidth').value) || 120;
            const newHeight = parseInt(document.getElementById('propHeight').value) || 80;

            const currentLeft = obj.left;
            const currentTop = obj.top;
            const currentAngle = obj.angle;

            const newBooth = createElement(
                'booth',
                currentLeft,
                currentTop,
                newLabel, {
                    width: newWidth,
                    height: newHeight,
                    boothType: newType,
                    price: newPrice
                }
            );

            newBooth.set({
                angle: currentAngle
            });

            canvas.remove(obj);
            canvas.add(newBooth);
            canvas.setActiveObject(newBooth);
            canvas.renderAll();

            updatePropertiesPanel(newBooth);
        }

        canvas.on('selection:created', function(e) {
            updatePropertiesPanel(e.selected[0]);
        });

        canvas.on('selection:updated', function(e) {
            updatePropertiesPanel(e.selected[0]);
        });

        canvas.on('selection:cleared', function() {
            updatePropertiesPanel(null);
        });

        canvas.on('mouse:dblclick', function(options) {
            if (options.target && options.target.type === 'group') {
                const group = options.target;
                const textObject = group.getObjects('text')[0];

                if (textObject) {
                    const newText = prompt('Enter new label:', textObject.text);
                    if (newText !== null && newText.trim() !== '') {
                        textObject.set('text', newText.trim());
                        group.set('elementLabel', newText.trim());
                        canvas.renderAll();
                        updatePropertiesPanel(group);
                    }
                }
            }
        });







        async function saveLayout() {
            const statusElement = document.getElementById('saveStatus');
            const saveBtn = document.getElementById('saveLayoutBtn');

            if (!eventId) {
                statusElement.textContent = 'No event ID available. Please create an event first.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
                return;
            }

            // Save current floor to memory first
            floorLayouts[currentFloorNumber] = canvas.toJSON(trackedProperties);

            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving All Floors...';
            statusElement.textContent = 'Saving all floor layouts...';
            statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-blue-600';

            let totalSaved = 0;
            let errors = [];

            try {
                // Save all floors that have layouts in memory
                for (const [floorNum, layoutData] of Object.entries(floorLayouts)) {
                    const floorNumber = parseInt(floorNum);
                    const floor = allFloors.find(f => f.floor_number === floorNumber);
                    const floorName = floor ? floor.floor_name : `Floor ${floorNumber}`;

                    // Check if this floor has at least one booth
                    const boothCount = (layoutData.objects || []).filter(obj => obj.elementType === 'booth').length;

                    if (boothCount === 0) {
                        console.log(`Skipping ${floorName} - no booths`);
                        continue; // Skip floors without booths
                    }

                    const payload = {
                        event_id: parseInt(eventId, 10),
                        floor_number: floorNumber,
                        floor_name: floorName,
                        layout_json: JSON.stringify(layoutData),
                        replace_existing: true
                    };

                    try {
                        const response = await fetch(saveEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(payload)
                        });

                        if (response.ok) {
                            totalSaved++;
                            console.log(`Saved ${floorName} with ${boothCount} booths`);
                        } else {
                            const errorData = await response.json();
                            errors.push(`${floorName}: ${errorData.message || 'Failed to save'}`);
                        }
                    } catch (error) {
                        errors.push(`${floorName}: Network error`);
                        console.error(`Error saving ${floorName}:`, error);
                    }
                }

                // Show result
                if (totalSaved > 0) {
                    statusElement.textContent = `Successfully saved ${totalSaved} floor${totalSaved > 1 ? 's' : ''}!`;
                    statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-green-600';
                } else {
                    statusElement.textContent = 'No floors with booths to save. Add at least one booth to any floor.';
                    statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-orange-600';
                }

                if (errors.length > 0) {
                    statusElement.textContent += ` (${errors.length} error${errors.length > 1 ? 's' : ''})`;
                    console.error('Save errors:', errors);
                }

                // Refresh floor list to get updated booth counts
                await loadFloors();

            } catch (error) {
                console.error('Save layout error:', error);
                statusElement.textContent = 'Error while saving layouts.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
            } finally {
                // Reset button state
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Save All Floors';
            }
        }

        // Load all floors for the event
        async function loadFloors() {
            if (!eventId) return;

            try {
                const response = await fetch(`{{ url('booth-layout/floors') }}/${eventId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.floors && data.floors.length > 0) {
                        // Merge server data with local floor list
                        const serverFloors = data.floors;

                        // Update booth counts from server for floors that exist
                        serverFloors.forEach(serverFloor => {
                            const localFloorIndex = allFloors.findIndex(f => f.floor_number === serverFloor.floor_number);
                            if (localFloorIndex !== -1) {
                                allFloors[localFloorIndex].booth_count = serverFloor.booth_count;
                            } else {
                                // Add floor from server if not in local list
                                allFloors.push(serverFloor);
                            }
                        });

                        // Sort floors
                        allFloors.sort((a, b) => a.floor_number - b.floor_number);
                    } else {
                        // No floors exist yet, keep current allFloors (might have new unsaved floors)
                        if (allFloors.length === 0) {
                            allFloors = [{
                                floor_number: 1,
                                floor_name: 'Floor 1',
                                booth_count: 0
                            }];
                        }
                    }
                    updateFloorSelector();
                }
            } catch (error) {
                console.error('Error loading floors:', error);
                // On error, keep current allFloors
                if (allFloors.length === 0) {
                    allFloors = [{
                        floor_number: 1,
                        floor_name: 'Floor 1',
                        booth_count: 0
                    }];
                }
                updateFloorSelector();
            }
        }

        // Update the floor selector UI
        function updateFloorSelector() {
            const floorList = document.getElementById('floorList');
            const deleteBtn = document.getElementById('deleteFloorBtn');
            const currentFloorDisplay = document.getElementById('currentFloorDisplay');

            floorList.innerHTML = '';

            // Sort floors by floor number (old to new, top to bottom)
            const sortedFloors = [...allFloors].sort((a, b) => a.floor_number - b.floor_number);

            sortedFloors.forEach(floor => {
                const floorBtn = document.createElement('button');
                floorBtn.className = 'floor-item px-4 py-3 rounded-full font-medium transition-all shadow-md hover:shadow-lg flex items-center justify-between gap-2';
                floorBtn.dataset.floor = floor.floor_number;

                if (floor.floor_number === currentFloorNumber) {
                    floorBtn.classList.add('active');
                }

                floorBtn.innerHTML = `
                    <span class="font-semibold text-sm">${floor.floor_name}</span>
                    <span class="text-xs ${floor.floor_number === currentFloorNumber ? 'bg-white/30' : 'bg-slate-300'} px-2 py-0.5 rounded-full">${floor.booth_count || 0}</span>
                `;

                floorBtn.onclick = () => switchFloor(floor.floor_number);
                floorList.appendChild(floorBtn);
            });

            // Show delete button only if there's more than one floor
            deleteBtn.style.display = allFloors.length > 1 ? 'flex' : 'none';

            // Update current floor display
            if (currentFloorDisplay) {
                currentFloorDisplay.textContent = currentFloorName;
            }
        }

        // Switch to a different floor
        async function switchFloor(floorNumber) {
            floorNumber = parseInt(floorNumber);

            // Don't switch if already on this floor
            if (floorNumber === currentFloorNumber) {
                return;
            }

            // ALWAYS save current floor layout in memory before switching
            const currentCanvas = canvas.toJSON(trackedProperties);
            floorLayouts[currentFloorNumber] = currentCanvas;

            // Update the booth count for current floor before switching
            const currentBoothCount = (currentCanvas.objects || []).filter(obj => obj.elementType === 'booth').length;
            const currentFloorIndex = allFloors.findIndex(f => f.floor_number === currentFloorNumber);
            if (currentFloorIndex !== -1) {
                allFloors[currentFloorIndex].booth_count = currentBoothCount;
            }

            // Switch to new floor
            currentFloorNumber = floorNumber;
            const floor = allFloors.find(f => f.floor_number === currentFloorNumber);
            currentFloorName = floor ? floor.floor_name : `Floor ${currentFloorNumber}`;

            // Clear canvas
            canvas.clear();
            canvas.backgroundColor = '#ffffff';

            // Load the floor layout
            if (floorLayouts[currentFloorNumber]) {
                // Load from memory if available
                canvas.loadFromJSON(floorLayouts[currentFloorNumber], function() {
                    canvas.renderAll();
                });
            } else {
                // Load from server
                try {
                    const response = await fetch(`{{ url('booth-layout/data') }}/${eventId}?floor_number=${currentFloorNumber}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.layout) {
                            canvas.loadFromJSON(data.layout, function() {
                                canvas.renderAll();
                            });
                            floorLayouts[currentFloorNumber] = data.layout;
                        }
                    } else {
                        // Floor doesn't exist in database yet, start with empty canvas
                        console.log(`Floor ${currentFloorNumber} not found in database, starting with empty canvas`);
                    }
                } catch (error) {
                    console.error('Error loading floor layout:', error);
                }
            }

            canvas.renderAll();
            updatePropertiesPanel(null);
            updateFloorSelector(); // Update UI to reflect new active floor

            // Clear save status
            document.getElementById('saveStatus').textContent = '';
        }

        // Add a new floor
        function addNewFloor() {
            const newFloorNumber = Math.max(...allFloors.map(f => f.floor_number), 0) + 1;
            const floorName = prompt('Enter name for the new floor:', `Floor ${newFloorNumber}`);

            if (floorName && floorName.trim() !== '') {
                // Save current floor before switching
                floorLayouts[currentFloorNumber] = canvas.toJSON(trackedProperties);

                // Add new floor to the list
                allFloors.push({
                    floor_number: newFloorNumber,
                    floor_name: floorName.trim(),
                    booth_count: 0
                });

                // Switch to the new floor
                currentFloorNumber = newFloorNumber;
                currentFloorName = floorName.trim();

                // Clear canvas for new floor
                canvas.clear();
                canvas.backgroundColor = '#ffffff';
                Object.keys(elementCounters).forEach(type => {
                    elementCounters[type] = 1;
                });
                canvas.renderAll();

                updateFloorSelector();

                alert(`New floor "${floorName.trim()}" created. Add booths and save the layout.`);
            }
        }

        // Rename current floor
        function renameCurrentFloor() {
            const newName = prompt('Enter new name for this floor:', currentFloorName);

            if (newName && newName.trim() !== '' && newName.trim() !== currentFloorName) {
                currentFloorName = newName.trim();

                const floorIndex = allFloors.findIndex(f => f.floor_number === currentFloorNumber);
                if (floorIndex !== -1) {
                    allFloors[floorIndex].floor_name = currentFloorName;
                }

                updateFloorSelector();
                alert(`Floor renamed to "${currentFloorName}". Remember to save the layout.`);
            }
        }

        // Delete current floor
        async function deleteCurrentFloor() {
            if (allFloors.length <= 1) {
                alert('Cannot delete the last floor. At least one floor is required.');
                return;
            }

            if (!confirm(`Are you sure you want to delete "${currentFloorName}"? This will remove all booths on this floor.`)) {
                return;
            }

            try {
                const response = await fetch(`{{ url('booth-layout/floors') }}/${eventId}/${currentFloorNumber}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (response.ok) {
                    alert('Floor deleted successfully.');

                    // Remove from memory
                    delete floorLayouts[currentFloorNumber];

                    // Remove from allFloors
                    allFloors = allFloors.filter(f => f.floor_number !== currentFloorNumber);

                    // Switch to first available floor
                    if (allFloors.length > 0) {
                        await switchFloor(allFloors[0].floor_number);
                    }

                    updateFloorSelector();
                } else {
                    const errorData = await response.json();
                    alert('Failed to delete floor: ' + (errorData.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting floor:', error);
                alert('Network error while deleting floor.');
            }
        }

        // Initialize floor selector on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load existing floors on page load
            loadFloors();
        });

        function clearCanvas() {
            if (confirm('Are you sure you want to clear the canvas?')) {
                canvas.clear();
                canvas.backgroundColor = '#ffffff';
                Object.keys(elementCounters).forEach(type => {
                    elementCounters[type] = 1;
                });
                updatePropertiesPanel(null);
                canvas.renderAll();
            }
        }

        // Zoom functions
        function zoomIn() {
            let zoom = canvas.getZoom();
            zoom += 0.1;
            if (zoom > 3) zoom = 3; // Max zoom level

            // Calculate center point
            const center = new fabric.Point(canvas.width / 2, canvas.height / 2);
            canvas.zoomToPoint(center, zoom);
            canvas.renderAll();
        }

        function zoomOut() {
            let zoom = canvas.getZoom();
            zoom -= 0.1;
            if (zoom < 0.3) zoom = 0.3; // Min zoom level

            // Calculate center point
            const center = new fabric.Point(canvas.width / 2, canvas.height / 2);
            canvas.zoomToPoint(center, zoom);
            canvas.renderAll();
        }

        function resetZoom() {
            canvas.setZoom(1);
            canvas.setViewportTransform([1, 0, 0, 1, 0, 0]); // Reset pan as well
            canvas.renderAll();
        }

        // Add mouse wheel zoom
        canvas.on('mouse:wheel', function(opt) {
            const delta = opt.e.deltaY;
            let zoom = canvas.getZoom();
            zoom *= 0.999 ** delta;
            if (zoom > 3) zoom = 3;
            if (zoom < 0.3) zoom = 0.3;

            // Zoom to the mouse pointer position
            const point = new fabric.Point(opt.e.offsetX, opt.e.offsetY);
            canvas.zoomToPoint(point, zoom);

            opt.e.preventDefault();
            opt.e.stopPropagation();
            canvas.renderAll();
        });

        // Panning functionality - Left click on empty space to pan
        canvas.on('mouse:down', function(opt) {
            const evt = opt.e;
            // Enable panning with left click only when clicking on empty canvas (no target object)
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
                canvas.selection = true;
                canvas.defaultCursor = 'default';
                canvas.renderAll();
            }
        });
        window.addEventListener('load', function() {
            const sampleElements = [{
                    type: 'entrance',
                    x: 100,
                    y: 50,
                    label: 'Main Entrance'
                },
                {
                    type: 'booth',
                    x: 200,
                    y: 150,
                    label: 'Booth 1',
                    props: {
                        boothType: 'Premium',
                        price: 5000,
                        width: 150,
                        height: 100
                    }
                },
                {
                    type: 'booth',
                    x: 400,
                    y: 150,
                    label: 'Booth 2',
                    props: {
                        boothType: 'Standard',
                        price: 3000,
                        width: 120,
                        height: 80
                    }
                },
                {
                    type: 'parking',
                    x: 500,
                    y: 350,
                    label: 'Visitor Parking'
                },
                {
                    type: 'toilet',
                    x: 100,
                    y: 350,
                    label: 'Restroom'
                }
            ];

            sampleElements.forEach(item => {
                const element = createElement(item.type, item.x, item.y, item.label, item.props || {});
                canvas.add(element);
                if (item.type === 'booth') {
                    const match = item.label.match(/Booth (\d+)/);
                    if (match) {
                        const num = parseInt(match[1]);
                        elementCounters.booth = Math.max(elementCounters.booth, num + 1);
                    }
                }
            });

            canvas.renderAll();
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                const activeObj = canvas.getActiveObject();
                if (activeObj && activeObj.elementType) {
                    activeObj.clone(function(cloned) {
                        const type = cloned.elementType;
                        cloned.set({
                            left: activeObj.left + 20,
                            top: activeObj.top + 20,
                            elementLabel: `${elementTypes[type].defaultLabel} ${elementCounters[type]++}`
                        });

                        canvas.add(cloned);
                        canvas.setActiveObject(cloned);
                        canvas.renderAll();
                    });
                }
            }

            if (e.key === 'Delete' || e.key === 'Backspace') {
                const activeObj = canvas.getActiveObject();
                if (activeObj && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                    canvas.remove(activeObj);
                    canvas.renderAll();
                }
            }
        });
    </script>
</body>

</html>