<script setup>
import { formatNumber, unformatNumber } from "@/helpers.js";
import { ref, watch, onMounted } from "vue";

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
  id: {
    type: String,
    default: "",
  },
  decimals: {
    type: Number,
    default: 0,
  },
  showButtons: {
    type: Boolean,
    default: false,
  },
  step: {
    type: Number,
    default: 1,
  },
  class: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue", "input"]);

const input = ref(null);
const displayValue = ref(formatNumber(props.modelValue, props.decimals));

watch(
  () => props.modelValue,
  (newVal) => {
    if (unformatNumber(newVal) !== unformatNumber(displayValue.value)) {
      displayValue.value = formatNumber(newVal, props.decimals);
    }
  },
);

const handleInput = (e) => {
  const el = e.target;
  const start = el.selectionStart;
  const oldLength = el.value.length;

  const rawValue = el.value;
  const numericValue = unformatNumber(rawValue);

  // Update parent value
  emit("update:modelValue", numericValue);

  // Update local display value
  displayValue.value = formatNumber(numericValue, props.decimals);

  // Sync DOM immediately to calculate new cursor position
  el.value = displayValue.value;

  // Adjust cursor position
  const newLength = displayValue.value.length;
  let newStart = start + (newLength - oldLength);

  // Ensure we don't go out of bounds
  newStart = Math.max(0, newStart);

  // Set selection back in next tick to ensure DOM is updated
  setTimeout(() => {
    el.setSelectionRange(newStart, newStart);
  }, 0);

  emit("input", numericValue);
};

const increment = () => {
  const numericValue = unformatNumber(props.modelValue) || 0;
  const newValue = numericValue + props.step;
  emit("update:modelValue", newValue);
  displayValue.value = formatNumber(newValue, props.decimals);
  emit("input", newValue);
};

const decrement = () => {
  const numericValue = unformatNumber(props.modelValue) || 0;
  const newValue = Math.max(0, numericValue - props.step);
  emit("update:modelValue", newValue);
  displayValue.value = formatNumber(newValue, props.decimals);
  emit("input", newValue);
};

defineExpose({
  focus: () => input.value?.focus(),
});

onMounted(() => {
  if (input.value && input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});
</script>

<template>
  <div class="relative flex items-center w-full">
    <button
      v-if="showButtons"
      type="button"
      @click="decrement"
      class="flex items-center justify-center h-full px-2 border border-r-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-l-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
      tabindex="-1"
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
          d="M20 12H4"
        />
      </svg>
    </button>
    <input
      ref="input"
      type="text"
      :id="id"
      :placeholder="placeholder"
      :required="required"
      inputmode="numeric"
      :value="displayValue"
      @input="handleInput"
      class="block w-full border-2 p-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-800 dark:text-gray-100"
      :class="[!showButtons ? 'rounded-md' : 'border-x-0', props.class]"
    />
    <button
      v-if="showButtons"
      type="button"
      @click="increment"
      class="flex items-center justify-center h-full px-2 border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-r-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
      tabindex="-1"
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
          d="M12 4v16m8-8H4"
        />
      </svg>
    </button>
  </div>
</template>
