@props([
    'title',
    'name',
    'id' => '',
    'photoId' => '',
    'class' => '',
    'required' => false,
    'helperText' => null,
    'imageSrc' => null,
])
<x-form.control>
   
    <x-form.label :title="$title" :required="$required" />
    <input type="file" name="{{ $name }}" id="{{ $id }}"
        class="block w-full text-sm text-[var(--form-input-text)] border border-[var(--form-input-border)] rounded-lg cursor-pointer bg-[var(--form-input-bg)] focus:outline-none focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)] {{ $class }}"
        aria-label="file example" accept="image/*" onchange="maxFileSize(this,'{{ $photoId }}')">
    <x-form.helper-text message="{{ $helperText }}" />
    <div class="flex justify-center">
         <img src="{{ $imageSrc == "" || null ? asset('images/default_profile_pic.jpg') : $imageSrc }}" id="{{ $photoId }}"
        width="20% " class="border rounded-full" />
    </div>
    <x-form.error :field="$name" />
</x-form.control>
