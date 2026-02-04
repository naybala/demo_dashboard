@props(['name','title','required'=>false ,'value' => null, 'placeholder' => null, 'rows' => 4,'helperText' => null])

<div class="mb-4 py-2">
    <label class="pb-2 pt-2 text-sm text-gray-900 dark:text-white font-mono select-none" for="{{ $name }}">
        {{ __($title) }}
        @if ($required)
            <sup class="text-red-600">*</sup>
        @endif
    </label>

    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
        class="bg-[var(--form-input-bg)] border border-[var(--form-input-border)] text-[var(--form-input-text)] placeholder-[var(--form-input-placeholder)] text-sm tracking-wider rounded-lg block w-full px-2.5 py-2 focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)] focus:outline-none"
        placeholder="{{ $placeholder ? __('placeholder.placeholder_' . $placeholder) : __('placeholder.placeholder_' . $name) }}">{!! old($name) ? old($name) : $value !!}</textarea>

    @if ($helperText)
        <p class="mt-1 text-xs text-gray-400 italic" id="file_input_help">{{ __('form_helper.' . $helperText) }}</p>
    @endif
    <p id="{{$name}}-error" class="text-red-500 text-xs italic"></p>
    @error($name)
        <p class="text-xs ps-2 italic text-red-700">
            {{ $message }}
        </p>
    @enderror


</div>
