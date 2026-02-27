<script>
  export let headers = [];
</script>

<div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead
      class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
    >
      <tr>
        {#each headers as header}
          <th scope="col" class="px-6 py-3">
            {header}
          </th>
        {/each}
        {#if $$slots.actions}
          <th scope="col" class="px-6 py-3 text-right"> Actions </th>
        {/if}
      </tr>
    </thead>
    <tbody>
      <slot />
    </tbody>
  </table>
</div>
