<script>
  import { __ } from "@/helpers.js";
  import { createEventDispatcher } from "svelte";

  const dispatch = createEventDispatcher();

  export let date = "";

  const handleFilter = () => {
    dispatch("filter", {
      date: date,
    });
  };

  const handleReset = () => {
    date = "";
    dispatch("reset");
  };

  const setToday = () => {
    const today = new Date().toISOString().split("T")[0];
    date = today;
    handleFilter();
  };
</script>

<div class="w-3/5 mb-6">
  <div
    class="bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-wrap items-center gap-2"
  >
    <!-- Label/Icon Group -->
    <div
      class="hidden md:flex items-center gap-2 px-4 py-2 border-r border-gray-100 dark:border-gray-700"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5 text-indigo-500"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
      </svg>
      <span
        class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap"
      >
        {__("messages.filter_by_date", "Filter by Date")}
      </span>
    </div>

    <!-- Date Input Container -->
    <div class="relative flex-grow min-w-[200px]">
      <input
        type="date"
        id="date_filter"
        bind:value={date}
        on:change={handleFilter}
        class="w-full bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 transition-all cursor-pointer"
      />
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-1.5 ml-auto">
      <button
        type="button"
        on:click={setToday}
        class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-colors"
      >
        {__("messages.today", "Today")}
      </button>

      <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

      <button
        type="button"
        on:click={handleReset}
        class="p-2.5 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all group"
        title={__("messages.reset", "Reset")}
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-5 h-5 transition-transform group-hover:rotate-[-90deg]"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
          <path d="M3 3v5h5"></path>
        </svg>
      </button>

      <button
        type="button"
        on:click={handleFilter}
        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <span class="text-sm font-bold tracking-wide"
          >{__("messages.filter", "Filter")}</span
        >
      </button>
    </div>
  </div>
</div>
