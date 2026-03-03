<script>
  import { onMount, createEventDispatcher } from "svelte";
  import { fly, fade } from "svelte/transition";
  import InputLabel from "./InputLabel.svelte";
  import InputError from "./InputError.svelte";

  export let options = []; // [{ id: 1, name: 'Category 1' }, ...]
  export let value = []; // [1, 2, ...]
  export let label = "Categories";
  export let error = null;
  export let placeholder = "Select options...";

  const dispatch = createEventDispatcher();

  let searchTerm = "";
  let isOpen = false;
  let container;
  let inputElement;

  $: selectedOptions = options.filter((opt) => value.includes(opt.id));
  $: filteredOptions = options.filter(
    (opt) =>
      !value.includes(opt.id) &&
      opt.name.toLowerCase().includes(searchTerm.toLowerCase()),
  );

  const toggleDropdown = () => {
    isOpen = !isOpen;
    if (isOpen) {
      setTimeout(() => inputElement?.focus(), 0);
    }
  };

  const selectOption = (id) => {
    value = [...value, id];
    searchTerm = "";
    dispatch("change", value);
    // Keep open to allow multiple selections quickly
  };

  const removeOption = (id) => {
    value = value.filter((v) => v !== id);
    dispatch("change", value);
  };

  const handleClickOutside = (e) => {
    if (container && !container.contains(e.target)) {
      isOpen = false;
      searchTerm = "";
    }
  };

  onMount(() => {
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  });
</script>

<div class="space-y-1 w-full" bind:this={container}>
  {#if label}
    <InputLabel value={label} />
  {/if}

  <div class="relative">
    <div
      role="button"
      tabindex="0"
      class="min-h-[42px] w-full p-1.5 flex flex-wrap gap-2 bg-white dark:bg-gray-800 border rounded-lg shadow-sm transition-all duration-200 cursor-text {isOpen
        ? 'border-indigo-500 ring-2 ring-indigo-500/20'
        : 'border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600'}"
      on:click={toggleDropdown}
      on:keydown={(e) =>
        (e.key === "Enter" || e.key === " ") && toggleDropdown()}
    >
      {#each selectedOptions as option}
        <span
          class="flex items-center gap-1 px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded text-sm font-medium border border-indigo-200 dark:border-indigo-800/50"
          in:fly={{ y: 5, duration: 200 }}
        >
          {option.name}
          <button
            type="button"
            class="hover:text-indigo-900 dark:hover:text-indigo-100 transition-colors"
            on:click|stopPropagation={() => removeOption(option.id)}
          >
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </span>
      {/each}

      <input
        bind:this={inputElement}
        bind:value={searchTerm}
        type="text"
        class="flex-1 bg-transparent border-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-gray-100 min-w-[120px]"
        {placeholder}
        on:focus={() => (isOpen = true)}
        on:click|stopPropagation
      />

      <div class="flex items-center pr-2 ml-auto">
        <svg
          class="w-5 h-5 text-gray-400 transition-transform duration-200 {isOpen
            ? 'rotate-180'
            : ''}"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 9l-7 7-7-7"
          />
        </svg>
      </div>
    </div>

    {#if isOpen}
      <div
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden max-h-60 overflow-y-auto"
        in:fade={{ duration: 100 }}
      >
        {#if filteredOptions.length > 0}
          <div class="py-1">
            {#each filteredOptions as option}
              <button
                type="button"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center justify-between"
                on:click|stopPropagation={() => selectOption(option.id)}
              >
                {option.name}
              </button>
            {/each}
          </div>
        {:else}
          <div
            class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center italic"
          >
            {searchTerm ? "No results found" : "All options selected"}
          </div>
        {/if}
      </div>
    {/if}
  </div>

  {#if error}
    <InputError message={error} />
  {/if}
</div>

<style>
  /* Scrollbar styling for dropdown */
  div::-webkit-scrollbar {
    width: 6px;
  }
  div::-webkit-scrollbar-track {
    background: transparent;
  }
  div::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
  }
  :global(.dark) div::-webkit-scrollbar-thumb {
    background: #334155;
  }
</style>
