@props(['title', 'data'])
<x-show.control>
    <div class="flex flex-col space-y-2">
        <div class="font-bold text-gray-700 dark:text-gray-300">
            <x-show.label :title="$title" />
        </div>
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-x-auto">
            @if(is_string($data) && (str_starts_with($data, '{') || str_starts_with($data, '[')))
                <pre class="text-sm font-mono whitespace-pre-wrap break-words text-blue-600 dark:text-blue-400">{!! $data !!}</pre>
            @else
                <x-show.text :data="$data" />
            @endif
        </div>
    </div>
</x-show.control>
