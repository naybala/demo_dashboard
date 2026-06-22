<script>
  import { fade } from "svelte/transition";
  import InputLabel from "@/Components/InputLabel.svelte";
  import InputError from "@/Components/InputError.svelte";
  import SearchableSelect from "@/Components/SearchableSelect.svelte";

  export let warehouseOptions = [];
  export let value;
  export let errors = {};
  export let fetchAllStock;
</script>

<div class="flex flex-col items-center justify-center py-20 px-4" in:fade>
  <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-750 shadow-xl p-8 text-center space-y-6">
    <div class="w-20 h-20 mx-auto rounded-full bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5-1.5l-3-1m-3.182-3.182a1.5 1.5 0 11-2.122-2.122m-1.57 3.692a3 3 0 10-4.243-4.243m.53 5.488A11.953 11.953 0 0012 10.5c-1.056 0-2.07-.136-3-.392m12 0a11.952 11.952 0 01-3 .392" />
      </svg>
    </div>
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Select Warehouse</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
        Please select a warehouse to check inventory levels and load products for sale.
      </p>
    </div>

    <div class="text-left">
      <InputLabel for="warehouse_select" value="Warehouse" />
      <div class="mt-2">
        <SearchableSelect
          asyncUrl="/warehouses/search"
          options={warehouseOptions}
          bind:value
          placeholder="Select Warehouse"
          on:change={fetchAllStock}
        />
      </div>
      {#if errors.warehouse_id}
        <InputError message={errors.warehouse_id} />
      {/if}
    </div>
  </div>
</div>
