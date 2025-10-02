<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booth Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>

<body class="bg-gray-100 p-5">
    @include('components.navbar')

    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg p-6 space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">View Saved Booth Layout</h1>
                <p class="text-sm text-gray-500 mt-1">Load a previously saved layout, then inspect the canvas and booth details for an event.</p>
            </div>
            <a href="{{ route('testing-layout') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-semibold transition-colors">Design New Layout</a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-5 items-end">
                <div>
                    <label for="eventIdInput" class="block text-sm font-semibold text-gray-700 mb-2">Event ID</label>
                    <input type="number" id="eventIdInput" min="1" placeholder="Enter event ID" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="loadLayout()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition-colors">Load Layout</button>
                    <button type="button" onclick="clearCanvas()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold transition-colors">Clear Canvas</button>
                </div>
            </div>
            <div id="statusMessage" class="mt-3 text-sm text-gray-600 min-h-[20px]"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">
            <div class="border-2 border-dashed border-gray-200 rounded-lg bg-gray-50 p-4">
                <canvas id="layoutCanvas" width="900" height="600"></canvas>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Event Summary</h2>
                    <dl class="mt-3 text-sm text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <dt class="font-medium">Event ID:</dt>
                            <dd id="eventSummaryId">—</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium">Event Name:</dt>
                            <dd id="eventSummaryName">—</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium">Stored Booths:</dt>
                            <dd id="eventSummaryBooths">—</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Booth Details</h2>
                    <div class="max-h-72 overflow-y-auto border border-gray-200 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide">
                                <tr>
                                    <th class="px-3 py-2 text-left">Number</th>
                                    <th class="px-3 py-2 text-left">Type</th>
                                    <th class="px-3 py-2 text-right">Price</th>
                                    <th class="px-3 py-2 text-left">Size</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody id="boothTableBody" class="divide-y divide-gray-100">
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-gray-500">No data loaded yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Raw Layout JSON</h2>
            <pre id="layoutJsonPreview" class="bg-gray-900 text-gray-100 text-xs rounded-lg p-4 max-h-72 overflow-y-auto">Load an event to preview its stored layout JSON.</pre>
        </div>
    </div>

    <script>
        const canvas = new fabric.Canvas('layoutCanvas', {
            backgroundColor: '#ffffff',
            selection: false
        });

        const loadEndpointTemplate = "{{ route('testing-layout.data', ['event' => '__EVENT__']) }}";

        function setStatus(message, tone = 'info') {
            const status = document.getElementById('statusMessage');
            status.textContent = message;

            const tones = {
                info: 'text-gray-600',
                success: 'text-green-600',
                error: 'text-red-600'
            };

            status.className = `mt-3 text-sm min-h-[20px] ${tones[tone] ?? tones.info}`;
        }

        function resetSummary() {
            document.getElementById('eventSummaryId').textContent = '—';
            document.getElementById('eventSummaryName').textContent = '—';
            document.getElementById('eventSummaryBooths').textContent = '—';
            const body = document.getElementById('boothTableBody');
            body.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">No data loaded yet.</td></tr>';
            document.getElementById('layoutJsonPreview').textContent = 'Load an event to preview its stored layout JSON.';
        }

        function clearCanvas() {
            canvas.clear();
            canvas.backgroundColor = '#ffffff';
            canvas.renderAll();
            resetSummary();
            setStatus('Canvas cleared.', 'info');
        }

        function populateBoothTable(booths) {
            const body = document.getElementById('boothTableBody');

            if (!Array.isArray(booths) || booths.length === 0) {
                body.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">No booths are stored for this event.</td></tr>';
                return;
            }

            body.innerHTML = booths.map(booth => {
                const price = (booth.price ?? 0).toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 0
                });

                return `
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="px-3 py-2 font-medium text-gray-800">${booth.number ?? '—'}</td>
                        <td class="px-3 py-2 text-gray-700">${booth.type ?? '—'}</td>
                        <td class="px-3 py-2 text-right text-gray-700">${price}</td>
                        <td class="px-3 py-2 text-gray-700">${booth.size ?? '—'}</td>
                        <td class="px-3 py-2 text-gray-700">${booth.status ?? '—'}</td>
                    </tr>
                `;
            }).join('');
        }

        async function loadLayout() {
            const eventIdInput = document.getElementById('eventIdInput');
            const rawEventId = eventIdInput.value.trim();

            if (!rawEventId) {
                setStatus('Please enter an event ID before loading.', 'error');
                eventIdInput.focus();
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
                    const error = await response.json().catch(() => ({
                        message: 'Unable to load layout.'
                    }));
                    clearCanvas();
                    setStatus(error.message ?? 'Unable to load layout.', 'error');
                    return;
                }

                const data = await response.json();

                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise((resolve) => {
                    canvas.loadFromJSON(data.layout, () => {
                        canvas.renderAll();
                        resolve();
                    });
                });

                document.getElementById('eventSummaryId').textContent = rawEventId;
                document.getElementById('eventSummaryName').textContent = data.event?.name ?? '—';
                document.getElementById('eventSummaryBooths').textContent = data.booth_count ?? data.booths?.length ?? 0;

                populateBoothTable(data.booths ?? []);
                document.getElementById('layoutJsonPreview').textContent = JSON.stringify(data.layout, null, 2);

                setStatus('Layout loaded successfully.', 'success');
            } catch (error) {
                console.error('Load layout error:', error);
                setStatus('Unexpected error while loading layout.', 'error');
            }
        }
    </script>
</body>

</html>