<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Booth Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>

<body class="bg-gray-100 p-5">
    @include('components.navbar')

    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-lg p-6 space-y-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Booth Layout</h1>
                <p class="text-sm text-gray-500 mt-1">Load an event layout, update booth positions and details, then save your changes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('testing-layout') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition-colors">Design New Layout</a>
                <a href="{{ route('testing-layout.view') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold transition-colors">View Saved Layouts</a>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-[300px_auto] gap-4 items-end">
                <div>
                    <label for="eventIdInput" class="block text-sm font-semibold text-gray-700 mb-2">Event ID</label>
                    <input type="number" id="eventIdInput" min="1" placeholder="Enter event ID" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-semibold transition-colors" onclick="loadExistingLayout()">Load Layout</button>
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold transition-colors" onclick="clearCanvas()">Clear Canvas</button>
                </div>
            </div>
            <div id="statusMessage" class="mt-2 text-sm text-gray-600 min-h-[20px]"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-gray-200 rounded-md p-3">
                    <p class="text-xs uppercase text-gray-500">Event ID</p>
                    <p id="eventSummaryId" class="text-lg font-semibold text-gray-800 mt-1">--</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-md p-3">
                    <p class="text-xs uppercase text-gray-500">Event Name</p>
                    <p id="eventSummaryName" class="text-lg font-semibold text-gray-800 mt-1 truncate">--</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-md p-3">
                    <p class="text-xs uppercase text-gray-500">Stored Booths</p>
                    <p id="eventSummaryStored" class="text-lg font-semibold text-gray-800 mt-1">--</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">
            <div class="space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="col-span-full pb-2 border-b border-gray-200 text-sm font-semibold text-gray-700">Add Elements</div>
                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('booth')">Add Booth</button>
                    <button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('parking')">Visitor Parking</button>
                    <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('entrance')">Entrance Gate</button>
                    <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('exit')">Exit Gate</button>
                    <button class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('toilet')">Toilet</button>

                    <div class="col-span-full pt-2 border-t border-gray-200 text-sm font-semibold text-gray-700">Canvas Actions</div>
                    <button class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="exportJSON()">Export JSON</button>
                    <label class="col-span-full flex flex-col gap-2 text-sm text-gray-700">
                        <span>Load from JSON</span>
                        <textarea id="jsonInput" class="w-full min-h-[110px] px-3 py-2 border border-gray-300 rounded-md text-xs font-mono resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Paste layout JSON, then click Load JSON"></textarea>
                        <div class="flex gap-2">
                            <button type="button" class="px-3 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md text-sm font-medium transition-colors" onclick="loadJSON()">Load JSON</button>
                            <button type="button" class="px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-md text-sm font-medium transition-colors" onclick="clearCanvas()">Clear Canvas</button>
                        </div>
                    </label>
                </div>

                <div class="border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 p-4 overflow-auto">
                    <canvas id="layoutCanvas" width="900" height="600"></canvas>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">Canvas Booths</h2>
                        <span id="boothCountBadge" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">0 booths</span>
                    </div>
                    <p class="text-sm text-gray-500">Click a row to select the booth on the canvas, or select a booth to edit it in the panel.</p>
                    <div class="max-h-72 overflow-y-auto border border-gray-200 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                                <tr>
                                    <th class="px-3 py-2 text-left">Name</th>
                                    <th class="px-3 py-2 text-left">Type</th>
                                    <th class="px-3 py-2 text-right">Price</th>
                                    <th class="px-3 py-2 text-left">Size</th>
                                </tr>
                            </thead>
                            <tbody id="boothTableBody" class="divide-y divide-gray-100">
                                <tr>
                                    <td colspan="4" class="px-3 py-4 text-center text-gray-500">Load an event to see its booths.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Layout JSON Preview</h2>
                    <pre id="jsonOutput" class="bg-gray-900 text-gray-100 text-xs rounded-lg p-4 max-h-72 overflow-y-auto">Export the canvas to preview JSON.</pre>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 space-y-6 h-fit sticky top-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-4">Booth Properties</h3>
                    <div id="propertiesContent" class="text-gray-500 italic text-center py-10">
                        Select a booth to edit its properties
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-base font-semibold text-gray-800 mb-4">Save Layout</h4>
                    <div class="mb-4">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" id="replaceExistingInput" checked class="w-4 h-4">
                            <span>Replace existing booths for this event</span>
                        </label>
                    </div>
                    <button type="button" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition-colors" onclick="saveLayout()">Save Layout Changes</button>
                    <div id="saveStatus" class="mt-3 text-sm min-h-[18px] text-gray-600"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const saveEndpoint = "{{ route('testing-layout.save') }}";
        const loadEndpointTemplate = "{{ route('testing-layout.data', ['event' => '__EVENT__']) }}";

        const trackedProperties = ['elementType', 'elementLabel', 'originalWidth', 'originalHeight', 'boothType', 'boothPrice'];

        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: true
        });

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

        let isLoadingLayout = false;
        let objectIdCounter = 1;

        function resetCounters() {
            Object.keys(elementCounters).forEach(key => {
                elementCounters[key] = 1;
            });
        }

        function ensureObjectId(obj) {
            if (!obj.__internalId) {
                obj.__internalId = `el-${objectIdCounter++}`;
            }
            return obj.__internalId;
        }

        function setStatus(message, type = 'info') {
            const el = document.getElementById('statusMessage');
            if (!el) {
                return;
            }

            let colorClass = 'text-gray-600';
            if (type === 'error') {
                colorClass = 'text-red-600';
            } else if (type === 'success') {
                colorClass = 'text-green-600';
            } else if (type === 'warning') {
                colorClass = 'text-yellow-600';
            }

            el.className = `mt-2 text-sm min-h-[20px] ${colorClass}`;
            el.textContent = message || '';
        }

        function updateEventSummary(data = null, eventId = '') {
            document.getElementById('eventSummaryId').textContent = eventId || '--';
            document.getElementById('eventSummaryName').textContent = data?.event?.name || '--';
            const stored = data?.booth_count ?? data?.booths?.length;
            document.getElementById('eventSummaryStored').textContent = stored !== undefined ? stored : '--';
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
        function createElement(type, left = 100, top = 100, customLabel = null, customProps = {}) {
            const config = elementTypes[type];
            const label = customLabel || `${config.defaultLabel} ${elementCounters[type]++}`;

            const width = customProps.width || config.width;
            const height = customProps.height || config.height;

            const rect = new fabric.Rect({
                left: 0,
                top: 0,
                width,
                height,
                fill: config.color,
                stroke: config.strokeColor,
                strokeWidth: 2,
                rx: 5,
                ry: 5
            });

            const text = new fabric.Text(label, {
                left: width / 2,
                top: height / 2 - 8,
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

                const infoText = new fabric.Text(`${boothType} - $${price}`, {
                    left: width / 2,
                    top: height / 2 + 12,
                    fontSize: 11,
                    fontFamily: 'Arial',
                    fill: config.textColor,
                    textAlign: 'center',
                    originX: 'center',
                    originY: 'center'
                });

                groupItems.push(infoText);
            }

            const elementGroup = new fabric.Group(groupItems, {
                left,
                top,
                cornerColor: config.strokeColor,
                cornerSize: 8,
                transparentCorners: false,
                lockRotation: false,
                hasRotatingPoint: true
            });

            elementGroup.set({
                elementType: type,
                elementLabel: label,
                originalWidth: width,
                originalHeight: height,
                boothType: customProps.boothType || 'Standard',
                boothPrice: customProps.price || 0
            });

            ensureObjectId(elementGroup);

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
            handleCanvasChange();
        }

        function updatePropertiesPanel(obj) {
            const content = document.getElementById('propertiesContent');
            if (!content) {
                return;
            }

            if (!obj || obj.elementType !== 'booth') {
                content.innerHTML = '<div class="text-gray-500 italic text-center py-10">Select a booth to edit its properties</div>';
                highlightSelectedRow(null);
                return;
            }

            const width = Math.round(obj.originalWidth || obj.width);
            const height = Math.round(obj.originalHeight || obj.height);
            const type = obj.boothType || 'Standard';
            const price = obj.boothPrice || 0;
            const label = obj.elementLabel || 'Booth';

            content.innerHTML = `
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Booth Name</label>
                    <input type="text" id="propLabel" value="${escapeHtml(label)}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Booth Type</label>
                    <select id="propType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        ${boothTypes.map(t => `<option value="${t}" ${t === type ? 'selected' : ''}>${t}</option>`).join('')}
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Price ($)</label>
                    <input type="number" id="propPrice" value="${price}" min="0" step="100" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Size</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" id="propWidth" value="${width}" min="50" placeholder="Width" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="number" id="propHeight" value="${height}" min="50" placeholder="Height" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <button class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-md font-semibold transition-colors" onclick="applyProperties()">Apply Changes</button>
            `;

            highlightSelectedRow(ensureObjectId(obj));
        }

        function applyProperties() {
            const obj = canvas.getActiveObject();
            if (!obj || obj.elementType !== 'booth') {
                return;
            }

            const newLabel = document.getElementById('propLabel')?.value.trim() || 'Booth';
            const newType = document.getElementById('propType')?.value || 'Standard';
            const newPrice = parseFloat(document.getElementById('propPrice')?.value) || 0;
            const newWidth = parseInt(document.getElementById('propWidth')?.value, 10) || 120;
            const newHeight = parseInt(document.getElementById('propHeight')?.value, 10) || 80;

            const currentLeft = obj.left;
            const currentTop = obj.top;
            const currentAngle = obj.angle;
            const currentId = ensureObjectId(obj);

            const newBooth = createElement('booth', currentLeft, currentTop, newLabel, {
                width: newWidth,
                height: newHeight,
                boothType: newType,
                price: newPrice
            });

            newBooth.set({
                angle: currentAngle,
                __internalId: currentId
            });

            canvas.remove(obj);
            canvas.add(newBooth);
            canvas.setActiveObject(newBooth);
            canvas.renderAll();

            updatePropertiesPanel(newBooth);
            handleCanvasChange();
        }
        function exportJSON() {
            const canvasData = canvas.toJSON(trackedProperties);
            const jsonString = JSON.stringify(canvasData, null, 2);
            document.getElementById('jsonOutput').textContent = jsonString;
            return jsonString;
        }

        async function saveLayout() {
            const eventIdField = document.getElementById('eventIdInput');
            const replaceExistingField = document.getElementById('replaceExistingInput');
            const statusElement = document.getElementById('saveStatus');

            if (!eventIdField || !statusElement) {
                return;
            }

            const eventId = eventIdField.value.trim();
            if (!eventId) {
                statusElement.textContent = 'Enter an event ID before saving.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-red-700';
                eventIdField.focus();
                return;
            }

            const boothCount = canvas.getObjects().filter(obj => obj.elementType === 'booth').length;
            if (boothCount === 0) {
                statusElement.textContent = 'Add at least one booth before saving.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-red-700';
                return;
            }

            statusElement.textContent = 'Saving layout...';
            statusElement.className = 'mt-3 text-sm min-h-[18px] text-gray-700';

            const canvasData = canvas.toJSON(trackedProperties);
            const payload = {
                event_id: parseInt(eventId, 10),
                layout_json: JSON.stringify(canvasData),
                replace_existing: replaceExistingField ? replaceExistingField.checked : true
            };

            try {
                const response = await fetch(saveEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    let message = 'Failed to save layout.';
                    try {
                        const errorData = await response.json();
                        if (errorData.errors) {
                            message = Object.values(errorData.errors).flat().join(' ');
                        } else if (errorData.message) {
                            message = errorData.message;
                        }
                    } catch (error) {
                        console.error('Error parsing save response:', error);
                    }

                    statusElement.textContent = message;
                    statusElement.className = 'mt-3 text-sm min-h-[18px] text-red-700';
                    return;
                }

                const data = await response.json();
                statusElement.textContent = data.message || 'Layout saved successfully.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-green-700';
                setStatus('Layout saved successfully.', 'success');
                updateEventSummary({ booth_count: boothCount }, eventId);
            } catch (error) {
                console.error('Save layout error:', error);
                statusElement.textContent = 'Network error while saving layout.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-red-700';
            }
        }

        function loadJSON() {
            const textarea = document.getElementById('jsonInput');
            if (!textarea) {
                return;
            }

            const jsonInput = textarea.value.trim();
            if (!jsonInput) {
                alert('Please paste some JSON data first!');
                return;
            }

            try {
                const jsonData = JSON.parse(jsonInput);
                isLoadingLayout = true;
                canvas.loadFromJSON(jsonData, () => {
                    canvas.renderAll();
                    isLoadingLayout = false;
                    updateCountersFromCanvas();
                    handleCanvasChange();
                    alert('Layout loaded successfully!');
                });
            } catch (error) {
                alert('Invalid JSON data!');
                console.error('JSON parsing error:', error);
            }
        }
        function updateCountersFromCanvas() {
            resetCounters();
            canvas.getObjects().forEach(obj => {
                if (!obj.elementType || !(obj.elementType in elementCounters)) {
                    return;
                }

                ensureObjectId(obj);

                const label = obj.elementLabel || '';
                const numberMatch = label.match(/(\d+)\s*$/);
                if (numberMatch) {
                    const candidate = parseInt(numberMatch[1], 10) + 1;
                    elementCounters[obj.elementType] = Math.max(elementCounters[obj.elementType], candidate);
                } else {
                    elementCounters[obj.elementType] = Math.max(elementCounters[obj.elementType], 2);
                }
            });
        }

        function refreshBoothTable() {
            const body = document.getElementById('boothTableBody');
            const badge = document.getElementById('boothCountBadge');
            if (!body || !badge) {
                return;
            }

            const booths = canvas.getObjects().filter(obj => obj.elementType === 'booth');
            if (booths.length === 0) {
                body.innerHTML = '<tr><td colspan="4" class="px-3 py-4 text-center text-gray-500">No booths on the canvas.</td></tr>';
                badge.textContent = '0 booths';
                return;
            }

            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                maximumFractionDigits: 0
            });

            const rows = booths.map(obj => {
                ensureObjectId(obj);
                const width = Math.round((obj.originalWidth || obj.width) * (obj.scaleX || 1));
                const height = Math.round((obj.originalHeight || obj.height) * (obj.scaleY || 1));
                const price = formatter.format(obj.boothPrice || 0);
                const type = obj.boothType || 'Standard';
                const label = obj.elementLabel || 'Booth';

                return `
                    <tr data-element-id="${obj.__internalId}" class="cursor-pointer hover:bg-blue-50">
                        <td class="px-3 py-2 font-medium text-gray-800">${escapeHtml(label)}</td>
                        <td class="px-3 py-2 text-gray-700">${escapeHtml(type)}</td>
                        <td class="px-3 py-2 text-right text-gray-700">${escapeHtml(price)}</td>
                        <td class="px-3 py-2 text-gray-700">${width} x ${height}</td>
                    </tr>
                `;
            }).join('');

            body.innerHTML = rows;
            badge.textContent = booths.length === 1 ? '1 booth' : `${booths.length} booths`;
            highlightSelectedRow(canvas.getActiveObject() ? canvas.getActiveObject().__internalId : null);
        }

        function highlightSelectedRow(id) {
            const rows = document.querySelectorAll('#boothTableBody tr[data-element-id]');
            rows.forEach(row => {
                if (row.getAttribute('data-element-id') === id) {
                    row.classList.add('bg-blue-100');
                } else {
                    row.classList.remove('bg-blue-100');
                }
            });
        }

        function handleCanvasChange() {
            if (isLoadingLayout) {
                return;
            }
            updateCountersFromCanvas();
            refreshBoothTable();
            exportJSON();
        }
        canvas.on('selection:created', event => {
            const selected = event.selected[0];
            updatePropertiesPanel(selected);
        });

        canvas.on('selection:updated', event => {
            const selected = event.selected[0];
            updatePropertiesPanel(selected);
        });

        canvas.on('selection:cleared', () => {
            updatePropertiesPanel(null);
        });

        canvas.on('object:modified', handleCanvasChange);
        canvas.on('object:added', handleCanvasChange);
        canvas.on('object:removed', handleCanvasChange);

        canvas.on('mouse:dblclick', options => {
            if (options.target && options.target.type === 'group') {
                const group = options.target;
                const textObject = group.getObjects('text')[0];

                if (textObject) {
                    const newText = prompt('Enter new label:', textObject.text);
                    if (newText !== null && newText.trim() !== '') {
                        textObject.set('text', newText.trim());
                        group.set('elementLabel', newText.trim());
                        updatePropertiesPanel(group);
                        handleCanvasChange();
                    }
                }
            }
        });

        document.getElementById('boothTableBody')?.addEventListener('click', event => {
            const row = event.target.closest('tr[data-element-id]');
            if (!row) {
                return;
            }

            const id = row.getAttribute('data-element-id');
            const target = canvas.getObjects().find(obj => ensureObjectId(obj) === id);
            if (target) {
                canvas.setActiveObject(target);
                canvas.renderAll();
                updatePropertiesPanel(target);
            }
        });

        async function loadExistingLayout() {
            const eventIdField = document.getElementById('eventIdInput');
            if (!eventIdField) {
                return;
            }

            const rawEventId = eventIdField.value.trim();
            if (!rawEventId) {
                setStatus('Please enter an event ID before loading.', 'error');
                eventIdField.focus();
                return;
            }

            setStatus('Loading layout...', 'info');

            const endpoint = loadEndpointTemplate.replace('__EVENT__', encodeURIComponent(rawEventId));

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json().catch(() => ({ message: 'Unable to load layout.' }));
                    clearCanvas(false);
                    setStatus(error.message || 'Unable to load layout.', 'error');
                    updateEventSummary(null, rawEventId);
                    return;
                }

                const data = await response.json();
                isLoadingLayout = true;
                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise(resolve => {
                    canvas.loadFromJSON(data.layout, () => {
                        canvas.renderAll();
                        resolve();
                    }, (revived, serialized) => {
                        if (revived && revived.type === 'group') {
                            ensureObjectId(revived);
                        }
                    });
                });

                canvas.getObjects().forEach(obj => {
                    ensureObjectId(obj);
                });

                isLoadingLayout = false;
                updateCountersFromCanvas();
                handleCanvasChange();
                updatePropertiesPanel(null);
                updateEventSummary(data, rawEventId);
                setStatus('Layout loaded. You can make changes and save them.', 'success');
            } catch (error) {
                console.error('Load layout error:', error);
                clearCanvas(false);
                setStatus('Unexpected error while loading layout.', 'error');
            } finally {
                isLoadingLayout = false;
            }
        }

        function clearCanvas(showConfirm = true) {
            if (showConfirm && !confirm('Are you sure you want to clear the canvas?')) {
                return;
            }

            canvas.clear();
            canvas.backgroundColor = '#ffffff';
            resetCounters();
            refreshBoothTable();
            updatePropertiesPanel(null);
            exportJSON();
        }

        window.addEventListener('load', () => {
            setStatus('Enter an event ID and click Load Layout to start editing.', 'info');
            updatePropertiesPanel(null);
            refreshBoothTable();
        });
    </script>
</body>

</html>
