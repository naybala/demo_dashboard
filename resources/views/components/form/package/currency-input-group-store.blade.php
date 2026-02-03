@props([
    'title', 
    'type' => 'text', 
    'ajaxError' => null,
])
<x-form.control>
    {{-- <x-form.label :title="$title" :required="$required" /> --}}
    <label class="pb-2 pt-2 text-sm text-gray-900 dark:text-white font-mono select-none" 
        for="price">
        {{ __($title) }}
        <sup class="text-red-600">*</sup>
    </label>

    <div class="grid grid-cols-10 items-center currency-container">
        <input 
            type="number" 
            name="price"
            class="rounded-r-none col-span-4 pkg-form-fillable"
        />
        <input type="text" name="currency" class="pkg-form-readonly col-span-2" value="USD" readonly />
        <input type="number" name="duration" value="1" class="pkg-form-fillable col-span-2" />
        <input type="text" name="type" class="pkg-form-readonly col-span-2" value="Per_Month" readonly/> 
    </div>
    <table id="currency-list-container" class="w-full border-collapse mt-10"></table>
    <button type="button" class="bg-theme text-white px-2 py-1.5 rounded-md ms-1 add-price">Add to List</button>
</x-form.control>
