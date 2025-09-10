@php
$defaultSteps = [
['title' => 'Step 1', 'subtitle' => 'First step'],
['title' => 'Step 2', 'subtitle' => 'Second step'],
['title' => 'Step 3', 'subtitle' => 'Third step'],
];
$steps = $steps ?? $defaultSteps;
$stepCount = count($steps);
@endphp

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4 w-full">
            @for($i = 0; $i < $stepCount; $i++)
                <div class="flex items-center {{ $i < $stepCount - 1 ? 'flex-1' : '' }}">
                <div class="flex items-center">
                    <div id="step-{{ $i+1 }}" class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $i+1 }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 stepper-title">{{ $steps[$i]['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $steps[$i]['subtitle'] }}</p>
                    </div>
                </div>

                @if($i < $stepCount - 1)
                    <div id="connector-{{ $i+1 }}" class="flex-1 h-1 mx-4 step-connector">
        </div>
        @endif
    </div>
    @endfor
</div>
</div>
</div>