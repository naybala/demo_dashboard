@props(['title', 'name','id', 'enumClass', 'selectedValue' => null,'required'=>false])
@php
    $enumClass = "\App\Enums\\" . $enumClass;
    $enumCases = $enumClass::cases();
    $selectedValue ??= old($name)
@endphp

<x-form.single-select title="{{ $title }}" :id="$id" name="{{ $name }}" :required="$required">
    @foreach ($enumCases as $status)
        <option value="{{ $status->value }}" 
            @if ($selectedValue == (string) $status->value) selected @endif>
            {{ str_replace('_', ' ', $status->label()) }}
        </option>
    @endforeach
    <x-slot name="ajaxError">
        <p id="{{$name}}-error" class="text-red-500 text-xs italic ajax-error-shower"></p>
    </x-slot>   
</x-form.single-select>
