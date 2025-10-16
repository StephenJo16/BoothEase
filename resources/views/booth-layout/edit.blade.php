<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Booths</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-white min-h-screen">
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', ['text' => 'Back to Edit Event', 'url' => request('event_id') ? route('my-events.edit', ['event' => request('event_id')]) : route('my-events.index')])

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
                <li>Drag elements to position them, resize using corner handles</li>
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
                                Save Layout
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
        const loadEndpointTemplate = "{{ route('booth-layout.data', ['event' => '__EVENT__']) }}";
        const initialEventId = "{{ request('event_id', '') }}";

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

                const infoText = new fabric.Text(`${boothType} - ${formatRupiah(price)}`, {
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
        }

        function updatePropertiesPanel(obj) {
            const content = document.getElementById('propertiesContent');
            if (!content) {
                return;
            }

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
                    <input type="text" id="propLabel" value="${escapeHtml(label)}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
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
            if (!obj || obj.elementType !== 'booth') {
                return;
            }

            const newLabel = document.getElementById('propLabel')?.value.trim() || 'Booth';
            const newType = document.getElementById('propType')?.value || 'Standard';
            const newPrice = parseFloat(document.getElementById('propPrice')?.value) || 0;
            const newWidth = parseInt(document.getElementById('propWidth')?.value, 10) || 120;
            const newHeight = parseInt(document.getElementById('propHeight')?.value, 10) || 80;

            // Store current position and angle
            const currentLeft = obj.left;
            const currentTop = obj.top;
            const currentAngle = obj.angle;
            const currentId = ensureObjectId(obj);

            // Create a new booth with updated properties
            const newBooth = createElement('booth', currentLeft, currentTop, newLabel, {
                width: newWidth,
                height: newHeight,
                boothType: newType,
                price: newPrice
            });

            // Restore position and angle
            newBooth.set({
                left: currentLeft,
                top: currentTop,
                angle: currentAngle,
                __internalId: currentId
            });

            // Replace the old booth with the new one
            canvas.remove(obj);
            canvas.add(newBooth);
            canvas.setActiveObject(newBooth);
            canvas.renderAll();

            // Update the properties panel to reflect the new object
            updatePropertiesPanel(newBooth);
            handleCanvasChange();
        }

        function exportJSON() {
            const canvasData = canvas.toJSON(trackedProperties);
            return JSON.stringify(canvasData, null, 2);
        }

        async function saveLayout() {
            const statusElement = document.getElementById('saveStatus');
            const saveBtn = document.getElementById('saveLayoutBtn');

            if (!initialEventId) {
                statusElement.textContent = 'No event ID available. Please create an event first.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
                return;
            }

            const boothCount = canvas.getObjects().filter(obj => obj.elementType === 'booth').length;
            if (boothCount === 0) {
                statusElement.textContent = 'At least one booth is required. Please add at least one booth to your layout before saving.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
                return;
            }

            // Show loading state
            saveBtn.disabled = true;
            saveBtn.textContent = '💾 Saving...';
            statusElement.textContent = 'Saving layout...';
            statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-blue-600';

            const canvasData = canvas.toJSON(trackedProperties);
            const payload = {
                event_id: parseInt(initialEventId, 10),
                layout_json: JSON.stringify(canvasData),
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

                if (!response.ok) {
                    let message = 'Failed to save layout.';
                    try {
                        const errorData = await response.json();
                        if (errorData.message) {
                            message = errorData.message;
                        } else if (errorData.errors && errorData.errors.layout_json) {
                            // Handle Laravel validation errors
                            message = errorData.errors.layout_json[0];
                        }
                    } catch (error) {
                        console.error('Error parsing save response:', error);
                    }

                    statusElement.textContent = message;
                    statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
                    return;
                }

                const data = await response.json();
                statusElement.textContent = data.message || `Layout saved successfully! ${boothCount} booths created and event finalized.`;
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-green-600';

            } catch (error) {
                console.error('Save layout error:', error);
                statusElement.textContent = 'Network error while saving layout.';
                statusElement.className = 'mt-3 text-sm min-h-[18px] text-center text-red-600';
            } finally {
                // Reset button state
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Save Layout';
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
                        updatePropertiesPanel(group);
                        canvas.renderAll();
                    }
                }
            }
        });

        async function loadExistingLayout() {
            if (!initialEventId) {
                return false;
            }

            const endpoint = loadEndpointTemplate.replace('__EVENT__', encodeURIComponent(initialEventId));

            try {
                console.log('Loading existing layout for event ID:', initialEventId);
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    console.log('No existing layout found, starting with sample elements');
                    return false;
                }

                const data = await response.json();
                if (!data.layout) {
                    console.log('No layout data found, starting with sample elements');
                    return false;
                }

                console.log('Loading saved layout with', data.layout.objects?.length || 0, 'objects');
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
                updatePropertiesPanel(null);

                const boothCount = canvas.getObjects().filter(obj => obj.elementType === 'booth').length;
                console.log('Layout loaded successfully with', boothCount, 'booths');
                return true;
            } catch (error) {
                console.error('Load layout error:', error);
                return false;
            }
        }





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

        window.addEventListener('load', async function() {
            // First try to load existing layout if event ID is provided
            const layoutLoaded = await loadExistingLayout();

            // If no layout was loaded, show sample elements
            if (!layoutLoaded) {
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
                            width: 120,
                            height: 80,
                            boothType: 'Premium',
                            price: 250
                        }
                    },
                    {
                        type: 'booth',
                        x: 400,
                        y: 150,
                        label: 'Booth 2',
                        props: {
                            width: 120,
                            height: 80,
                            boothType: 'Standard',
                            price: 150
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
                            const num = parseInt(match[1], 10);
                            elementCounters.booth = Math.max(elementCounters.booth, num + 1);
                        }
                    }
                });

                canvas.renderAll();
            }
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                const activeObj = canvas.getActiveObject();
                if (activeObj && activeObj.elementType) {
                    activeObj.clone(function(cloned) {
                        cloned.set({
                            left: cloned.left + 20,
                            top: cloned.top + 20
                        });
                        ensureObjectId(cloned);
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