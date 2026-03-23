<script setup>
import { __ } from "@/helpers.js";
import Logo from "../../../public/images/logo.png";

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  message: {
    type: String,
    default: () => __("messages.loading", "Loading..."),
  },
});
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-300"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      class="min-h-screen absolute inset-0 z-[100] flex items-center justify-center bg-white/60 dark:bg-gray-900/60 backdrop-blur-[30px]"
    >
      <div
        class="flex flex-col items-center gap-4 p-8 rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700"
      >
        <div class="relative w-16 h-16 flex items-center justify-center">
          <!-- Outer Ring -->
          <div
            class="absolute inset-0 border-4 border-indigo-100 dark:border-gray-700 rounded-full"
          ></div>
          <!-- Spinning Ring -->
          <div
            class="absolute inset-0 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin shadow-lg"
          ></div>
          <!-- Stationary Logo -->
          <img
            :src="Logo"
            alt="Logo"
            class="w-10 h-10 rounded-lg object-contain relative z-10"
          />
        </div>
        <div class="flex flex-col items-center gap-1">
          <p class="text-gray-800 dark:text-gray-100 font-bold text-lg">
            {{ message }}
          </p>
          <p class="text-gray-500 dark:text-gray-400 text-xs animate-pulse">
            {{ __("messages.please_wait", "Please wait a moment") }}
          </p>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 3s linear infinite;
}
</style>
