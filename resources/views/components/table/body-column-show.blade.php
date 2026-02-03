@props(['dataArray'=>[],'field','limit' => 20,'style' => null])
@foreach ($dataArray as $data)    
    <td scope="row" class="px-6 py-4 {{ $style }}">
        {{ Str::limit($field, $limit) }}
    </td>
@endforeach