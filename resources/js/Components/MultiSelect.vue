<script setup>
import { onMounted, onUnmounted, ref, computed, nextTick } from "vue";
import InputLabel from "./InputLabel.vue";
import InputError from "./InputError.vue";

const props = defineProps({
  options: {
    type: Array,
    default: () => [],
  },
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: "Categories",
  },
  error: {
    type: String,
    default: null,
  },
  placeholder: {
    type: String,
    default: "Select options...",
  },
});

const emit = defineEmits(["update:modelValue", "change"]);

const searchTerm = ref("");
const isOpen = ref(false);
const container = ref(null);
const inputElement = ref(null);

const selectedOptions = computed(() =>
  props.options.filter((opt) => props.modelValue.includes(opt.id)),
);

const filteredOptions = computed(() =>
  props.options.filter(
    (opt) =>
      !props.modelValue.includes(opt.id) &&
      opt.name.toLowerCase().includes(searchTerm.value.toLowerCase()),
  ),
);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    nextTick(() => inputElement.value?.focus());
  }
};

const selectOption = (id) => {
  const newValue = [...props.modelValue, id];
  emit("update:modelValue", newValue);
  emit("change", newValue);
  searchTerm.value = "";
};

const removeOption = (id) => {
  const newValue = props.modelValue.filter((v) => v !== id);
  emit("update:modelValue", newValue);
  emit("change", newValue);
};

const handleClickOutside = (e) => {
  if (container.value && !container.value.contains(e.target)) {
    isOpen.value = false;
    searchTerm.value = "";
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <div class="space-y-1 w-full" ref="container">
    <InputLabel v-if="label" :value="label" />

    <div class="relative">
      <div
        role="button"
        tabindex="0"
        class="min-h-[42px] w-full p-1.5 flex flex-wrap gap-2 bg-white dark:bg-gray-800 border rounded-lg shadow-sm transition-all duration-200 cursor-text"
        :class="
          isOpen
            ? 'border-indigo-500 ring-2 ring-indigo-500/20'
            : 'border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600'
        "
        @click="toggleDropdown"
        @keydown.enter.space.prevent="toggleDropdown"
      >
        <TransitionGroup
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="transform translate-y-1 opacity-0"
          enter-to-class="transform translate-y-0 opacity-100"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="transform translate-y-0 opacity-100"
          leave-to-class="transform translate-y-1 opacity-0"
        >
          <span
            v-for="option in selectedOptions"
            :key="option.id"
            class="flex items-center gap-1 px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded text-sm font-medium border border-indigo-200 dark:border-indigo-800/50"
          >
            {{ option.name }}
            <button
              type="button"
              class="hover:text-indigo-900 dark:hover:text-indigo-100 transition-colors"
              @click.stop="removeOption(option.id)"
            >
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </span>
        </TransitionGroup>

        <input
          ref="inputElement"
          v-model="searchTerm"
          type="text"
          class="flex-1 bg-transparent border-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-gray-100 min-w-[120px]"
          :placeholder="placeholder"
          @focus="isOpen = true"
          @click.stop
        />

        <div class="flex items-center pr-2 ml-auto">
          <svg
            class="w-5 h-5 text-gray-400 transition-transform duration-200"
            :class="isOpen ? 'rotate-180' : ''"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </div>
      </div>

      <Transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="isOpen"
          class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden max-h-60 overflow-y-auto"
        >
          <div v-if="filteredOptions.length > 0" class="py-1">
            <button
              v-for="option in filteredOptions"
              :key="option.id"
              type="button"
              class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center justify-between"
              @click.stop="selectOption(option.id)"
            >
              {{ option.name }}
            </button>
          </div>
          <div
            v-else
            class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center italic"
          >
            {{ searchTerm ? "No results found" : "All options selected" }}
          </div>
        </div>
      </Transition>
    </div>

    <InputError v-if="error" :message="error" />
  </div>
</template>

<style scoped>
/* Scrollbar styling for dropdown */
div::-webkit-scrollbar {
  width: 6px;
}
div::-webkit-scrollbar-track {
  background: transparent;
}
div::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
:global(.dark) div::-webkit-scrollbar-thumb {
  background: #334155;
}
</style>
