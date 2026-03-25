<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
  options: {
    type: Array,
    default: () => [],
  },
  placeholder: {
    type: String,
    default: "Select an option...",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  required: {
    type: Boolean,
    default: false,
  },
  asyncUrl: {
    type: String,
    default: "",
  },
  asyncKey: {
    type: String,
    default: "keyword",
  },
  error: {
    type: [String, Boolean],
    default: false,
  },
});

const emit = defineEmits(["update:modelValue", "change"]);

const isOpen = ref(false);
const searchTerm = ref("");
const container = ref(null);
const inputElement = ref(null);
const dropdownStyle = ref({});
const isLoading = ref(false);
let debounceTimer = null;
const localOptions = ref([...props.options]);

// Sync localOptions when props.options change (for non-async usage)
watch(
  () => props.options,
  (newOptions) => {
    if (!props.asyncUrl) {
      localOptions.value = [...newOptions];
    }
  },
  { deep: true },
);

const selectedOption = computed(() => {
  return localOptions.value.find((opt) => opt.id === props.modelValue);
});

const filteredOptions = computed(() => {
  if (props.asyncUrl) return localOptions.value;

  return localOptions.value.filter(
    (opt) =>
      opt.label.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      (opt.searchKey &&
        opt.searchKey.toLowerCase().includes(searchTerm.value.toLowerCase())),
  );
});

const calculatePosition = () => {
  if (container.value) {
    const rect = container.value.getBoundingClientRect();
    dropdownStyle.value = {
      position: "fixed",
      top: `${rect.bottom}px`,
      left: `${rect.left}px`,
      width: `${rect.width}px`,
      zIndex: 9999,
    };
  }
};

const fetchOptions = async (term = "") => {
  if (!props.asyncUrl) return;

  isLoading.value = true;
  try {
    const response = await fetch(`${props.asyncUrl}?${props.asyncKey}=${term}`);
    const data = await response.json();
    const results = data.data || data;

    const newOptions = results.map((item) => ({
      id: item.id,
      label: `${item.name} (${item.unit || "No Unit"})`,
      searchKey: item.name,
      original: item,
    }));

    // Preserve the current selected option if it's not in the new results
    if (
      selectedOption.value &&
      !newOptions.find((opt) => opt.id === selectedOption.value.id)
    ) {
      localOptions.value = [selectedOption.value, ...newOptions];
    } else {
      localOptions.value = newOptions;
    }
  } catch (error) {
    console.error("Error fetching options:", error);
  } finally {
    isLoading.value = false;
  }
};

const handleInput = () => {
  if (!props.asyncUrl) return;

  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchOptions(searchTerm.value);
  }, 300);
};

const toggleOpen = () => {
  if (props.disabled) return;
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    searchTerm.value = "";
    calculatePosition();
    if (props.asyncUrl) fetchOptions("");
    nextTick(() => {
      inputElement.value?.focus();
    });
  }
};

const selectOption = (opt) => {
  emit("update:modelValue", opt.id);
  isOpen.value = false;
  emit("change", opt);
};

const handleClickOutside = (event) => {
  if (container.value && !container.value.contains(event.target)) {
    isOpen.value = false;
  }
};

const handleScrollResize = () => {
  if (isOpen.value) calculatePosition();
};

onMounted(() => {
  window.addEventListener("click", handleClickOutside);
  window.addEventListener("scroll", handleScrollResize, true);
  window.addEventListener("resize", handleScrollResize);
});

onUnmounted(() => {
  window.removeEventListener("click", handleClickOutside);
  window.removeEventListener("scroll", handleScrollResize, true);
  window.removeEventListener("resize", handleScrollResize);
  clearTimeout(debounceTimer);
});
</script>

<template>
  <div class="relative w-full" ref="container">
    <div
      class="relative cursor-pointer bg-white dark:bg-gray-900 border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      :class="[
        error ? 'border-red-500 dark:border-red-600' : 'border-gray-300 dark:border-gray-700',
        props.disabled ? 'bg-gray-100 cursor-not-allowed' : '',
      ]"
      @click="toggleOpen"
    >
      <span
        class="block truncate"
        :class="
          !selectedOption ? 'text-gray-500' : 'text-gray-900 dark:text-gray-100'
        "
      >
        {{ selectedOption ? selectedOption.label : placeholder }}
      </span>
      <span
        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
      >
        <svg
          class="h-5 w-5 text-gray-400"
          viewBox="0 0 20 20"
          fill="none"
          stroke="currentColor"
        >
          <path
            d="M7 7l3-3 3 3m0 6l-3 3-3-3"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </span>
    </div>

    <Teleport to="body">
      <Transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div
          v-if="isOpen"
          :style="dropdownStyle"
          class="bg-white dark:bg-gray-800 shadow-xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-hidden focus:outline-none sm:text-sm"
        >
          <div class="sticky top-0 bg-white dark:bg-gray-800 p-2">
            <input
              ref="inputElement"
              v-model="searchTerm"
              @input="handleInput"
              @click.stop
              type="text"
              placeholder="Search..."
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <ul class="max-h-48 overflow-y-auto">
            <li
              v-if="isLoading"
              class="py-2 pl-3 text-gray-500 italic flex items-center gap-2"
            >
              <svg
                class="animate-spin h-4 w-4 text-indigo-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Searching...
            </li>
            <template v-else>
              <li
                v-for="option in filteredOptions"
                :key="option.id"
                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500"
                :class="
                  props.modelValue === option.id
                    ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-200'
                    : 'text-gray-900 dark:text-gray-100'
                "
                @click.stop="selectOption(option)"
              >
                <span
                  class="block truncate"
                  :class="
                    props.modelValue === option.id
                      ? 'font-semibold'
                      : 'font-normal'
                  "
                >
                  {{ option.label }}
                </span>
                <span
                  v-if="props.modelValue === option.id"
                  class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 dark:text-indigo-400"
                >
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </span>
              </li>
              <li
                v-if="filteredOptions.length === 0"
                class="py-2 pl-3 text-gray-500 italic"
              >
                No results found
              </li>
            </template>
          </ul>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>
