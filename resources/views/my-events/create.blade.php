<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Event</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @viteCss
    @viteJs
</head>

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="min-h-screen py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['text' => 'Back to My Events', 'url' => route('my-events.index')])

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
                <p class="mt-2 text-gray-600">Fill in the details below to save a draft or publish your event.</p>
            </div>

            @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                <p class="font-semibold">Please review the following before continuing:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <form method="POST" action="{{ route('my-events.store') }}" enctype="multipart/form-data" class="space-y-10 px-6 py-8">
                    @csrf

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Event Details</h2>
                            <p class="text-sm text-gray-500">Provide the core information for your event.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <x-image-upload
                                name="image"
                                label="Event image"
                                :required="true"
                                :error="$errors->first('image')" />
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700">Event title<span class="text-red-500"> *</span></label>
                                <input id="title" name="title" type="text" value="{{ old('title') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                            </div>
                            <div>
                                <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description<span class="text-red-500"> *</span></label>
                                <textarea id="description" name="description" rows="4" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Describe your event, target audience, and highlights.">{{ old('description') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="category_id" class="mb-2 block text-sm font-medium text-gray-700">Category<span class="text-red-500"> *</span></label>
                                    <select id="category_id" name="category_id" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="capacity" class="mb-2 block text-sm font-medium text-gray-700">Expected capacity<span class="text-red-500"> *</span></label>
                                    <input id="capacity" name="capacity" type="number" min="0" value="{{ old('capacity') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="e.g. 2500">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Location & Schedule</h2>
                            <p class="text-sm text-gray-500">Tell attendees where and when the event takes place.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="province_id" class="mb-2 block text-sm font-medium text-gray-700">Province<span class="text-red-500"> *</span></label>
                                    <select id="province_id" name="province_id" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                        <option value="">Select province</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" @selected(old('province_id')==$province->id)>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="city_id" class="mb-2 block text-sm font-medium text-gray-700">City / Regency<span class="text-red-500"> *</span></label>
                                    <select id="city_id" name="city_id" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" {{ $cities->isEmpty() ? 'disabled' : '' }}>
                                        <option value="">Select city</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" @selected(old('city_id')==$city->id)>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="district_id" class="mb-2 block text-sm font-medium text-gray-700">District</label>
                                    <select id="district_id" name="district_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" {{ $districts->isEmpty() ? 'disabled' : '' }}>
                                        <option value="">Select district</option>
                                        @foreach($districts as $district)
                                        <option value="{{ $district->id }}" @selected(old('district_id')==$district->id)>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="subdistrict_id" class="mb-2 block text-sm font-medium text-gray-700">Subdistrict</label>
                                    <select id="subdistrict_id" name="subdistrict_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" {{ $subdistricts->isEmpty() ? 'disabled' : '' }}>
                                        <option value="">Select subdistrict</option>
                                        @foreach($subdistricts as $subdistrict)
                                        <option value="{{ $subdistrict->id }}" @selected(old('subdistrict_id')==$subdistrict->id)>{{ $subdistrict->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="venue" class="mb-2 block text-sm font-medium text-gray-700">Venue<span class="text-red-500"> *</span></label>
                                    <input id="venue" name="venue" type="text" value="{{ old('venue') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Venue or building name">
                                </div>
                                <div>
                                    <label for="address" class="mb-2 block text-sm font-medium text-gray-700">Address<span class="text-red-500"> *</span></label>
                                    <input id="address" name="address" type="text" value="{{ old('address') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]" placeholder="Street address or location details">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">Start date<span class="text-red-500"> *</span></label>
                                    <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                                <div>
                                    <label for="start_time" class="mb-2 block text-sm font-medium text-gray-700">Start time<span class="text-red-500"> *</span></label>
                                    <input id="start_time" name="start_time" type="time" value="{{ old('start_time') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">End date<span class="text-red-500"> *</span></label>
                                    <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                                <div>
                                    <label for="end_time" class="mb-2 block text-sm font-medium text-gray-700">End time<span class="text-red-500"> *</span></label>
                                    <input id="end_time" name="end_time" type="time" value="{{ old('end_time') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="registration_deadline" class="mb-2 block text-sm font-medium text-gray-700">Registration deadline date<span class="text-red-500"> *</span></label>
                                    <input id="registration_deadline" name="registration_deadline" type="date" value="{{ old('registration_deadline') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                                <div>
                                    <label for="registration_deadline_time" class="mb-2 block text-sm font-medium text-gray-700">Registration deadline time<span class="text-red-500"> *</span></label>
                                    <input id="registration_deadline_time" name="registration_deadline_time" type="time" value="{{ old('registration_deadline_time', '23:59') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#ff7700] focus:outline-none focus:ring-2 focus:ring-[#ff7700]">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Refund Policy</h2>
                            <p class="text-sm text-gray-500">Set whether paid bookings can be refunded.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="refundable" name="refundable" value="1" class="mt-1 h-4 w-4 rounded accent-[#ff7700] focus:ring-[#ff7700] border-gray-300" @checked(old('refundable'))>
                            <div class="flex-1">
                                <label for="refundable" class="block text-sm font-medium text-gray-700">Allow refunds for paid bookings</label>
                                <p class="mt-1 text-xs text-gray-500">When enabled, tenants can request refunds for their paid bookings. You'll need to review and approve each refund request.</p>
                            </div>
                        </div>
                    </section>



                    <section class="space-y-4">
                        <label class="flex items-start gap-3 text-sm text-gray-600">
                            <input type="checkbox" name="confirm_terms" value="1" required class="mt-1 rounded accent-[#ff7700] focus:ring-[#ff7700] border-gray-300" @checked(old('confirm_terms'))>
                            <span>I confirm that the event details are correct.<span class="text-red-500"> *</span></span>
                        </label>
                    </section>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-4">
                        <div class="text-xs text-gray-500">Fields marked with * are required to create the event.</div>
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" name="action" value="draft" class="hover:cursor-pointer inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Save draft
                            </button>
                            <button type="submit" name="action" value="publish" class="hover:cursor-pointer inline-flex items-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e66600]">
                                Setup Booths
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    // Cascading location dropdowns
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const subdistrictSelect = document.getElementById('subdistrict_id');

    provinceSelect.addEventListener('change', async function() {
        const provinceId = this.value;

        // Reset dependent dropdowns
        citySelect.innerHTML = '<option value="">Select city</option>';
        districtSelect.innerHTML = '<option value="">Select district</option>';
        subdistrictSelect.innerHTML = '<option value="">Select subdistrict</option>';

        citySelect.disabled = true;
        districtSelect.disabled = true;
        subdistrictSelect.disabled = true;

        if (!provinceId) return;

        // Show loading state
        citySelect.innerHTML = '<option value="">Loading cities...</option>';

        // Fetch cities for selected province
        try {
            const response = await fetch(`/api/cities?province_id=${provinceId}`);
            const cities = await response.json();

            citySelect.innerHTML = '<option value="">Select city</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });

            citySelect.disabled = false;
        } catch (error) {
            console.error('Error fetching cities:', error);
            citySelect.innerHTML = '<option value="">Error loading cities</option>';
        }
    });

    citySelect.addEventListener('change', async function() {
        const cityId = this.value;

        // Reset dependent dropdowns
        districtSelect.innerHTML = '<option value="">Select district</option>';
        subdistrictSelect.innerHTML = '<option value="">Select subdistrict</option>';

        districtSelect.disabled = true;
        subdistrictSelect.disabled = true;

        if (!cityId) return;

        // Show loading state
        districtSelect.innerHTML = '<option value="">Loading districts...</option>';

        // Fetch districts for selected city
        try {
            const response = await fetch(`/api/districts?city_id=${cityId}`);
            const districts = await response.json();

            districtSelect.innerHTML = '<option value="">Select district</option>';
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });

            districtSelect.disabled = false;
        } catch (error) {
            console.error('Error fetching districts:', error);
            districtSelect.innerHTML = '<option value="">Error loading districts</option>';
        }
    });

    districtSelect.addEventListener('change', async function() {
        const districtId = this.value;

        // Reset dependent dropdown
        subdistrictSelect.innerHTML = '<option value="">Select subdistrict</option>';
        subdistrictSelect.disabled = true;

        if (!districtId) return;

        // Show loading state
        subdistrictSelect.innerHTML = '<option value="">Loading subdistricts...</option>';

        // Fetch subdistricts for selected district
        try {
            const response = await fetch(`/api/subdistricts?district_id=${districtId}`);
            const subdistricts = await response.json();

            subdistrictSelect.innerHTML = '<option value="">Select subdistrict</option>';
            subdistricts.forEach(subdistrict => {
                const option = document.createElement('option');
                option.value = subdistrict.id;
                option.textContent = subdistrict.name;
                subdistrictSelect.appendChild(option);
            });

            subdistrictSelect.disabled = false;
        } catch (error) {
            console.error('Error fetching subdistricts:', error);
            subdistrictSelect.innerHTML = '<option value="">Error loading subdistricts</option>';
        }
    });

    // Date and time validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const registrationDeadlineInput = document.getElementById('registration_deadline');
    const registrationDeadlineTimeInput = document.getElementById('registration_deadline_time');

    // Get tomorrow's date
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowString = tomorrow.toISOString().split('T')[0];

    // Set minimum date for registration deadline to tomorrow
    registrationDeadlineInput.min = tomorrowString;

    function validateDates() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            if (endDate < startDate) {
                endDateInput.setCustomValidity('End date cannot be before start date');
            } else {
                endDateInput.setCustomValidity('');

                // If same day, validate times
                if (startDate === endDate) {
                    validateTimes();
                } else {
                    startTimeInput.setCustomValidity('');
                    endTimeInput.setCustomValidity('');
                }
            }
        }

        validateRegistrationDeadline();
    }

    function validateRegistrationDeadline() {
        const deadline = registrationDeadlineInput.value;
        const deadlineTime = registrationDeadlineTimeInput.value;
        const startDate = startDateInput.value;
        const startTime = startTimeInput.value;

        if (deadline) {
            // Check if deadline is not today or in the past
            if (deadline < tomorrowString) {
                registrationDeadlineInput.setCustomValidity('Registration deadline must be at least tomorrow');
                return;
            }

            // Check if deadline datetime is before start datetime
            if (startDate && deadline && deadlineTime && startTime) {
                const deadlineDateTime = new Date(deadline + 'T' + deadlineTime);
                const startDateTime = new Date(startDate + 'T' + startTime);

                if (deadlineDateTime >= startDateTime) {
                    registrationDeadlineInput.setCustomValidity('Registration deadline must be before the event start time');
                    return;
                }
            }

            registrationDeadlineInput.setCustomValidity('');
        }
    }

    function validateTimes() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        // Only validate times if it's the same day
        if (startDate && endDate && startDate === endDate && startTime && endTime) {
            if (endTime <= startTime) {
                endTimeInput.setCustomValidity('End time must be after start time for same-day events');
            } else {
                endTimeInput.setCustomValidity('');
            }
        } else if (startDate !== endDate) {
            // Clear time validation if different days
            endTimeInput.setCustomValidity('');
        }
    }

    startDateInput.addEventListener('change', function() {
        // Update min attribute for end date
        endDateInput.min = this.value;
        validateDates();
    });

    endDateInput.addEventListener('change', validateDates);
    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
    registrationDeadlineInput.addEventListener('change', validateRegistrationDeadline);
    registrationDeadlineTimeInput.addEventListener('change', validateRegistrationDeadline);
</script>

@stack('scripts')

@include('components.footer')

</html>