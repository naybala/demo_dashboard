@props(['title', 'name', 'id','dataArray','hasSearch'=>false, 'selectedValue' => null,'required'=>false,'disabled'=>false])
@php
    $selectedValue ??= old($name)
@endphp
<x-form.single-select :title="$title" :name="$name" :id="$id" :hasSearch="$hasSearch" :required="$required" :disabled="$disabled">
    @foreach ($dataArray as $key=>$value)
        <option value="{{ $key }}"
            @if ($selectedValue == $key) selected @endif>
            {{ $value }} 
        </option>
    @endforeach
    <x-slot name="ajaxError">
        <p id="{{$name}}-error" class="text-red-500 text-xs italic ajax-error-shower"></p>
    </x-slot>
</x-form.single-select>