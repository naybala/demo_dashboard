<script setup>
import { __ } from "@/helpers.js";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { computed } from "vue";
const props = defineProps({
  selectedYear: {
    type: [String, Number],
    default: "",
  },
  availableYears: {
    type: Array,
    default: () => [],
  },
});
const emit = defineEmits(["change"]);

const yearOptions = computed(() => [
  { id: "", label: __("messages.last_12_months", "Last 12 Months") },
  ...(props.availableYears || []).map((year) => ({
    id: year.toString(),
    label: year.toString(),
  })),
]);

const handleChange = (id) => {
  emit("change", id);
};
</script>

<template>
  <div class="w-full md:w-4/12 mb-4">
    <label
      for="year"
      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
      >{{ __("messages.year", "Year") }}</label
    >
    <SearchableSelect
      id="year"
      :model-value="selectedYear?.toString() || ''"
      @update:model-value="handleChange"
      :options="yearOptions"
      class="w-full"
    />
  </div>
</template>
