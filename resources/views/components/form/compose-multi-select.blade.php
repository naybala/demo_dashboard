@props(['title', 'name','id', 'dataArray','hasSearch'=>false, 'selectedValue' => [],'required'=>false])
@php
    if(!$selectedValue){
        $selectedValue = old($name) ?? [];
    }
@endphp
<x-form.multi-select :title="$title" :name="$name" :id="$id" :hasSearch="$hasSearch" :required="$required">
    @foreach ($dataArray as $key=>$value)
        <option value="{{ $key }}"
            @if (in_array($key,$selectedValue)) selected @endif>
            {{ $value }}
        </option>
    @endforeach
</x-form.multi-select>