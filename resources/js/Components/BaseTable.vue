<script setup>
defineProps({
  headers: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead
        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
      >
        <tr>
          <th
            v-for="(header, index) in headers"
            :key="index"
            scope="col"
            class="px-6 py-3"
          >
            {{ typeof header === "object" ? header.label : header }}
          </th>
          <th v-if="$slots.actions" scope="col" class="px-6 py-3 text-right">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <slot />
      </tbody>
    </table>
  </div>
</template>
