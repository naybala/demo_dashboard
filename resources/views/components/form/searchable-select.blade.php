@props([
    'title',
    'name',
    'id' => null,
    'viewData' => [],
    'selectedValue' => null,
    'required' => false,
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
            if (this.search === '') return this.options;
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
            if (this.isOpen) {
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
            class="w-full relative py-1.5 ps-4 pe-9 flex gap-x-2 text-nowrap cursor-pointer bg-gray-50 border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
            aria-haspopup="listbox" 
            :aria-expanded="isOpen">
            
            <span x-text="selectedLabel || @js($placeholder)" :class="!selectedLabel && 'text-gray-400'"></span>

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
            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden">
            
            <!-- Search Input -->
            <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                <input type="text" 
                    x-model="search"
                    x-ref="searchInput"
                    placeholder="Search..."
                    class="w-full px-3 py-1.5 text-sm border-0 focus:ring-0 dark:bg-gray-700 dark:text-white"
                    @keydown.escape="isOpen = false"
                >
            </div>

            <!-- Options List -->
            <ul class="max-h-60 overflow-y-auto py-1">
                <template x-for="(label, key) in filteredOptions" :key="key">
                    <li @click="selectOption(key, label)"
                        class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300"
                        :class="selectedValue == key ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : ''">
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