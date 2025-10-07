<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booth Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    @include('components.navbar')


    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', [
        'text' => 'Back to Event Details',
        'url' => $eventId ? route('my-events.edit', ['event' => $eventId]) : route('my-events.index')
        ])
        <!-- Load Layout Section -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-5 items-end">
                <div>
                    <label for="eventIdInput" class="block text-sm font-semibold text-slate-700 mb-2">Event ID</label>
                    <input type="number" id="eventIdInput" min="1" placeholder="Enter event ID" value="{{ $eventId ?? '' }}" readonly
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg text-sm bg-slate-100 text-slate-700 cursor-not-allowed">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="editLayout()" id="editButton" style="display: none;"
                        class="px-6 py-3 bg-[#ff7700] text-white rounded-lg font-semibold transition-all duration-200 shadow-md">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Layout
                    </button>
                    <button type="button" onclick="clearCanvas()"
                        class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors">
                        Clear Canvas
                    </button>
                </div>
            </div>
            <div id="statusMessage" class="mt-4 text-sm text-slate-600 min-h-[20px]"></div>
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
                <!-- Event Summary Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Event Summary
                    </h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <dt class="font-semibold text-slate-700">Event ID:</dt>
                            <dd id="eventSummaryId" class="text-slate-600 font-medium">—</dd>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <dt class="font-semibold text-slate-700">Event Name:</dt>
                            <dd id="eventSummaryName" class="text-slate-600 font-medium">—</dd>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <dt class="font-semibold text-slate-700">Stored Booths:</dt>
                            <dd id="eventSummaryBooths" class="text-slate-600 font-medium">—</dd>
                        </div>
                    </dl>
                </div>

                <!-- Booth Details Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Booth Details
                    </h2>
                    <div class="max-h-72 overflow-y-auto border border-slate-200 rounded-lg">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-wide text-xs">
                                <tr>
                                    <th class="px-3 py-3 text-left font-semibold">Number</th>
                                    <th class="px-3 py-3 text-left font-semibold">Type</th>
                                    <th class="px-3 py-3 text-right font-semibold">Price</th>
                                    <th class="px-3 py-3 text-left font-semibold">Size</th>
                                    <th class="px-3 py-3 text-left font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody id="boothTableBody" class="divide-y divide-slate-100">
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-slate-500">No data loaded yet.</td>
                                </tr>
                            </tbody>
                        </table>
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


        const loadEndpointTemplate = "{{ route('testing-layout.data', ['event' => '__EVENT__']) }}";

        function setStatus(message, tone = 'info') {
            const status = document.getElementById('statusMessage');
            status.textContent = message;

            const tones = {
                info: 'text-slate-600',
                success: 'text-emerald-600',
                error: 'text-red-600'
            };

            status.className = `mt-4 text-sm min-h-[20px] ${tones[tone] ?? tones.info}`;
        }

        function resetSummary() {
            document.getElementById('eventSummaryId').textContent = '—';
            document.getElementById('eventSummaryName').textContent = '—';
            document.getElementById('eventSummaryBooths').textContent = '—';
            const body = document.getElementById('boothTableBody');
            body.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">No data loaded yet.</td></tr>';
            document.getElementById('editButton').style.display = 'none';
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

        async function loadLayout(providedEventId) {
            const eventIdInput = document.getElementById('eventIdInput');
            const rawValue = providedEventId ?? eventIdInput.value;
            const rawEventId = (rawValue ?? '').toString().trim();

            if (!rawEventId) {
                setStatus('Please enter an event ID before loading.', 'error');
                eventIdInput.focus();
                return;
            }

            eventIdInput.value = rawEventId;

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

                if (!data.layout) {
                    clearCanvas();
                    setStatus('No layout data found for this event.', 'error');
                    return;
                }

                canvas.clear();
                canvas.backgroundColor = '#ffffff';

                await new Promise((resolve) => {
                    canvas.loadFromJSON(data.layout, () => {
                        canvas.renderAll();
                        resolve();
                    });
                });

                document.getElementById('eventSummaryId').textContent = rawEventId;
                document.getElementById('eventSummaryName').textContent = data.event?.title ?? data.event?.name ?? 'N/A';
                document.getElementById('eventSummaryBooths').textContent = data.booth_count ?? data.booths?.length ?? 0;

                populateBoothTable(data.booths ?? []);
                document.getElementById('editButton').style.display = 'inline-flex';

            } catch (error) {
                console.error('Load layout error:', error);
            }
        }

        function editLayout() {
            const eventIdInput = document.getElementById('eventIdInput');
            const eventId = eventIdInput.value.trim();
            if (eventId) {
                const editUrl = "{{ route('testing-layout.edit') }}" + "?event_id=" + encodeURIComponent(eventId);
                window.location.href = editUrl;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadLayout();
        });
    </script>
</body>

</html>