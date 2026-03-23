<script setup>
import { __ } from "@/helpers.js";
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
});

const emit = defineEmits(["update:modelValue", "change"]);

const toggleOption = (id) => {
  let newValue;
  if (props.modelValue.includes(id)) {
    newValue = props.modelValue.filter((v) => v !== id);
  } else {
    newValue = [...props.modelValue, id];
  }
  emit("update:modelValue", newValue);
  emit("change", newValue);
};
</script>

<template>
  <div
    class="border border-gray-200 dark:border-gray-700 p-4 rounded-2xl bg-white dark:bg-gray-800"
  >
    <InputLabel :value="label" />
    <div class="mt-2 flex flex-wrap gap-2">
      <button
        v-for="option in options"
        :key="option.id"
        type="button"
        @click="toggleOption(option.id)"
        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border"
        :class="
          props.modelValue.includes(option.id)
            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
            : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 hover:border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
        "
      >
        {{ option.name }}
      </button>
    </div>
    <div v-if="error" class="mt-2">
      <InputError :message="error" />
    </div>
  </div>
</template>
