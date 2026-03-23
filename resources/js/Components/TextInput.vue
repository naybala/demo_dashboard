<script setup>
import { onMounted, ref } from "vue";

defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

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
  <input
    ref="input"
    type="text"
    :value="modelValue"
    @input="emit('update:modelValue', $event.target.value)"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100"
  />
</template>
