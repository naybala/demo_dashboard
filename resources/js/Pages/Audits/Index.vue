<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, Link, Head } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { __ } from "@/helpers.js";
import { ref } from "vue";

defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
});

const search = ref("");

const headers = [
  { key: "user", label: __("audit.user", "User") },
  { key: "event", label: __("audit.event", "Event") },
  { key: "auditable_type", label: __("audit.model", "Model") },
  { key: "ip_address", label: __("audit.ip_address", "IP Address") },
  { key: "created_at", label: __("audit.created_at", "Date") },
  { key: "actions", label: __("table.action", "Actions") },
];

const handleSearch = () => {
  router.get(
    "/audits",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/audits");
};

const getEventColor = (event) => {
  switch (event) {
    case "created":
      return "bg-green-100 text-green-800";
    case "updated":
      return "bg-blue-100 text-blue-800";
    case "deleted":
      return "bg-red-100 text-red-800";
    default:
      return "bg-gray-100 text-gray-800";
  }
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.audit") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.audit", "Activity Logs") }}
      </h2>
    </template>

    <div class="mb-6 flex justify-between items-center">
      <div class="flex gap-2 w-1/2">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search logs...')"
          v-model="search"
          @keydown.enter="handleSearch"
          class="w-full"
        />
        <SecondaryButton @click="handleSearch">
          {{ __("messages.search", "Search") }}
        </SecondaryButton>
        <SecondaryButton
          v-if="search"
          class="bg-gray-100 dark:bg-gray-700"
          @click="handleReset"
        >
          {{ __("messages.reset", "Clear") }}
        </SecondaryButton>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="audit in data"
        :key="audit.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ audit.user_name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold"
            :class="getEventColor(audit.event)"
          >
            {{ audit.event }}
          </span>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ audit.auditable_type?.split("\\").pop() || "---" }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ audit.ip_address }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ audit.created_at }}
        </td>
        <td class="flex gap-2 px-6 py-4">
          <Link :href="`/audits/${audit.id}`">
            <SecondaryButton>{{
              __("messages.view", "View Details")
            }}</SecondaryButton>
          </Link>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />
  </AdminLayout>
</template>
