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

    <!-- Full Page Loader -->
    <div id="pageLoader">
        <x-loader overlay="true" message="Loading booth layout..." size="lg" />
    </div>

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

        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div class="lg:col-span-2 xl:col-span-3 flex flex-col gap-6">
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
                            <button class="px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('stairs')">
                                <i class="fas fa-stairs"></i>
                                Stairs
                            </button>
                            <button class="px-4 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('elevator')">
                                <i class="fas fa-elevator"></i>
                                Elevator
                            </button>
                            <button class="px-4 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('wall')">
                                <i class="fas fa-grip-lines"></i>
                                Wall
                            </button>
                            <button class="px-4 py-3 bg-white hover:bg-gray-50 text-gray-800 border-2 border-gray-800 rounded-lg font-medium transition-all hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-2" onclick="addElement('custom')">
                                <i class="fas fa-square"></i>
                                Custom
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
                    <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4 overflow-hidden">
                        <canvas id="layoutCanvas" width="860" height="600"></canvas>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 xl:col-span-1">
                    <div class="sticky top-5 space-y-6">
                        <!-- Properties Card -->
                        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b-2 border-slate-200 flex items-center">
                                <i class="fas fa-cog mr-2 text-[#ff7700]"></i>
                                Properties
                            </h3>
                            <div id="propertiesContent" class="text-slate-500 italic text-center py-6 text-sm">
                                Select a booth to edit its properties
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-200">
                                <button type="button" id="saveLayoutBtn" class="w-full px-6 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center justify-center gap-2" onclick="saveLayout()">
                                    <i class="fas fa-save"></i>
                                    Save All Floors
                                </button>
                                <div id="saveStatus" class="mt-3 text-sm min-h-[18px] text-center break-words"></div>
                            </div>
                        </div>

                        <!-- Instructions Card -->
                        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-5">
                            <h4 class="text-base font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-[#ff7700]"></i>
                                Instructions
                            </h4>
                            <ul class="list-disc pl-5 space-y-1.5 text-slate-600 text-xs leading-relaxed">
                                <li>Click any element button to add it to the canvas</li>
                                <li>Select a booth to edit its properties in this panel</li>
                                <li>Double-click any element to edit its label</li>
                                <li>Drag elements to position them</li>
                                <li>Elements align with nearby objects (orange guides appear)</li>
                                <li>Use Zoom buttons or mouse wheel to zoom</li>
                                <li>Click and drag empty space to pan</li>
                            </ul>
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
        const trackedProperties = ['elementType', 'elementLabel', 'originalWidth', 'originalHeight', 'boothType', 'boothPrice', 'lockScalingX', 'lockScalingY'];
        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: true
        });

        // Snapping configuration
        const SNAP_THRESHOLD = 10; // Distance in pixels to trigger snapping
        const ANGLE_SNAP_THRESHOLD = 10; // Degrees to snap to cardinal directions (smaller = less aggressive)
        const SNAP_ANGLES = [0, 90, 180, 270]; // Cardinal angles for wall snapping
        let alignmentLines = []; // Store alignment guide lines
        let isSnapping = false; // Prevent snapping feedback loops

        // Custom rotate icon - modern circular arrow design
        const rotateImg = document.createElement('img');
        rotateImg.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none">
                <circle cx="16" cy="16" r="15" fill="white" stroke="#ff7700" stroke-width="2"/>
                <path d="M11 10.5 A8 8 0 1 0 16 8" 
                      stroke="#ff7700" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <path d="M21 8 L17 6 L17 10 Z"
                      fill="#ff7700" stroke="#ff7700" stroke-width="1" stroke-linejoin="round"/>
            </svg>
        `);

        // Set custom rotation control icon
        fabric.Object.prototype.controls.mtr = new fabric.Control({
            x: 0,
            y: -0.5,
            offsetY: -40,
            cursorStyle: 'grab',
            actionHandler: fabric.controlsUtils.rotationWithSnapping,
            actionName: 'rotate',
            render: function(ctx, left, top, styleOverride, fabricObject) {
                const size = 28;
                ctx.save();
                ctx.translate(left, top);
                ctx.drawImage(rotateImg, -size / 2, -size / 2, size, size);
                ctx.restore();
            },
            cornerSize: 32
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
            },
            stairs: {
                color: '#fff3e0',
                strokeColor: '#fb8c00',
                textColor: '#e65100',
                defaultLabel: 'Stairs',
                width: 80,
                height: 80
            },
            elevator: {
                color: '#e0f7fa',
                strokeColor: '#00acc1',
                textColor: '#007c91',
                defaultLabel: 'Elevator',
                width: 80,
                height: 80
            },
            wall: {
                color: '#000000',
                strokeColor: '#000000',
                textColor: '#000000',
                defaultLabel: 'Wall',
                width: 150,
                height: 8
            },
            custom: {
                color: '#ffffff',
                strokeColor: '#000000',
                textColor: '#000000',
                defaultLabel: 'Custom',
                width: 100,
                height: 80
            }
        };

        const boothTypes = ['Standard', 'Premium', 'VIP', 'Corner', 'Island'];

        let elementCounters = {
            booth: 1,
            parking: 1,
            entrance: 1,
            exit: 1,
            toilet: 1,
            stairs: 1,
            elevator: 1,
            wall: 1,
            custom: 1
        };

        // Function to find the lowest available number for a given element type
        function getNextAvailableNumber(type) {
            const defaultLabel = elementTypes[type].defaultLabel;
            const existingNumbers = [];

            // Get all objects of this type and extract their numbers
            canvas.getObjects().forEach(obj => {
                if (obj.elementType === type && obj.elementLabel) {
                    const match = obj.elementLabel.match(new RegExp(`${defaultLabel}\\s+(\\d+)`));
                    if (match) {
                        existingNumbers.push(parseInt(match[1], 10));
                    }
                }
            });

            // Find the lowest available number starting from 1
            let number = 1;
            while (existingNumbers.includes(number)) {
                number++;
            }

            return number;
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, match => {
                switch (match) {
                    case '&':
                        return '&amp;';
                    case '<':
                        return '&lt;';
                    case '>':
                        return '&gt;';
                    case '"':
                        return '&quot;';
                    case '\'':
                        return '&#39;';
                    default:
                        return match;
                }
            });
        }

        // Helper function to format Rupiah
        function formatRupiah(value) {
            const digits = String(value ?? 0).replace(/\D/g, '');
            const num = digits === '' ? 0 : parseInt(digits);
            return 'Rp' + num.toLocaleString('id-ID');
        }

        function createElement(type, left = 100, top = 100, customLabel = null, customProps = {}) {
            const config = elementTypes[type];
            // Use custom label if provided, otherwise find the next available number
            const label = customLabel || `${config.defaultLabel} ${getNextAvailableNumber(type)}`;

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

            if (type === 'wall') {
                const width = customProps.width || config.width;
                const height = customProps.height || config.height;

                const line = new fabric.Rect({
                    left: left,
                    top: top,
                    width: width,
                    height: height,
                    fill: '#000000',
                    stroke: '#000000',
                    strokeWidth: 0,
                    rx: 0,
                    ry: 0,
                    lockScalingY: true, // Lock vertical scaling, only allow horizontal
                    lockUniScaling: true // Prevent uniform scaling
                });

                line.set({
                    elementType: 'wall',
                    elementLabel: customLabel || `${config.defaultLabel} ${elementCounters[type]++}`,
                    originalWidth: width,
                    originalHeight: height,
                    snapAngle: 90,
                    snapThreshold: ANGLE_SNAP_THRESHOLD
                });

                line.setControlsVisibility({
                    mt: false, // Hide middle top
                    mb: false, // Hide middle bottom
                    ml: true, // Show middle left for horizontal scaling
                    mr: true, // Show middle right for horizontal scaling
                    tl: false, // Hide top left corner
                    tr: false, // Hide top right corner
                    bl: false, // Hide bottom left corner
                    br: false, // Hide bottom right corner
                    mtr: true // Keep rotation control
                });

                return line;
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
            if (!content) {
                return;
            }

            if (!obj || (obj.elementType !== 'booth' && obj.elementType !== 'custom')) {
                content.innerHTML = '<div class="text-slate-500 italic text-center py-10">Select a booth or custom element to edit its properties</div>';
                return;
            }

            const width = Math.round(obj.originalWidth || obj.width);
            const height = Math.round(obj.originalHeight || obj.height);
            const label = obj.elementLabel || (obj.elementType === 'booth' ? 'Booth' : 'Custom');

            let html = '';

            if (obj.elementType === 'custom') {
                html += `
                    <div class="mb-4">
                        <label class="block mb-2 text-slate-700 font-medium text-sm">Label:</label>
                        <input type="text" id="propLabel" value="${escapeHtml(label)}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                    </div>
                 `;
            }

            if (obj.elementType === 'booth') {
                const type = obj.boothType || 'Standard';
                const price = obj.boothPrice || 0;
                html += `
                    <div class="mb-4">
                        <label class="block mb-2 text-slate-700 font-medium text-sm">Booth Type:</label>
                        <select id="propType" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                            ${boothTypes.map(t => `<option value="${t}" ${t === type ? 'selected' : ''}>${t}</option>`).join('')}
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-slate-700 font-medium text-sm">Price:</label>
                        <input type="number" id="propPrice" value="${price}" min="0" step="100" class="w-full px-2 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-slate-700 font-medium text-sm">Size (cm):</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="number" id="propWidth" value="${width}" min="50" placeholder="Width" class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">cm</span>
                            </div>
                            <div class="relative">
                                <input type="number" id="propHeight" value="${height}" min="50" placeholder="Height" class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">cm</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += `
                <button class="w-full px-4 py-3 bg-[#ff7700] hover:bg-[#e66600] text-white rounded-lg font-semibold transition-all duration-200 shadow-md flex items-center justify-center gap-2" onclick="applyProperties()">
                    <i class="fas fa-check"></i>
                    Apply Changes
                </button>
            `;
            content.innerHTML = html;
        }

        function applyProperties() {
            const obj = canvas.getActiveObject();
            if (!obj || (obj.elementType !== 'booth' && obj.elementType !== 'custom')) {
                return;
            }

            const labelInput = document.getElementById('propLabel');
            const newLabel = labelInput ? labelInput.value : (obj.elementLabel || (obj.elementType === 'booth' ? 'Booth' : 'Custom'));

            let newWidth, newHeight;
            const widthInput = document.getElementById('propWidth');

            if (widthInput) {
                newWidth = parseInt(widthInput.value, 10) || 120;
                newHeight = parseInt(document.getElementById('propHeight').value, 10) || 80;
            } else {
                newWidth = obj.getScaledWidth();
                newHeight = obj.getScaledHeight();
            }

            // Store current position and angle
            const currentLeft = obj.left;
            const currentTop = obj.top;
            const currentAngle = obj.angle;
            const currentId = obj.__internalId; // Use existing ID if available

            let customProps = {
                width: newWidth,
                height: newHeight
            };

            if (obj.elementType === 'booth') {
                const newType = document.getElementById('propType')?.value || 'Standard';
                const newPrice = parseFloat(document.getElementById('propPrice')?.value) || 0;
                customProps.boothType = newType;
                customProps.price = newPrice;
            }

            // Create a new element with updated properties
            const newElement = createElement(obj.elementType, currentLeft, currentTop, newLabel, customProps);

            // Restore position and angle
            newElement.set({
                left: currentLeft,
                top: currentTop,
                angle: currentAngle,
                __internalId: currentId
            });

            // Replace the old element with the new one
            canvas.remove(obj);
            canvas.add(newElement);
            canvas.setActiveObject(newElement);
            canvas.renderAll();

            // Update the properties panel to reflect the new object
            updatePropertiesPanel(newElement);
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
                    // Re-apply locks for booths
                    canvas.getObjects().forEach(obj => {
                        if (obj.elementType === 'booth') {
                            obj.set({
                                lockScalingX: true,
                                lockScalingY: true
                            });
                        } else if (obj.elementType === 'wall') {
                            // Apply snapping properties
                            obj.set({
                                snapAngle: 90,
                                snapThreshold: ANGLE_SNAP_THRESHOLD
                            });
                        }
                    });
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
                                // Re-apply locks for booths
                                canvas.getObjects().forEach(obj => {
                                    if (obj.elementType === 'booth') {
                                        obj.set({
                                            lockScalingX: true,
                                            lockScalingY: true
                                        });
                                    } else if (obj.elementType === 'wall') {
                                        // Apply snapping properties
                                        obj.set({
                                            snapAngle: 90,
                                            snapThreshold: ANGLE_SNAP_THRESHOLD
                                        });
                                    }
                                });
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
            // Show loader
            const pageLoader = document.getElementById('pageLoader');
            if (pageLoader) pageLoader.classList.remove('hidden');

            // Load existing floors on page load
            loadFloors().finally(() => {
                // Hide loader after loading
                if (pageLoader) pageLoader.classList.add('hidden');
            });
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

        // Snapping helper functions
        function clearAlignmentLines() {
            alignmentLines.forEach(line => canvas.remove(line));
            alignmentLines = [];
        }

        function drawAlignmentLine(x1, y1, x2, y2) {
            const line = new fabric.Line([x1, y1, x2, y2], {
                stroke: '#ff7700',
                strokeWidth: 1,
                strokeDashArray: [5, 5],
                selectable: false,
                evented: false,
                opacity: 0.8
            });
            canvas.add(line);
            alignmentLines.push(line);
        }

        function getObjectEdges(obj) {
            const matrix = obj.calcTransformMatrix();
            const tl = fabric.util.transformPoint({
                x: -obj.width / 2,
                y: -obj.height / 2
            }, matrix);
            const tr = fabric.util.transformPoint({
                x: obj.width / 2,
                y: -obj.height / 2
            }, matrix);
            const bl = fabric.util.transformPoint({
                x: -obj.width / 2,
                y: obj.height / 2
            }, matrix);
            const br = fabric.util.transformPoint({
                x: obj.width / 2,
                y: obj.height / 2
            }, matrix);

            const left = Math.min(tl.x, tr.x, bl.x, br.x);
            const right = Math.max(tl.x, tr.x, bl.x, br.x);
            const top = Math.min(tl.y, tr.y, bl.y, br.y);
            const bottom = Math.max(tl.y, tr.y, bl.y, br.y);

            return {
                left,
                right,
                top,
                bottom,
                centerX: (left + right) / 2,
                centerY: (top + bottom) / 2,
                points: {
                    tl,
                    tr,
                    bl,
                    br
                }
            };
        }

        // Object moving with snapping
        canvas.on('object:moving', function(e) {
            const obj = e.target;

            // Prevent feedback loops
            if (isSnapping) return;

            clearAlignmentLines();

            // Skip snapping if not a wall or booth
            if (!obj.elementType) return;

            const movingEdges = getObjectEdges(obj);

            let bestSnapX = null;
            let minDiffX = SNAP_THRESHOLD;

            let bestSnapY = null;
            let minDiffY = SNAP_THRESHOLD;

            canvas.getObjects().forEach(target => {
                if (target === obj || !target.elementType || target === canvas.getActiveObject()) return;

                const targetEdges = getObjectEdges(target);

                // Horizontal snapping
                const diffLeft = targetEdges.left - movingEdges.left;
                if (Math.abs(diffLeft) < minDiffX) {
                    minDiffX = Math.abs(diffLeft);
                    bestSnapX = {
                        val: diffLeft,
                        line: targetEdges.left
                    };
                }

                const diffRight = targetEdges.right - movingEdges.right;
                if (Math.abs(diffRight) < minDiffX) {
                    minDiffX = Math.abs(diffRight);
                    bestSnapX = {
                        val: diffRight,
                        line: targetEdges.right
                    };
                }

                const diffCenterX = targetEdges.centerX - movingEdges.centerX;
                if (Math.abs(diffCenterX) < minDiffX) {
                    minDiffX = Math.abs(diffCenterX);
                    bestSnapX = {
                        val: diffCenterX,
                        line: targetEdges.centerX
                    };
                }

                // Vertical snapping
                const diffTop = targetEdges.top - movingEdges.top;
                if (Math.abs(diffTop) < minDiffY) {
                    minDiffY = Math.abs(diffTop);
                    bestSnapY = {
                        val: diffTop,
                        line: targetEdges.top
                    };
                }

                const diffBottom = targetEdges.bottom - movingEdges.bottom;
                if (Math.abs(diffBottom) < minDiffY) {
                    minDiffY = Math.abs(diffBottom);
                    bestSnapY = {
                        val: diffBottom,
                        line: targetEdges.bottom
                    };
                }

                const diffCenterY = targetEdges.centerY - movingEdges.centerY;
                if (Math.abs(diffCenterY) < minDiffY) {
                    minDiffY = Math.abs(diffCenterY);
                    bestSnapY = {
                        val: diffCenterY,
                        line: targetEdges.centerY
                    };
                }
            });

            if (bestSnapX) {
                obj.left += bestSnapX.val;
                drawAlignmentLine(bestSnapX.line, 0, bestSnapX.line, canvas.height);
            }

            if (bestSnapY) {
                obj.top += bestSnapY.val;
                drawAlignmentLine(0, bestSnapY.line, canvas.width, bestSnapY.line);
            }

            if (bestSnapX || bestSnapY) {
                obj.setCoords();
            }
        });



        // Clear alignment lines when object is released
        canvas.on('object:modified', function() {
            setTimeout(() => clearAlignmentLines(), 100);
        });

        canvas.on('selection:cleared', function() {
            clearAlignmentLines();
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