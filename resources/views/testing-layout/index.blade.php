<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enhanced Booth Layout Designer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>

<body class="bg-gray-100 p-5">
    @include('components.navbar')
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-lg p-5">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">🏢 Enhanced Booth Layout Designer</h1>

        <div class="bg-gray-100 rounded-lg p-5 mb-5">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Instructions:</h4>
            <ul class="list-disc pl-5 space-y-2 text-gray-600">
                <li>Click any element button to add it to the canvas</li>
                <li>Select a booth to edit its properties (size, price, type) in the right panel</li>
                <li>Double-click any element to quickly edit its text label</li>
                <li>Drag elements to position them, resize using corner handles</li>
                <li>Keyboard shortcuts: Ctrl+D to duplicate, Delete to remove</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-5">
            <div class="flex flex-col">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-5 p-4 bg-gray-50 rounded-lg">
                    <div class="col-span-full pb-2 border-b border-gray-300 text-sm font-semibold text-gray-700">Add Elements:</div>
                    <button class="px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('booth')">🏢 Add Booth</button>
                    <button class="px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('parking')">🅿️ Visitor Parking</button>
                    <button class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('entrance')">🚪 Entrance Gate</button>
                    <button class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('exit')">🚪 Exit Gate</button>
                    <button class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="addElement('toilet')">🚽 Toilet</button>

                    <div class="col-span-full pb-2 border-b border-gray-300 text-sm font-semibold text-gray-700 mt-2">Actions:</div>
                    <button class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="exportJSON()">📤 Export JSON</button>
                    <button class="px-4 py-3 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="loadJSON()">📥 Load JSON</button>
                    <button class="px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-md font-medium transition-all hover:-translate-y-0.5 hover:shadow-md" onclick="clearCanvas()">🗑️ Clear Canvas</button>
                </div>

                <div class="border-2 border-gray-300 rounded-lg my-5 bg-gray-50 inline-block">
                    <canvas id="layoutCanvas" width="800" height="600"></canvas>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">JSON Output:</h3>
                    <pre id="jsonOutput" class="bg-gray-50 border border-gray-300 rounded-md p-4 max-h-72 overflow-y-auto font-mono text-xs leading-relaxed whitespace-pre-wrap">Canvas is empty. Add some elements and click "Export JSON".</pre>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Load JSON:</h3>
                    <textarea id="jsonInput" class="w-full min-h-[150px] border border-gray-300 rounded-md p-4 font-mono text-xs resize-y" placeholder="Paste your exported JSON here and click 'Load JSON'..."></textarea>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-5 h-fit sticky top-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 pb-3 border-b-2 border-gray-300">⚙️ Properties</h3>
                <div id="propertiesContent" class="text-gray-500 italic text-center py-10">
                    Select a booth to edit its properties
                </div>
                <div class="mt-8 pt-5 border-t border-gray-300">
                    <h4 class="text-base font-semibold text-gray-800 mb-4">Save Layout</h4>
                    <div class="mb-5">
                        <label class="block mb-2 text-gray-700 font-medium text-sm" for="eventIdInput">Event ID</label>
                        <input type="number" id="eventIdInput" placeholder="Enter event ID" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="mb-5">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" id="replaceExistingInput" checked class="w-4 h-4">
                            <span>Replace existing booths for this event</span>
                        </label>
                    </div>
                    <button type="button" class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-semibold transition-colors" onclick="saveLayout()">Save Layout to Database</button>
                    <div id="saveStatus" class="mt-3 text-sm min-h-[18px]"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        // Set your save endpoint URL here

        const saveEndpoint = "{{ route('testing-layout.save') }}"; // => /testing-layout/save
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

                const infoText = new fabric.Text(
                    `${boothType} - $${price}`, {
                        left: width / 2,
                        top: height / 2 + 12,
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
                content.innerHTML = '<div class="text-gray-500 italic text-center py-10">Select a booth to edit its properties</div>';
                return;
            }

            const width = Math.round(obj.originalWidth || obj.width);
            const height = Math.round(obj.originalHeight || obj.height);
            const type = obj.boothType || 'Standard';
            const price = obj.boothPrice || 0;
            const label = obj.elementLabel || 'Booth';

            content.innerHTML = `
                <div class="mb-5">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Booth Name:</label>
                    <input type="text" id="propLabel" value="${label}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Booth Type:</label>
                    <select id="propType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        ${boothTypes.map(t => `<option value="${t}" ${t === type ? 'selected' : ''}>${t}</option>`).join('')}
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Price ($):</label>
                    <input type="number" id="propPrice" value="${price}" min="0" step="100" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-gray-700 font-medium text-sm">Size:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" id="propWidth" value="${width}" min="50" placeholder="Width" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="number" id="propHeight" value="${height}" min="50" placeholder="Height" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <button class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-md font-semibold transition-colors" onclick="applyProperties()">Apply Changes</button>
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

        function exportJSON() {
            const canvasData = canvas.toJSON(trackedProperties);
            const jsonString = JSON.stringify(canvasData, null, 2);
            document.getElementById('jsonOutput').textContent = jsonString;
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
            statusElement.className = 'mt-3 text-sm min-h-[18px]';

            const canvasData = canvas.toJSON(trackedProperties);
            const payload = {
                event_id: parseInt(eventId, 10),
                layout_json: JSON.stringify(canvasData),
                replace_existing: replaceExistingField ? replaceExistingField.checked : true,
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
            } catch (error) {
                console.error('Save layout error:', error);
                statusElement.textContent = 'Network error while saving layout.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-red-700';
            }
        }

        function loadJSON() {
            const jsonInput = document.getElementById('jsonInput').value.trim();
            if (!jsonInput) {
                alert('Please paste some JSON data first!');
                return;
            }

            try {
                const jsonData = JSON.parse(jsonInput);
                canvas.loadFromJSON(jsonData, function() {
                    canvas.renderAll();
                    alert('Layout loaded successfully!');
                });
            } catch (error) {
                alert('Invalid JSON data!');
                console.error('JSON parsing error:', error);
            }
        }

        function clearCanvas() {
            if (confirm('Are you sure you want to clear the canvas?')) {
                canvas.clear();
                canvas.backgroundColor = '#ffffff';
                Object.keys(elementCounters).forEach(type => {
                    elementCounters[type] = 1;
                });
                document.getElementById('jsonOutput').textContent = 'Canvas cleared.';
                updatePropertiesPanel(null);
                canvas.renderAll();
            }
        }

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