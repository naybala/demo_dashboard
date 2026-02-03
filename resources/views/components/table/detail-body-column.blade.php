@props(['field', 'limit' => 30, 'style' => null, 'route', 'id' , 'rowSpan' => 1])
<td rowspan="{{ $rowSpan }}" scope="row" class="px-4 py-3 {{ $style }} whitespace-nowrap dark:text-white">
    <a href="{{ route($route,$id) }}" class="text-blue-600 underline">
        {{ Str::limit($field, $limit) }}
    </a>
</td>
