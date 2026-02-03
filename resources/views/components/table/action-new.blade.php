@props(['id', 'field','deleteShow'=>true])
<td class="px-3 py-2">
    <div class="flex gap-2 justify-end items-center">
        <a href="{{ route($field.'.show', $id) }}" class="bg-sky-600 text-white rounded-md px-2 py-1 text-xs">
            <i class="fas fa-eye"></i>
        </a>
        @if (permissionCheck(['edit '.$field]))
        <a href="{{ route($field.'.edit', $id) }}" class="bg-blue-600 text-white rounded-md px-2 py-1 text-xs">
            <i class="fas fa-edit"></i>
        </a>
        @endif
        @if (permissionCheck(['delete '.$field]) && $deleteShow)
        <form action="{{ route($field.'.destroy', $id) }}" method="post"
            class="formActionDelete bg-red-600 text-white rounded-md px-2 py-1 text-xs">
            @csrf
            @method('DELETE')
            <input type="hidden" value="{{ $id }}" name="id">
            <button type="submit">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        @endif
        {{$slot}}
    </div>
</td>
