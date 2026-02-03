@props(['title', 'name', 'id', 'selectedValue' => null, 'required' => false, 'dataArray' => []])
@php
    $selectedValue ??= old($name);
@endphp
<x-form.control>
    <x-form.label :title="$title" :required="$required" />

    <select name="{{$name}}" id="{{$id}}"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="">Choose</option>
        {{ $slot }}
        @if (count($dataArray) > 0)
            @foreach ($dataArray as $key=>$value)
                <option value="{{$key}}" {{$selectedValue == $key ? 'selected' : ''}}>{{$value}}</option>
            @endforeach
        @endif
    </select>
    <p id="{{ $name }}-error" class="text-red-500 text-xs italic ajax-error-shower"></p>
</x-form.control>
