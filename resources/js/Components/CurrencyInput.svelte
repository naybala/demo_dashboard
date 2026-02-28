<script>
  import { formatNumber, unformatNumber } from "@/helpers.js";
  import { onMount } from "svelte";

  export let value = "";
  export let placeholder = "";
  export let required = false;
  export let id = "";
  export let decimals = 0;

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
  };

  export const focus = () => input.focus();

  onMount(() => {
    if (input && input.hasAttribute("autofocus")) {
      input.focus();
    }
  });
</script>

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
  class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100 {$$props.class ||
    ''}"
/>
