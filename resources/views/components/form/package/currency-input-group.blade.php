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
    'ajaxError' => null,
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

    <div class="grid grid-cols-10 items-center">
        <x-form.input 
            type="number" 
            :name="$name" 
            :id="$id" 
            :value="$value" 
            :customAttributes="['step' => '0.01']"
            :placeholder="$placeholder" 
            :disabled="$disabled" 
            class="{{ $errors->has($name) ? 'border-red-500' : '' }} rounded-r-none col-span-7"
        />
        <input type="hidden" name="duration" value="1" />
        <input type="text" name="currency" class="text-center bg-gray-200 border border-gray-300 text-gray-900 text-xs dark:bg-gray-100 dark:text-gray-900 tracking-wider py-2.5" value="USD" readonly />
        <input type="text" name="type" class="text-center bg-gray-200 border border-gray-300 text-gray-900 text-xs dark:bg-gray-100 dark:text-gray-900 tracking-wider rounded-r-lg py-2.5 col-span-2" value="Per_Month" readonly/> 
    </div>
    
    @if($helperText)
        <p class="mt-1 text-xs text-gray-400 italic" id="file_input_help">{{ __('form_helper.'.$helperText) }}</p>
    @endif
    
    @error($name)
        <p class="text-xs ps-2 italic text-red-700">
            {{ $message }}
        </p>
    @enderror
    {{ $ajaxError }}
</x-form.control>
