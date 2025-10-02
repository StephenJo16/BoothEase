<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    @php
        $location = is_array($event->location) ? $event->location : [];
        $booths = $location['booths'] ?? [];
        $status = $event->status;
        $statusStyles = [
            'published' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
            'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
        ];
        $badge = $statusStyles[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
    @endphp

    <div class="min-h-screen py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['text' => 'Back to My Events', 'url' => route('my-events.index')])

            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit event</h1>
                    <p class="mt-1 text-gray-600">Update the details and republish when you are ready.</p>
                </div>
                <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $badge['class'] }}">
                    Current status: {{ $badge['label'] }}
                </span>
            </div>

            @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                <p class="font-semibold">Please fix the following before saving:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <form method="POST" action="{{ route('my-events.update', $event) }}" class="space-y-10 px-6 py-8">
                    @csrf
                    @method('PUT')

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Event details</h2>
                            <p class="text-sm text-gray-500">Adjust the information attendees will see.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700">Event title<span class="text-red-500"> *</span></label>
                                <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                            </div>
                            <div>
                                <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Describe your event, target audience, and highlights.">{{ old('description', $event->description) }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="category_id" class="mb-2 block text-sm font-medium text-gray-700">Category<span class="text-red-500"> *</span></label>
                                    <select id="category_id" name="category_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id) == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="capacity" class="mb-2 block text-sm font-medium text-gray-700">Expected capacity</label>
                                    <input id="capacity" name="capacity" type="number" min="0" value="{{ old('capacity', $event->capacity) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="e.g. 2500">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Location & schedule</h2>
                            <p class="text-sm text-gray-500">Update where and when the event is hosted.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="venue" class="mb-2 block text-sm font-medium text-gray-700">Venue</label>
                                    <input id="venue" name="venue" type="text" value="{{ old('venue', $location['venue'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Venue or building name">
                                </div>
                                <div>
                                    <label for="city" class="mb-2 block text-sm font-medium text-gray-700">City</label>
                                    <input id="city" name="city" type="text" value="{{ old('city', $location['city'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="City or region">
                                </div>
                            </div>
                            <div>
                                <label for="address" class="mb-2 block text-sm font-medium text-gray-700">Address</label>
                                <input id="address" name="address" type="text" value="{{ old('address', $location['address'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Street address or location details">
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">Start date<span class="text-red-500"> *</span></label>
                                    <input id="start_date" name="start_date" type="date" value="{{ old('start_date', optional($event->start_time)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                                <div>
                                    <label for="start_time" class="mb-2 block text-sm font-medium text-gray-700">Start time<span class="text-red-500"> *</span></label>
                                    <input id="start_time" name="start_time" type="time" value="{{ old('start_time', optional($event->start_time)->format('H:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">End date<span class="text-red-500"> *</span></label>
                                    <input id="end_date" name="end_date" type="date" value="{{ old('end_date', optional($event->end_time)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                                <div>
                                    <label for="end_time" class="mb-2 block text-sm font-medium text-gray-700">End time<span class="text-red-500"> *</span></label>
                                    <input id="end_time" name="end_time" type="time" value="{{ old('end_time', optional($event->end_time)->format('H:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                            </div>
                            <div>
                                <label for="registration_deadline" class="mb-2 block text-sm font-medium text-gray-700">Registration deadline</label>
                                <input id="registration_deadline" name="registration_deadline" type="date" value="{{ old('registration_deadline', $location['registration_deadline'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Booth configuration</h2>
                            <p class="text-sm text-gray-500">Update the sizes, pricing, and availability for each booth tier.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            @php
                                $boothTypes = [
                                    'standard' => 'Standard',
                                    'premium' => 'Premium',
                                    'vip' => 'VIP',
                                ];
                            @endphp
                            @foreach($boothTypes as $key => $label)
                            @php
                                $defaults = $booths[$key] ?? [];
                            @endphp
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-base font-semibold text-gray-900">{{ $label }} booth</h3>
                                    <i class="fa-solid {{ $key === 'vip' ? 'fa-crown' : ($key === 'premium' ? 'fa-gem' : 'fa-store') }} text-[#ff7700]"></i>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Size</label>
                                        <input name="booth_{{ $key }}_size" type="text" value="{{ old('booth_'.$key.'_size', $defaults['size'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="e.g. 3x3 m">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Price (IDR)</label>
                                        <input name="booth_{{ $key }}_price" type="number" min="0" value="{{ old('booth_'.$key.'_price', $defaults['price'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="500000">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Quantity</label>
                                        <input name="booth_{{ $key }}_qty" type="number" min="0" value="{{ old('booth_'.$key.'_qty', $defaults['qty'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="50">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="space-y-4">
                        <label class="flex items-start gap-3 text-sm text-gray-600">
                            <input type="checkbox" name="confirm_terms" value="1" class="mt-1 rounded border-gray-300 text-[#ff7700] focus:ring-[#ff7700]" @checked(old('confirm_terms'))>
                            <span>Confirm that the event details are accurate before publishing.</span>
                        </label>
                        <p class="text-xs text-gray-500">Checking the confirmation is required when you publish or republish the event.</p>
                    </section>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-4">
                        <div class="text-xs text-gray-500">Fields marked with * are required to publish.</div>
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" name="action" value="draft" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Save draft
                            </button>
                            <button type="submit" name="action" value="publish" class="inline-flex items-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                                Publish changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
