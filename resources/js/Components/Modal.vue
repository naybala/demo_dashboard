<script setup>
import { onMounted, onUnmounted, computed } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: "2xl",
  },
});

const maxWidthClass = computed(() => {
  return {
    sm: "max-w-sm",
    md: "max-w-md",
    lg: "max-w-lg",
    xl: "max-w-xl",
    "2xl": "max-w-2xl",
  }[props.maxWidth];
});

const emit = defineEmits(["close"]);

const close = () => {
  emit("close");
};

const handleKeydown = (e) => {
  if (e.key === "Escape" && props.show) {
    close();
  }
};

onMounted(() => {
  window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener("keydown", handleKeydown);
});
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
        @click="close"
      />
    </Transition>

    <div
      v-if="show"
      class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none p-4"
    >
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div
          v-if="show"
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full pointer-events-auto relative overflow-hidden"
          :class="maxWidthClass"
          role="dialog"
          aria-modal="true"
        >
          <button
            type="button"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors z-10"
            @click="close"
          >
            <svg
              class="w-5 h-5"
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

          <slot />
        </div>
      </Transition>
    </div>
  </Teleport>
</template>
