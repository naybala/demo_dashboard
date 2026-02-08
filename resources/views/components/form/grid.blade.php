@props(['cols' => 2,'class' => ''])

@php
    $gridCols = [
        1 => 'md:grid-cols-1',
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-4',
        5 => 'md:grid-cols-5',
        6 => 'md:grid-cols-6',
        12 => 'md:grid-cols-12',
    ][$cols] ?? 'md:grid-cols-2';
@endphp

<div {{ $attributes->merge(['class' => "grid gap-2 md:gap-4 $gridCols $class"]) }}>
    {{ $slot }}
</div>