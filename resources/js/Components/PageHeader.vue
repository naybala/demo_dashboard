<script setup>
defineProps({
  title: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <header
    class="bg-white dark:bg-gray-800 shadow-sm mb-6 px-4 py-4 sm:px-6 lg:px-8 rounded-lg flex justify-between items-center"
  >
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
    >
      {{ title }}
    </h2>
    <div>
      <slot name="actions" />
    </div>
  </header>
</template>
