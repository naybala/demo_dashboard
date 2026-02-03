@props(['dataId'=>null])
<tr class="bg-white hover:bg-gray-50 dark:hover:bg-gray-600 border-b dark:bg-gray-900 dark:border-gray-700 items-center sortable-item"
    data-id="{{ $dataId }}">
    {{ $slot }}
</tr>
