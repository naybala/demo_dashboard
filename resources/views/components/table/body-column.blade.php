@props(['field', 'limit' => 30, 'style' => null, 'image' => null, 'imageStyle' => null , 'rowSpan' => 1])
<td rowspan="{{ $rowSpan }}" scope="row" class="px-4 py-3 {{ $style }} whitespace-nowrap dark:text-white">
    @if ($image)
        <img src="{{ $field }}" alt="" class="{{ $imageStyle }}">
    @else
        {{ Str::limit($field, $limit) }}
    @endif
</td>
