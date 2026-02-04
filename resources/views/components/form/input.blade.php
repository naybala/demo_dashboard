@props([
    'type' => 'text',
    'name',
    'id' => null, 
    'class' => '',
    'customAttributes' => [], 
    'value' => null,
    'placeholder'=>null,
    'disabled' => false
    ])
<input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{!! old($name) ? old($name) : $value !!}" @if($disabled) disabled @endif
    class="bg-[var(--form-input-bg)] border border-[var(--form-input-border)] text-[var(--form-input-text)] text-sm tracking-wider rounded-lg block w-full px-2.5 py-2 focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)] focus:outline-none {{ $class }}"
    placeholder="{{ $placeholder ? __('placeholder.placeholder_' . $placeholder) : __('placeholder.placeholder_' . $name) }}" {!! implode(
        ' ',
        array_map(
            function ($value, $key) {
                return $key . '="' . $value . '"';
            },
            $customAttributes,
            array_keys($customAttributes),
        ),
    ) 
    />


    
