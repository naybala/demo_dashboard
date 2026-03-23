<script setup>
import { __ } from "@/helpers.js";
import { ref, watch } from "vue";

const props = defineProps({
  startDate: {
    type: String,
    default: "",
  },
  endDate: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["filter", "reset"]);

const startDate = ref(props.startDate);
const endDate = ref(props.endDate);

watch(
  () => props.startDate,
  (newVal) => (startDate.value = newVal),
);
watch(
  () => props.endDate,
  (newVal) => (endDate.value = newVal),
);

const handleFilter = () => {
  emit("filter", {
    start_date: startDate.value,
    end_date: endDate.value,
  });
};

const handleReset = () => {
  startDate.value = "";
  endDate.value = "";
  emit("reset");
};

const todaySelector = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0");
  const day = String(today.getDate()).padStart(2, "0");
  const formattedToday = `${year}-${month}-${day}`;
  startDate.value = formattedToday;
  endDate.value = formattedToday;
};
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <form
      @submit.prevent="handleFilter"
      class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
    >
      <div>
        <label
          for="start_date"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >{{ __("messages.start_date", "Start Date") }}</label
        >
        <input
          type="date"
          id="start_date"
          v-model="startDate"
          class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>
      <div>
        <label
          for="end_date"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >{{ __("messages.end_date", "End Date") }}</label
        >
        <input
          type="date"
          id="end_date"
          v-model="endDate"
          class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>

      <div class="flex gap-2">
        <button
          type="button"
          @click="todaySelector"
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
        >
          {{ __("messages.today", "Today") }}
        </button>
        <button
          v-if="startDate && endDate"
          type="submit"
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
        >
          {{ __("messages.filter", "Filter") }}
        </button>
        <button
          v-if="startDate || endDate"
          type="button"
          @click="handleReset"
          class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity"
        >
          {{ __("messages.reset", "Reset") }}
        </button>
      </div>
    </form>
  </div>
</template>
