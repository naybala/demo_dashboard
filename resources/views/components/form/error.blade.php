@props(['field'])
@error($field)
    <p class="text-xs ps-2 italic text-red-700">
        {{ $message }}
    </p>
@enderror
