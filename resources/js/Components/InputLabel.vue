<script setup>
defineProps({
  value: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
    <span v-if="value">{{ value }}</span>
    <slot v-else />
  </label>
</template>
