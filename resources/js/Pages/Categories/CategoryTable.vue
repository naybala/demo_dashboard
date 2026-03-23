<script setup>
import { usePage } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { computed } from "vue";

defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  onEdit: {
    type: Function,
    default: () => {},
  },
  onDelete: {
    type: Function,
    default: () => {},
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const headers = [
  __("category.name", "Name"),
  __("category.name_other", "Other Name"),
  __("category.description", "Description"),
  __("category.is_show", "Show"),
  __("table.action", "Action"),
];
</script>

<template>
  <BaseTable :headers="headers">
    <tr
      v-for="category in categories"
      :key="category.id"
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <td
        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
      >
        {{ category.name }}
      </td>
      <td class="px-6 py-4">{{ category.name_other || "-" }}</td>
      <td class="px-6 py-4">{{ category.description || "-" }}</td>
      <td class="px-6 py-4">
        <span
          class="px-2 py-1 text-xs font-semibold rounded-full"
          :class="
            category.is_show
              ? 'bg-green-100 text-green-800'
              : 'bg-red-100 text-red-800'
          "
        >
          {{ category.is_show ? "Yes" : "No" }}
        </span>
      </td>
      <td class="flex gap-2 px-6 py-4">
        <SecondaryButton
          v-if="permissions.includes('edit categories')"
          @click="onEdit(category)"
        >
          {{ __("messages.edit", "Edit") }}
        </SecondaryButton>

        <SecondaryButton
          v-if="permissions.includes('delete categories')"
          variant="danger"
          @click="onDelete(category)"
        >
          {{ __("messages.delete", "Delete") }}
        </SecondaryButton>
      </td>
    </tr>
  </BaseTable>
</template>
