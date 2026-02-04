@props(['title', 'name', 'id','dataArray','hasSearch'=>false, 'selectedValue' => null,'required'=>false,'disabled'=>false])
@php
    $selectedValue ??= old($name)
@endphp
<x-form.single-select 
    :title="$title" 
    :name="$name" 
    :id="$id" 
    :hasSearch="$hasSearch" 
    :required="$required" 
    :disabled="$disabled" 
    :viewData="$dataArray" 
    :selectedValue="$selectedValue" 
/>