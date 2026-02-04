@props([
    'title' => '',
    'type' => 'line',
    'series' => [],
    'labels' => [],
    'height' => 300,
    'options' => '{}'
])

<div class="bg-white rounded-xl shadow-sm dark:bg-gray-800 p-5 border border-gray-200 dark:border-gray-700 h-full flex flex-col"
     x-data="chartComponent({
        type: '{{ $type }}',
        height: {{ $height }},
        series: {{ is_array($series) ? json_encode($series) : $series }},
        labels: {{ is_array($labels) ? json_encode($labels) : $labels }},
        options: {{ is_array($options) ? json_encode($options) : $options }}
     })">
    @if($title)
    <div class="mb-4">
        <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 tracking-tight">{{ __($title) }}</h3>
    </div>
    @endif
    <div class="flex-grow flex items-center justify-center min-h-0">
        <div x-ref="chart" class="w-full"></div>
    </div>
</div>
