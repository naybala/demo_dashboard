@props(['title', 'name','id', 'dataArray','hasSearch'=>false, 'selectedValue' => [],'required'=>false])
@php
    if(!$selectedValue){
        $selectedValue = old($name) ?? [];
    }
@endphp
<x-form.multi-select 
    :title="$title" 
    :name="$name" 
    :id="$id" 
    :hasSearch="$hasSearch" 
    :required="$required" 
    :viewData="$dataArray" 
    :selectedValue="$selectedValue" 
/>