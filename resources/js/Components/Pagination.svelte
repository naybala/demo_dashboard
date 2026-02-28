<script>
  import { __ } from "@/helpers.js";
  import { router } from "@inertiajs/svelte";

  export let meta = {};

  /**
   * Translates the pagination labels if they are "Previous" or "Next".
   * Laravel usually returns labels like "&laquo; Previous" or "Next &raquo;".
   */
  const getLabel = (label) => {
    if (label.includes("Previous")) {
      return __("messages.previous", "Previous");
    }
    if (label.includes("Next")) {
      return __("messages.next", "Next");
    }
    return label;
  };
</script>

{#if meta && meta.links && meta.links.length > 0}
  <div class="mt-6 flex items-center justify-center md:justify-between">
    <div class="text-sm text-gray-700 dark:text-gray-400 hidden md:block">
      {__("messages.showing", "Showing")}
      {meta.from}
      {__("messages.to", "to")}
      {meta.to}
      {__("messages.of", "of")}
      {meta.total}
      {__("messages.results", "results")}
    </div>
    <div class="flex gap-1">
      {#each meta.links as link}
        <button
          class="px-3 py-1 rounded border {link.active
            ? 'bg-indigo-600 text-white border-indigo-600'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'}"
          on:click={() => link.url && router.visit(link.url)}
          disabled={!link.url}
        >
          {@html getLabel(link.label)}
        </button>
      {/each}
    </div>
  </div>
{/if}
