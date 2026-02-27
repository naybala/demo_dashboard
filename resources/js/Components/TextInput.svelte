<script>
  import { onMount } from "svelte";

  export let value = "";

  let input;

  export const focus = () => input.focus();

  onMount(() => {
    if (input.hasAttribute("autofocus")) {
      input.focus();
    }
  });
</script>

<input
  bind:this={input}
  bind:value
  type="text"
  {...$$restProps}
  class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100"
/>
