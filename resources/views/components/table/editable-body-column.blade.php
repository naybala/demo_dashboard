@props(['id','field', 'style' => null])
<td scope="row" class="{{ $style }} dark:text-white">
    <input 
        type="number"
        name="sort_id_{{ $id }}"
        class="border rounded-l border-blue-500 text-center change-sort" 
        value="{{ $field }}"
        data-id="{{ $id }}" />
</td>
