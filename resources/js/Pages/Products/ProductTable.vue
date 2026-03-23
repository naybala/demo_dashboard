<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { __, formatNumber } from "@/helpers.js";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { computed } from "vue";

defineProps({
  data: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["confirmDelete"]);

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const headers = [
  { key: "primary_photo", label: __("product.photo", "Photo") },
  { key: "name", label: __("product.name", "Name") },
  { key: "price", label: __("product.price", "Price") },
  { key: "category_names", label: __("product.category", "Categories") },
  { key: "actions", label: __("table.action", "Actions") },
];
</script>

<template>
  <BaseTable :headers="headers">
    <tr
      v-for="product in data"
      :key="product.id"
      class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
    >
      <td class="px-6 py-4 whitespace-nowrap">
        <template v-if="product.primary_photo">
          <img
            :src="product.primary_photo"
            :alt="product.name"
            class="h-10 w-10 rounded-full object-cover border border-gray-200"
          />
        </template>
        <template v-else>
          <div
            class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
          </div>
        </template>
      </td>
      <td
        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
      >
        {{ product.name }}
      </td>
      <td
        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
      >
        {{ formatNumber(product.price) }}
      </td>
      <td
        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
      >
        <div class="flex flex-wrap gap-1">
          <span
            v-for="category in product.category_names"
            :key="category"
            class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-xs"
          >
            {{ category }}
          </span>
        </div>
      </td>
      <td class="flex gap-2 px-6 py-4">
        <Link
          v-if="permissions.includes('edit products')"
          :href="`/products/${product.id}/edit`"
        >
          <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
        </Link>
        <SecondaryButton
          v-if="permissions.includes('delete products')"
          variant="danger"
          @click="emit('confirmDelete', product)"
        >
          {{ __("messages.delete", "Delete") }}
        </SecondaryButton>
      </td>
    </tr>
  </BaseTable>
</template>
