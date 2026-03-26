<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <div class="space-y-6 shadow-lg rounded-lg p-3">
    <div>
      <h4 class="text-base font-bold text-gray-900 dark:text-white">
        {{ title }}
      </h4>
      <p class="text-xs text-gray-400 mt-0.5">
        {{ description }}
      </p>
    </div>

    <div class="grid grid-cols-2 gap-y-4">
      <slot />
    </div>
  </div>
</template>
