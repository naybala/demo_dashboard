<script setup>
defineProps({
  message: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0 -translate-y-1"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 -translate-y-1"
  >
    <div v-if="message">
      <p class="text-sm text-red-600 dark:text-red-400 mt-2">
        {{ message }}
      </p>
    </div>
  </Transition>
</template>
