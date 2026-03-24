<script setup>
defineProps({
  type: {
    type: String,
    default: "button",
  },
});
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center justify-center px-4 h-9 min-w-[100px] bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 shadow-sm transition ease-in-out duration-150 active:bg-red-700"
  >
    <slot />
  </button>
</template>
