<script setup>
defineProps({
  currentLocale: {
    type: String,
    default: "en",
  },
  onLanguageChange: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
    <button
      @click="onLanguageChange('en')"
      class="px-2 py-1 text-xs font-bold rounded-md transition-colors"
      :class="
        currentLocale === 'en'
          ? 'bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm'
          : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
      "
    >
      EN
    </button>
    <button
      @click="onLanguageChange('mm')"
      class="px-2 py-1 text-xs font-bold rounded-md transition-colors"
      :class="
        currentLocale === 'mm'
          ? 'bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm'
          : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
      "
    >
      MM
    </button>
  </div>
</template>
