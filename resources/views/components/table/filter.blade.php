@props([
    'viewData',
    'key',
    'route',
    'wrapperClass'=>null
])

<div x-data="{ searchQuery: '' }">
    <button id="{{ $viewData }}.-button" data-dropdown-toggle="{{ $key }}" 
        class="font-medium rounded-lg text-sm px-3 py-1.5 text-md text-center inline-flex items-center bg-theme text-white dark:bg-white dark:text-theme" type="button">
        {{ request($key) ? $viewData[request($key)] : "Filter By " . strtoupper($key) }}
        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>  
    </button>
    
    <div id="{{ $key }}" class="z-[1000] hidden bg-gray-100 divide-y divide-gray-100 rounded-lg shadow-2xl dark:bg-gray-700 w-44 {{$wrapperClass}}">
        <div class="p-2">
            <input type="text" 
                x-model="searchQuery"
                placeholder="Search..."
                class="w-full px-2 py-1 text-sm border rounded-md dark:bg-gray-800 dark:text-white"
            >
        </div>
        <ul class="py-2 text-xs text-gray-700 text-start dark:text-gray-200 max-h-[200px] overflow-y-scroll">
            @foreach ($viewData as $dataKey=>$dataValue)
                <li x-show="searchQuery === '' || 
                    '{{ strtolower($dataKey) }}'.includes(searchQuery.toLowerCase()) || 
                    '{{ strtolower($dataValue) }}'.includes(searchQuery.toLowerCase())">
                    <a href="{{ route($route,array_merge(request()->query(),[$key=>$dataKey])) }}" 
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        {{ $dataValue }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>