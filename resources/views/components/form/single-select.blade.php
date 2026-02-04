@props([
    'title',
    'name',
    'id' => null,
    'viewData' => [],
    'selectedValue' => null,
    'required' => false,
    'hasSearch' => false,
    'placeholder' => 'Select an option'
])

@php
    $id = $id ?? $name;
    $selectedValue = old($name, $selectedValue);
    $selectedLabel = $viewData[$selectedValue] ?? null;
@endphp

<x-form.control>
    <x-form.label :title="$title" :required="$required" />

    <div x-data="{
        isOpen: false,
        search: '',
        selectedValue: @js($selectedValue),
        selectedLabel: @js($selectedLabel),
        options: @js($viewData),
        
        get filteredOptions() {
            if (!@js($hasSearch) || this.search === '') return this.options;
            const searchLower = this.search.toLowerCase();
            const filtered = {};
            Object.keys(this.options).forEach(key => {
                if (this.options[key].toLowerCase().includes(searchLower)) {
                    filtered[key] = this.options[key];
                }
            });
            return filtered;
        },

        selectOption(key, value) {
            this.selectedValue = key;
            this.selectedLabel = value;
            this.isOpen = false;
            this.search = '';
        },

        toggle() {
            this.isOpen = !this.isOpen;
            if (this.isOpen && @js($hasSearch)) {
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
            }
        }
    }" class="relative w-full">
        
        <!-- Hidden Input for Form Submission -->
        <input type="hidden" name="{{ $name }}" :value="selectedValue">

        <!-- Selected Value Display -->
        <button type="button" 
            @click="toggle()"
            class="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-[var(--form-input-bg)] border border-[var(--form-input-border)] rounded-lg text-start text-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)] text-[var(--form-input-text)] tracking-wider"
            aria-haspopup="listbox" 
            :aria-expanded="isOpen">
            
            <span x-text="selectedLabel || @js($placeholder)" :class="!selectedLabel && 'text-[var(--form-input-placeholder)]'"></span>

            <span class="absolute top-1/2 end-3 -translate-y-1/2">
                <svg class="shrink-0 size-3.5 text-gray-600 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/>
                </svg>
            </span>
        </button>

        <!-- Dropdown -->
        <div x-show="isOpen" 
            @click.away="isOpen = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="absolute z-50 mt-2 max-h-72 w-full bg-[var(--dropdown-bg)] border border-[var(--dropdown-border)] shadow-[var(--dropdown-shadow)] rounded-lg overflow-hidden overflow-y-auto">
            
            <!-- Optional Search Input -->
            @if($hasSearch)
            <div class="p-2 border-b border-[var(--dropdown-border)]">
                <input type="text" 
                    x-model="search"
                    x-ref="searchInput"
                    placeholder="Search..."
                    class="py-1 px-4 w-full text-sm text-[var(--form-input-text)] cursor-pointer hover:bg-[var(--option-hover-bg)] rounded-lg bg-[var(--form-input-bg)] border border-[var(--form-input-border)] tracking-wider focus:outline-none focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)]"
                    @keydown.escape="isOpen = false"
                    @click.stop
                >
            </div>
            @endif

            <!-- Options List -->
            <ul class="py-1">
                <template x-for="(label, key) in filteredOptions" :key="key">
                    <li @click="selectOption(key, label)"
                        class="py-2 px-4 w-full text-sm text-[var(--option-text)] cursor-pointer hover:bg-[var(--option-hover-bg)] bg-[var(--option-bg)] border-b last:border-0 border-[var(--dropdown-border)] tracking-wider"
                        :class="selectedValue == key ? 'bg-[var(--option-selected-bg)] !text-[var(--option-selected-text)] font-medium' : ''">
                        <span x-text="label"></span>
                    </li>
                </template>
                <li x-show="Object.keys(filteredOptions).length === 0" 
                    class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 italic text-center">
                    No results found
                </li>
            </ul>
        </div>
    </div>

    @error($name)
        <p class="text-xs ps-2 italic text-red-700 mt-1">
            {{ $message }}
        </p>
    @enderror

    <p id="{{ $name }}-error" class="text-red-500 text-xs italic ajax-error-shower mt-1"></p>
</x-form.control>
