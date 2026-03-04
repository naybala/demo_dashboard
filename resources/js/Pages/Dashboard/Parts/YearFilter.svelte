<script>
  import { __ } from "@/helpers.js";
  import { createEventDispatcher } from "svelte";

  const dispatch = createEventDispatcher();

  export let selectedYear = "";
  export let availableYears = [];

  const handleChange = () => {
    dispatch("change", selectedYear);
  };
</script>

<div class="w-full md:w-4/12 mb-4">
  <label
    for="year"
    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
    >{__("messages.year", "Year")}</label
  >
  <select
    id="year"
    bind:value={selectedYear}
    on:change={handleChange}
    class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
  >
    <option value="">{__("messages.last_12_months", "Last 12 Months")}</option>
    {#each availableYears as year}
      <option value={year}>{year}</option>
    {/each}
  </select>
</div>
