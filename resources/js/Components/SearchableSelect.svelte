<script>
  import { createEventDispatcher, onMount } from "svelte";
  import { slide } from "svelte/transition";

  export let value = "";
  export let options = []; // Array of { id, label, searchKey }
  export let placeholder = "Select an option...";
  export let disabled = false;
  export let required = false;
  export let asyncUrl = ""; // API endpoint for searching
  export let asyncKey = "keyword"; // Query parameter name for search

  const dispatch = createEventDispatcher();
  let isOpen = false;
  let searchTerm = "";
  let container;
  let inputElement;
  let dropdownStyle = "";
  let isLoading = false;
  let debounceTimer;

  $: selectedOption = options.find((opt) => opt.id === value);
  $: filteredOptions = asyncUrl
    ? options
    : options.filter(
        (opt) =>
          opt.label.toLowerCase().includes(searchTerm.toLowerCase()) ||
          (opt.searchKey &&
            opt.searchKey.toLowerCase().includes(searchTerm.toLowerCase())),
      );

  const calculatePosition = () => {
    if (container) {
      const rect = container.getBoundingClientRect();
      dropdownStyle = `position: fixed; top: ${rect.bottom}px; left: ${rect.left}px; width: ${rect.width}px; z-index: 9999;`;
    }
  };

  const fetchOptions = async (term = "") => {
    if (!asyncUrl) return;

    isLoading = true;
    try {
      const response = await fetch(`${asyncUrl}?${asyncKey}=${term}`);
      const data = await response.json();
      const results = data.data || data;

      const newOptions = results.map((item) => ({
        id: item.id,
        label: `${item.name} (${item.unit || "No Unit"})`,
        searchKey: item.name,
        original: item,
      }));

      // Preserve the current selected option if it's not in the new results
      if (
        selectedOption &&
        !newOptions.find((opt) => opt.id === selectedOption.id)
      ) {
        options = [selectedOption, ...newOptions];
      } else {
        options = newOptions;
      }
    } catch (error) {
      console.error("Error fetching options:", error);
    } finally {
      isLoading = false;
    }
  };

  const handleInput = () => {
    if (!asyncUrl) return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      fetchOptions(searchTerm);
    }, 300);
  };

  const toggleOpen = () => {
    if (disabled) return;
    isOpen = !isOpen;
    if (isOpen) {
      searchTerm = "";
      calculatePosition();
      if (asyncUrl) fetchOptions("");
      setTimeout(() => inputElement?.focus(), 0);
    }
  };

  const selectOption = (opt) => {
    value = opt.id;
    isOpen = false;
    dispatch("change", opt);
  };

  const handleClickOutside = (event) => {
    if (container && !container.contains(event.target)) {
      isOpen = false;
    }
  };

  const handleScrollResize = () => {
    if (isOpen) calculatePosition();
  };

  onMount(() => {
    window.addEventListener("click", handleClickOutside);
    window.addEventListener("scroll", handleScrollResize, true);
    window.addEventListener("resize", handleScrollResize);
    return () => {
      window.removeEventListener("click", handleClickOutside);
      window.removeEventListener("scroll", handleScrollResize, true);
      window.removeEventListener("resize", handleScrollResize);
    };
  });
</script>

<div class="relative w-full" bind:this={container}>
  <!-- svelte-ignore a11y-click-events-have-key-events -->
  <!-- svelte-ignore a11y-no-static-element-interactions -->
  <div
    class="relative cursor-pointer bg-white dark:bg-gray-900 border {required &&
    !value
      ? 'border-red-300 dark:border-red-700'
      : 'border-gray-300 dark:border-gray-700'} rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {disabled
      ? 'bg-gray-100 cursor-not-allowed'
      : ''}"
    on:click={toggleOpen}
  >
    <span
      class="block truncate {!selectedOption
        ? 'text-gray-500'
        : 'text-gray-900 dark:text-gray-100'}"
    >
      {selectedOption ? selectedOption.label : placeholder}
    </span>
    <span
      class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
    >
      <svg
        class="h-5 w-5 text-gray-400"
        viewBox="0 0 20 20"
        fill="none"
        stroke="currentColor"
      >
        <path
          d="M7 7l3-3 3 3m0 6l-3 3-3-3"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </span>
  </div>

  {#if isOpen}
    <!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
    <div
      transition:slide={{ duration: 100 }}
      style={dropdownStyle}
      class="bg-white dark:bg-gray-800 shadow-xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-hidden focus:outline-none sm:text-sm"
    >
      <div class="sticky top-0 bg-white dark:bg-gray-800 p-2">
        <input
          bind:this={inputElement}
          bind:value={searchTerm}
          on:input={handleInput}
          type="text"
          placeholder="Search..."
          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          on:click|stopPropagation
        />
      </div>
      <ul class="max-h-48 overflow-y-auto">
        {#if isLoading}
          <li class="py-2 pl-3 text-gray-500 italic flex items-center gap-2">
            <svg
              class="animate-spin h-4 w-4 text-indigo-500"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
            Searching...
          </li>
        {:else}
          {#each filteredOptions as option}
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <li
              class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500 {value ===
              option.id
                ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-200'
                : 'text-gray-900 dark:text-gray-100'}"
              on:click|stopPropagation={() => selectOption(option)}
            >
              <span
                class="block truncate {value === option.id
                  ? 'font-semibold'
                  : 'font-normal'}"
              >
                {option.label}
              </span>
              {#if value === option.id}
                <span
                  class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 dark:text-indigo-400"
                >
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </span>
              {/if}
            </li>
          {:else}
            <li class="py-2 pl-3 text-gray-500 italic">No results found</li>
          {/each}
        {/if}
      </ul>
    </div>
  {/if}
</div>
