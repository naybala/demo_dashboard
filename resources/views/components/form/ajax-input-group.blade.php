@props([
    'title', 
    'type' => 'text', 
    'name', 
    'value' => null,
    'id' => null, 
    'customAttributes' => [],
    'required' => false, 
    'placeholder'=> null,
    'helperText' => null,
    'disabled' => false
])
<x-form.control>
    {{-- <x-form.label :title="$title" :required="$required" /> --}}
    <label class="pb-2 pt-2 text-sm text-gray-900 dark:text-white font-mono select-none" 
        for="{{ $name }}">
        {{ __($title) }}
        @if($required)
            <sup class="text-red-600">*</sup>
        @endif
    </label>

    <div>
        <x-form.input 
            :type="$type" 
            :name="$name" 
            :id="$id" 
            :value="$value" 
            :customAttributes="$customAttributes"
            :placeholder="$placeholder" 
            :disabled="$disabled" 
        />
    </div>
    
    @if($helperText)
        <p class="mt-1 text-xs text-gray-400 italic" id="file_input_help">{{ __('form_helper.'.$helperText) }}</p>
    @endif
    <p id="{{$name}}-error" class="text-xs ps-2 italic text-red-700 ajax-error-shower"></p>
</x-form.control>
