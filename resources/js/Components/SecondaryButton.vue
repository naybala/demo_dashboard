<script setup>
defineProps({
  type: {
    type: String,
    default: "button",
  },
  variant: {
    type: String,
    default: "default", // "default" | "danger"
  },
});
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center justify-center px-4 h-[2.55rem] min-w-[100px] bg-white rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    :class="
      variant === 'danger'
        ? 'border border-red-400 text-red-600 hover:bg-red-50 focus:ring-red-400 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20'
        : 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800'
    "
  >
    <slot />
  </button>
</template>
