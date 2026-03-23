<script setup>
import { __ } from "@/helpers.js";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
  meta: {
    type: Object,
    default: () => ({}),
  },
});

const prevLink = computed(() =>
  props.meta.links?.find((link) => link.label.includes("Previous")),
);
const nextLink = computed(() =>
  props.meta.links?.find((link) => link.label.includes("Next")),
);

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

const visit = (url) => {
  if (url) {
    router.visit(url, {
      preserveScroll: true,
      preserveState: true,
    });
  }
};
</script>

<template>
  <div
    v-if="meta && meta.links && meta.links.length > 0"
    class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
  >
    <!-- Mobile View -->
    <div class="flex flex-1 flex-col items-center gap-3 sm:hidden">
      <div class="flex w-full justify-between">
        <button
          @click="visit(prevLink?.url)"
          :disabled="!prevLink?.url"
          class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          {{ __("messages.previous", "Previous") }}
        </button>
        <div class="flex items-center text-sm text-gray-700 dark:text-gray-400">
          {{ __("messages.page", "Page") }}
          {{ meta.current_page }}
          {{ __("messages.of", "of") }}
          {{ meta.last_page }}
        </div>
        <button
          @click="visit(nextLink?.url)"
          :disabled="!nextLink?.url"
          class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          {{ __("messages.next", "Next") }}
        </button>
      </div>
      <div class="text-xs text-gray-500 dark:text-gray-400">
        {{ __("messages.showing", "Showing") }}
        <span class="font-medium">{{ meta.from }}</span>
        {{ __("messages.to", "to") }}
        <span class="font-medium">{{ meta.to }}</span>
        {{ __("messages.of", "of") }}
        <span class="font-medium">{{ meta.total }}</span>
      </div>
    </div>

    <!-- Tablet/Desktop View -->
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-400">
          {{ __("messages.showing", "Showing") }}
          <span class="font-medium">{{ meta.from }}</span>
          {{ __("messages.to", "to") }}
          <span class="font-medium">{{ meta.to }}</span>
          {{ __("messages.of", "of") }}
          <span class="font-medium">{{ meta.total }}</span>
          {{ __("messages.results", "results") }}
        </p>
      </div>
      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-sm"
          aria-label="Pagination"
        >
          <button
            v-for="(link, index) in meta.links"
            :key="index"
            @click="visit(link.url)"
            :disabled="!link.url"
            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
            :class="[
              link.active
                ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'
                : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 dark:bg-gray-800',
              link.url ? '' : 'cursor-default opacity-50',
              index === 0 ? 'rounded-l-md' : '',
              index === meta.links.length - 1 ? 'rounded-r-md' : '',
            ]"
          >
            <span v-html="getLabel(link.label)"></span>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>
