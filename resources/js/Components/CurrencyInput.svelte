<script>
  import { formatNumber, unformatNumber } from "@/helpers.js";
  import { onMount, createEventDispatcher } from "svelte";

  const dispatch = createEventDispatcher();
  export let value = "";
  export let placeholder = "";
  export let required = false;
  export let id = "";
  export let decimals = 0;
  export let showButtons = false;
  export let step = 1;

  let input;
  let displayValue = formatNumber(value, decimals);

  $: if (unformatNumber(value) !== unformatNumber(displayValue)) {
    displayValue = formatNumber(value, decimals);
  }

  const handleInput = (e) => {
    const el = e.target;
    const start = el.selectionStart;
    const oldLength = el.value.length;

    const rawValue = el.value;
    const numericValue = unformatNumber(rawValue);

    // Update parent value
    value = numericValue;

    // Update local display value
    displayValue = formatNumber(numericValue, decimals);

    // Sync DOM immediately to calculate new cursor position
    el.value = displayValue;

    // Adjust cursor position
    const newLength = displayValue.length;
    let newStart = start + (newLength - oldLength);

    // Ensure we don't go out of bounds
    newStart = Math.max(0, newStart);

    // Set selection back
    el.setSelectionRange(newStart, newStart);

    dispatch("input", numericValue);
  };

  const increment = () => {
    const numericValue = unformatNumber(value) || 0;
    value = numericValue + step;
    displayValue = formatNumber(value, decimals);
    dispatch("input", value);
  };

  const decrement = () => {
    const numericValue = unformatNumber(value) || 0;
    value = Math.max(0, numericValue - step);
    displayValue = formatNumber(value, decimals);
    dispatch("input", value);
  };

  export const focus = () => input.focus();

  onMount(() => {
    if (input && input.hasAttribute("autofocus")) {
      input.focus();
    }
  });
</script>

<div class="relative flex items-center w-full">
  {#if showButtons}
    <button
      type="button"
      on:click={decrement}
      class="flex items-center justify-center h-full px-2 border border-r-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-l-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
      tabindex="-1"
    >
      <svg
        class="w-4 h-4"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M20 12H4"
        />
      </svg>
    </button>
  {/if}
  <input
    bind:this={input}
    type="text"
    {id}
    {placeholder}
    {required}
    inputmode="numeric"
    value={displayValue}
    on:input={handleInput}
    {...$$restProps}
    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-800 dark:text-gray-100 {!showButtons
      ? 'rounded-md'
      : 'border-x-0'} {$$props.class || ''}"
  />
  {#if showButtons}
    <button
      type="button"
      on:click={increment}
      class="flex items-center justify-center h-full px-2 border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-r-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
      tabindex="-1"
    >
      <svg
        class="w-4 h-4"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 4v16m8-8H4"
        />
      </svg>
    </button>
  {/if}
</div>
