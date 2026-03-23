<script setup>
import { formatNumber, __ } from "@/helpers.js";
defineProps({
  label: {
    type: String,
    default: "",
  },
  value: {
    type: [Number, String],
    default: 0,
  },
});
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:shadow-md"
  >
    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
      {{ label }}
    </p>
    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
      {{ formatNumber(value) || 0 }}
      {{ __("dashboard.kyats", "Kyats") }}
    </h4>
  </div>
</template>
