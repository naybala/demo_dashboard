@props([
    'title',
    'name',
    'id' => null,
    'viewData' => [],
    'selectedValue' => [],
    'required' => false,
    'hasSearch' => false,
    'placeholder' => 'Select options'
])

@php
    $id = $id ?? $name;
    // ensure selectedValue is an array
    if (is_string($selectedValue)) {
        $selectedValue = json_decode($selectedValue, true) ?? [$selectedValue];
    }
    $selectedValue = old($name, $selectedValue) ?? [];
    
    // map selected values to their labels
    $selectedOptions = [];
    foreach ($selectedValue as $value) {
        if (isset($viewData[$value])) {
            $selectedOptions[] = ['key' => $value, 'label' => $viewData[$value]];
        }
    }
@endphp

<x-form.control>
    <x-form.label :title="$title" :required="$required" />

    <div x-data="{
        isOpen: false,
        search: '',
        selectedItems: @js($selectedOptions),
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

        isSelected(key) {
            return this.selectedItems.some(item => item.key == key);
        },

        toggleOption(key, label) {
            const index = this.selectedItems.findIndex(item => item.key == key);
            if (index > -1) {
                this.selectedItems.splice(index, 1);
            } else {
                this.selectedItems.push({ key: key, label: label });
            }
            if (@js($hasSearch)) {
                this.search = '';
            }
        },

        removeItem(key) {
            this.selectedItems = this.selectedItems.filter(item => item.key != key);
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
        
        <!-- Hidden Inputs for Form Submission -->
        <template x-for="item in selectedItems" :key="item.key">
            <input type="hidden" name="{{ $name }}[]" :value="item.key">
        </template>
        <!-- Fallback hidden input to ensure the field is sent even if empty -->
        @if(!$required)
            <input type="hidden" name="{{ $name }}[]" value="" x-show="selectedItems.length === 0">
        @endif

        <!-- Selection Area -->
        <div 
            @click="toggle()"
            class="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-2 pe-9 flex flex-wrap gap-1 cursor-pointer bg-[var(--form-input-bg)] border border-[var(--form-input-border)] rounded-lg text-start text-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-theme)] focus:border-[var(--color-theme)] text-[var(--form-input-text)] tracking-wider min-h-[42px]"
            :aria-expanded="isOpen">
            
            <template x-for="item in selectedItems" :key="item.key">
                <span class="inline-flex items-center bg-[var(--tag-bg)] border border-[var(--tag-border)] text-[var(--tag-text)] text-xs font-medium px-2 py-0.5 rounded-full">
                    <span x-text="item.label"></span>
                    <button type="button" @click.stop="removeItem(item.key)" class="ms-1 inline-flex items-center p-0.5 text-gray-500 hover:bg-gray-300 hover:text-gray-900 rounded-full dark:hover:bg-gray-500 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Remove</span>
                    </button>
                </span>
            </template>

            <span x-show="selectedItems.length === 0" class="text-[var(--form-input-placeholder)] py-1.5 px-2">@js($placeholder)</span>

            <span class="absolute top-1/2 end-3 -translate-y-1/2">
                <svg class="shrink-0 size-3.5 text-gray-900 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/>
                </svg>
            </span>
        </div>

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
                    class="py-1 px-4 w-full text-sm text-[var(--form-input-text)] cursor-pointer hover:bg-[var(--option-hover-bg)] rounded-lg bg-[var(--form-input-bg)] border border-[var(--form-input-border)] tracking-wider focus:outline-none focus:ring-1 focus:ring-[var(--color-theme)]"
                    @keydown.escape="isOpen = false"
                    @click.stop
                >
            </div>
            @endif

            <!-- Options List -->
            <ul class="py-1">
                <template x-for="(label, key) in filteredOptions" :key="key">
                    <li @click.stop="toggleOption(key, label)"
                        class="flex items-center justify-between py-2 px-4 w-full text-sm text-[var(--option-text)] cursor-pointer hover:bg-[var(--option-hover-bg)] bg-[var(--option-bg)] border-b last:border-0 border-[var(--dropdown-border)] tracking-wider"
                        :class="isSelected(key) ? 'bg-[var(--option-selected-bg)] !text-[var(--option-selected-text)] font-medium' : ''">
                        <span x-text="label"></span>
                        <span x-show="isSelected(key)">
                            <svg class="w-4 h-4 text-[var(--color-theme)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
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
