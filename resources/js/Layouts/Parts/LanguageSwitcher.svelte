<script>
  export let currentLocale = "en";
  export let onLanguageChange;
</script>

<div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
  <button
    on:click={() => onLanguageChange("en")}
    class={`px-2 py-1 text-xs font-bold rounded-md transition-colors ${currentLocale === "en" ? "bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm" : "text-gray-500 hover:text-gray-700 dark:text-gray-400"}`}
  >
    EN
  </button>
  <button
    on:click={() => onLanguageChange("mm")}
    class={`px-2 py-1 text-xs font-bold rounded-md transition-colors ${currentLocale === "mm" ? "bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm" : "text-gray-500 hover:text-gray-700 dark:text-gray-400"}`}
  >
    MM
  </button>
</div>
