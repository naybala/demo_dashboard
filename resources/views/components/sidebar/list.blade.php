@props(['model','title','icon'=>null,'class'=>null,'target'=>false])
@php
$routeName = $model . '.*';
$indexRoute = $model . '.index';
$managePermission = 'manage ' . $model;
@endphp
@if(permissionCheck([$managePermission]))
<li class="{{ request()->routeIs($routeName) ? 'bg-secondary text-white' : ''}} mb-2 {{ $class }}">
    <a href="{{ route($indexRoute) }}" @if ($target) target="_blank" @endif
        class="flex items-center ps-4 py-2 font-normal hover:bg-secondary hover:text-white dark:hover:bg-secondary dark:rounded-none">
        <i class="{{ $icon }} text-sm"></i>
        <span class="ml-4 text-sm menu-title">{{ __($title) }}</span>
    </a>
</li>
@endif
