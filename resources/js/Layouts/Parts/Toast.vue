<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch, onUnmounted } from "vue";
import { isOnline } from "../adminLayout.js";
import { __ } from "@/helpers.js";

const page = usePage();

const showSuccessToast = ref(false);
const showErrorToast = ref(false);
const showConnectionError = ref(false);
const showConnectionSuccess = ref(false);
const wasOffline = ref(false);

let successTimer;
let errorTimer;
let connErrorTimer;
let connSuccessTimer;

watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash?.success) {
      showSuccessToast.value = false;
      setTimeout(() => {
        showSuccessToast.value = true;
      }, 10);
      clearTimeout(successTimer);
      successTimer = setTimeout(() => {
        showSuccessToast.value = false;
      }, 3000);
    }
  },
  { deep: true, immediate: true },
);

watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash?.error) {
      showErrorToast.value = false;
      setTimeout(() => {
        showErrorToast.value = true;
      }, 10);
      clearTimeout(errorTimer);
      errorTimer = setTimeout(() => {
        showErrorToast.value = false;
      }, 4000);
    }
  },
  { deep: true, immediate: true },
);

watch(isOnline, (newVal) => {
  if (!newVal) {
    showConnectionError.value = true;
    wasOffline.value = true;
    clearTimeout(connErrorTimer);
  } else if (newVal && wasOffline.value) {
    showConnectionError.value = false;
    showConnectionSuccess.value = true;
    wasOffline.value = false;
    clearTimeout(connSuccessTimer);
    connSuccessTimer = setTimeout(() => {
      showConnectionSuccess.value = false;
    }, 3000);
  }
});

onUnmounted(() => {
  clearTimeout(successTimer);
  clearTimeout(errorTimer);
  clearTimeout(connErrorTimer);
  clearTimeout(connSuccessTimer);
});
</script>

<template>
  <Teleport to="body">
    <!-- Success Toast -->
    <div
      v-if="showSuccessToast && page.props.flash?.success"
      class="fixed top-5 right-5 z-[100] animate-slide-in"
    >
      <div
        class="flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
      >
        <div class="flex-shrink-0">
          <svg
            class="w-5 h-5 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            />
          </svg>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1">{{
          page.props.flash.success
        }}</span>
        <button
          @click="showSuccessToast = false"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>

    <!-- Error Toast -->
    <div
      v-if="showErrorToast && page.props.flash?.error"
      class="fixed top-5 right-5 z-[100] animate-slide-in"
      :style="showSuccessToast ? 'top: 5rem;' : ''"
    >
      <div
        class="flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
      >
        <div class="flex-shrink-0">
          <svg
            class="w-5 h-5 text-red-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1">{{
          page.props.flash.error
        }}</span>
        <button
          @click="showErrorToast = false"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>

    <!-- Connection Error Toast -->
    <div
      v-if="showConnectionError"
      class="fixed top-5 right-5 z-[100] animate-slide-in"
    >
      <div
        class="flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
      >
        <div class="flex-shrink-0">
          <svg
            class="w-5 h-5 text-red-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            />
          </svg>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1">
          {{
            __(
              "messages.connection_failed",
              "Network connection failed, Please use internet",
            )
          }}
        </span>
        <button
          @click="showConnectionError = false"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>

    <!-- Connection Success Toast -->
    <div
      v-if="showConnectionSuccess"
      class="fixed top-5 right-5 z-[100] animate-slide-in"
    >
      <div
        class="flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
      >
        <div class="flex-shrink-0">
          <svg
            class="w-5 h-5 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            />
          </svg>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1">
          {{ __("messages.connection_restored", "Connection restored") }}
        </span>
        <button
          @click="showConnectionSuccess = false"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
@keyframes slide-in {
  from {
    opacity: 0;
    transform: translateX(100%);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-slide-in {
  animation: slide-in 0.3s ease-out forwards;
}
</style>
