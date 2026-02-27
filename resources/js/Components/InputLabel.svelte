<script>
  export let value = "";
</script>

<label
  class="block font-medium text-sm text-gray-700 dark:text-gray-300"
  for={$$restProps.for}
>
  {#if value}
    <span>{value}</span>
  {:else}
    <slot />
  {/if}
</label>
