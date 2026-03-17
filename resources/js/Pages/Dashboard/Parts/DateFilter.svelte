<script>
  import { __ } from "@/helpers.js";
  import { createEventDispatcher } from "svelte";

  const dispatch = createEventDispatcher();

  export let startDate = "";
  export let endDate = "";

  const handleFilter = () => {
    dispatch("filter", {
      start_date: startDate,
      end_date: endDate,
    });
  };

  const handleReset = () => {
    startDate = "";
    endDate = "";
    dispatch("reset");
  };
  const todaySelector = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");
    startDate = `${year}-${month}-${day}`;
    endDate = `${year}-${month}-${day}`;
  };
</script>

<div
  class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
>
  <form
    on:submit|preventDefault={handleFilter}
    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
  >
    <div>
      <label
        for="start_date"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
        >{__("messages.start_date", "Start Date")}</label
      >
      <input
        type="date"
        id="start_date"
        bind:value={startDate}
        class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
      />
    </div>
    <div>
      <label
        for="end_date"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
        >{__("messages.end_date", "End Date")}</label
      >
      <input
        type="date"
        id="end_date"
        bind:value={endDate}
        class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
      />
    </div>

    <div class="flex gap-2">
      <button
        type="button"
        on:click={todaySelector}
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
        >{__("messages.today", "Today")}</button
      >
      <button
        type="submit"
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
        >{__("messages.filter", "Filter")}</button
      >
      <button
        type="button"
        on:click={handleReset}
        class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity"
        >{__("messages.reset", "Reset")}</button
      >
    </div>
  </form>
</div>
