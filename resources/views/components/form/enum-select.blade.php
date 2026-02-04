@props(['title', 'name','id', 'enumClass', 'selectedValue' => null,'required'=>false,'hasSearch'=>false])
@php
    $enumNamespace = "\App\Enums\\" . $enumClass;
    $enumCases = $enumNamespace::cases();
    $viewData = [];
    foreach ($enumCases as $status) {
        $viewData[$status->value] = $status->label();
    }
@endphp

<x-form.single-select 
    :title="$title" 
    :id="$id" 
    :name="$name" 
    :required="$required" 
    :hasSearch="$hasSearch" 
    :viewData="$viewData" 
    :selectedValue="$selectedValue" 
/>
